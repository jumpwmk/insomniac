<?php 
use App\Config; 
use App\Contest; 

Contest::isTrueType($contest_id, 'partial_feedback');
?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])

		<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

			<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

			<div class="row" ng-controller="PartialFeedbackController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}');">
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
							<input type="checkbox" ng-checked="ConCtrl.contest.data.score_only" ng-model="ConCtrl.contest.data.score_only">
							แสดงคะแนนเท่านั้น น่าจะไม่มีอะไรเกิดขึ้น ขี้เกียจลบ
						</label>
						| 
						<label>
							<input type="checkbox" ng-checked="ConCtrl.contest.data.scoreboard" ng-model="ConCtrl.contest.data.scoreboard">
							แสดงตารางคะแนนผู้เข้าแข่งขันระหว่างการแข่งขัน
						</label>
						<label>
							<input type="checkbox" ng-checked="ConCtrl.contest.data.scoreboard_pending" ng-model="ConCtrl.contest.data.scoreboard_pending">
							ซ่อนตารางคะแนนหลังการแข่ง (Pending)
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
								<td><button data-toggle="modal" data-target="#setScore" class="btn btn-sm btn-primary" ng-click="ConCtrl.setScore(task, $index);">กำหนดคะแนน</button></td>
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
								<form style="margin:0">
									<div class="modal-body">
										<div ng-repeat="testcase in xrange(1,ConCtrl.curScore.info.testcase)" class="form-horizontal">
											<div class="col-sm-6">
												<label class="pull-right control-label">คะแนนข้อมูลทดสอบที่ @{{testcase}}</label>
											</div>
											<div class="col-sm-3">
												<input type="number" class="form-control" ng-model="ConCtrl.curScore.data.score[$index]" placeholder="จำนวนนับ หรือจำนวนจริง" required>
											</div>
											<div class="col-sm-3">
												<input type="number" class="form-control" ng-model="ConCtrl.curScore.data.scoreReal[$index]" placeholder="จำนวนนับ หรือจำนวนจริง" required>
											</div>
											<br><br>
										</div>

										<hr>
										<div class="form-horizontal">
											<div class="col-sm-5">
												<label class="pull-right control-label">จำนวนกลุ่มข้อมูลทดสอบ</label>
											</div>
											<div class="col-sm-7 form-inline">
												<input class="form-control" style="width:150" ng-model="countGroup" required>
												<button class="btn btn-primary" ng-click="ConCtrl.initTaskData(countGroup)">ยืนยัน</button>
											</div>
										</div>
										<br>
										<br>
										<br>
										<div ng-repeat="group in ConCtrl.curScore.data.group" class="panel panel-body panel-default form-horizontal">
											<div class="col-sm-6">
												<label class="pull-right control-label">กลุ่มข้อมูลทดสอบที่ @{{$index+1}} ประเภท</label>
											</div>
											<div class="col-sm-6">
												<select ng-options="type for type in ['isolate' ,'link']" ng-model="ConCtrl.curScore.data.group[$index].type" class="form-control"></select>
											</div>
											<br><br>

											<label class=" control-label">ข้อมูลทดสอบในกลุ่ม</label>
											<div class="form-inline">
												<span ng-repeat="testcase in ConCtrl.curScore.data.group[$index].case"><button class="btn btn-default btn-sm" ng-click="remove(testcase, ConCtrl.curScore.data.group[$parent.$index].case)"><b>@{{testcase}}</b></button> </span>
												<select ng-options="testcase for testcase in xrange(1, ConCtrl.curScore.data.score.length)" ng-model="addTestcase" class="form-control"></select>
												<a class="btn btn-primary" ng-click="ConCtrl.addTestcase($index, addTestcase)">เพิ่ม</a>
											</div>

											<label class=" control-label">จะให้คะแนนก็ต่อเมื่อกลุ่มดังนี้ผ่านแล้ว</label>
											<div class="form-inline">
												<span ng-repeat="depend in ConCtrl.curScore.data.group[$index].depend"><button class="btn btn-default btn-sm" ng-click="remove(depend, ConCtrl.curScore.data.group[$parent.$index].depend)"><b>@{{depend + 1}}</b></button> </span>
												<select ng-options="depend+1 for depend in xrange(0, ConCtrl.curScore.data.group.length-1)" ng-model="addDepend" class="form-control"></select>
												<a class="btn btn-primary" ng-click="ConCtrl.addDepend($index, addDepend)">เพิ่ม</a>
											</div>
										</div>
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