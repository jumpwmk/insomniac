<?php namespace App\Http\Controllers;

use App\User;
use App\Message;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller {

	public function sendMessage()
	{
		$data = json_decode(file_get_contents("php://input"));
		$user = User::where('username', '=', $data->username)->first();
		$msg = new Message;
		$msg->body = $data->body;
		$msg->from_user = Auth::user()->id;
		$msg->to_user = $user->id;
		$msg->read = false;
		$msg->save();
		return json_encode(true);
	}

	public function readMessage()
	{
		$data = json_decode(file_get_contents("php://input"));
		$msg = Message::find($data->msg_id);
		if($msg->to_user == Auth::user()->id)
		{
			$msg->read = true;
			$msg->save();
		}
	}

	public function unReadMessage()
	{
		$count_msgs = Message::whereRaw('to_user = ? and `read` = 0', [Auth::user()->id])->count();
		return $count_msgs;
	}

}
