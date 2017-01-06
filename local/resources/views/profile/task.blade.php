<?php use App\Config; ?>

<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="UserController as UserCtrl">
		@include('partials.menubar', ['active' => 'profile'])
		<div class="container" ng-controller="SubmitController as SubmitCtrl">
			
			<div class="row" ng-init="UserCtrl.getUserInfo('{{$user}}')" ng-controller="MessageController as MsgCtrl">
				<div class="col-md-8" ng-init="UserCtrl.getUserTask('{{$user}}');">
					<h4 class="pull-right"><i class="fa fa-puzzle-piece"></i> โจทย์ที่เคยทำ</h4>
					<ul class="nav nav-tabs" ng-init="typeTask = 'recent';">
						<li ng-class="{active: typeTask == 'recent'}"><a href ng-click="typeTask = 'recent'; currentTasks = UserCtrl.tasks.recent"><b>ล่าสุด</b></a></li>
						<li ng-class="{active: typeTask == 'pass'}"><a href ng-click="typeTask = 'pass'; currentTasks = UserCtrl.tasks.pass"><b>ผ่านแล้ว</b> <span class="label label-success">@{{UserCtrl.tasks.pass.length}}</span></a></li>
						<li ng-class="{active: typeTask == 'notpass'}"><a href ng-click="typeTask = 'notpass'; currentTasks = UserCtrl.tasks.notpass"><b>ยังไม่ผ่าน</b> <span class="label label-danger">@{{UserCtrl.tasks.notpass.length}}</span></a></li>
					</ul>

					<br>

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

					<table class="table table-condensed table-hover" style="text-align:center">
						<thead>
							<tr>
								<td><a href ng-click="currentSort = 'id'; rev = !rev"><b>#</b></a></td>
								<td style="text-align:left"><a href ng-click="currentSort = 'name'; rev = !rev"><b>ชื่อโจทย์</b></a></td>
								<td><a href ng-click="currentSort = 'date.date'; rev = !rev"><b>เวลาล่าสุด</b></a></td>
								@if(Auth::check())
									@if(Auth::user()->username == $user || $user == '')
									<td></td>
									@endif
								@endif
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="task in currentTasks | filter: searchTasks3 | orderBy : currentSort : rev" ng-class="{success: task.pass == '1'}">
								<td>@{{task.id}}</td>
								<td style="text-align:left"><a href="../../task/@{{task.id}}">@{{task.name}}</a></td>
								<td>@{{task.date.date}}</td>
								@if(Auth::check())
									@if(Auth::user()->username == $user || $user == '')
									<td><a href class="btn btn-default btn-xs" ng-click="SubmitCtrl.setShowCode(task.submit)" data-toggle="modal" data-target="#showCode"><b>{ }</b></a></td>
									@endif
								@endif
							</tr>
						</tbody>
					</table>

					<h1 style="text-align:center; color:#999" ng-show="currentTasks.length == 0"><br>ไม่มีโจทย์ในส่วนนี้</h1>

				</div>
				<div class="col-md-4">
					@include('profile.partials.menu', ['active' => 'task', 'user' => $user])
				</div>
			</div>

			@if(Auth::check())
				@if(Auth::user()->username == $user || $user == '')
				<!-- show code -->
				<div class="modal fade" id="showCode" aria-hidden="true">
					<div class="modal-dialog" style="width:800px; height:70%">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">#@{{SubmitCtrl.currentCode.id}}</h4>
							</div>
							<iframe src="@{{'../../code/' + SubmitCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
							</div>
						</div>
					</div>
				</div>
				@endif
			@endif

		</div>

	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>
