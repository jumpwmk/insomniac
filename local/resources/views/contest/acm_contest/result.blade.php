<?php 
use App\Config;
use App\Problem;
use App\Contest;
use Illuminate\Http\RedirectResponse;

$contest = Contest::find($contest_id);
if(!(Auth::isAdmin() or $contest->visible) or !Auth::check())
{
	echo '<i style="display:none">'.redirect('signin').'</i>';
	exit();
}

Contest::isTrueType($contest_id, 'acm_contest'); 
?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])

		<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

			<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

			<div class="row" ng-controller="ACMContestController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}');">
				<div class="col-md-8">

					<ul class="nav nav-tabs">
						<li ng-class="{'active': 'task' == '{{$active}}'}"><a href="task"><b>โจทย์</b></a></li>
						<li ng-show="ConCtrl.contest.registered == '1'" ng-class="{'active': 'result' == '{{$active}}'}"><a href="result"><b>ผลตรวจ</b></a></li>
						<li ng-class="{'active': 'scoreboard' == '{{$active}}'}"><a href="scoreboard"><b>ตารางคะแนนผู้เข้าแข่งขัน</b></a></li>
					</ul>

					<div ng-show="ConCtrl.contest.registered == '1'" >
						<div ng-init="ConCtrl.getSubmits('{{$contest_id}}', 0, 25); skip = 25;">

							<br>
							<table class="table table-condensed table-hover" style="text-align:center">
								<thead>
									<tr>
										<td><b><a href ng-click="key = 'id'; rev = !rev">#</a></b></td>
										<td><b><a href ng-click="key = 'created_at'; rev = !rev">เวลาส่ง</a></b></td>
										<td><b><a href ng-click="key = 'task.name'; rev = !rev">โจทย์</a></b></td>
										<td><b><a href ng-click="key = 'result'; rev = !rev">ผลตรวจ</a></b></td>
										<td ng-show="ConCtrl.contest.data.full_feedback == '1' || ConCtrl.contest.status == 'old'"><b><a href ng-click="key = 'time'; rev = !rev">เวลา</a></b></td>
										<td ng-show="ConCtrl.contest.data.full_feedback == '1' || ConCtrl.contest.status == 'old'" style="width:120px"><b><a href ng-click="key = 'memory'; rev = !rev" style="width:120px">หน่วยความจำ</a></b></td>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="submit in ConCtrl.submits | orderBy: key: rev" ng-class="{'success': submit.pass == 1}" ng-show="submit.visible == '1'">
										<td><a href ng-click="ConCtrl.setShowCode(submit)" data-toggle="modal" data-target="#showCode">@{{submit.id}}</a></td>
										<td>@{{submit.created_at}}</td>
										<td><a href="task/@{{submit.task.order}}">@{{submit.task.name}}</a></td>
										
										<td ng-show="submit.result.length">@{{submit.result}}</td>
										<td ng-hide="submit.result.length || submit.result.compile_result.length"><a href ng-click='ConCtrl.setShowError(submit.compile_result)' data-toggle="modal" data-target="#showError">compilation error</a></td>
										
										<td ng-show="ConCtrl.contest.data.full_feedback == '1' || ConCtrl.contest.status == 'old'">@{{submit.time}} s</td>
										<td ng-show="ConCtrl.contest.data.full_feedback == '1' || ConCtrl.contest.status == 'old'">@{{submit.memory}} KB</td>
									</tr>
								</tbody>
							</table>

							<center><button ng-show="loadSubmit == '1'" ng-click="ConCtrl.getSubmits('{{$contest_id}}', skip, 25); skip = skip + 25" class="btn btn-info">แสดงผลตรวจเพิ่มเติม</button></center><br>

							<!-- show error -->
							<div class="modal fade" id="showError" aria-hidden="true">
								<div class="modal-dialog" style="width:50%">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">compilation error</h4>
										</div>
										<div class="modal-body"><pre class="code">@{{ConCtrl.currentError}}</pre></div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
										</div>
									</div>
								</div>
							</div>

							<!-- show code -->
							<div class="modal fade" id="showCode" aria-hidden="true">
								<div class="modal-dialog" style="width:800px; height:70%">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">#@{{ConCtrl.currentCode.id}}</h4>
										</div>
										<iframe src="@{{'../../../code/' + ConCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
										</div>
									</div>
								</div>
							</div>

						</div>
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
