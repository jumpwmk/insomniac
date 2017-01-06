<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'admin'])
		<div class="container" ng-controller="AdminController as TaskCtrl">

			<div class="row">
				<div class="col-md-8">
					<div ng-controller="UploadController">
					
						<h2>
							<i class="fa fa-puzzle-piece"></i>
							โจทย์ทั้งหมด
						</h2>
						<hr>
						<div id="tasks">
							<div class="form-horizontal">
								<div class="form-group has-feedback">
								<label class="control-label col-md-3">ค้นหาโจทย์</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchTasks">
									<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
									<span id="inputSuccess3Status" class="sr-only">(success)</span>
								</div>
								</div>
							</div>
							<table class="table table-condensed table-hover" ng-init="TaskCtrl.getTasks()" style="text-align:center">
								<thead>
									<tr>
										<td ng-click="TaskCtrl.sortTasks('id')"><b><a href>#</a></b></td>
										<td ng-click="TaskCtrl.sortTasks('name')"><b><a href>ชื่อโจทย์</a></b></td>
										<td ng-click="TaskCtrl.sortTasks('testcase')"><b><a href>ข้อมูลทดสอบ</a></b></td>
										<td ng-click="TaskCtrl.sortTasks('time')"><b><a href>เวลา</a></b></td>
										<td ng-click="TaskCtrl.sortTasks('memory')" style="width:120px"><b><a href>หน่วยความจำ</a></b></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="task in TaskCtrl.tasks | filter: searchTasks" ng-class="{success: task.visible == '1'}">
										<td>@{{task.id}}</td>
										<td><a href="../task/@{{task.id}}">@{{task.name}}</a></td>
										<td>@{{task.testcase}} ( +@{{task.pretestcase}} )</td>
										<td>@{{task.time}}</td>
										<td>@{{task.memory}}</td>
										<td>
											<div class="dropdown">
											<a href class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
												<span class="glyphicon glyphicon-cog"></span>
											</a>
											<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
												<li><a href data-toggle="modal" data-target="#editTask" ng-click="TaskCtrl.setEditTask(task)">แก้ไขโจทย์</a></li>
												<li><a href data-toggle="modal" data-target="#fileTask" ng-click="TaskCtrl.setfileTask(task); uploader.clearQueue();">ไฟล์โจทย์</a></li>
												<li><a href data-toggle="modal" data-target="#rejudgeTask" ng-click="TaskCtrl.currentRejudgeTask = task">ตรวจใหม่</a></li>
												<li><a href data-toggle="modal" data-target="#removeTask" ng-click="TaskCtrl.setRemoveTask(task)">ลบ</a></li>
											</ul>
										</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<br>

						<!-- edit Task -->
						@include('admin.forms.editTask')

						<!-- file of Task -->
						@include('admin.forms.fileTask')

						<!-- rejudge Task -->
						<div class="modal fade" id="rejudgeTask" aria-hidden="true">
							<div class="modal-dialog" style="width:400px">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">ตัวการส่งใหม่</h4>
									</div>
									<div class="modal-body">แน่ใจหรือไม่ที่จะเริ่มทำการตรวจใหม่โจทย์ "@{{TaskCtrl.currentRejudgeTask.name}}"</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
										<button type="submit" class="btn btn-primary pull-right" ng-click="TaskCtrl.rejudgeTask(TaskCtrl.currentRejudgeTask)" data-dismiss="modal">เริ่มตรวจใหม่</button>
									</div>
								</div>
							</div>
						</div>

						<!-- remove Task -->
						<div class="modal fade" id="removeTask" aria-hidden="true">
							<div class="modal-dialog" style="width:400px">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">ลบโจทย์</h4>
									</div>
									<div class="modal-body">แน่ใจหรือไม่ที่จะลบโจทย์ "@{{TaskCtrl.currentRemoveTask.name}}"</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
										<button type="submit" class="btn btn-primary pull-right" ng-click="TaskCtrl.removeTask()" data-dismiss="modal">ลบโจทย์</button>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="col-md-4">
					@include('admin.partials.menu', ['active' => 'task'])
					@include('admin.forms.addTask')
				</div>
			</div>

			<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>

		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>