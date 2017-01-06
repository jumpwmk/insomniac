<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Grading;
use App\Contest;
use App\Problem;
use App\Contestant;
use App\Library\RatingController;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ACMContestController extends Controller {

	function initContestantData($contestant) // Get contestant's data
	{
		if($contestant->data != '') // If exists
		{
			$contestant->data = json_decode($contestant->data);
			if($contestant->data->type != "acm_contest")
			{
				$contestant = null;
				exit(0);
			}
		}
		else // Create default one
		{
			$contestant->data = (object) array();
			$contestant->data->type = "acm_contest";
			$contestant->data->submit_of_task = (object) array();
			$contestant->data->score_of_order = (object) array();
			$contestant->data->pass_of_order = (object) array();
			$contestant->data->total_score = 0;
			$contestant->data->place = 0;
			$contestant->data->rating = 0;
			
			// Added for ACM contest
			$contestant->data->total_penalty = 0;
			$contestant->data->submission_count = (object) array();
			$contestant->data->penalty_time = (object) array();
		}
	}

	function initContestData($contest) // Get contest's data
	{
		if($contest->data != '') // If exists
		{
			$contest->data = json_decode($contest->data);
			if($contest->data->type != "acm_contest")
			{
				$contest = null;
				exit(0);
			}
		}
		else // Create default one
		{
			$contest->data = (object) array();
			$contest->data->type = "acm_contest";
			$contest->data->scoreboard = true;
			$contest->data->save_scoreboard = false;
		}
	}

	function initTaskData($task) // Get task's data
	{
		if($task->data != '') // If exists
		{
			$task->data = json_decode($task->data);
			if($task->data->type != "acm_contest")
			{
				$task = null;
				exit(0);
			}
		}
		else
		{
			$task->data = (object) array();
			$task->data->type = "acm_contest";
			
			$task->data->score = 1; // default score per task
		}
	}

	function getResult($submit, $contest, $type = "full") // Get result in the contest format (with type) from the normal judge result
	{
		/* 
		$type = full
		*/
		
		$submit->pass = 1;
		$submit->score = 0;
		
		// submission count here ?
		
		// Get submission result and task data
		$task = Problem::whereRaw("`contest_id` = ? and `task_id` = ?",[$contest->id, $submit->task_id])->first();
		$task->info = Task::find($submit->task_id);
		ACMContestController::initTaskData($task);
		
		// Check if normal submission result is valid 
		$allow = "/[PTX-]+$/";
		$check = 0;
		if($submit->result != "") // Check if the task's testdata has changed
		{
			$check = 1;
			if(strlen($submit->result) != $task->info->testcase + $task->info->pretestcase + 1) $check = 0;
			else
			{
				if($task->info->pretestcase != 0)
					if(!preg_match($allow, substr($submit->result, 0, $task->info->testcase))) $check = 0;
				if($task->info->pretestcase != 0)
					if(!preg_match($allow, substr($submit->result, $task->info->testcase + 1, $task->info->pretestcase))) $check = 0;
			}
			
			if($check == 0)
			{
				$submit->pass = 0;
				$submit->compile_result = "มีการแก้ไขข้อมูลทดสอบ กรุณาส่งใหม่";
				$submit->result = '';
			}
		}
		else
		{
			$submit->pass = 0;
		}

		if($check == 1) // Start converting to contest's format
		{
			// Check for X
			$verdict = "Yes";
			for($i = 0; $i < $task->info->testcase; $i++)
			{
				if($submit->result[$i] == 'X') 
				{
					$submit->pass = 0;
					$verdict = "No - Runtime error";
				}
			}
			for($i = 0; $i < $task->info->testcase; $i++)
			{
				if($submit->result[$i] == 'T' && $verdict == "Yes") 
				{
					$submit->pass = 0;
					$verdict = "No - Time limit exceeded";
				}
			}
			for($i = 0; $i < $task->info->testcase; $i++)
			{
				if($submit->result[$i] == '-' && $verdict == "Yes") 
				{
					$submit->pass = 0;
					$verdict = "No - Wrong answer";
				}
			}
			
			if($verdict == "Yes")
			{
				$submit->score = $task->data->score;
			}
			else 
			{
				$submit->score = 0;
			}
			
			$submit->result = $verdict;
		}
	}

	public function getTasks() // Get all task (Page - "โจทย์")
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		ACMContestController::initContestData($contest);

		if(strtotime($contest->start_contest) <= time() or Auth::isAdmin()) // If contest started or is admin then can see this page
		{
			$tasks = Problem::where("contest_id", "=", $contest->id)->get();
			foreach ($tasks as $key => $task) {
				$task->info = Task::find($task->task_id);
				ACMContestController::initTaskData($task);

				$task->full_score = $task->data->score;
				$task->info->full_score = $task->data->score;

				if(!Auth::isAdmin())
					$task->info->level = null;

				$task->count_pass = 0;
				
				// count passed
				$users = Contestant::whereRaw('contest_id = ?',[$contest->id])->get();
				foreach ($users as $key => $user) {
					
					ACMContestController::initContestantData($user);
					$user->info = User::find($user->user_id);
					$tmp = $task->info->id;

					if(isset($user->data->submit_of_task->$tmp))
					{
						if($submit = Submit::find($user->data->submit_of_task->$tmp))
						{
							ACMContestController::getResult($submit, $contest, "full");
							
							$task->count_pass += $submit->pass;
							
							// If user passed, show that user passed
							if(Auth::check())
							{
								if(Auth::user()->id == $user->user_id)
								{
									$task->pass = $submit->pass;
								}
							}
						}

					}
					
				}

				if(!($contest->data->scoreboard)) $task->count_pass = 0; // Hide count pass if scoreboard not show

			}
			return json_encode($tasks);
		}
	}

	public function saveScore()
	{
		$data = json_decode(file_get_contents("php://input"));
		$problem = Problem::find($data->id);
		$problem->data = json_encode($data->data);
		$problem->save();
		echo $problem->data;
	}

	public function saveData()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->id);
		$contest->data = json_encode($data->data);
		$contest->save();
	}

	public function getSubmits() // Get all submits from a user (Page - "ผลตรวจ")
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		ACMContestController::initContestData($contest);
		if(strtotime($contest->start_contest) <= time() or Auth::isAdmin())
		{
			$submits = Submit::whereRaw("user_id = ? and created_at < ? and created_at > ?",[Auth::user()->id, $contest->end_contest, $contest->start_contest])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();
			foreach ($submits as $key => $value) {

				if(Problem::whereRaw("contest_id = ? and task_id = ?", [$contest->id, $submits[$key]->task_id])->count() == 0)
				{
					$submits[$key]->visible = 0;
					continue;
				}

				$submits[$key]->visible = 1;
				$submits[$key]->user = User::find($value->user_id);
				$submits[$key]->task = Task::find($value->task_id);

				$submits[$key]->task->order = Problem::whereRaw("contest_id = ? and task_id = ?", [$contest->id, $value->task_id])->first()->order;
				
				if(Grading::where('submit_id','=',$value->id)->count())
				{
					$submits[$key]->result = "กำลังตรวจ...";
					$submits[$key]->pass = null;
				}
				else if(Queue::where('submit_id','=',$value->id)->count())
				{
					$submits[$key]->result = "รอตรวจ";
					$submits[$key]->pass = null;
				}
				else
				{
					ACMContestController::getResult($submits[$key], $contest, "full");
				}

			}

			return json_encode($submits);
		}
	}

	public function upload()
	{
		if(Request::hasFile('code'))
		{
			$code = Request::file('code');
			$post = Request::all();
			$type = array('text/x-c', 'text/x-c++');
			if(in_array($code->getMimeType(), $type))
			{
				$contest = Contest::find($post['contest_id']);
				$contest->start_contest = strtotime($contest->start_contest);
				$contest->end_contest = strtotime($contest->end_contest);
				if(Auth::isAdmin() or ($contest->start_contest <= time() and time() <= $contest->end_contest and Contestant::whereRaw("user_id = ? and contest_id = ?", [Auth::user()->id, $contest->id])->count()))
				{

					$submit = new Submit;
					$submit->task_id = $post['task_id'];
					$submit->user_id = Auth::user()->id;
					
					$submit->save();
					
					$queue = new Queue;
					$queue->submit_id = $submit->id;
					$queue->save();

					Request::file('code')->move('judge/codes',$submit->id.'.cpp');

					if(Contestant::whereRaw("user_id = ? and contest_id = ?", [Auth::user()->id, $contest->id])->count())
					{
						$contestant = Contestant::whereRaw("user_id = ? and contest_id = ?", [Auth::user()->id, $contest->id])->first();
						ACMContestController::initContestantData($contestant);
						$contestant->data->submit_of_task->$post['task_id'] = $submit->id;
						
						// Count submission
						if(isset($contestant->data->submission_count->$post['task_id'])) 
							$contestant->data->submission_count->$post['task_id'] += 1;
						else
							$contestant->data->submission_count->$post['task_id'] = 1;
						
						$contestant->data = json_encode($contestant->data);
						$contestant->save();
					}

					return redirect('contest/acm_contest/'.$post['contest_id'].'/result');
				}
			}
		}
		return redirect('contest/acm_contest/'.$post['contest_id'].'/task/'.$post['task_order']);
	}
	
	public function getContestants() // Get all contestants data (Scoreboard) (Page - "ตารางคะแนน")
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		ACMContestController::initContestData($contest);

		$users = Contestant::whereRaw('contest_id = ?',[$contest->id])->get();
		
		if($data->raw_scoreboard or time() <= strtotime($contest->end_contest) or !$contest->data->save_scoreboard)
		{ 
			// Evaulate all submissions from all users
			foreach ($users as $key => $user) {
				
				ACMContestController::initContestantData($user);
				
				$user->info = User::find($user->user_id);
				$user->total_score = 0;
				$user->score = (object) array();
				$user->pass = (object) array();
				
				$user->total_penalty = 0;
				$user->penalty_time = (object) array();
				$user->submission_count = (object) array();
				
				// If this user is the requester
				$user->me = 0;
				if(Auth::check())
				{
					if(Auth::user()->id == $user->user_id)
						$user->me = 1;
				}

				for($order = 1; $order <= $contest->task; $order++) // $order = order of task in contest
				{
					$task = Problem::whereRaw("`contest_id` = ? and `order` = ?",[$contest->id, $order])->first();
					$task->info = Task::find($task->task_id);
					ACMContestController::initTaskData($task);
					
					$user->score->$order = 0;
					$user->pass->$order = null;
					$user->penalty_time->$order = '-';
					
					$tmp = $task->info->id; // $tmp = task id (whole site)

					if(isset($user->data->submit_of_task->$tmp)) // If has submission from this user
					{
						if($submit = Submit::find($user->data->submit_of_task->$tmp)) // Get that submission
						{
							// Get submitted time
							$start_contest = Carbon::parse($contest->start_contest);
							
							$timeSubmitted = $submit->created_at->diffInMinutes($start_contest); // Added for time penalty
							
							if(Grading::where('submit_id','=',$submit->id)->count()) // Grading
							{
								$user->score->$order = 0;
								$user->pass->$order = null;
							}
							else if(Queue::where('submit_id','=',$submit->id)->count()) // In queue
							{
								$user->score->$order = 0;
								$user->pass->$order = null;
							}
							else
							{
								ACMContestController::getResult($submit, $contest, "full");
								$user->score->$order = $submit->score;
								$user->pass->$order = $submit->pass;
								$user->submission_count->$order = $user->data->submission_count->$tmp;
								if($submit->pass) {
									$user->penalty_time->$order = $timeSubmitted;
									$user->total_penalty += ($user->submission_count->$order - 1) * 20 + $timeSubmitted;
								}
							}
						}
					}
					$user->total_score += $user->score->$order;
					
				}

			}
			
			// Calculate everyone place in O(n log n)
			
			// LIMIT : total penalty < 10^5 , total_score < 10^4
			// TODO : Please fix this to real sort function
			$users = array_values(array_sort($users, function($value) {return -$value->total_score*100000 + $value->total_penalty;}));
			$place = 0;
			$old_score = -1000000000;
			$old_penalty = -100000000;
			
			foreach ($users as $key => $value) {

				$user = Contestant::find($value->id);
				ACMContestController::initContestantData($user);

				if($value->total_score != $old_score || $value->total_penalty != $old_penalty)
				{
					$place = $key + 1;
					$old_score = $value->total_score;
					$old_penalty = $value->total_penalty;
				}
				$value->place = $place;
			}
		}
		else
		{
			// map [task id] => [order in contest] (Should have better way)
			$taskIdToOrder = array();
			for($order = 1; $order <= $contest->task; $order++) // $order = order of task in contest
			{
				$task = Problem::whereRaw("`contest_id` = ? and `order` = ?",[$contest->id, $order])->first();
				$task->info = Task::find($task->task_id);
				ACMContestController::initTaskData($task);
				$tmp = $task->info->id; // $tmp = task id (whole site)
				$taskIdToOrder[$tmp] = $order;
			}
				
			foreach ($users as $key => $user) {
				
				ACMContestController::initContestantData($user);
				$user->info = User::find($user->user_id);
				$user->place = $user->data->place;
				$user->score = $user->data->score_of_order;
				$user->pass = $user->data->pass_of_order;
				$user->total_score = $user->data->total_score;
				$user->total_penalty = $user->data->total_penalty;
				
				$user->penalty_time = $user->data->penalty_time;
				//var_dump($user->penalty_time);
				
				$user->submission_count = (object) array();
				// Convert stored submission_count (id) to contest's submission_count (order)
				foreach($user->data->submission_count as $submission => $value) 
				{
					$user->submission_count->$taskIdToOrder[(int)$submission] = $value;
				}

				if(Auth::check())
				{
					if($user->user_id == Auth::user()->id)
						$user->me = 1;
				}
			}
		}		

		return json_encode($users);
	}

	public function saveScoreboard()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data[0]->contest_id);
		ACMContestController::initContestData($contest);
		$contest->data->save_scoreboard = true;
		$contest->data = json_encode($contest->data);
		$contest->save();
		
		foreach ($data as $key => $value) { // Store calculated data in user's data ($value = user)

			$user = Contestant::find($value->id);
			ACMContestController::initContestantData($user);

			$user->data->total_score = 0;
			$user->data->place = $value->place;
			$user->data->total_penalty = $value->total_penalty;
			
			foreach ($value->score as $order => $score) {
				$user->data->score_of_order->$order = $score;
				$user->data->total_score += $score;
				$user->data->pass_of_order->$order = $value->pass->$order;
				
				$user->data->penalty_time->$order = $value->penalty_time->$order;
			}
			
			
			$user->data = json_encode($user->data);
			$user->save();
		}

		RatingController::onceUpdateRating(Contest::find($contest->id));
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

		ACMContestController::initContestData($contest);

		return json_encode($contest);
	}

}
