<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Contest;
use App\Problem;
use App\Contestant;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class ContestController extends Controller {

	public function getContests()
	{
		$data = Contest::where("visible", "=", "1")->get();

		$contests = (object) array();

		$contests->new = array();
		$contests->old = array();
		$contests->declare = array();
		$contests->register = array();
		$contests->coming = array();
		$contests->running = array();

		foreach ($data as $key => $value) {
			
			$value->contestant = Contestant::where("contest_id", "=", $value->id)->count();

			if(Auth::check())
				$value->registered = Contestant::whereRaw("contest_id = ? and user_id = ?",[$value->id, Auth::user()->id])->count();
			else
				$value->registered = 0;

			$data[$key]->start_register = strtotime($value->start_register);
			$data[$key]->end_register = strtotime($value->end_register);
			$data[$key]->start_contest = strtotime($value->start_contest);
			$data[$key]->end_contest = strtotime($value->end_contest);
		}

		foreach ($data as $key => $value) {

			if(time() <= $value->end_contest)
			{

				if(time() <= $value->start_register)
				{
					$value->status = "declare";
					array_push($contests->declare, $value);
				}

				else if(time() <= $value->end_register)
				{
					$value->status = "register";
					array_push($contests->register, $value);
				}

				else if(time() <= $value->start_contest)
				{
					$value->status = "coming";
					array_push($contests->coming, $value);
				}

				else if(time() <= $value->end_contest)
				{
					$value->status = "running";
					array_push($contests->running, $value);
				}
				
				array_push($contests->new, $value);
			}
			else
			{
				$value->status = "old";
				array_push($contests->old, $value);
			}

		}

		return json_encode($contests);

	}

	public function getContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);

		$contest->contestant = Contestant::where("contest_id", "=", $contest->id)->count();
		if(Auth::check())
			$contest->registered = Contestant::whereRaw("contest_id = ? and user_id = ?",[$contest->id, Auth::user()->id])->count();
		else
			$contest->registered = 0;

		$contest->start_register = strtotime($contest->start_register);
		$contest->end_register = strtotime($contest->end_register);
		$contest->start_contest = strtotime($contest->start_contest);
		$contest->end_contest = strtotime($contest->end_contest);

		if(time() <= $contest->end_contest)
		{
			if(time() <= $contest->start_register) $contest->status = "declare";
			else if(time() <= $contest->end_register) $contest->status = "register";
			else if(time() <= $contest->start_contest) $contest->status = "coming";
			else if(time() <= $contest->end_contest) $contest->status = "running";
		}
		else $contest->status = "old";

		return json_encode($contest);
	}

	public function acceptRegister($contest_id)
	{
		$contest = Contest::find($contest_id);
		$contest->start_register = strtotime($contest->start_register);
		$contest->end_register = strtotime($contest->end_register);
		$registerd = Contestant::whereRaw("contest_id = ? and user_id = ?",[$contest->id, Auth::user()->id])->count();
		if(!$registerd and $contest->start_register <= time() and time() <= $contest->end_register)
		{
			$player = new Contestant;
			$player->contest_id = $contest_id;
			$player->user_id = Auth::user()->id;
			$player->save();
			return redirect('contest/'.$contest->type.'/'.$contest->id);
		}
		else
			return "Invalid Registration";
	}

}
