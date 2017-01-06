var app = angular.module('taskApp',[]);

app.controller('TaskController', function ($http, $scope, $filter, FileUploader, toaster){

	this.getTasks = function (type){
		var tmp = this;
		tmp.tasks = {};
		var info = {};
		info.type = type;
		$http.post('active/getTasks', info).success(function (data){
			tmp.tasks = data;
			for(i = 0; i < tmp.tasks.length; i++)
			{
				tmp.tasks[i].id = Number(tmp.tasks[i].id);
				tmp.tasks[i].rating = Number(tmp.tasks[i].rating);
			}
			tmp.tasks = $filter('orderBy')(tmp.tasks, 'id', true);
		});
	};

	this.infoTask = function (id){
		var task = {};
		task.id = id;
		var tmp = this;
		tmp.info = {};
		$http.post('active/infoTask', task).success(function (data){
			tmp.info = data;
			tmp.info.id = Number(tmp.info.id);
			tmp.info.formattedId = tmp.info.id.toString();
			while(tmp.info.formattedId.length < 5) tmp.info.formattedId = '0' + tmp.info.formattedId;
			tmp.oldRated = tmp.info.rated;
		});
	};

	this.rateTask = function (star){
		var info = {};
		var tmp = this;
		info.rate = star;
		info.task_id = this.info.id;
		$http.post('active/rateTask', info).success(function (data){
			tmp.oldRated = star;
			tmp.info.rated = star;
			tmp.info.rating = data;
			toaster.pop('success', "คะแนนความยากง่าย", "บันทึกเรียบร้อย");
		});
	}

});
