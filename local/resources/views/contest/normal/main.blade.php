<?php 
use App\Config;
use App\Problem;
use App\Contest;
use Illuminate\Http\RedirectResponse;

$contest = Contest::find($contest_id);
if(!(Auth::isAdmin() or $contest->visible))
{
	echo '<i style="display:none">'.redirect('signin').'</i>';
	exit();
}

Contest::isTrueType($contest_id, 'normal'); 
?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])

		<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

			<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

			<div class="row" ng-controller="NormalController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}'); ConCtrl.getTasks('{{$contest_id}}');">
				<div class="col-md-8">

					<ul class="nav nav-tabs">
						<li ng-class="{'active': 'task' == '{{$active}}'}"><a href="task"><b>โจทย์</b></a></li>
						<li ng-show="ConCtrl.contest.registered == '1'"  ng-class="{'active': 'result' == '{{$active}}'}"><a href="result"><b>ผลตรวจ</b></a></li>
						<li ng-class="{'active': 'scoreboard' == '{{$active}}'}"><a href="scoreboard"><b>ตารางคะแนนผู้เข้าแข่งขัน</b></a></li>
					</ul>
					<br>

					@if(Auth::isAdmin())
					<div>
					@else
					<div ng-show="ConCtrl.contest.status == 'running' || ConCtrl.contest.status == 'old'">
					@endif
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
									<td><b><a href ng-click="rev = !rev; key = 'order'">#</a></b></td>
									<td style="text-align:left"><b><a href ng-click="rev = !rev; key = 'info.name'">ชื่อโจทย์</a></b></td>
									<td><b><a href ng-click="rev = !rev; key = 'info.time'">เวลา</a></b></td>
									<td><b><a href ng-click="rev = !rev; key = 'info.memory'" style="width:120px">หน่วยความจำ</a></b></td>
									<td><b><a href ng-click="rev = !rev; key = 'full_score'">คะแนนเต็ม</a></b></td>
									<td ng-show="ConCtrl.contest.data.scoreboard || ConCtrl.contest.status == 'old'"><b><a href ng-click="rev = !rev; key = 'count_pass'">ผ่านแล้ว</a></b></td>
								</tr>
							</thead>

							<tbody>
								<tr ng-repeat="task in ConCtrl.tasks | filter: searchTasks | orderBy: key: rev" ng-class="{'success': task.pass == '1', 'danger': task.pass == '0'}">
									<td>@{{task.order}}</td>
									<td style="text-align:left"><a href="task/@{{task.order}}">@{{task.info.name}}</a></td>
									<td>@{{task.info.time}} วินาที</td>
									<td>@{{task.info.memory}} MB</td>
									<td>@{{task.full_score}}</td>
									<td ng-show="ConCtrl.contest.data.scoreboard || ConCtrl.contest.status == 'old'">@{{task.count_pass}}</td>
								</tr>
							</tbody>

						</table>
					</div>

					<div ng-show="ConCtrl.contest.status != null && ConCtrl.contest.status != 'running' && ConCtrl.contest.status != 'old'">
						<br><br>
						<h1 style="color:#999; text-align:center">การแข่งขันยังไม่เริ่ม</h1>
					</div>

				</div>
				<div class="col-md-4">

					@include('contest.partials.status', ['active' => 'contest'])

				</div>
			</div>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>
