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

class NormalController extends Controller {

	function initContestantData($contestant)
	{
		if($contestant->data != '')
		{
			$contestant->data = json_decode($contestant->data);
			if($contestant->data->type != "normal")
			{
				$contestant = null;
				exit(0);
			}
		}
		else
		{
			$contestant->data = (object) array();
			$contestant->data->type = "normal";
			$contestant->data->submit_of_task = (object) array();
			$contestant->data->score_of_order = (object) array();
			$contestant->data->pass_of_order = (object) array();
			$contestant->data->total_score = 0;
			$contestant->data->place = 0;
			$contestant->data->rating = 0;
		}
	}

	function initContestData($contest)
	{
		if($contest->data != '')
		{
			$contest->data = json_decode($contest->data);
			if($contest->data->type != "normal")
			{
				$contest = null;
				exit(0);
			}
		}
		else
		{
			$contest->data = (object) array();
			$contest->data->type = "normal";
			$contest->data->full_feedback = true;
			$contest->data->scoreboard = true;
			$contest->data->save_scoreboard = false;
		}
	}

	function initTaskData($task)
	{
		if($task->data != '')
		{
			$task->data = json_decode($task->data);
			if($task->data->type != "normal")
			{
				$task = null;
				exit(0);
			}
			if(count($task->data->score) != $task->info->testcase + $task->info->pretestcase)
			{
				$task->data = (object) array();
				$task->data->type = "normal";
				$task->data->score = array();
				for($i = 0; $i < $task->info->testcase + $task->info->pretestcase; $i++)
				{
					if(($i < $task->info->testcase))
					$task->data->score[$i] = 100/$task->info->testcase;
					else
						$task->data->score[$i] = 100/$task->info->pretestcase;
				}
			}
		}
		else
		{
			$task->data = (object) array();
			$task->data->type = "normal";
			$task->data->score = array();
			for($i = 0; $i < $task->info->testcase + $task->info->pretestcase; $i++)
			{
				if(($i < $task->info->testcase))
				$task->data->score[$i] = 100/$task->info->testcase;
				else
					$task->data->score[$i] = 100/$task->info->pretestcase;
			}
		}
	}

	function getResult($submit, $contest, $type = "full")
	{
		$submit->pass = 1;
		$submit->score = 0;

		$task = Problem::whereRaw("`contest_id` = ? and `task_id` = ?",[$contest->id, $submit->task_id])->first();
		$task->info = Task::find($submit->task_id);
		NormalController::initTaskData($task);

		$allow = "/[PTX-]+$/";
		$check = 0;
		if($submit->result != "")
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

		if($check == 1)
		{
			if($type == "pre")
			{
				for($i = $task->info->testcase+1; $i <= $task->info->pretestcase + $task->info->testcase; $i++)
				{
					if($submit->result[$i] == 'P')
						$submit->score += $task->data->score[$i-1];
					else
						$submit->pass = 0;
				}

				$submit->result = substr($submit->result, $task->info->testcase + 1, $task->info->pretestcase);
			}
			else if($type == "real" or $type == "full")
			{
				for($i = 0; $i < $task->info->testcase; $i++)
				{
					if($submit->result[$i] == 'P')
						$submit->score += $task->data->score[$i];
					else
						$submit->pass = 0;
				}

				if($type == "real")
					$submit->result = substr($submit->result, 0, $task->info->testcase);
			}
		}

	}

	public function getTasks()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		NormalController::initContestData($contest);

