var app = angular.module('submitApp',inject);

app.controller('SubmitController', function ($http, $scope, $filter, FileUploader){

	var isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	this.getSubmits = function (id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.id = id;
		info.skip = skip;
		info.take = take;
		$http.post('active/getSubmits', info).success(function (data){
			tmp.submits = tmp.submits.concat(data);
			$scope.loadSubmit = (take == data.length);
			for (var i = tmp.submits.length - data.length; i < tmp.submits.length; i++) {
				tmp.submits[i].memory = Number(tmp.submits[i].memory);
				tmp.submits[i].time = Number(tmp.submits[i].time);
				tmp.submits[i].id = Number(tmp.submits[i].id);
			};
		});
	};

	this.getTaskSubmits = function (id, task_id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.id = id;
		info.task_id = task_id;
		info.skip = skip;
		info.take = take;
		$http.post('active/getTaskSubmits', info).success(function (data){
			tmp.submits = tmp.submits.concat(data);
			$scope.loadSubmit = (take == data.length);
			for (var i = tmp.submits.length - data.length; i < tmp.submits.length; i++) {
				tmp.submits[i].memory = Number(tmp.submits[i].memory);
				tmp.submits[i].time = Number(tmp.submits[i].time);
				tmp.submits[i].id = Number(tmp.submits[i].id);
			};
		});
	};

	this.rejudgeSubmit = function (submit){
		$http.post('active/rejudgeSubmit', submit).success(function (data){
			if(data.isSuccess) window.location = '';
		})
	};

	this.rev = [];

	this.sortSubmits = function (by){
		this.submits = $filter('orderBy')(this.submits, by, this.rev[by]);
		this.rev[by] = !this.rev[by];
	};

	this.setShowError = function (msg){
		this.currentError = msg;
	};

	this.setShowCode = function (submit){
		this.currentCode = submit;
	};

});