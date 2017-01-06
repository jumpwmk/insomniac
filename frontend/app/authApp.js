var app = angular.module('authApp',[]);

app.controller('AuthController', function ($http, $scope, toaster){

	$scope.doSignup = function (){
		var info = {};
		info.username = $scope.username;
		info.password = $scope.password;
		info.email = $scope.email;
		$http.post('active/doSignup', info).success(function (data){
			if(data.isSuccess)
				location.reload();
			else
			{
				tmp_msg = data.error_msg.split(',');
				for(i = 0; i < tmp_msg.length; i++) toaster.pop('error', "สมัครสมาชิก", tmp_msg[i]);
			}
		});
	};

	$scope.doSignin = function (){
		var info = {};
		info.username = $scope.username;
		info.password = $scope.password;
		$http.post('active/doSignin', info).success(function (data){
			if(data.isSuccess)
				location.reload();
			else
				toaster.pop('error', "ล็อกอิน", data.error_msg);;
		});
	};
});