		if(strtotime($contest->start_contest) <= time() or Auth::isAdmin())
		{
			$tasks = Problem::where("contest_id", "=", $contest->id)->get();
			foreach ($tasks as $key => $task) {
				$task->info = Task::find($task->task_id);
				NormalController::initTaskData($task);

				$task->full_score = 0;

				if(Auth::isAdmin() or strtotime($contest->end_contest) <= time() or $contest->data->full_feedback)
				{
					for ($i=0; $i < $task->info->testcase; $i++)
						$task->full_score += $task->data->score[$i];
				}
				else
				{
					for ($i=$task->info->testcase; $i < $task->info->testcase + $task->info->pretestcase; $i++)
						$task->full_score += $task->data->score[$i];
				}

				if(!Auth::isAdmin())
					$task->info->level = null;

				$task->count_pass = 0;
					
				$users = Contestant::whereRaw('contest_id = ?',[$contest->id])->get();
				foreach ($users as $key => $user) {
					
					NormalController::initContestantData($user);
					$user->info = User::find($user->user_id);
					$tmp = $task->info->id;

					if(isset($user->data->submit_of_task->$tmp))
					{
						
						if($submit = Submit::find($user->data->submit_of_task->$tmp))
						{
							if(Auth::isAdmin() or strtotime($contest->end_contest) <= time() or $contest->data->full_feedback)
								NormalController::getResult($submit, $contest, "real");
							else
								NormalController::getResult($submit, $contest, "pre");
							
							$task->count_pass += $submit->pass;

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

				 if(!($contest->data->scoreboard or strtotime($contest->end_contest) <= time())) $task->count_pass = 0;

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

	public function getSubmits()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		NormalController::initContestData($contest);
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
				else if(Auth::isAdmin())
					NormalController::getResult($submits[$key], $contest, "full");
				else if(strtotime($contest->end_contest) <= time() or $contest->data->full_feedback)
					NormalController::getResult($submits[$key], $contest, "real");
				else
				{
					NormalController::getResult($submits[$key], $contest, "pre");

					$submits[$key]->time = null;
					$submits[$key]->memory = null;
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
						NormalController::initContestantData($contestant);
						$contestant->data->submit_of_task->$post['task_id'] = $submit->id;
						$contestant->data = json_encode($contestant->data);
						$contestant->save();
					}

					return redirect('contest/normal/'.$post['contest_id'].'/result');
				}
			}
		}
		return redirect('contest/normal/'.$post['contest_id'].'/task/'.$post['task_order']);
	}

	public function getContestants()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->contest_id);
		NormalController::initContestData($contest);

		$users = Contestant::whereRaw('contest_id = ?',[$contest->id])->get();

		if($data->raw_scoreboard or time() <= strtotime($contest->end_contest) or !$contest->data->save_scoreboard)
		{
			foreach ($users as $key => $user) {
				
				NormalController::initContestantData($user);
				
				$user->info = User::find($user->user_id);
				$user->total_score = 0;
				$user->score = (object) array();
				$user->pass = (object) array();

				$user->me = 0;
				if(Auth::check())
				{
					if(Auth::user()->id == $user->user_id)
						$user->me = 1;
				}

				for($order = 1; $order <= $contest->task; $order++)
				{
					$task = Problem::whereRaw("`contest_id` = ? and `order` = ?",[$contest->id, $order])->first();
					$task->info = Task::find($task->task_id);
					NormalController::initTaskData($task);
					
					$user->score->$order = 0;
					$user->pass->$order = null;
					
					$tmp = $task->info->id;

					if(isset($user->data->submit_of_task->$tmp))
					{
						if($submit = Submit::find($user->data->submit_of_task->$tmp))
						{
							if(Grading::where('submit_id','=',$submit->id)->count())
							{
								$user->score->$order = 0;
								$user->pass->$order = null;
							}
							else if(Queue::where('submit_id','=',$submit->id)->count())
							{
								$user->score->$order = 0;
								$user->pass->$order = null;
							}
							else if(Auth::isAdmin())
							{
								NormalController::getResult($submit, $contest, "real");
								$user->score->$order = $submit->score;
								$user->pass->$order = $submit->pass;
							}
							else if(strtotime($contest->end_contest) <= time() or $contest->data->full_feedback)
							{
								NormalController::getResult($submit, $contest, "real");
								$user->score->$order = $submit->score;
								$user->pass->$order = $submit->pass;
							}
							else
							{
								NormalController::getResult($submit, $contest, "pre");
								$user->score->$order = $submit->score;
								$user->pass->$order = $submit->pass;
							}
						}
					}

					$user->total_score += $user->score->$order;
				}

			}

			$users = array_values(array_sort($users, function($value) {return -$value->total_score;}));
			$place = 0;
			$old_score = -1000000000;
			
			foreach ($users as $key => $value) {

				$user = Contestant::find($value->id);
				NormalController::initContestantData($user);

				if($value->total_score != $old_score)
				{
					$place = $key + 1;
					$old_score = $value->total_score;
				}
				$value->place = $place;
			}
		}
		else
		{
			foreach ($users as $key => $user) {
				
				NormalController::initContestantData($user);
				$user->info = User::find($user->user_id);
				$user->place = $user->data->place;
				$user->score = $user->data->score_of_order;
				$user->pass = $user->data->pass_of_order;
				$user->total_score = $user->data->total_score;

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
		NormalController::initContestData($contest);
		$contest->data->save_scoreboard = true;
		$contest->data = json_encode($contest->data);
		$contest->save();
		
		foreach ($data as $key => $value) {

			$user = Contestant::find($value->id);
			NormalController::initContestantData($user);

			$user->data->total_score = 0;
			$user->data->place = $value->place;
			foreach ($value->score as $order => $score) {
				$user->data->score_of_order->$order = $score;
				$user->data->total_score += $score;
				$user->data->pass_of_order->$order = $value->pass->$order;
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

		NormalController::initContestData($contest);

		return json_encode($contest);
	}

}
