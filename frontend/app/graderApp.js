var app = angular.module('graderApp',[]);

app.controller('GraderController', function ($http, $scope, $filter, FileUploader, toaster){

	this.getGraderInfo = function (grader_id){
		var tmp = this;
		tmp.info = {};
		var input = {};
		input.grader_id = grader_id;
		$http.post('active/getGraderInfo', input).success(function (data){
			tmp.info = data;
		});
	};

	this.start = function (grader_id){
		toaster.pop('waiting', 'สถานะตัวตรวจ', 'กำลังโหลด');
		var tmp = this;
		tmp.info = {};
		var input = {};
		input.grader_id = grader_id;
		$http.post('active/startGrader', input).success(function (data){
			tmp.info = data;
			tmp.info.working = '-1';
		});
		
		var inter = setInterval(function(){
			$http.post('active/getGraderInfo', input).success(function (data){
				if(data.working == 1)
				{
					tmp.info = data;
					clearInterval(inter);
					toaster.pop('success', 'สถานะตัวตรวจ', 'กำลังทำงาน');
				}
			});
		}, 1000);
	};

	this.stop = function (grader_id){
		var tmp = this;
		tmp.info = {};
		var input = {};
		input.grader_id = grader_id;
		$http.post('active/stopGrader', input).success(function (data){
			tmp.info = data;
			toaster.pop('error', 'สถานะตัวตรวจ', 'หยุดการทำงานแล้ว');
		});
	};

});
