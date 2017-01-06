<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Pass;
use App\Grading;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class SubmitController extends Controller {

	public function getSubmits(){

		$data = json_decode(file_get_contents("php://input"));
		
		if($data->id == 'myresult')
			$submits = Submit::whereRaw('user_id = ?',[Auth::user()->id])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();
		else if($data->id >= 1)
			$submits = Submit::whereRaw('user_id = ?',[$data->id])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();
		else
			$submits = Submit::orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();

		foreach ($submits as $key => $value) {
			$submits[$key]->user = User::find($value->user_id);
			$submits[$key]->task = Task::find($value->task_id);

			$submits[$key]->status = "graded";
			if($submits[$key]->result != "")
			{
				$allow = "/[PTX-]+$/";
				$check = 1;
				if(strlen($submits[$key]->result) != $submits[$key]->task->testcase + $submits[$key]->task->pretestcase + 1) $check = 0;
				else
				{
					if($submits[$key]->task->testcase != 0)
						if(!preg_match($allow, substr($submits[$key]->result, 0, $submits[$key]->task->testcase))) $check = 0;
					if($submits[$key]->task->pretestcase != 0)
						if(!preg_match($allow, substr($submits[$key]->result, $submits[$key]->task->testcase + 1, $submits[$key]->task->pretestcase))) $check = 0;
				}

				if($check == 0)
				{
					$submits[$key]->compile_result = "มีการแก้ไขข้อมูลทดสอบ กรุณาส่งใหม่";
					$submits[$key]->result = '';
				}
			}

			if(Grading::where('submit_id','=',$value->id)->count())
			{
				$submits[$key]->result = "กำลังตรวจ...";
				$submits[$key]->status = "ungraded";
			}
			else if(Queue::where('submit_id','=',$value->id)->count())
			{
				$submits[$key]->result = "รอตรวจ";
				$submits[$key]->status = "ungraded";
			}
			else if($submits[$key]->result != "")
			{
				$pass = Pass::whereRaw('user_id = ? and task_id = ?', [$value->user_id, $value->task_id])->first();
				$submits[$key]->pass = 0;
				if(isset($pass->id)) $submits[$key]->pass = in_array($value->id, json_decode($pass->submit_data));
				if(!Auth::isAdmin()) $submits[$key]->result = substr($submits[$key]->result, 0, $submits[$key]->task->testcase);
			}

			if($submits[$key]->task->visible == 0 and !Auth::isAdmin())
			{
				$submits[$key] = (object) array();
				$submits[$key]->task = (object) array();
				$submits[$key]->task->visible = 0;
			}
		}
		return json_encode($submits);

	}

	public function getTaskSubmits(){

		$data = json_decode(file_get_contents("php://input"));
		
		if($data->id == 'myresult')
			$submits = Submit::whereRaw('user_id = ? and task_id = ?',[Auth::user()->id, $data->task_id])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();
		else if($data->id >= 1)
			$submits = Submit::whereRaw('user_id = ? and task_id = ?',[$data->id, $data->task_id])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();
		else
			$submits = Submit::whereRaw('task_id = ?',[$data->task_id])->orderBy('created_at', 'desc')->skip($data->skip)->take($data->take)->get();

		foreach ($submits as $key => $value) {
			$submits[$key]->user = User::find($value->user_id);
			$submits[$key]->task = Task::find($value->task_id);
			
			$submits[$key]->status = "graded";
			if($submits[$key]->result != "")
			{
				$allow = "/[PTX-]+$/";
				$check = 1;
				if(strlen($submits[$key]->result) != $submits[$key]->task->testcase + $submits[$key]->task->pretestcase + 1) $check = 0;
				else
				{
					if($submits[$key]->task->testcase != 0)
						if(!preg_match($allow, substr($submits[$key]->result, 0, $submits[$key]->task->testcase))) $check = 0;
					if($submits[$key]->task->pretestcase != 0)
						if(!preg_match($allow, substr($submits[$key]->result, $submits[$key]->task->testcase + 1, $submits[$key]->task->pretestcase))) $check = 0;
				}

				if($check == 0)
				{
					$submits[$key]->compile_result = "มีการแก้ไขข้อมูลทดสอบ กรุณาส่งใหม่";
					$submits[$key]->result = '';
				}
			}

			if(Grading::where('submit_id','=',$value->id)->count())
			{
				$submits[$key]->result = "กำลังตรวจ...";
				$submits[$key]->status = "ungraded";
			}
			else if(Queue::where('submit_id','=',$value->id)->count())
			{
				$submits[$key]->result = "รอตรวจ";
				$submits[$key]->status = "ungraded";
			}
			else if($submits[$key]->result != "")
			{
				$pass = Pass::whereRaw('user_id = ? and task_id = ?', [$value->user_id, $value->task_id])->first();
				$submits[$key]->pass = 0;
				if(isset($pass->id)) $submits[$key]->pass = in_array($value->id, json_decode($pass->submit_data));
				if(!Auth::isAdmin()) $submits[$key]->result = substr($submits[$key]->result, 0, $submits[$key]->task->testcase);
			}

			if($submits[$key]->task->visible == 0 and !Auth::isAdmin())
			{
				$submits[$key] = (object) array();
				$submits[$key]->task = (object) array();
				$submits[$key]->task->visible = 0;
			}
		}
		return json_encode($submits);

	}

}
