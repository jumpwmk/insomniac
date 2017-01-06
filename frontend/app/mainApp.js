var inject = [
				'authApp',
				'styleApp',
				'userApp',
				'graderApp',
				'submitApp',
				'taskApp',
				'adminApp',
				'uploadApp',
				'contestApp',
				'discussApp',
				'messageApp',
				'textAngular',
				'toaster',
				'angular-loading-bar',
				'ngAnimate',
				'notular'
			];

var app = angular.module('mainApp',inject)
	.config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
		cfpLoadingBarProvider.includeSpinner = false;
	}]);

app.controller('MainController', function ($interval, $scope){

	$scope.realTime = function(serverDate){
		var diffDate = Date.now() - Number(serverDate * 1000);
		$scope.dateNow = Date.now() - diffDate;
		$interval(function(){
			$scope.dateNow = Date.now() - diffDate;
		}, 1000);
	}

	$scope.timeLeft = function(time){

		var tmp = {};
		var tmp_time = Math.floor(time / 1000);

		if(-1.1 <= tmp_time && tmp_time < 0) location.reload();

		tmp.msec = time % 1000;
		tmp.sec = tmp_time % 60;
		tmp_time = Math.floor(tmp_time / 60);
		tmp.min = tmp_time % 60;
		tmp_time = Math.floor(tmp_time / 60);
		tmp.hour = tmp_time % 24;
		tmp_time = Math.floor(tmp_time / 24);
		tmp.day = tmp_time;

		if(tmp.day <= 0)
		{
			tmp.day = null;
			if(tmp.hour <= 0)
			{
				tmp.hour = null;
				if(tmp.min <= 0)
				{
					tmp.min = null;
					if(tmp.sec <= 0)
						return {};
				}
			}
		}
		return tmp;
	}

	$scope.range = function (x){
		var ar = [];
		for(i = 1; i <= x; i++)
			ar.push(i);
		return ar;
	}

	$scope.xrange = function (a,b){
		var ar = [];
		for(i = a; i <= b; i++)
			ar.push(i);
		return ar;
	}

	$scope.isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	$scope.remove = function (mem, obj){
		obj.splice(obj.indexOf(mem),1);
	}

	$scope.getUserRatingColorClass = function (user) {
		rating = parseInt(user.rating)
		return {
			'user-unrated' : user.contest == 0,
			'user-red'     : user.contest > 0 && rating >= 2000,
			'user-orange'  : user.contest > 0 && 2000 > rating && rating >= 1800,
			'user-purple'  : user.contest > 0 && 1800 > rating && rating >= 1600,
			'user-blue'    : user.contest > 0 && 1600 > rating && rating >= 1400,
			'user-cyan'    : user.contest > 0 && 1400 > rating && rating >= 1200,
			'user-green'   : user.contest > 0 && 1200 > rating && rating >= 1000,
			'user-gray'    : user.contest > 0 && 1000 > rating
		}
	}

});
