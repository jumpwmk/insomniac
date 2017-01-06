var app = angular.module('adminApp',[]);

app.controller('AdminController', function ($http, $scope, $filter, FileUploader, toaster){

	//Global function
	{
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

		var isset = function (x){
			return !(typeof x == 'undefined' || x == null);
		}

		var remove = function (mem, obj){
			obj.splice(obj.indexOf(mem),1);
		}

		$scope.toggle = function (id){
			$('#'+id).slideToggle(100);
		}


		this.rev = [];
	}

	// Configs
	{
		this.getConfigs = function (){
			var tmp = this;
			$http.get('active/getConfigs').success(function (data){
				tmp.configs = data;
				tmp.oldConfigs = angular.copy(data);
			});
		};

		this.resetConfigs = function (){
			var tmp = this;
			tmp.configs = angular.copy(tmp.oldConfigs);
			$scope.editConfig_success_msg = null;
			toaster.pop('note', 'รีเซ็ตค่า', 'ทำการให้ค่าเดิมเรียบร้อยแล้ว');
		};

		this.editConfigs = function (){
			var tmp = this;
			$http.post('active/editConfigs', tmp.configs).success(function (data){
				if(data.isSuccess)
				{
					tmp.oldConfigs = angular.copy(tmp.configs);
					toaster.pop('success', 'ตั้งค่าระบบ', 'บันทึกเรียบร้อย');
				}
			});
		};
	}

	//Tasks
	{
		this.getTasks = function (){
			var tmp = this;
			tmp.tasks = {};
			$http.get('active/getTasks').success(function (data){
				tmp.tasks = data;
				tmp.visible_tasks = [];
				for(i = 0; i < tmp.tasks.length; i++)
				{
					tmp.tasks[i].id = Number(tmp.tasks[i].id);
					tmp.tasks[i].testcase = Number(tmp.tasks[i].testcase);
					tmp.tasks[i].pretestcase = Number(tmp.tasks[i].pretestcase);
					tmp.tasks[i].time = Number(tmp.tasks[i].time);
					tmp.tasks[i].memory = Number(tmp.tasks[i].memory);
					if(tmp.tasks[i].visible)
						tmp.visible_tasks.push(tmp.tasks[i]);
				}
				tmp.tasks = $filter('orderBy')(tmp.tasks, '-id', false);
			});
		};

		this.sortTasks = function (by){
			this.tasks = $filter('orderBy')(this.tasks, '-'+by, this.rev[by]);
			this.rev[by] = !this.rev[by];
		};

		this.addTask = function (){
			var tmp = this;

			if(!isset(tmp.add.tags)) tmp.add.tags = '';
			if(!isset(tmp.add.visible)) tmp.add.visible = false;
			if(!isset(tmp.add.general_check)) tmp.add.general_check = false;

			$http.post('active/addTask', this.add).success(function (data){
				if(data.isSuccess)
				{
					tmp.add.id = data.id;
					tmp.tasks.push(tmp.add);
					tmp.add = null;
					toaster.pop('success', 'เพิ่มโจทย์', 'บันทึกโจทย์ใหม่เรียบร้อย');
				}
				else
					toaster.pop('error', 'เพิ่มโจทย์', data.error_msg);
			});
		};

		this.setRemoveTask = function (task){
			this.currentRemoveTask = task;
		};

		this.removeTask = function (task){
			var tmp = this;
			$http.post('active/removeTask', this.currentRemoveTask).success(function (data){
				if(data.isSuccess)
				{
					remove(tmp.currentRemoveTask, tmp.tasks);
					toaster.pop('success', 'ลบโจทย์', 'ลบเรียบร้อย');
				}
			});
		};

		this.setfileTask = function (task){
			this.currentFileTask = task;
			this.showFileTask = null;
			var tmp = this;
			$http.post('active/infoFileTask', task).success(function (data){
				tmp.infoFileTask = data;
			});
		};

		this.setEditTask = function (task){
			this.edit = angular.copy(task);
			this.oldEdit = task;
			$scope.editTask_success_msg = null;
			$scope.editTask_error_msg = null;
		};

		this.editTask = function (){
			var tmp = this;
			$scope.editTask_success_msg = null;
			$scope.editTask_error_msg = null;
			$http.post('active/editTask', tmp.edit).success(function (data){
				if(data.isSuccess)
				{
					tmp.oldEdit.name = data.name;
					tmp.oldEdit.pretestcase = data.pretestcase;
					tmp.oldEdit.testcase = data.testcase;
					tmp.oldEdit.time = data.time;
					tmp.oldEdit.memory = data.memory;
					tmp.oldEdit.level = data.level;
					tmp.oldEdit.visible = data.visible;
					toaster.pop('success', 'แก้ไขโจทย์', data.success_msg);
				}
				else
				{
					toaster.pop('error', 'แก้ไขโจทย์', data.error_msg);
				}
			});
		};

		this.rejudgeTask = function (task){
			$http.post('active/rejudgeTask', task).success(function (data){
				if(data.isSuccess) toaster.pop('success', 'แก้ไขโจทย์', "เริ่มการตรวจใหม่เรียบร้อย");
			})
		};

	}

	// Contests

	{

		this.setInitDate = function (){
			var dt = new Date();
			$scope.date = $filter('date')(dt, 'yyyy-MM-dd HH:mm:ss');
		};

		this.getContests =function (){
			var tmp = this;
			tmp.contests = {};
			$http.get('active/getContests').success( function (data){
				tmp.contests = data;
				for(i = 0; i < tmp.contests.length; i++)
				{
					tmp.contests[i].id = Number(tmp.contests[i].id);
				}
			});
		};

		this.addContest = function (){
			var tmp = this;
			$http.post('active/addContest', this.add).success(function (data){
				tmp.contests.push(data);
				var dt = new Date();
				tmp.add.name = '';
				tmp.add.type = '';
				tmp.add.task = 1;
				tmp.add.start_register = $filter('date')(dt, 'yyyy-MM-dd HH:mm:ss');
				tmp.add.end_register = $filter('date')(dt, 'yyyy-MM-dd HH:mm:ss');
				tmp.add.start_contest = $filter('date')(dt, 'yyyy-MM-dd HH:mm:ss');
				tmp.add.end_contest = $filter('date')(dt, 'yyyy-MM-dd HH:mm:ss');
				tmp.add.visible = false;
				toaster.pop('success', 'สร้างการแข่งขัน', 'สร้างเรียบร้อย');
			});
		};

		this.setRemoveContest = function (contest){
			this.currentRemoveContest = contest;
		}

		this.removeContest = function (){
			var tmp = this;
			$http.post('active/removeContest', this.currentRemoveContest).success(function (data){
				if(data.isSuccess)
				{
					toaster.pop('success', 'ลบการแข่งขัน', 'ลบเรียบร้อย');
					remove(tmp.currentRemoveContest, tmp.contests);
				}
			});
		};

		this.setEditContest = function (contest){
			var tmp = this;
			$scope.editContest_success_msg = null;
			$scope.editContest_error_msg = null;
			tmp.edit = angular.copy(contest);
			tmp.old = contest;
		}

		this.editContest = function (){
			var tmp = this;
			$http.post('active/editContest', this.edit).success(function (data){
				tmp.old.name = tmp.edit.name;
				tmp.old.task = tmp.edit.task;
				tmp.old.type = tmp.edit.type;
				tmp.old.start_register = tmp.edit.start_register;
				tmp.old.end_register = tmp.edit.end_register;
				tmp.old.start_contest = tmp.edit.start_contest;
				tmp.old.end_contest = tmp.edit.end_contest;
				tmp.old.visible = tmp.edit.visible;
				toaster.pop('success', 'แก้ไขการแข่งขัน', 'บันทึกเรียบร้อย');
			});
		}

		this.setTaskContest = function (contest){
			this.curTaskContest = contest;
			var tmp = this;
			tmp.realTaskContest = [];
			$http.post('active/getTaskContest', this.curTaskContest).success(function (data){
				tmp.realTaskContest = data;
			});
			$scope.taskContest_success_msg = "";
		}

		this.addTaskContest = function (task, order)
		{
			if(isset(order))
			{
				var tmp = angular.copy(task);
				tmp.order = order;
				var chk = 0;
				for(i = 0; i < this.realTaskContest.length; i++)
				{
					if(this.realTaskContest[i].order == order)
					{
						this.realTaskContest[i] = tmp;
						chk = 1;
					}
				}
				if(chk == 0)
				{
					this.realTaskContest.push(tmp);
					this.realTaskContest = $filter('orderBy')(this.realTaskContest, 'order');
				}
			}
		}

		this.removeTaskContest = function (task)
		{
			remove(task, this.realTaskContest);
		}

		this.saveTaskContest = function ()
		{
			var tmp = this;
			var info = {};
			info.contest_id = this.curTaskContest.id;
			info.tasks = angular.copy(tmp.realTaskContest);

			$http.post('active/saveTaskContest', info).success( function (data){
				toaster.pop('success', 'โจทย์ที่ใช้แข่งขัน', 'บันทึกเรียบร้อย');
				tmp.curTaskContest.data.tasks = angular.copy(tmp.realTaskContest);
			});
		}

		this.setDetailContest = function (contest){
			$scope.success_msg = '';
			this.curDetailContest = angular.copy(contest);
			this.oldDetailContest = contest;
		}

		this.saveDetailContest = function ()
		{
			var tmp = this;
			$http.post('active/saveDetailContest', this.curDetailContest).success( function (data){
				toaster.pop('success', 'อธิบายการแข่งขัน', 'บันทึกเรียบร้อย');
				tmp.oldDetailContest.detail = data.detail;
			})
		}

		this.updateRating = function ()
		{
			$http.get('active/updateRating').success( function (data){
				toaster.pop('success', 'อัพเดทระดับผู้ใช้', 'อัพเดทเรียบร้อย');
			});
		}

		this.updateContestRating = function (contest)
		{
			$http.post('active/updateContestRating', contest).success( function (data){
				toaster.pop('success', 'อัพเดทระดับผู้ใช้ในการแข่งขัน', 'อัพเดทเรียบร้อย');
			});
		}

	}

	// Users

	{
		this.getUsers = function (){
			var tmp = this;
			tmp.users = {};
			$http.get('active/getUsers').success(function (data){
				tmp.users = data;
				tmp.admins = [];
				for(i = 0; i < tmp.users.length; i++)
				{
					tmp.users[i].id = Number(tmp.users[i].id);
					if(tmp.users[i].admin)
						tmp.admins.push(tmp.users[i]);
				}
				tmp.users = $filter('orderBy')(tmp.users, '-id', false);

			});
		};

		this.sortUsers = function (by){
			this.users = $filter('orderBy')(this.users, '-'+by, this.rev[by]);
			this.rev[by] = !this.rev[by];
		};

		this.addUser = function (){
			
			var info = {};

			info.username = $scope.username;
			info.password = $scope.password;
			info.confimPassword = $scope.confirmPassword;
			info.email = $scope.email;
			info.display = $scope.display ? $scope.display : $scope.username;
			info.admin = $scope.admin;
			if(!isset(info.display)) info.display = '';
			if(!isset(info.admin)) info.admin = false;

			var tmp = this;
			
			$http.post('active/addUser', info).success(function (data){
				if(data.isSuccess)
				{
					$scope.username = null;
					$scope.password = null;
					$scope.confirmPassword = null;
					$scope.email = null;
					$scope.display = null;
					$scope.admin = null;
					info.id = data.id;
					tmp.users.push(info);
					toaster.pop('success', 'เพิ่มผู้ใช้', data.success_msg);
				}
				else
				{
					tmp_msg = data.error_msg.split(',');
					for(i = 0; i < tmp_msg.length; i++) toaster.pop('error', 'เพิ่มผู้ใช้', tmp_msg[i]);
				}
			});
		};

		this.setEditUser = function (user){
			this.oldEditUser = user;
			this.currentEditUser = angular.copy(user);
			$scope.editUser_error_msg = "";
			$scope.editUser_success_msg = "";
		};

		this.editUser = function (){
			var tmp = this;
			$http.post('active/editUser', this.currentEditUser).success(function (data){
				if(data.isSuccess)
				{
					tmp.oldEditUser.email = tmp.currentEditUser.email;
					tmp.oldEditUser.display = tmp.currentEditUser.display;
					tmp.oldEditUser.admin = tmp.currentEditUser.admin;
					toaster.pop('success', 'แก้ไขผู้ใช้', data.success_msg);
				}
				else
				{
					tmp_msg = data.error_msg.split(',');
					for(i = 0; i < tmp_msg.length; i++) toaster.pop('error', 'แก้ไขผู้ใช้', tmp_msg[i]);
				}
			});
		};

		this.setRemoveUser = function (user){
			this.currentRemoveUser = user;
		}

		this.removeUser = function (){
			var tmp = this;
			$http.post('active/removeUser', this.currentRemoveUser).success(function (data){
				if(data.isSuccess)
				{
					toaster.pop('success', 'ลบผู้ใช้', 'ลบเรียบร้อย');
					remove(tmp.currentRemoveUser, tmp.users);
				}
			});
		};
	}

});