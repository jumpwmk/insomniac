var app = angular.module('userApp',[]);

app.controller('UserController', function ($http, $scope, $filter, FileUploader, toaster){

	this.getUsers = function (orderBy, orderRev, skip, take)
	{
		var tmp = this;
		tmp.users = [];
		var info = {};
		info.orderBy = orderBy;
		info.orderRev = orderRev;
		info.skip = skip;
		info.take = take;
		$http.post('active/getUsers', info).success(function (data){
			tmp.users = data;
			oldRating = 0;
			place = 0;
			unratedCount = 0;
			for(i = 0; i < tmp.users.length; i++)
			{
				currentRating = tmp.users[i].contest != 0 ? Number(tmp.users[i].rating) : 0;
				if(currentRating == 0)
					unratedCount++;
				else if(oldRating != currentRating)
				{
					oldRating = currentRating
					place = i - unratedCount + 1;
				}
				tmp.users[i].place = currentRating ? place : tmp.users.length+2; // 2 is the magic number :)
				tmp.users[i].id = Number(tmp.users[i].id);
				tmp.users[i].rating = currentRating;
				tmp.users[i].pass = Number(tmp.users[i].pass);
				tmp.users[i].contest = Number(tmp.users[i].contest);
			}
		});
	}

	this.getUserInfo = function (username){
		var tmp = this;
		tmp.userInfo = {};
		var info = {};
		info.username = username;
		$http.post('active/getUserInfo', info).success(function (data){
			tmp.userInfo = data;
		});
	};

	this.getUserTask = function (username){
		var info = {};
		info.username = username;
		var tmp = this;
		tmp.tasks = {};
		$http.post('active/getUserTask', info).success(function (data){
			tmp.tasks = data;
			$scope.currentTasks = tmp.tasks.recent;
		});
	};

	this.getUserContest = function (username){
		var info = {};
		info.username = username;
		var tmp = this;
		tmp.contests = {};
		$http.post('active/getUserContest', info).success(function (data){
			tmp.contests = data;
		});
	};

	this.getUserRatingChart = function (username){
		var info = {};
		info.username = username;
		var tmp = [];
		var contest_name = ['Start'];
		var contest_rating = [1500];
		$http.post('contest/active/getUserContest', info).success(function (data){
			tmp = angular.copy(data);
			if(tmp.length <= 50)
			{
				showTip = true;
				eventTip = ["mousemove", "touchstart", "touchmove"];
			}
			else
			{
				showTip = false;
				eventTip = [];
			}
			for(i = 0; i < tmp.length; i++)
			{
				if(tmp.length <= 19) contest_name.push(tmp[i].name);
				else contest_name.push('');
				contest_rating.push(tmp[i].rating);
			}

			var lineChartData = {
				labels : contest_name,
				datasets : [
					{
						label: "myRating",
						strokeColor : "rgba(150,220,255,1)",
						pointColor : "rgba(150,220,255,1)",
						pointStrokeColor : "#fff",
						pointHighlightFill : "#fff",
						pointHighlightStroke : "rgba(220,220,220,1)",
						data : contest_rating
					}
				]

			}

			var ctx = document.getElementById("canvas").getContext("2d");
			window.myLine = new Chart(ctx).Line(lineChartData, {
				responsive: true,
				bezierCurve : false,
				datasetFill : false,
				showTooltips : showTip,
				tooltipEvents: eventTip,
				scaleShowVerticalLines : false,
			});
		});
	};

	this.getCompareRatingChart = function (username){
		if($scope.shown) return 0;
		toaster.pop('wating', "เปรียบเทียบระดับผู้ใช้", "กำลังทำการเปรียบเทียบ");
		$scope.shown = true;
		var info = {};
		var sinfo = {};
		info.username = username;
		sinfo.username = '';
		var tmp = [];
		var xtmp = [];
		var contest_name = ['Start'];
		var contest_rating_1 = [1500];
		var contest_rating_2 = [1500];
		$http.post('contest/active/getUserContest', info).success(function (data){
			tmp = angular.copy(data);
			$http.post('contest/active/getUserContest', sinfo).success(function (xdata){
				xtmp = angular.copy(xdata);
				i1 = 0;
				i2 = 0;
				while(i1 < tmp.length || i2 < xtmp.length)
				{
					if(i1 >= tmp.length)
					{
						contest_name.push(xtmp[i2].name);
						contest_rating_1.push(null);
						contest_rating_2.push(xtmp[i2].rating);
						i2++;
					}
					else if(i2 >= xtmp.length)
					{
						contest_name.push(tmp[i1].name);
						contest_rating_1.push(tmp[i1].rating);
						contest_rating_2.push(null);
						i1++;
					}
					else if(tmp[i1].id == xtmp[i2].id)
					{
						contest_name.push(xtmp[i2].name);
						contest_rating_1.push(tmp[i1].rating);
						contest_rating_2.push(xtmp[i2].rating);
						i1++;
						i2++;
					}
					else if(tmp[i1].contestDate > xtmp[i2].contestDate)
					{
						contest_name.push(xtmp[i2].name);
						contest_rating_1.push(null);
						contest_rating_2.push(xtmp[i2].rating);
						i2++;
					}
					else if(tmp[i1].contestDate < xtmp[i2].contestDate)
					{
						contest_name.push(tmp[i1].name);
						contest_rating_1.push(tmp[i1].rating);
						contest_rating_2.push(null);
						i1++;
					}

				}

				if(contest_name.length <= 50)
				{
					showTip = true;
					eventTip = ["mousemove", "touchstart", "touchmove"];
				}
				else
				{
					showTip = false;
					eventTip = [];
				}

				var multilineChartData = {
					labels : contest_name,
					datasets : [
						{
							label: "Rating 1",
							strokeColor : "rgba(150,220,255,1)",
							pointColor : "rgba(150,220,255,1)",
							pointStrokeColor : "#fff",
							pointHighlightFill : "#fff",
							pointHighlightStroke : "rgba(220,220,220,1)",
							data : contest_rating_1
						},
						{
							label: "Rating 2",
							strokeColor : "rgba(220,150,150,1)",
							pointColor : "rgba(220,150,150,1)",
							pointStrokeColor : "#fff",
							pointHighlightFill : "#fff",
							pointHighlightStroke : "rgba(220,220,220,1)",
							data : contest_rating_2
						}
					]

				}

				var ctx = document.getElementById("compareRating").getContext("2d");
				window.multiLine = new Chart(ctx).Line(multilineChartData, {
					responsive: true,
					bezierCurve : false,
					datasetFill : false,
					showTooltips : showTip,
					tooltipEvents: eventTip,
					scaleShowVerticalLines : false,
				});
			});
			toaster.pop('success', "เปรียบเทียบระดับผู้ใช้", "เปรียบเทียบเรียบร้อย");
		});
	};

	this.setChangeStyle = function (){
		$scope.style_success_msg = null;
		this.changeStyle = angular.copy(this.userInfo.codestyle);
	};

	this.saveStyle = function (){
		var tmp = this;
		$http.post('active/saveStyle', this.changeStyle).success(function (data){
			tmp.userInfo.codestyle = angular.copy(tmp.changeStyle);
			toaster.pop('success', "เปลี่ยนธีมโค้ด", "บันทึกเรียบร้อย");
		});
	};

	this.setEditUserInfo = function (){
		this.editUserInfo = angular.copy(this.userInfo);
		$scope.editUserInfo_success_msg = "";
		$scope.editUserInfo_error_msg = "";
	};

	this.saveUserInfo = function (){
		var tmp = this;
		$http.post('active/saveUserInfo', this.editUserInfo).success(function (data){
			if(data.isSuccess)
			{
				tmp.userInfo.display = data.display;
				tmp.userInfo.email = data.email;
				toaster.pop('success', "แก้ไขข้อมูลทั่วไป", data.success_msg);
			}
			else
				toaster.pop('error', "แก้ไขข้อมูลทั่วไป", data.error_msg);
		});
	};

	this.clearChangePassword = function (){

		this.changePass = {};
		$scope.changePassword.password.$pristine = 1;
		$scope.changePassword.password.$dirty = 0;
		$scope.changePassword.confirmPassword.$pristine = 1;
		$scope.changePassword.confirmPassword.$dirty = 0;
		$scope.changePassword_error_msg = null;
		$scope.changePassword_success_msg = null;
	};

	this.changePassword = function (){
		$http.post('active/changePassword', this.changePass).success(function (data){
			if(data.isSuccess)
				toaster.pop('success', "เปลี่ยนรหัสผ่าน", data.success_msg);
			else
				toaster.pop('error', "เปลี่ยนรหัสผ่าน", data.success_msg);
		});
	};

	this.getUserDiscuss = function (username){
		var info = {};
		info.username = username;
		var tmp = this;
		tmp.userDiscuss = {};
		$http.post('active/getUserDiscuss', info).success(function (data){
			tmp.userDiscuss = data;
		});
	}

	this.getUserMessage = function (username){
		var info = {};
		info.username = username;
		var tmp = this;
		$http.post('active/getUserMessage', info).success(function (data){
			tmp.userMsgs = data;
			for (var i = tmp.userMsgs.length - 1; i >= 0; i--) {
				tmp.userMsgs[i].short_body = $filter('limitTo')(tmp.userMsgs[i].body, 30);
				if(tmp.userMsgs[i].body.length > 30)tmp.userMsgs[i].short_body = tmp.userMsgs[i].short_body.concat(' ...');
			};
		});
	}
});
