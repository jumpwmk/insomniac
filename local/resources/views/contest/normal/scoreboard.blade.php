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

			<div class="row" ng-controller="NormalController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}'); ConCtrl.getTasks('{{$contest_id}}')">
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
					<div ng-hide="ConCtrl.contest.status == 'old' || ConCtrl.contest.data.scoreboard == '1'">
						<br><br>
						<h1 style="color:#999; text-align:center">จะแสดงตารางคะแนนเมื่อจบการแข่งขัน</h1>
					</div>
					<div ng-show="ConCtrl.contest.status == 'old' || ConCtrl.contest.data.scoreboard == '1'">
					@endif
						<div class="form-horizontal">
								<div class="form-group has-feedback">
								<label class="control-label col-md-3">ค้นหาผู้เข้าแข่งขัน</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchUsers">
									<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
									<span id="inputSuccess3Status" class="sr-only">(success)</span>
								</div>
								</div>
							</div>
							
							<table class="table table-condensed" ng-init="ConCtrl.getContestants('{{$contest_id}}', false)" style="text-align:center">
								<thead>
									<tr>
										<td><b>#</b></td>
										<td style="text-align:left"><b>ชื่อผู้เข้าแข่งขัน</b></td>
										<td ng-repeat="task in ConCtrl.tasks | orderBy: 'order'"><b><a href="task/@{{task.order}}">ข้อที่ @{{task.order}}</a></td>
										<td><b>คะแนนรวม</b></td>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="user in ConCtrl.contestants | filter: searchUsers | orderBy: '-total_score'" ng-class="{'active': user.me == '1'}">
										<td>@{{user.place}}</td>
										<td style="text-align:left"><a href="../../../profile/@{{user.info.username}}" ng-class="getUserRatingColorClass(user.info)">@{{user.info.display}}</a></td>
										<td ng-repeat="task in ConCtrl.tasks | orderBy: 'order'" ng-class="{'success': user.pass[task.order] == '1', 'danger': user.pass[task.order] == '0'}">
											<span ng-show="user.pass[task.order] != null">@{{user.score[task.order]}}</span>
										</td>
										<td><b>@{{user.total_score}}</b></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div ng-hide="ConCtrl.contest.status == 'running' || ConCtrl.contest.status == 'old'">
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
