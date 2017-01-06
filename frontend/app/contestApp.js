var app = angular.module('contestApp',[]);

app.controller('ContestController', function ($http, $scope, $filter){

	this.getContests = function(){
		var tmp = this
		tmp.contests = {}
		$http.get('active/getContests').success(function (data){
			tmp.contests = data;
			$scope.contests = data.new;
		})
	}

	this.getContest = function(contest_id){
		var tmp = this
		tmp.contest = {}
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getContest', info).success(function (data){
			tmp.contest = data;
		})
	}

});

app.controller('NormalController', function ($http, $scope, $filter, toaster){

	var isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	this.getContests = function(){
		var tmp = this
		tmp.contests = {}
		$http.get('active/getContests').success(function (data){
			tmp.contests = data;
			$scope.contests = data.new;
		})
	}

	this.getContest = function(contest_id){
		var tmp = this
		tmp.contest = {}
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getContest', info).success(function (data){
			tmp.contest = data;
		})
	}

	this.getTasks = function(contest_id){
		var tmp = this;
		tmp.tasks = {};
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getTasks', info).success(function (data){
			tmp.tasks = data;
			for (var i = 0; i < tmp.tasks.length; i++) {
				tmp.tasks[i].info.memory = Number(tmp.tasks[i].info.memory);
				tmp.tasks[i].info.time = Number(tmp.tasks[i].info.time);
				tmp.tasks[i].order = Number(tmp.tasks[i].order);
				tmp.tasks[i].count_pass = Number(tmp.tasks[i].count_pass);
			};
			$filter('orderBy')(tmp.tasks, 'order');
		})
	}

	this.getTask = function(contest_id, task_order){ // call getTask[s] first
		var tmp = this;
		tmp.task = {};
		var info = {};
		info.contest_id = contest_id;
		$http.post('active/getTasks', info).success(function (data){
			for(i = 0; i < data.length; i++)
			{
				if(task_order == data[i].order)
				{
					tmp.task = data[i];
					break;
				}
			}
		})
	}

	this.setScore = function(task, idx){
		this.curScore = angular.copy(task);
		this.curScoreIdx = idx;
		this.curScore.info.testcase = Number(this.curScore.info.testcase);
		this.curScore.info.pretestcase = Number(this.curScore.info.pretestcase);
	}

	this.saveScore = function(){
		var tmp = this;
		$http.post('active/saveScore', tmp.curScore).success(function (data){
			tmp.tasks[tmp.curScoreIdx] = angular.copy(tmp.curScore);
			toaster.pop('success', 'กำหนดคะแนน', 'บันทึกเรียบร้อย');
		})
	}

	this.saveData = function(){
		$http.post('active/saveData', this.contest).success(function (data){
			toaster.pop('success', 'ตั้งค่าการแข่งขันขั้นสูง', 'บันทึกเรียบร้อย');
		})
	}

	this.saveScoreboard = function(contest_id){
		var tmp = this;
		tmp.contestants = [];
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = true;
		$http.post('active/getContestants', info).success(function (data){
			$http.post('active/saveScoreboard', data).success(function (){
				toaster.pop('success', 'ตารางคะแนน', 'บันทึกเรียบร้อย');
				tmp.contest.data.save_scoreboard = true;
			});
		});
	}

	this.getSubmits = function (contest_id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.contest_id = contest_id;
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

	this.setShowCode = function (submit){
		this.currentCode = submit;
	};

	this.setShowError = function (msg){
		this.currentError = msg;
	};

	this.getContestants = function (contest_id, raw_scoreboard)
	{
		var tmp = this;
		tmp.contestants = {};
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = raw_scoreboard;
		$http.post('active/getContestants', info).success(function (data){
			tmp.contestants = data;
			for(i = 0; i < tmp.contestants.length; i++)
				tmp.contestants[i].total_score = Number(tmp.contestants[i].total_score);
		});
	}


});

