<?php namespace App\Library;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Grading;
use App\Contest;
use App\Problem;
use App\Contestant;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

define("RATING_CONSTANT_W", 400); // Win
define("RATING_CONSTANT_L", 400); // Lose

class RatingController {

	// bT33 rating system
	static function updateRating() // Update rating from all contest
	{
		$all_contest = Contest::whereRaw('end_contest < ? and visible = 1',[date("Y-m-d H:i:s")])->orderBy('end_contest', 'asc')->get();
		
		// reset every user contest count
		$all_users = User::orderBy('rating', 'desc')->get();
		foreach ($all_users as $key => $user) {
			$user->contest = 0;
			$user->save();
		}
		
		foreach ($all_contest as $main_key => $contest) {
			RatingController::onceUpdateRating($contest);
		}
		
	}
	
	static function prob($x)
	{
		return 1.0/(1.0+pow(10.0,$x/400.0));
	}
	

	static function onceUpdateRating($contest)
	{
		$contestants = Contestant::where('contest_id', '=', $contest->id)->get();
		foreach ($contestants as $key => $contestant) {
			$contestant->data = json_decode($contestant->data);
			if($contestant->data && $contestant->data->submit_of_task == (object) array()) $contestant->delete();
		}

		$contestants = Contestant::where('contest_id', '=', $contest->id)->where('data', '!=', '')->get();
		
		$pre_contests = Contest::whereRaw('end_contest < ? and visible = 1', [$contest->end_contest])->orderBy('end_contest', 'desc')->get();
		
		$numberOfContestant = sizeof($contestants);
		if($numberOfContestant <= 1)
		{
			$numberOfContestant = 2;
		}
		
		$ratingChange = array();
		foreach ($contestants as $key => $contestant)
		{
			$ratingChange[$key] = 0;
		}
		
		foreach ($contestants as $key => $contestant) 
		{
			$contestant->data = json_decode($contestant->data);
			
			// pre_contestant = this user from the previous contest
			$pre_contestant = (object) array();
			$pre_contestant->data = (object) array();
			$pre_contestant->data->rating = 1500;
			$pre_contestant->data->contest = 0;
			$pre_contestant->data = json_encode($pre_contestant->data);
			foreach ($pre_contests as $sub_key => $pre_contest) {

				if(Contestant::whereRaw('contest_id = ? and user_id = ?', [$pre_contest->id, $contestant->user_id])->count())
				{
					$pre_contestant = Contestant::whereRaw('contest_id = ? and user_id = ?', [$pre_contest->id, $contestant->user_id])->first();
					break;
				}
			}
			// unpack previous contest data (if first time competing then use default data [Rating = 1500])
			$pre_contestant->data = json_decode($pre_contestant->data);
			
			// copy important data from previous contest to now
			$contestant->data->rating = $pre_contestant->data->rating;
			$contestant->data->contest = $pre_contestant->data->contest;
			
			foreach ($contestants as $sub_key => $sub_contestant) 
			{
				if($key == $sub_key) break;
				$p = RatingController::prob($contestant->data->rating - $sub_contestant->data->rating);
				
				if($contestant->data->place < $sub_contestant->data->place)
				{
					$ratingChange[$key] += $p*RATING_CONSTANT_W;
					$ratingChange[$sub_key] -= $p*RATING_CONSTANT_L;
				}
				else if($contestant->data->place > $sub_contestant->data->place)
				{
					$ratingChange[$key] -= (1.0-$p)*RATING_CONSTANT_L;
					$ratingChange[$sub_key] += (1.0-$p)*RATING_CONSTANT_W;
				}
			}

		}
		// update contestant now
		foreach($contestants as $key => $contestant) 
		{
			$contestant->data->rating += $ratingChange[$key] / ($numberOfContestant - 1);
			$contestant->data->contest+=1;
		}
		RatingController::pushUserRating($contestants);
		
		// save this contestant data (for querying on next contest)
		foreach ($contestants as $key => $contestant) 
		{
			$contestant->data = json_encode($contestant->data);
			$contestant->save();
		}
	}

	static function pushUserRating($contestants)
	{
		foreach ($contestants as $key => $contestant) {

			$user = User::find($contestant->user_id);
			$user->rating = $contestant->data->rating;
			$user->contest = $contestant->data->contest;
			$user->save();
		}
	}

}
