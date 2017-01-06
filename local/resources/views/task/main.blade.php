<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'task'])
		<div class="container" ng-controller="TaskController as TaskCtrl">

			<div class="row">

				<div class="col-md-8" ng-init="TaskCtrl.getTasks('{{$active}}')">

					<h2><i class="fa fa-puzzle-piece"></i> โจทย์</h2><hr>

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

					<table class="table table-hover table-condensed" style="text-align:center">
						<thead>
							<tr>
								<td><a href ng-click="key = 'id'; rev = !rev"><b>#</b></a></td>
								<td style="text-align:left"><a href ng-click="key = 'name'; rev = !rev"><b>ชื่อโจทย์</b></a></td>
								@if($active == 'all')
								<td><a href ng-click="key = 'rating'; rev = !rev"><b>ระดับ</b></a></td>
								@endif
								<td><a href ng-click="key = 'pass'; rev = !rev"><b>ผ่านแล้ว</b></a></td>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="task in TaskCtrl.tasks | filter: searchTasks | orderBy: key: rev" ng-class="{'success': task.me == '1'}">
								<td>@{{task.id}}</td>
								<td style="text-align:left"><a href="@{{task.id}}">@{{task.name}}</a></td>
								@if($active == 'all')
								<td style="vertical-align:middle">
									<span ng-hide="task.rating" class="label label-default">UNRATED</span>
									<span ng-show="1 <= task.rating && task.rating <= 2" class="label label-success">EASY</span>
									<span ng-show="2 < task.rating && task.rating <= 3.5" class="label label-warning">MEDIUM</span>
									<span ng-show="3.5 < task.rating" class="label label-danger">HARD</span>
								</td>
								@endif
								<td>@{{task.pass}}</td>
							</tr>
						</tbody>
					</table>

					<center ng-show="TaskCtrl.tasks.length == 0"><br><h1 style="color:#999">ยังไม่มีโจทย์ในส่วนนี้</h1></center>

				</div>

				<div class="col-md-4">
					@include('task.partials.menu')
				</div>
				
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
