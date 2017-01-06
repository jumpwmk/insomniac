<?php use App\Config; ?>

<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="UserController as UserCtrl">
		@include('partials.menubar', ['active' => 'profile'])
		<div class="container">
			
			<div class="row" ng-controller="MessageController as MsgCtrl">
				<div class="col-md-8" ng-init="UserCtrl.getUserContest('{{$user}}');">
					<h2><i class="fa fa-trophy"></i> การแข่งขันที่เข้าร่วม</h2><hr>

					<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

					<div class="form-horizontal">
						<div class="form-group has-feedback">
						<label class="control-label col-md-3">ค้นหาการแข่งขัน</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchContests">
							<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
							<span id="inputSuccess3Status" class="sr-only">(success)</span>
						</div>
						</div>
					</div>

					<table class="table table-condensed table-hover" style="text-align:center">
						<thead>
							<tr>
								<td><b><a href ng-click="rev = !rev; key = 'id'">#</a></b></td>
								<td style="text-align:left"><a href ng-click="rev = !rev; key = 'name'"><b>ชื่อการแข่งขัน</b></a></td>
								<td><a href ng-click="rev = !rev; key = 'start_contest'"><b>เวลาการแข่งขัน</b></a></td>
								<td><a href ng-click="rev = !rev; key = 'contestant'"><b>จำนวนผู้เข้าแข่งขัน</b></a></td>
								<td><a href ng-click="rev = !rev; key = 'place'"><b>อันดับ</b></a></td>
								<td><a href ng-click="rev = !rev; key = 'rating'"><b>ระดับ</b></a></td>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="contest in UserCtrl.contests | filter: searchContests | orderBy: key: rev" ng-hide="contest.place == 0">
								<td>@{{contest.id}}</td>
								<td style="text-align:left"><a href="../../contest/@{{contest.type}}/@{{contest.id}}">@{{contest.name}}</a></td>
								<td>@{{contest.start_contest}}</td>
								<td>@{{contest.contestant}}</td>
								<td>@{{contest.place}}</td>
								<td>@{{contest.rating}}</td>
							</tr>
						</tbody>
					</table>

					<h1 style="text-align:center; color:#999" ng-show="UserCtrl.contests.length == 0"><br>ไม่มีการแข่งขันในส่วนนี้</h1>

				</div>
				<div class="col-md-4">
					@include('profile.partials.menu', ['active' => 'contest', 'user' => $user])
				</div>
			</div>
		</div>

	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>
