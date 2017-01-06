var app = angular.module('messageApp', []);

app.controller('MessageController', function ($filter, $http, $scope){

	this.sendMessage = function (username, body)
	{
		var info = {};
		info.username = username;
		info.body = body;
		$http.post('active/sendMessage', info).success(function (){
			window.location = 'message/'+username;
		});
	}

	this.readMessage = function (msg)
	{
		var tmp = this;
		var info = {};
		info.msg_id = msg.id;
		$http.post('active/readMessage', info).success(function (){
			tmp.curReadMsg = msg;
			msg.read = '1';
			$http.get('active/unReadMessage').success(function (data){
				tmp.unread = data;
			});
		});
	}	

	this.unReadMessage = function ()
	{
		var tmp = this;
		$http.get('active/unReadMessage').success(function (data){
			tmp.unread = data;
		});
	}

});