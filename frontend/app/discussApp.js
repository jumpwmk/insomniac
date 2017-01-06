var app = angular.module('discussApp',[]);

app.controller('DiscussController', function ($http, $scope, $filter){

	this.getPosts = function (orderBy, orderRev, skip, take, key){

		var tmp = this;
		if(!$scope.isset(tmp.posts)) tmp.posts = [];

		var info = {};
		info.orderBy = orderBy;
		info.orderRev = orderRev;
		if(skip == null) info.skip = tmp.posts.length;
		else info.skip = skip;
		info.take = take;
		info.key = key;

		this.loadPost = true;

		$http.post('active/getPosts', info).success(function (data){
			tmp.loadPost = false;
			if(data.length != take) tmp.empty = true;
			tmp.posts = tmp.posts.concat(data);
		});
	}

	this.getPins = function (orderBy, orderRev, skip, take, key){

		var tmp = this;
		if(!$scope.isset(tmp.pins)) tmp.pins = [];

		var info = {};
		info.orderBy = orderBy;
		info.orderRev = orderRev;
		if(skip == null) info.skip = tmp.pins.length;
		else info.skip = skip;
		info.take = take;
		info.key = key;

		$http.post('active/getPins', info).success(function (data){
			tmp.pins = tmp.pins.concat(data);
			for (var i = tmp.pins.length - 1; i >= 0; i--) {
				tmp.pins[i].short_body = $filter('limitTo')(tmp.pins[i].body, 300);
				if(tmp.pins[i].short_body.length != tmp.pins[i].body.length) tmp.pins[i].short_body = tmp.pins[i].short_body.concat(' ...');

				tmp.pins[i].mid_body = $filter('limitTo')(tmp.pins[i].body, 1000);
				if(tmp.pins[i].mid_body.length != tmp.pins[i].body.length) tmp.pins[i].mid_body = tmp.pins[i].mid_body.concat(' ...');
			};
		});
	}

	this.getPost = function (post_id)
	{
		var tmp = this;
		var info = {};
		info.post_id = post_id;
		$http.post('active/getPost', info).success(function (data){
			tmp.post = data;
		});
	}

	this.addComment = function (){

		if(this.newComment.length)
		{
			var tmp = this;
			var info = {};
			info.body = tmp.newComment;
			info.post_id = this.post.id;
			$http.post('active/addComment', info).success(function (data){
				
				tmp.newComment = '';
				tmp.post.comments.push(data);

			});
		}
	}

	this.addPost = function (newPost){

		if(newPost.title.length && newPost.body.length)
		{
			var info = {};
			info.body = newPost.body;
			info.title = newPost.title;
			$http.post('active/addPost', info).success(function (data){
				
				window.location = 'post/'+data.id;
			});
		}
	}

	this.getPages = function ()
	{

		var tmp = this;
		$http.get('active/getPages').success(function (data){
			tmp.allPages = data;
		});
	}

	this.removeDiscuss = function (discuss, path)
	{
		$http.post('active/removeDiscuss', discuss).success(function (){
			discuss.remove = 1;
			if(path == 'back')
				window.location = '../';
		});
	}

	this.setEditDiscuss = function (discuss)
	{
		this.oldEditDis = discuss;
		this.curEditDis = angular.copy(discuss);
	}

	this.editDiscuss = function ()
	{
		var tmp = this;
		if(tmp.oldEditDis.title.length && tmp.oldEditDis.body.length)
		{
			$http.post('active/editDiscuss', this.curEditDis).success(function (){
				tmp.oldEditDis.title = tmp.curEditDis.title;
				tmp.oldEditDis.body = tmp.curEditDis.body;
			});
		}
	}

	this.editComment = function (comment)
	{
		if(comment.body.length)
		{
			comment.isEdit = 0;
			$http.post('active/editDiscuss', comment);
		}
	}

	this.togglePin = function ()
	{
		var tmp = this;
		$http.post('active/togglePin', this.post).success(function (){
			if(tmp.post.pin == '1') tmp.post.pin = '0';
			else tmp.post.pin = '1';
		});
	}

	this.searchPost = function (keyword, path)
	{
		window.location = path + keyword;
	}

});