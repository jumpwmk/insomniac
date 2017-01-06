<?php 
use App\Config; 
use App\Contest; 

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
				<div class="col-md-8" ng-init="ConCtrl.getTasks('{{$contest_id}}')">

					<h2><i class="fa fa-cog"></i> ตั้งค่าการแข่งขันขั้นสูง</h2>

					<hr>

					<div class="panel panel-default panel-body">

						<p>
							<label>
								บันทึกตารางคะแนน และนำข้อมูลไปใช้กับความสามารถส่วนอื่น
							</label>
							<button ng-hide="ConCtrl.contest.data.save_scoreboard == true" class="btn btn-success pull-right" ng-click="ConCtrl.saveScoreboard('{{$contest_id}}')" ng-class="{disabled : ConCtrl.contest.status != 'old'}">บันทึกตารางคะแนน</button>
							<button ng-show="ConCtrl.contest.data.save_scoreboard == true" class="btn btn-primary pull-right" ng-click="ConCtrl.saveScoreboard('{{$contest_id}}')" ng-class="{disabled : ConCtrl.contest.status != 'old'}">อัพเดทตารางคะแนน</button>
						</p>

						<br>
						<label>
							<input type="checkbox" ng-checked="ConCtrl.contest.data.scoreboard" ng-model="ConCtrl.contest.data.scoreboard">
							แสดงตารางคะแนนผู้เข้าแข่งขันระหว่างการแข่งขัน
						</label>

						<button class="btn btn-primary pull-right" ng-click="ConCtrl.saveData()">บันทึก</button>

					</div>

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
								<td><b><a href ng-click="rev = !rev; key = 'info.name'">ชื่อโจทย์</a></b></td>
								<td><b><a href ng-click="rev = !rev; key = 'info.testcase'">ข้อมูลทดสอบ</a></b></td>
								<td><b><a href ng-click="rev = !rev; key = 'info.time'">เวลา</a></b></td>
								<td><b><a href ng-click="rev = !rev; key = 'info.memory'">หน่วยความจำ</a></b></td>
								<td><b><a href ng-click="rev = !rev; key = 'info.full_score'">คะแนน</a></b></td>
								<td></td>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="task in ConCtrl.tasks | filter: searchTasks | orderBy: key: rev">
								<td>@{{task.order}}</td>
								<td><a href="task/@{{task.order}}">@{{task.info.name}}</a></td>
								<td>@{{task.info.testcase}} ( +@{{task.info.pretestcase}} )</td>
								<td>@{{task.info.time}}</td>
								<td>@{{task.info.memory}}</td>
								<td>@{{task.info.full_score}}</td>
								<td><button data-toggle="modal" data-target="#setScore" class="btn btn-sm btn-primary" ng-click="ConCtrl.setScore(task, $index)">กำหนดคะแนน</button></td>
							</tr>
						</tbody>

					</table>

					<!-- set score -->
					<div class="modal fade" id="setScore" aria-hidden="true">
						<div class="modal-dialog" style="width:500px">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">กำหนดคะแนนโจทย์ข้อ "@{{ConCtrl.curScore.info.name}}"</h4>
								</div>
								<form>
									<div class="modal-body">
										<input type="number" class="form-control" ng-model="ConCtrl.curScore.data.score" placeholder="จำนวนนับ หรือจำนวนจริง" required>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
										<input type="submit" class="btn btn-primary pull-right" ng-click="ConCtrl.saveScore()" data-dismiss="modal" value="บันทึก">
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>
				<div class="col-md-4">

					@include('contest.partials.status', ['active' => 'config'])

				</div>
				
				<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>
			</div>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>