<?php namespace App\Http\Controllers;

use App\User;
use App\Submit;
use App\Task;
use App\Codestyle;
use App\Contestant;
use App\Contest;
use App\Pass;
use App\Discuss;
use App\Message;
use App\Session;
use Request;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

	function inSession($user_id) {

		Session::whereRaw('signin_at < ?', [date("Y-m-d H:i:s", strtotime('-1 month'))])->delete();

		$session = new Session;
		$session->user_id = $user_id;
		$session->ip = Request::ip();
		$session->signin_at = date("Y-m-d H:i:s");
		$session->save();
	}

	function outSession($user_id) {

		$session = Session::whereRaw('ip = ? and user_id = ?', [Request::ip(), $user_id])->orderBy('id', 'desc')->first();
		if($session)
		{
			$session->signout_at = date("Y-m-d H:i:s");
			$session->save();
		}
	}
	
	public function doSignin() {

		$data = json_decode(file_get_contents("php://input"));
		$user = new User;
		$user->username = $data->username;
		$user->password = $data->password;

		if(Auth::attempt(['username' => $user->username, 'password' => $user->password]) or Auth::attempt(['email' => $user->username, 'password' => $user->password]))
		{
			$data->isSuccess = true;
			UserController::inSession(Auth::user()->id);
		}
		else
		{
			$data->isSuccess = false;
			$data->error_msg = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
		}

		return json_encode($data);

	}

	public function doSignup() {
		
		$data = json_decode(file_get_contents("php://input"));

		$user = new User;
		$user->username = $data->username;
		$user->password = Hash::make($data->password);
		$user->email = $data->email;

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
			Auth::login($user);
			UserController::inSession(Auth::user()->id);
			$data->isSuccess = true;
		}

		return json_encode($data);
	}

	public function doSignout() {
		
		UserController::outSession(Auth::user()->id);
		Auth::logout();
		return Redirect::To('main');

	}

	public function getUserInfo()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->username == '') $data->username = Auth::user()->username;
		$user = User::where('username', '=', $data->username)->first();
		$user->codestyle = Codestyle::where('file_name', '=', $user->codestyle)->first();
		return json_encode($user);
	}

	public function saveStyle()
	{
		$data = json_decode(file_get_contents("php://input"));
		$user = Auth::user();
		$user->codestyle = $data->file_name;
		$user->save();
	}

	public function uploadImage()
	{
		if(Request::hasFile('img'))
		{
			$image = Request::file('img');
			$allow = explode('/', $image->getMimeType());
			if($allow[0] == 'image')
			{
				$user = new User;
				$user = Auth::user();
				$user->image = 1;
				$user->save();
				$image->move('img/user', $user->username.'.jpg');
			}
			else
			{
				echo "ไฟล์ไม่ถูกต้อง (กำลังไปยังหน้าผู้ใช้ใน 3 วินาที...)";
				return '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=..">';
			}
		}
		return '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=..">';
	}

	public function saveUserInfo()
	{
		$data = json_decode(file_get_contents("php://input"));

		$user = new User;
		$user = Auth::user();

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
			$user->save();
			$data = $user;
			$data->success_msg = "บันทักเรียบร้อย";
			$data->isSuccess = true;
		}

		return json_encode($data);
	}

	public function changePassword()
	{
		$data = json_decode(file_get_contents("php://input"));

		$user = new User;
		$user = Auth::user();
		if(Auth::attempt(['username' => $user->username, 'password' => $data->old]))
		{
			if($data->new == $data->confirmNew)
			{
				$user->password = Hash::make($data->new);
				$user->save();
				$data->success_msg = "บันทักเรียบร้อย";
				$data->isSuccess = true;
			}
			else
			{
				$data->success_msg = "หรรม!!! อย่ามาแฮคสิ ไม่ไหวๆ";
				$data->isSuccess = false;
			}
		}
		else
		{
			$data->success_msg = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
			$data->isSuccess = false;
		}
		return json_encode($data);
	}

	public function getUserTask()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->username == '') $data->username = Auth::user()->username;
		$user = User::where('username', '=', $data->username)->first();	
		$submits = Submit::where('user_id', '=', $user->id)->orderBy('id', 'asc')->get();
		
		$data->recent = array();
		$recent = 0;
		$data->pass = array();
		$pass = 0;
		$data->notpass = array();
		$notpass = 0;

		foreach ($submits as $key => $value) {

			if(!isset($check[$value->task_id]))
			{
				$check[$value->task_id] = 1;
				array_push($data->recent, Task::find($value->task_id));
				if($data->recent[$recent]->visible == 1)
				{
					$data->recent[$recent]->date = $value->created_at;
					$data->recent[$recent]->pass = Pass::whereRaw('task_id = ? and user_id = ?',[$value->task_id, $user->id])->count();
					if($data->recent[$recent]->pass != 0)
					{
						$pass = Pass::whereRaw('task_id = ? and user_id = ?',[$value->task_id, $user->id])->first();
						$pass->submit_data = json_decode($pass->submit_data);
						$data->recent[$recent]->submit = Submit::find($pass->submit_data[0]);

						array_push($data->pass, $data->recent[$recent]);
						$pass++;
					}
					else
					{
						$data->recent[$recent]->submit = $value;

						array_push($data->notpass, $data->recent[$recent]);
						$notpass++;
					}
					$recent++;
				}
				else
					array_pop($data->recent);
			}
		}

		return json_encode($data);

	}

	public function getUserContest()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->username == '') $data->username = Auth::user()->username;
		$me = User::where("username", "=", $data->username)->first();

		$contestants = Contestant::where("user_id", "=", $me->id)->get();
		$contests = array();
		
		foreach ($contestants as $key => $contestant) 
		{
			if($contestant->data != '')
			{
				$contestant->data = json_decode($contestant->data);
				$contest = Contest::find($contestant->contest_id);
				$contest->contestDate = strtotime($contest->end_contest) * 1000;
				$contest->contestant = Contestant::whereRaw("contest_id = ?",[$contest->id])->count();
				$contest->place = $contestant->data->place;
				$contest->rating = floor($contestant->data->rating);

				if($contest->place != 0)
					array_push($contests, $contest);
			}

		}
		return json_encode($contests);
	}

	public function getUsers()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->take == '')
			$users = User::orderBy($data->orderBy, $data->orderRev)->get();
		else
			$users = User::orderBy($data->orderBy, $data->orderRev)->skip($data->skip)->take($data->take)->get();

		foreach ($users as $key => $user) {
			if(Auth::check())
			{
				if(Auth::user()->id == $user->id)
					$user->me = 1;
			}
			$user->pass = Pass::whereRaw('user_id = ?', [$user->id])->count();
			$user->contest = Contestant::whereRaw('user_id = ?', [$user->id])->count();
		}

		return json_encode($users);
	}

	function getComments($post)
	{
		$post->comments = Discuss::whereRaw('type = ? and post_id = ?', ['comment', $post->id])->orderBy('created_at', 'asc')->get();
		foreach ($post->comments as $key => $comment) {
			$comment->user = User::find($comment->user_id);
		}
	}

	function initPostData($post)
	{
		if($post->view_data != '') $post->view_data = json_decode($post->view_data);
		else $post->view_data = array();
	}

	public function getUserDiscuss()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->username != '') $user = User::where('username', '=', $data->username)->first();
		else $user = Auth::user();
		$discuss = (object) array();
		$discuss->posts = Discuss::whereRaw('user_id = ? and type = "post"', [$user->id])->get();
		foreach ($discuss->posts as $key => $post) {
			UserController::getComments($post);
			UserController::initPostData($post);
			$post->created = strtotime($post->created_at) * 1000;
			$post->updated = strtotime($post->updated_at) * 1000;
			$post->commentDate = strtotime($post->comment_at) * 1000;
		}

		$discuss->comments = Discuss::whereRaw('user_id = ? and type = "comment"', [$user->id])->get();
		return json_encode($discuss);
	}

	public function getUserMessage()
	{
		$data = json_decode(file_get_contents("php://input"));
		if($data->username != '' && $data->username != Auth::user()->username)
		{
			$user = User::where('username', '=', $data->username)->first();
			$msgs = Message::whereRaw('(to_user = ? and from_user = ?) or (to_user = ? and from_user = ?)', [Auth::user()->id, $user->id, $user->id, Auth::user()->id])->orderBy('created_at', 'DESC')->get();
		}
		else
			$msgs = Message::whereRaw('to_user = ? or from_user = ?', [Auth::user()->id, Auth::user()->id])->orderBy('created_at', 'DESC')->get();
		foreach ($msgs as $key => $msg) {
			$msg->created = strtotime($msg->created_at) * 1000;
			$msg->updated = strtotime($msg->updated_at) * 1000;
			$msg->to_user = User::find($msg->to_user);
			$msg->from_user = User::find($msg->from_user);
		}
		return json_encode($msgs);
	}

}