app.controller('TestrunController', function ($http, $scope, $filter, toaster){

	var isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	this.getContests = function(){
		var tmp = this
		tmp.contests = {}
		$http.get('active/getContests').success(function (data){
			tmp.contests = data;
			$scope.contests = data.new;
		})
	}

	this.getContest = function(contest_id){
		var tmp = this
		tmp.contest = {}
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getContest', info).success(function (data){
			tmp.contest = data;
		})
	}

	this.getTasks = function(contest_id){
		var tmp = this;
		tmp.tasks = {};
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getTasks', info).success(function (data){
			tmp.tasks = data;
			for (var i = 0; i < tmp.tasks.length; i++) {
				tmp.tasks[i].info.memory = Number(tmp.tasks[i].info.memory);
				tmp.tasks[i].info.time = Number(tmp.tasks[i].info.time);
				tmp.tasks[i].order = Number(tmp.tasks[i].order);
				tmp.tasks[i].count_pass = Number(tmp.tasks[i].count_pass);
			};
			$filter('orderBy')(tmp.tasks, 'order');
		})
	}

	this.getTask = function(contest_id, task_order){ // call getTask[s] first
		var tmp = this;
		tmp.task = {};
		var info = {};
		info.contest_id = contest_id;
		$http.post('active/getTasks', info).success(function (data){
			for(i = 0; i < data.length; i++)
			{
				if(task_order == data[i].order)
				{
					tmp.task = data[i];
					break;
				}
			}
		})
	}

	this.setScore = function(task, idx){
		this.curScore = angular.copy(task);
		this.curScoreIdx = idx;
		this.curScore.info.testcase = Number(this.curScore.info.testcase);
		this.curScore.info.pretestcase = Number(this.curScore.info.pretestcase);
		$scope.countGroup = this.curScore.data.group.length;
	}

	this.initTaskData = function(countGroup){

		if(countGroup > this.curScore.data.group.length)
		{
			var group = {};
			group.type = 'isolate';
			group.case = [];
			group.depend = [];
			for(i = this.curScore.data.group.length; i < countGroup; i++){
				this.curScore.data.group.push(angular.copy(group));
			}
		}
		else
		{
			for(i = this.curScore.data.group.length; i > countGroup; i--)
				this.curScore.data.group.pop();
		}
	}

	this.addTestcase = function(index, testcase){

		if($scope.isset(testcase))
		{
			var check = 1;
			for(i = 0; i < this.curScore.data.group[index].case.length; i++)
			{
				if(this.curScore.data.group[index].case[i] == testcase)
				{
					check = 0;
					break;
				}
			}
			if(check == 1)
				this.curScore.data.group[index].case.push(testcase);
		}
	}

	this.addDepend = function(index, depend){

		if($scope.isset(depend))
		{
			var check = 1;
			for(i = 0; i < this.curScore.data.group[index].depend.length; i++)
			{
				if(this.curScore.data.group[index].depend[i] == depend)
				{
					check = 0;
					break;
				}
			}
			if(check == 1)
				this.curScore.data.group[index].depend.push(depend);
		}
	}

	this.saveScore = function(){
		var tmp = this;
		$http.post('active/saveScore', tmp.curScore).success(function (data){
			tmp.tasks[tmp.curScoreIdx] = angular.copy(tmp.curScore);
			toaster.pop('success', 'กำหนดคะแนน', 'บันทึกเรียบร้อย');
		})
	}

	this.saveData = function(){
		$http.post('active/saveData', this.contest).success(function (data){
			toaster.pop('success', 'ตั้งค่าการแข่งขันขั้นสูง', 'บันทึกเรียบร้อย');
		})
	}

	this.saveScoreboard = function(contest_id){
		var tmp = this;
		tmp.contestants = [];
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = true;
		$http.post('active/getContestants', info).success(function (data){
			$http.post('active/saveScoreboard', data).success(function (){
				toaster.pop('success', 'ตารางคะแนน', 'บันทึกเรียบร้อย');
				tmp.contest.data.save_scoreboard = true;
			});
		});
	}

	this.getSubmits = function (contest_id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.contest_id = contest_id;
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

	this.setShowCode = function (submit){
		this.currentCode = submit;
	};

	this.setShowError = function (msg){
		this.currentError = msg;
	};

	this.getContestants = function (contest_id, raw_scoreboard)
	{
		var tmp = this;
		tmp.contestants = {};
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = raw_scoreboard;
		$http.post('active/getContestants', info).success(function (data){
			tmp.contestants = data;
			for(i = 0; i < tmp.contestants.length; i++)
				tmp.contestants[i].total_score = Number(tmp.contestants[i].total_score);
		});
	}
});

