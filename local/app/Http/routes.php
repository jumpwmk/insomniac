<?php

date_default_timezone_set('Asia/Bangkok');

Route::group(['middleware' => 'guest'], function(){

	Route::get('signin', function(){return view('signin');});
	Route::post('active/doSignin', 'UserController@doSignin');

	Route::group(['middleware' => 'online'], function(){
		
		Route::group(['middleware' => 'register'], function(){
			
			Route::get('signup', function(){return view('signup');});
			Route::post('active/doSignup', 'UserController@doSignup');
		});

		// Task
		Route::post('task/active/doSignin', 'UserController@doSignin');
	});

});

Route::group(['middleware' => 'online'], function(){
	
	Route::get('/', function(){return view('main');});
	Route::get('main', function(){return view('main');});
	Route::get('users', function(){return view('users');});

	Route::group(['prefix' => 'active'], function(){
			
		// User info
		Route::post('getUserInfo', 'UserController@getUserInfo');
		Route::post('getUserTask', 'UserController@getUserTask');
		Route::post('getUserContest', 'UserController@getUserContest');

		// Contest
		Route::get('getContests', 'ContestController@getContests');

		// Task
		Route::post('getTasks', 'TaskController@getTasks');

		// User
		Route::post('getUsers', 'UserController@getUsers');

		//Discuss
		Route::post('getPins', 'DiscussController@getPins');
		Route::post('getPosts', 'DiscussController@getPosts');
	});

	Route::group(['middleware' => 'auth'], function(){

		Route::get('signout', 'UserController@doSignout');

		// Task
		Route::post('task/active/upload', ['as' => 'task.active.upload', 'uses' => 'TaskController@upload']);

		// Code
		Route::get('code/{submit_id?}', function($submit_id = 0){return view('code', ['submit_id' => $submit_id]);});

		// Admin
		Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function(){

			Route::get('/{page?}', function($page = 'main'){return view('admin.'.$page);});
			
			Route::group(['prefix' => 'active'], function(){

				// Configs
				Route::get('getConfigs', 'AdminController@getConfigs');
				Route::post('editConfigs', 'AdminController@editConfigs');
				Route::post('getGraderInfo', 'AdminController@getGraderInfo');
				Route::post('startGrader', 'AdminController@startGrader');
				Route::post('stopGrader', 'AdminController@stopGrader');

				//Tasks
				Route::get('getTasks', 'AdminController@getTasks');
				Route::post('addTask', 'AdminController@addTask');
				Route::post('removeTask', 'AdminController@removeTask');
				Route::post('infoFileTask', 'AdminController@infoFileTask');
				Route::post('editTask', 'AdminController@editTask');
				Route::post('rejudgeTask', 'AdminController@rejudgeTask');

				// Contest
				Route::get('getContests', 'AdminController@getContests');
				Route::post('addContest', 'AdminController@addContest');
				Route::post('removeContest', 'AdminController@removeContest');
				Route::post('editContest', 'AdminController@editContest');
				Route::post('getTaskContest', 'AdminController@getTaskContest');
				Route::post('saveTaskContest', 'AdminController@saveTaskContest');
				Route::post('saveDetailContest', 'AdminController@saveDetailContest');
				Route::get('updateRating', 'AdminController@updateRating');
				Route::post('updateContestRating', 'AdminController@updateContestRating');
				
				// Users
				Route::get('getUsers', 'AdminController@getUsers');
				Route::post('addUser', 'AdminController@addUser');
				Route::post('editUser', 'AdminController@editUser');
				Route::post('removeUser', 'AdminController@removeUser');

			});

		});

	});

	//Task
	Route::group(['prefix' => 'task'], function(){

		Route::get('result', function(){return view('task.result', ['active' => 'result']);});
		Route::get('myresult', function(){return view('task.result', ['active' => 'myresult']);});
		Route::get('{page?}', function($page = 'all'){return view('task.main', ['active' => $page]);})->where('page', '[A-Za-z]+');
		Route::get('{id?}', function($id){return view('task.info', ['active' => $id]);})->where('id', '[0-9]+');
		Route::get('{id?}/result', function($id){return view('task.taskResult', ['task_id' => $id, 'active' => 'result']);})->where('id', '[0-9]+');
		Route::get('{id?}/myresult', function($id){return view('task.taskResult', ['task_id' => $id, 'active' => 'myresult']);})->where('id', '[0-9]+');

		Route::post('{id?}/active/getTaskSubmits', 'SubmitController@getTaskSubmits')->where('id', '[0-9]+');
		Route::post('{id?}/active/infoTask', 'TaskController@infoTask')->where('id', '[0-9]+');
		Route::group(['middleware' => 'admin'], function(){
				Route::post('{id?}/active/rejudgeSubmit', 'AdminController@rejudgeSubmit');
			});

		Route::group(['prefix' => 'active'], function(){

			// Task
			Route::post('getTasks', 'TaskController@getTasks');
			Route::post('infoTask', 'TaskController@infoTask');

			// Submit
			Route::post('getSubmits', 'SubmitController@getSubmits');
			Route::post('getTaskSubmits', 'SubmitController@getTaskSubmits');

			Route::group(['middleware' => 'admin'], function(){
				Route::post('rejudgeSubmit', 'AdminController@rejudgeSubmit');
			});

			Route::group(['middleware' => 'auth'], function (){
				// Rating
				Route::post('rateTask', 'TaskController@rateTask');
			});

		});

	});

	//Contest
	Route::group(['prefix' => 'contest'], function(){

		Route::group(['prefix' => 'active'], function(){

			Route::get('getContests', 'ContestController@getContests');
			Route::post('getContest', 'ContestController@getContest');

		});

		Route::group(['middleware' => 'auth'], function (){

			Route::get('/register/{contest_id?}/accept', ['uses' => 'ContestController@acceptRegister']);
			Route::get('/registered', function(){return view('contest.registered');});

		});

		// General
		Route::post('/{contest_page?}/active/getContest', 'ContestController@getContest');

		// Normal
		Route::group(['prefix' => 'normal'], function(){

			Route::get('/{contest_id?}', function($contest_id){return redirect('contest/normal/'.$contest_id.'/task');});
			Route::get('/{contest_id?}/task', function($contest_id){return view('contest.normal.main', ['contest_id' => $contest_id,'active' => 'task']);});
			Route::get('/{contest_id?}/task/{task_order?}', function($contest_id, $task_order){return view('contest.normal.task', ['contest_id' => $contest_id,'active' => 'task', 'task_order' => $task_order]);});
			Route::get('/{contest_id?}/scoreboard', function($contest_id){return view('contest.normal.scoreboard', ['contest_id' => $contest_id,'active' => 'scoreboard']);});

			Route::post('/{contest_id?}/task/active/getContest', 'NormalController@getContest');
			Route::post('/{contest_id?}/task/active/getTasks', 'NormalController@getTasks');
			Route::post('/{contest_id?}/active/getContest', 'NormalController@getContest');
			Route::post('/{contest_id?}/active/getTasks', 'NormalController@getTasks');
			Route::post('/{contest_id?}/active/getContestants', 'NormalController@getContestants');
			Route::post('/{contest_id?}/active/saveScoreboard', 'NormalController@saveScoreboard');
			
			Route::group(['middleware' => 'auth'], function(){
				Route::get('/{contest_id?}/result', function($contest_id){return view('contest.normal.result', ['contest_id' => $contest_id,'active' => 'result']);});
				Route::post('/{contest_id?}/active/getSubmits', 'NormalController@getSubmits');
				Route::post('/{contest_id?}/task/active/getSubmits', 'NormalController@getSubmits');
				Route::post('/active/upload', ['as' => 'contest.normal.active.upload', 'uses' => 'NormalController@upload']);
				
				Route::group(['middleware' => 'admin'], function(){
					Route::get('/{contest_id?}/config', function($contest_id){return view('contest.normal.config', ['contest_id' => $contest_id]);});
					Route::post('/{contest_id?}/active/saveScore', 'NormalController@saveScore');
					Route::post('/{contest_id?}/active/saveData', 'NormalController@saveData');
				});
			});

		});

		// Test run
		Route::group(['prefix' => 'testrun'], function(){

			Route::get('/{contest_id?}', function($contest_id){return redirect('contest/testrun/'.$contest_id.'/task');});
			Route::get('/{contest_id?}/task', function($contest_id){return view('contest.testrun.main', ['contest_id' => $contest_id,'active' => 'task']);});
			Route::get('/{contest_id?}/task/{task_order?}', function($contest_id, $task_order){return view('contest.testrun.task', ['contest_id' => $contest_id,'active' => 'task', 'task_order' => $task_order]);});
			Route::get('/{contest_id?}/scoreboard', function($contest_id){return view('contest.testrun.scoreboard', ['contest_id' => $contest_id,'active' => 'scoreboard']);});

			Route::post('/{contest_id?}/task/active/getContest', 'TestrunController@getContest');
			Route::post('/{contest_id?}/task/active/getTasks', 'TestrunController@getTasks');
			Route::post('/{contest_id?}/active/getContest', 'TestrunController@getContest');
			Route::post('/{contest_id?}/active/getTasks', 'TestrunController@getTasks');
			Route::post('/{contest_id?}/active/getContestants', 'TestrunController@getContestants');
			Route::post('/{contest_id?}/active/saveScoreboard', 'TestrunController@saveScoreboard');
			
			Route::group(['middleware' => 'auth'], function(){
				Route::get('/{contest_id?}/result', function($contest_id){return view('contest.testrun.result', ['contest_id' => $contest_id,'active' => 'result']);});
				Route::post('/{contest_id?}/active/getSubmits', 'TestrunController@getSubmits');
				Route::post('/{contest_id?}/task/active/getSubmits', 'TestrunController@getSubmits');
				Route::post('/active/upload', ['as' => 'contest.testrun.active.upload', 'uses' => 'TestrunController@upload']);
				
				Route::group(['middleware' => 'admin'], function(){
					Route::get('/{contest_id?}/config', function($contest_id){return view('contest.testrun.config', ['contest_id' => $contest_id]);});
					Route::post('/{contest_id?}/active/saveScore', 'TestrunController@saveScore');
					Route::post('/{contest_id?}/active/saveData', 'TestrunController@saveData');
				});
			});

		});	
		// partial_feedback
		Route::group(['prefix' => 'partial_feedback'], function(){

			Route::get('/{contest_id?}', function($contest_id){return redirect('contest/partial_feedback/'.$contest_id.'/task');});
			Route::get('/{contest_id?}/task', function($contest_id){return view('contest.partial_feedback.main', ['contest_id' => $contest_id,'active' => 'task']);});
			Route::get('/{contest_id?}/task/{task_order?}', function($contest_id, $task_order){return view('contest.partial_feedback.task', ['contest_id' => $contest_id,'active' => 'task', 'task_order' => $task_order]);});
			Route::get('/{contest_id?}/scoreboard', function($contest_id){return view('contest.partial_feedback.scoreboard', ['contest_id' => $contest_id,'active' => 'scoreboard']);});

			Route::post('/{contest_id?}/task/active/getContest', 'PartialFeedbackController@getContest');
			Route::post('/{contest_id?}/task/active/getTasks', 'PartialFeedbackController@getTasks');
			Route::post('/{contest_id?}/active/getContest', 'PartialFeedbackController@getContest');
			Route::post('/{contest_id?}/active/getTasks', 'PartialFeedbackController@getTasks');
			Route::post('/{contest_id?}/active/getContestants', 'PartialFeedbackController@getContestants');
			Route::post('/{contest_id?}/active/saveScoreboard', 'PartialFeedbackController@saveScoreboard');
			
			Route::group(['middleware' => 'auth'], function(){
				Route::get('/{contest_id?}/result', function($contest_id){return view('contest.partial_feedback.result', ['contest_id' => $contest_id,'active' => 'result']);});
				Route::post('/{contest_id?}/active/getSubmits', 'PartialFeedbackController@getSubmits');
				Route::post('/{contest_id?}/task/active/getSubmits', 'PartialFeedbackController@getSubmits');
				Route::post('/active/upload', ['as' => 'contest.partial_feedback.active.upload', 'uses' => 'PartialFeedbackController@upload']);
				
				Route::group(['middleware' => 'admin'], function(){
					Route::get('/{contest_id?}/config', function($contest_id){return view('contest.partial_feedback.config', ['contest_id' => $contest_id]);});
					Route::get('/{contest_id?}/debug', function($contest_id){return view('contest.partial_feedback.debug', ['contest_id' => $contest_id]);});
					
					Route::post('/{contest_id?}/active/saveScore', 'PartialFeedbackController@saveScore');
					Route::post('/{contest_id?}/active/saveData', 'PartialFeedbackController@saveData');
				});
			});

		});	
		// acm_contest
		Route::group(['prefix' => 'acm_contest'], function(){

			Route::get('/{contest_id?}', function($contest_id){return redirect('contest/acm_contest/'.$contest_id.'/task');});
			Route::get('/{contest_id?}/task', function($contest_id){return view('contest.acm_contest.main', ['contest_id' => $contest_id,'active' => 'task']);});
			Route::get('/{contest_id?}/task/{task_order?}', function($contest_id, $task_order){return view('contest.acm_contest.task', ['contest_id' => $contest_id,'active' => 'task', 'task_order' => $task_order]);});
			Route::get('/{contest_id?}/scoreboard', function($contest_id){return view('contest.acm_contest.scoreboard', ['contest_id' => $contest_id,'active' => 'scoreboard']);});

			Route::post('/{contest_id?}/task/active/getContest', 'ACMContestController@getContest');
			Route::post('/{contest_id?}/task/active/getTasks', 'ACMContestController@getTasks');
			Route::post('/{contest_id?}/active/getContest', 'ACMContestController@getContest');
			Route::post('/{contest_id?}/active/getTasks', 'ACMContestController@getTasks');
			Route::post('/{contest_id?}/active/getContestants', 'ACMContestController@getContestants');
			Route::post('/{contest_id?}/active/saveScoreboard', 'ACMContestController@saveScoreboard');
			
			Route::group(['middleware' => 'auth'], function(){
				Route::get('/{contest_id?}/result', function($contest_id){return view('contest.acm_contest.result', ['contest_id' => $contest_id,'active' => 'result']);});
				Route::post('/{contest_id?}/active/getSubmits', 'ACMContestController@getSubmits');
				Route::post('/{contest_id?}/task/active/getSubmits', 'ACMContestController@getSubmits');
				Route::post('/active/upload', ['as' => 'contest.acm_contest.active.upload', 'uses' => 'ACMContestController@upload']);
				
				Route::group(['middleware' => 'admin'], function(){
					Route::get('/{contest_id?}/config', function($contest_id){return view('contest.acm_contest.config', ['contest_id' => $contest_id]);});
					Route::get('/{contest_id?}/debug', function($contest_id){return view('contest.acm_contest.debug', ['contest_id' => $contest_id]);});
					
					Route::post('/{contest_id?}/active/saveScore', 'ACMContestController@saveScore');
					Route::post('/{contest_id?}/active/saveData', 'ACMContestController@saveData');
				});
			});

		});	

		// Register
		Route::get('/register/{contest_id?}', function($contest_id){return view('contest.register', ['contest_id' => $contest_id]);});
		
		Route::get('/{page?}', function($page = 'main'){return view('contest.'.$page);});

	});

	//Profile
	Route::group(['prefix' => 'profile'], function(){

		Route::group(['prefix' => 'active'], function(){

			Route::post('getUserInfo', 'UserController@getUserInfo');
			Route::post('getUserContest', 'UserController@getUserContest');
		});

		Route::group(['middleware' => 'auth'], function(){

			Route::get('message/{user?}', function($user = null){return view('profile.message', ['user' => $user]);});
			Route::post('message/active/getUserInfo', 'UserController@getUserInfo');
			Route::post('message/active/getUserMessage', 'UserController@getUserMessage');
			Route::post('message/active/readMessage', 'MessageController@readMessage');
			Route::get('message/active/unReadMessage', 'MessageController@unReadMessage');
			Route::get('task/active/unReadMessage', 'MessageController@unReadMessage');
			Route::get('contest/active/unReadMessage', 'MessageController@unReadMessage');
			Route::get('discuss/active/unReadMessage', 'MessageController@unReadMessage');
			Route::get('active/unReadMessage', 'MessageController@unReadMessage');

			Route::group(['prefix' => 'active'], function(){

				// Codestyle
				Route::get('getCodestyles', 'CodestyleController@getCodestyles');
				Route::post('saveStyle', 'UserController@saveStyle');

				// Image
				Route::post('upload', ['as' => 'profile.active.upload', 'uses' => 'UserController@uploadImage']);

				// User info
				Route::post('saveUserInfo', 'UserController@saveUserInfo');

				// Change password
				Route::post('changePassword', 'UserController@changePassword');

				// Message
				Route::post('sendMessage', 'MessageController@sendMessage');
				Route::get('unReadMessage', 'MessageController@unReadMessage');

			});
		});

		// Task
		Route::get('task/{user?}', function($user = null){return view('profile.task', ['user' => $user]);});
		Route::post('task/active/getUserInfo', 'UserController@getUserInfo');
		Route::post('task/active/getUserTask', 'UserController@getUserTask');

		// Contest
		Route::get('contest/{user?}', function($user = null){return view('profile.contest', ['user' => $user]);});
		Route::post('contest/active/getUserContest', 'UserController@getUserContest');

		// Discuss
		Route::get('discuss/{user?}', function($user = null){return view('profile.discuss', ['user' => $user]);});
		Route::post('discuss/active/getUserDiscuss', 'UserController@getUserDiscuss');

		Route::get('/{user?}', function($user = null){return view('profile.main', ['user' => $user]);});
		
	});

	// Code Style
	Route::get('codestyle/{style?}', function($style = 'default'){return view('codestyle', ['style' => $style]);});

	Route::group(['prefix' => 'discuss'], function(){

		Route::get('/{key?}', function($key = null){return view('discuss.main', ['key' => $key]);});

		Route::group(['prefix' => 'active'], function(){
			
			// Login
			Route::post('doSignin', 'UserController@doSignin');
			// UserInfo
			Route::post('getUserInfo', 'UserController@getUserInfo');

			Route::post('getPost', 'DiscussController@getPost');
			Route::post('getPosts', 'DiscussController@getPosts');
			Route::post('getPins', 'DiscussController@getPins');
			Route::get('getPages', 'DiscussController@getPages');

			Route::group(['middleware' => 'auth'], function(){

				Route::post('addPost', 'DiscussController@addPost');
				Route::post('addComment', 'DiscussController@addComment');
				Route::post('removeDiscuss', 'DiscussController@removeDiscuss');
				Route::post('editDiscuss', 'DiscussController@editDiscuss');
				Route::post('getUserDiscuss', 'UserController@getUserDiscuss');
				Route::post('getUserContest', 'UserController@getUserContest');
			});

		});

		Route::group(['prefix' => 'post'], function(){

			Route::get('/{post_id?}', function($post_id){return view('discuss.post', ['post_id' => $post_id]);});

			Route::group(['prefix' => 'active'], function(){
			
				// Login
				Route::post('doSignin', 'UserController@doSignin');
				// UserInfo
				Route::post('getUserInfo', 'UserController@getUserInfo');

				Route::post('getPost', 'DiscussController@getPost');
				Route::post('getPosts', 'DiscussController@getPosts');
				Route::get('getPages', 'DiscussController@getPages');

				Route::group(['middleware' => 'auth'], function(){

					Route::post('addPost', 'DiscussController@addPost');
					Route::post('addComment', 'DiscussController@addComment');
					Route::post('removeDiscuss', 'DiscussController@removeDiscuss');
					Route::post('editDiscuss', 'DiscussController@editDiscuss');
					Route::post('getUserDiscuss', 'UserController@getUserDiscuss');
				});

				Route::group(['middleware' => 'admin'], function(){
					Route::post('togglePin', 'DiscussController@togglePin');
				});

			});
		});

	});

});
