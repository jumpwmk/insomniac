<?php namespace App\Http\Controllers;

use App\User;
use App\Config;
use App\Task;
use App\Submit;
use App\Queue;
use App\Pass;
use App\Grading;
use App\Discuss;
use Auth;
use Request;
use Illuminate\Http\RedirectResponse;

class DiscussController extends Controller {

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

	public function getPosts()
	{
		$data = json_decode(file_get_contents("php://input"));

		$searchTerms = explode(' ', $data->key);
		$posts = Discuss::whereRaw('type = ? and pin = 0', ['post'])->orderBy($data->orderBy, $data->orderRev);

		foreach($searchTerms as $term) $posts->where('keywords', 'LIKE', '%'. $term .'%');

		if($data->take == '') $posts = $posts->get();
		else $posts = $posts->skip($data->skip)->take($data->take)->get();

		foreach ($posts as $key => $post) {
			$post->user = User::find($post->user_id);
			if($post->user->display == '') $post->user->display = $post->user->username;

			DiscussController::getComments($post);
			DiscussController::initPostData($post);
			$post->created = strtotime($post->created_at) * 1000;
			$post->updated = strtotime($post->updated_at) * 1000;
			$post->commentDate = strtotime($post->comment_at) * 1000;
		}

		return json_encode($posts);
	}

	public function getPins()
	{
		$data = json_decode(file_get_contents("php://input"));	
		
		$searchTerms = explode(' ', $data->key);
		$posts = Discuss::whereRaw('type = ? and pin = 1', ['post'])->orderBy($data->orderBy, $data->orderRev);

		foreach($searchTerms as $term) $posts->where('keywords', 'LIKE', '%'. $term .'%');
		
		if($data->take == '') $posts = $posts->get();
		else $posts = $posts->skip($data->skip)->take($data->take)->get();

		foreach ($posts as $key => $post) {
			$post->user = User::find($post->user_id);
			if($post->user->display == '') $post->user->display = $post->user->username;

			DiscussController::getComments($post);
			DiscussController::initPostData($post);
			$post->created = strtotime($post->created_at) * 1000;
			$post->updated = strtotime($post->updated_at) * 1000;
			$post->commentDate = strtotime($post->comment_at) * 1000;
		}

		return json_encode($posts);
	}

	public function getPost()
	{
		$data = json_decode(file_get_contents("php://input"));
		$post = Discuss::find($data->post_id);
		if($post->type == "post")
		{
			DiscussController::initPostData($post);
			$tmp = $post->view_data;
			if(Auth::check()) $tmp_stamp = Auth::user()->id;
			else $tmp_stamp = Request::ip();

			if(!in_array($tmp_stamp, $tmp)) array_push($tmp, $tmp_stamp);

			$post->view_data = $tmp;
			$post->view_data = json_encode($post->view_data);
			$post->save();

			DiscussController::initPostData($post);

			DiscussController::getComments($post);
			$post->user = User::find($post->user_id);
			$post->created = strtotime($post->created_at) * 1000;
			$post->updated = strtotime($post->updated_at) * 1000;
			$post->commentDate = strtotime($post->comment_at) * 1000;
			return json_encode($post);
		}
	}
	
	public function addPost()
	{
		$data = json_decode(file_get_contents("php://input"));
		$post = new Discuss;
		$post->user_id = Auth::user()->id;
		$post->type = "post";
		$post->title = $data->title;
		$post->body = $data->body;
		$post->keywords = $post->body.' '.$post->title;
		$post->save();
		return json_encode($post);
	}	

	public function addComment()
	{
		$data = json_decode(file_get_contents("php://input"));
		$comment = new Discuss;
		$comment->post_id = $data->post_id;
		$comment->user_id = Auth::user()->id;
		$comment->type = "comment";
		$comment->body = $data->body;
		$comment->save();

		$comment->user = User::find($comment->user_id);

		$post = Discuss::find($data->post_id);
		$post->comment_at = date("Y-m-d H:i:s");
		$post->save();

		return json_encode($comment);
	}

	public function getPages()
	{
		$posts = Discuss::whereRaw('type = ?', ['post'])->count();
		$page = ceil($posts/10.0);
		return json_encode($page);
	}

	public function removeDiscuss()
	{
		$data = json_decode(file_get_contents("php://input"));
		$discuss = Discuss::find($data->id);
		if(Auth::user()->id == $discuss->user_id or Auth::isAdmin())
		{
			Discuss::whereRaw('post_id = ?', [$data->id])->delete();
			$discuss->delete();
		}
	}

	public function editDiscuss()
	{
		$data = json_decode(file_get_contents("php://input"));
		$discuss = Discuss::find($data->id);
		if(Auth::user()->id == $discuss->user_id or Auth::isAdmin())
		{
			$discuss->title = $data->title;
			$discuss->body = $data->body;
			$discuss->keywords = $discuss->title.' '.$discuss->body;
			$discuss->save();
		}
	}

	public function togglePin()
	{
		$data = json_decode(file_get_contents("php://input"));
		$post = Discuss::find($data->id);
		if($data->pin == '1') $post->pin = 0;
		else $post->pin = 1;
		$post->comment_at = date("Y-m-d H:i:s");
		$post->save();
	}

}