app.controller('PartialFeedbackController', function ($http, $scope, $filter, toaster){

	var isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	this.getContests = function(){
		var tmp = this
		tmp.contests = {}
		$http.get('active/getContests').success(function (data){
			tmp.contests = data;
			$scope.contests = data.new;
		})
	}

	this.getContest = function(contest_id){
		var tmp = this
		tmp.contest = {}
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getContest', info).success(function (data){
			tmp.contest = data;
		})
	}

	this.getTasks = function(contest_id){
		var tmp = this;
		tmp.tasks = {};
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getTasks', info).success(function (data){
			tmp.tasks = data;
			for (var i = 0; i < tmp.tasks.length; i++) {
				tmp.tasks[i].info.memory = Number(tmp.tasks[i].info.memory);
				tmp.tasks[i].info.time = Number(tmp.tasks[i].info.time);
				tmp.tasks[i].order = Number(tmp.tasks[i].order);
				tmp.tasks[i].count_pass = Number(tmp.tasks[i].count_pass);
			};
			$filter('orderBy')(tmp.tasks, 'order');
		})
	}

	this.getTask = function(contest_id, task_order){ // call getTask[s] first
		var tmp = this;
		tmp.task = {};
		var info = {};
		info.contest_id = contest_id;
		$http.post('active/getTasks', info).success(function (data){
			for(i = 0; i < data.length; i++)
			{
				if(task_order == data[i].order)
				{
					tmp.task = data[i];
					break;
				}
			}
		})
	}

	this.setScore = function(task, idx){
		this.curScore = angular.copy(task);
		this.curScoreIdx = idx;
		this.curScore.info.testcase = Number(this.curScore.info.testcase);
		this.curScore.info.pretestcase = Number(this.curScore.info.pretestcase);
		$scope.countGroup = this.curScore.data.group.length;
	}

	this.initTaskData = function(countGroup){

		if(countGroup > this.curScore.data.group.length)
		{
			var group = {};
			group.type = 'isolate';
			group.case = [];
			group.depend = [];
			for(i = this.curScore.data.group.length; i < countGroup; i++){
				this.curScore.data.group.push(angular.copy(group));
			}
		}
		else
		{
			for(i = this.curScore.data.group.length; i > countGroup; i--)
				this.curScore.data.group.pop();
		}
	}

	this.addTestcase = function(index, testcase){

		if($scope.isset(testcase))
		{
			var check = 1;
			for(i = 0; i < this.curScore.data.group[index].case.length; i++)
			{
				if(this.curScore.data.group[index].case[i] == testcase)
				{
					check = 0;
					break;
				}
			}
			if(check == 1)
				this.curScore.data.group[index].case.push(testcase);
		}
	}

	this.addDepend = function(index, depend){

		if($scope.isset(depend))
		{
			var check = 1;
			for(i = 0; i < this.curScore.data.group[index].depend.length; i++)
			{
				if(this.curScore.data.group[index].depend[i] == depend)
				{
					check = 0;
					break;
				}
			}
			if(check == 1)
				this.curScore.data.group[index].depend.push(depend);
		}
	}

	this.saveScore = function(){
		var tmp = this;
		$http.post('active/saveScore', tmp.curScore).success(function (data){
			tmp.tasks[tmp.curScoreIdx] = angular.copy(tmp.curScore);
			toaster.pop('success', 'กำหนดคะแนน', 'บันทึกเรียบร้อย');
		})
	}

	this.saveData = function(){
		$http.post('active/saveData', this.contest).success(function (data){
			toaster.pop('success', 'ตั้งค่าการแข่งขันขั้นสูง', 'บันทึกเรียบร้อย');
		})
	}

	this.saveScoreboard = function(contest_id){
		var tmp = this;
		tmp.contestants = [];
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = true;
		$http.post('active/getContestants', info).success(function (data){
			$http.post('active/saveScoreboard', data).success(function (){
				toaster.pop('success', 'ตารางคะแนน', 'บันทึกเรียบร้อย');
				tmp.contest.data.save_scoreboard = true;
			});
		});
	}

	this.getSubmits = function (contest_id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.contest_id = contest_id;
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

	this.setShowCode = function (submit){
		this.currentCode = submit;
	};

	this.setShowError = function (msg){
		this.currentError = msg;
	};

	this.getContestants = function (contest_id, raw_scoreboard)
	{
		var tmp = this;
		tmp.contestants = {};
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = raw_scoreboard;
		$http.post('active/getContestants', info).success(function (data){
			tmp.contestants = data;
			for(i = 0; i < tmp.contestants.length; i++)
				tmp.contestants[i].total_score = Number(tmp.contestants[i].total_score);
		});
	}


});

