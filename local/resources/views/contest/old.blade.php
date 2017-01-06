<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])
		<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

			<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

			<div class="row" ng-controller="ContestController as ConCtrl" ng-init="ConCtrl.getContests();">
				<div class="col-md-8">

					<h2><i class="fa fa-trophy"></i> การแข่งขัน</h2><hr>

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
					<table class="table table-condensed table-hover" style="text-align:center" ng-init="curSrt = '-end_contest'">
						<thead>
							<tr>
								<td ng-click="curSrt = 'id'; rev = !rev"><b><a href>#</a></b></td>
								<td style="text-align:left" ng-click="curSrt = 'name'; rev = !rev"><b><a href>ชื่อ</a></b></td>
								<td ng-click="curSrt = 'start_contest'; rev = !rev"><b><a href>จบการแข่งขันเมื่อ</a></b></td>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="contest in ConCtrl.contests.old | filter: searchContests | orderBy: curSrt: rev">
								
								<td>@{{contest.id}}</td>
								<td style="text-align:left">
									<a href="@{{contest.type}}/@{{contest.id}}">@{{contest.name}}</a>
								</td>
								<td>@{{contest.end_contest * 1000 | date: 'medium'}}</td>
								</div>
								</td>
							</tr>
						</tbody>
					</table>

					<h1 style="text-align:center; color:#999" ng-show="ConCtrl.contests.old.length == 0"><br>ไม่มีการแข่งขันในส่วนนี้</h1>

				</div>
				<div class="col-md-4">
					@include('contest.partials.menu', ['active' => 'old'])
				</div>
			</div>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>
