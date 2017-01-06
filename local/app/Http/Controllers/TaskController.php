<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Pass;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller {

	function initTaskRating($task)
	{
		$sumRating = 0;
		$task->rated = 0;
		if($task->rating == '') $task->rating = (object) array();
		else $task->rating = json_decode($task->rating);
		foreach ($task->rating as $key => $rating) {
			$sumRating += $rating;
			if(Auth::check())
			{
				if($key == Auth::user()->id)
					$task->rated = $rating;
			}
		}
		$task->ratingCount = count((array) $task->rating);
		if(($task->ratingCount >= 5 or Auth::isAdmin()) and $task->ratingCount > 0) $task->rating = number_format($sumRating/count((array) $task->rating), 2);
		else $task->rating = null;
	}

	public function getTasks()
	{
		$data = json_decode(file_get_contents("php://input"));
		$tmp_tasks = Task::whereRaw('visible = 1')->get();
		$tasks = array();
		foreach ($tmp_tasks as $key => $task) {
			TaskController::initTaskRating($task);

			if($data->type == "unrate")
			{
				if($task->ratingCount >= 5) continue;
			}
			else if($data->type == "easy")
			{
				if(!(1 <= $task->rating and $task->rating <= 2 and $task->ratingCount >= 5)) continue;
			}
			else if($data->type == "medium")
			{
				if(!(2 < $task->rating and $task->rating <= 3.5 and $task->ratingCount >= 5)) continue;
			}
			else if($data->type == "hard")
			{
				if(!(3.5 < $task->rating and $task->rating <= 5 and $task->ratingCount >= 5)) continue;
			}

			$value = $task;
			$value->pass = Pass::whereRaw("task_id = ?",[$value->id])->count();
			if(Auth::check())
			{
				if(Submit::whereRaw("task_id = ? and user_id = ?",[$value->id, Auth::user()->id])->count()) 
					$value->me = Pass::whereRaw("task_id = ? and user_id = ?",[$value->id, Auth::user()->id])->count();
			}

			$value->pretestcase = null;
			$value->testcase = null;
			$value->time = null;
			$value->memory = null;
			$value->general_check = null;
			$value->visible = null;

			array_push($tasks, $value);
		}
		return json_encode($tasks);
	}

	public function infoTask()
	{
		$data = json_decode(file_get_contents("php://input"));
		$task = Task::find($data->id);
		if(Auth::isAdmin() or $task->visible)
		{
			$task->visible = 1;
			TaskController::initTaskRating($task);
			return json_encode($task);
		}
		else
		{
			$task = new Task;
			$task->visible = 0;
			return json_encode($task);
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
				
				$submit = new Submit;
				$submit->task_id = $post['task_id'];
				$submit->user_id = Auth::user()->id;
				$submit->save();

				$queue = new Queue;
				$queue->submit_id = $submit->id;
				$queue->save();

				Request::file('code')->move('judge/codes',$submit->id.'.cpp');

				return redirect('task/myresult');
			}
		}
		return redirect('task/'.$post['task_id']);
	}

	public function rateTask()
	{
		$data = json_decode(file_get_contents("php://input"));
		$task = Task::find($data->task_id);
		$user_id = Auth::user()->id;
		if($task->rating == '') $task->rating = (object) array();
		else $task->rating = json_decode($task->rating);
		$task->rating->$user_id = $data->rate;
		$task->rating = json_encode($task->rating);
		$task->save();
		TaskController::initTaskRating($task);
		return $task->rating;
	}

}
