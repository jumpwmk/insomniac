<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Contest;
use App\Problem;
use App\Submit;
use App\Contestant;
use App\Pass;
use App\Queue;
use Auth;
use App\Library\RatingController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {

	// Configs

	public function getConfigs() {
		return json_encode(Config::main());
	}

	public function editConfigs() {

		$data = json_decode(file_get_contents("php://input"));
		
		$config = Config::main();
		$config->title = $data->title;
		$config->logo = $data->logo;
		$config->root = $data->root;
		$config->custom = $data->custom;
		$config->online = $data->online;
		$config->allow_register = $data->allow_register;
		$config->save();

		$data->isSuccess = true;
		$data->success_msg = 'บันทึกเรียบร้อย';
		return json_encode($data);

	}

	public function getGraderInfo() {

		$info = json_decode(file_get_contents("php://input"));
		$pids = explode(' ', shell_exec('pidof nohup php judge/grading_'.$info->grader_id.'.php 2>&1'));
		$cur_pid = 0;
		foreach ($pids as $key => $pid) {
			$grader_info = shell_exec('ps '.$pid.' 2>&1');
			if(strstr($grader_info, 'php judge/grading_'.$info->grader_id.'.php') != false)
			{
				$cur_pid = $pid;
				break;
			}
		}

		$data = (object) array();
		if(strstr($grader_info, 'php judge/grading_'.$info->grader_id.'.php') != false)
		{
			$data->working = true;
			$data->pid = $cur_pid;
		}
		else
		{
			$data->working = false;
		}	
		return json_encode($data);
	}

	public function startGrader() {

		$info = json_decode(file_get_contents("php://input"));
		shell_exec('nohup php judge/grading_'.$info->grader_id.'.php </dev/null &>/dev/null &');
	}

	public function stopGrader() {

		$info = json_decode(file_get_contents("php://input"));
		$pids = explode(' ', shell_exec('pidof nohup php judge/grading_'.$info->grader_id.'.php 2>&1'));

		foreach ($pids as $key => $pid) {
			$grader_info = shell_exec('ps '.$pid.' 2>&1');
			if(strstr($grader_info, 'php judge/grading_'.$info->grader_id.'.php') != false) shell_exec('kill '.$pid);
		}

		$data = (object) array();
		$data->working = false;
		return json_encode($data);
	}

	// Tasks

	public function getTasks() {
		return json_encode(Task::all());
	}	

	public function addTask() {

		$data = json_decode(file_get_contents("php://input"));

		$task = new Task;
		$task->name = $data->name;
		$task->pretestcase = $data->pretestcase;
		$task->testcase = $data->testcase;
		$task->time = $data->time;
		$task->memory = $data->memory;
		$task->rating = '{}';
		$task->visible = $data->visible;
		$task->general_check = $data->general_check;
		$task->tags = $data->tags;

		$data->error_msg = "";

		if(Task::where('name','=',$task->name)->first())
		{	
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "ชื่อโจทย์นี้ถูกใช้งานแล้ว";
		}
		if(!isset($data->isSuccess))
		{
			$task->save();
			$data->id = $task->id;
			$data->isSuccess = true;
		}

		return json_encode($data);

	}

	public function removeTask() {

		$data = json_decode(file_get_contents("php://input"));

		$task = Task::find($data->id);
		$task->delete();

		Problem::where("task_id", "=", $data->id)->delete();
		Submit::whereRaw("task_id = ?",[$data->id])->delete();

		$data->isSuccess = true;

		return json_encode($data);

	}

	public function infoFileTask() {

		$task = json_decode(file_get_contents("php://input"));
		$data = $task;

		$data->pin = 0;
		$data->psol = 0;
		$data->pin_list = "";
		$data->psol_list = "";

		for($i = 1; $i <= $task->pretestcase; $i++)
		{
			$sub = 'p'.$i.'in';
			$data->$sub = file_exists('judge/testcases/'.$task->id.'/p'.$i.'.in');
			$data->pin += $data->$sub;
			if(!$data->$sub) $data->pin_list .= 'p'.$i.'.in ';
			$sub = 'p'.$i.'sol';
			$data->$sub = file_exists('judge/testcases/'.$task->id.'/p'.$i.'.sol');
			$data->psol += $data->$sub;
			if(!$data->$sub) $data->psol_list .= 'p'.$i.'.sol ';
		}

		if($data->pin_list == "") $data->pin_list = "ไม่มี";
		if($data->psol_list == "") $data->psol_list = "ไม่มี";

		$data->in = 0;
		$data->sol = 0;
		$data->in_list = "";
		$data->sol_list = "";

		for($i = 1; $i <= $task->testcase; $i++)
		{
			$sub = $i.'in';
			$data->$sub = file_exists('judge/testcases/'.$task->id.'/'.$i.'.in');
			$data->in += $data->$sub;
			if(!$data->$sub) $data->in_list .= $i.'.in ';
			$sub = $i.'sol';
			$data->$sub = file_exists('judge/testcases/'.$task->id.'/'.$i.'.sol');
			$data->sol += $data->$sub;
			if(!$data->$sub) $data->sol_list .= $i.'.sol ';
		}

		if($data->in_list == "") $data->in_list = "ไม่มี";
		if($data->sol_list == "") $data->sol_list = "ไม่มี";

		$data->checkcode = file_exists('judge/checkcodes/'.$task->id.'.cpp');

		$data->doc = file_exists('judge/docs/'.$task->id.'.pdf');

		return json_encode($data);
	}

	public function editTask() {

		$data = json_decode(file_get_contents("php://input"));
		$task = Task::find($data->id);
		$task->pretestcase = $data->pretestcase;
		$task->testcase = $data->testcase;
		$task->time = $data->time;
		$task->memory = $data->memory;
		$task->general_check = $data->general_check;
		$task->visible = $data->visible;
		$task->tags = $data->tags;

		if(Task::where('name','=',$data->name)->first() and $task->name != $data->name)
		{
			$data->isSuccess = false;
			$data->error_msg = "มีโจทย์ชื่อนี้แล้ว";
		}
		else
		{
			$task->name = $data->name;
			$data->success_msg = "บันทึกเรียบร้อย";
			$task->save();
			$data->isSuccess = true;
		}

		return json_encode($data);

	}

	public function rejudgeTask() {

		$data = json_decode(file_get_contents("php://input"));

		$pass = Pass::where('task_id', '=', $data->id)->delete();
		$submits = Submit::where('task_id', '=', $data->id)->get();
		foreach ($submits as $submit) {
			if(Queue::where('submit_id', '=', $submit->id)->count() == 0)
			{
				$submit->result = '';
				$submit->time = 0;
				$submit->memory = 0;
				$submit->save();
				$queue = new Queue;
				$queue->submit_id = $submit->id;
				$queue->save();
			}
		}

		$data->isSuccess = true;
		return json_encode($data);
	}

	// Submits

	function rejudge($submit_id, $user_id, $task_id) {

		$pass = Pass::where('user_id', '=', $user_id)->where('task_id', '=', $task_id)->first();
		if($pass)
		{
			$tmp = json_decode($pass->submit_data);
			$tmp2 = array();
			foreach ($tmp as $id) {
				if($id != $submit_id) array_push($tmp2, $id);
			}
			if(count($tmp2)==0) $pass->delete();
			else
			{
				$pass->submit_data = json_encode($tmp2);
				$pass->save();
			}
		}
		$queue = new Queue;
		$queue->submit_id = $submit_id;
		$queue->save();

	}

	public function rejudgeSubmit() {

		$data = json_decode(file_get_contents("php://input"));

		$submit = Submit::find($data->id);
		$submit->result = '';
		$submit->time = 0;
		$submit->memory = 0;
		$submit->save();
		if(Queue::where('submit_id', '=', $submit->id)->count() == 0) AdminController::rejudge($submit->id, $submit->user_id, $submit->task_id);

		$data->isSuccess = true;
		return json_encode($data);

	}

	// Users

	public function getUsers() {
		return json_encode(User::all());
	}

	public function addUser() {

		$data = json_decode(file_get_contents("php://input"));

		$user = new User;
		$user->username = $data->username;
		$user->password = Hash::make($data->password);
		$user->email = $data->email;
		$user->display = $data->display;
		$user->admin = $data->admin;

		$data->error_msg = "";
		$notAllow = array("task", "profile", "contest", "admin", "discuss", "message", "main");

		if(!preg_match("/[A-Za-z0-9_]+$/",$user->username) or strlen($user->password) < 8)// or ($data->password != $data->confirmPassword))
		{
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "มีบางอย่างผิดพลาด";
		}
		if(User::where('username','=',$user->username)->first() or in_array($user->username, $notAllow))
		{	
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
		}
		if(User::where('email','=',$user->email)->first())
		{
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "อีเมลนี้ถูกใช้แล้ว";
		}
		if($user->display != '' and User::where('display','=',$user->display)->first())
		{	
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "ชื่อที่ใช้แสดงนี้ถูกใช้แล้ว";
		}
		if(!isset($data->isSuccess))
		{
			$user->display = $user->username;
			for($i = 1;; $i++)
			{
				if(User::where('display', '=', $user->display)->first())
					$user->display = $user->username.'_'.$i;
				else
					break;
			}
			$user->save();
			$data->id = $user->id;
			$data->isSuccess = true;
		}

		return json_encode($data);

	}

	public function editUser() {

		$data = json_decode(file_get_contents("php://input"));

		$user = User::find($data->id);

		$data->error_msg = "";

		if(User::where('email','=',$data->email)->first() and $user->email != $data->email)
		{
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "อีเมลนี้ถูกใช้แล้ว";
		}
		if($data->display != '' and User::where('display','=',$data->display)->first() and strtolower($user->display) != strtolower($data->display))
		{	
			$data->isSuccess = false;
			if($data->error_msg != "") $data->error_msg .= ", ";
			$data->error_msg .= "ชื่อที่ใช้แสดงนี้ถูกใช้แล้ว";
		}
		if(!isset($data->isSuccess))
		{
			$user->email = $data->email;
			$user->display = $data->display;
			$user->admin = $data->admin;
			$user->save();
			$data = $user;
			$data->success_msg = "บันทักเรียบร้อย";
			$data->isSuccess = true;
		}

		return json_encode($data);
	}

	public function removeUser() {

		$data = json_decode(file_get_contents("php://input"));
		User::find($data->id)->delete();
		Submit::whereRaw("user_id = ?",[$data->id])->delete();
		Contestant::where("user_id", "=", $data->id)->delete();
		$data->isSuccess = true;
		return json_encode($data);
	}


	public function removeContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		Contest::find($data->id)->delete();
		Problem::where("contest_id", "=", $data->id)->delete();
		Contestant::where("contest_id", "=", $data->id)->delete();
		$data->isSuccess = 1;
		return json_encode($data);
	}

	public function editContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->id);
		foreach ($data as $key => $value) {
			$contest->$key = $value;
		}
		$contest->save();
	}

	public function addContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = new Contest;
		foreach ($data as $key => $value) {
			$contest->$key = $value;
		}
		
		$contest->save();

		return json_encode($contest);
	}

	public function getContests()
	{
		$data = Contest::all();
		return json_encode($data);
	}

	public function getTaskContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$tasks = Problem::where('contest_id', '=', $data->id)->get();
		foreach ($tasks as $key => $value) {
			$order = $value->order;
			$contest_id = $value->contest_id;
			$tasks[$key] = Task::find($value->task_id);
			$tasks[$key]->order = $order;
			$tasks[$key]->contest_id = $contest_id;
		}
		return json_encode($tasks);
	}

	public function saveTaskContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$tmp = Problem::where('contest_id', '=', $data->contest_id)->get();
		Problem::where('contest_id', '=', $data->contest_id)->delete();
		foreach ($data->tasks as $key => $value) {
			$problem = new Problem;
			$problem->order = $value->order;
			$problem->task_id = $value->id;
			$problem->contest_id = $data->contest_id;

			foreach ($tmp as $k => $v) {
				if($v->order == $value->order)
				{
					if($v->task_id == $value->id)
						$problem->data = $v->data;
					break;
				}
			}

			$problem->save();
		}
	}

	public function saveDetailContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		$contest = Contest::find($data->id);
		$contest->detail = $data->detail;
		$contest->save();
		return json_encode($contest);
	}

	public function updateRating()
	{
		RatingController::updateRating();
	}

	public function updateContestRating()
	{
		$contest = json_decode(file_get_contents("php://input"));
		RatingController::onceUpdateRating($contest);
	}

}