app.controller('ACMContestController', function ($http, $scope, $filter, toaster){

	var isset = function (x){
		return !(typeof x == 'undefined' || x == null);
	}

	this.getContests = function(){
		var tmp = this
		tmp.contests = {}
		$http.get('active/getContests').success(function (data){
			tmp.contests = data;
			$scope.contests = data.new;
		})
	}

	this.getContest = function(contest_id){
		var tmp = this
		tmp.contest = {}
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getContest', info).success(function (data){
			tmp.contest = data;
		})
	}

	this.getTasks = function(contest_id){
		var tmp = this;
		tmp.tasks = {};
		var info = {}
		info.contest_id = contest_id
		$http.post('active/getTasks', info).success(function (data){
			tmp.tasks = data;
			for (var i = 0; i < tmp.tasks.length; i++) {
				tmp.tasks[i].info.memory = Number(tmp.tasks[i].info.memory);
				tmp.tasks[i].info.time = Number(tmp.tasks[i].info.time);
				tmp.tasks[i].order = Number(tmp.tasks[i].order);
				tmp.tasks[i].count_pass = Number(tmp.tasks[i].count_pass);
			};
			$filter('orderBy')(tmp.tasks, 'order');
		})
	}

	this.getTask = function(contest_id, task_order){ // call getTask[s] first
		var tmp = this;
		tmp.task = {};
		var info = {};
		info.contest_id = contest_id;
		$http.post('active/getTasks', info).success(function (data){
			for(i = 0; i < data.length; i++)
			{
				if(task_order == data[i].order)
				{
					tmp.task = data[i];
					break;
				}
			}
		})
	}

	this.setScore = function(task, idx){
		this.curScore = angular.copy(task);
		this.curScoreIdx = idx;
		this.curScore.info.testcase = Number(this.curScore.info.testcase);
		this.curScore.info.pretestcase = Number(this.curScore.info.pretestcase);
	}

	this.saveScore = function(){
		var tmp = this;
		$http.post('active/saveScore', tmp.curScore).success(function (data){
			tmp.tasks[tmp.curScoreIdx] = angular.copy(tmp.curScore);
			toaster.pop('success', 'กำหนดคะแนน', 'บันทึกเรียบร้อย');
		})
	}

	this.saveData = function(){
		$http.post('active/saveData', this.contest).success(function (data){
			toaster.pop('success', 'ตั้งค่าการแข่งขันขั้นสูง', 'บันทึกเรียบร้อย');
		})
	}

	this.saveScoreboard = function(contest_id){
		var tmp = this;
		tmp.contestants = [];
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = true;
		$http.post('active/getContestants', info).success(function (data){
			$http.post('active/saveScoreboard', data).success(function (){
				toaster.pop('success', 'ตารางคะแนน', 'บันทึกเรียบร้อย');
				tmp.contest.data.save_scoreboard = true;
			});
		});
	}

	this.getSubmits = function (contest_id, skip, take){
		var tmp = this;
		if(!isset(tmp.submits)) tmp.submits = [];
		var info = {};
		info.contest_id = contest_id;
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

	this.setShowCode = function (submit){
		this.currentCode = submit;
	};

	this.setShowError = function (msg){
		this.currentError = msg;
	};

	this.getContestants = function (contest_id, raw_scoreboard)
	{
		var tmp = this;
		tmp.contestants = {};
		var info = {};
		info.contest_id = contest_id;
		info.raw_scoreboard = raw_scoreboard;
		$http.post('active/getContestants', info).success(function (data){
			tmp.contestants = data;
			for(i = 0; i < tmp.contestants.length; i++) {
				tmp.contestants[i].total_score = Number(tmp.contestants[i].total_score);
				tmp.contestants[i].total_penalty = Number(tmp.contestants[i].total_penalty);
			}
		});
	}
});