<!DOCTYPE html>
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

					<ul class="nav nav-tabs" ng-init="active = 'new';">
						<li ng-class="{active: active == 'new'}"><a href ng-click="active = 'new'; contests = ConCtrl.contests.new"><b>การแข่งขันใหม่</b></a></li>
						<li ng-class="{active: active == 'declare'}"><a href ng-click="active = 'declare'; contests = ConCtrl.contests.declare"><b>ยังไม่รับสมัคร</b></a></li>
						<li ng-class="{active: active == 'register'}"><a href ng-click="active = 'register'; contests = ConCtrl.contests.register"><b>กำลังรับสมัคร</b></a></li>
						<li ng-class="{active: active == 'coming'}"><a href ng-click="active = 'coming'; contests = ConCtrl.contests.coming"><b>ปิดรับสมัครแล้ว</b></a></li>
						<li ng-class="{active: active == 'running'}"><a href ng-click="active = 'running'; contests = ConCtrl.contests.running"><b>กำลังแข่งขัน</b></a></li>
					</ul>

					<br>

					<h1 style="text-align:center; color:#999" ng-show="contests.length == 0"><br>ไม่มีการแข่งขันในส่วนนี้</h1>

					<div ng-repeat="contest in contests | orderBy : 'start_contest'" class="panel" ng-class="{'panel-danger': contest.status == 'register', 'panel-warning': contest.status == 'coming', 'panel-success': contest.status == 'running', 'panel-default': contest.status == 'declare'}">
						<div class="panel-heading">
							<div class="panel-title">

								<h5 class="pull-right" ng-show="contest.status == 'register'">
									<span ng-hide="true">@{{contest.nowRegister = timeLeft((contest.end_register) * 1000 - dateNow)}}</span>
									<b>
										เหลือเวลาสมัคร
										<span ng-show="contest.nowRegister.day != null">@{{contest.nowRegister.day}} วัน</span> 
										<span ng-show="contest.nowRegister.hour != null">@{{contest.nowRegister.hour}} ชั่วโมง</span> 
										<span ng-show="contest.nowRegister.min != null">@{{contest.nowRegister.min}} นาที</span>
										<span ng-show="contest.nowRegister.sec != null">@{{contest.nowRegister.sec}} วินาที</span>
									</b>

								</h5>

								<h5 class="pull-right" ng-show="contest.status == 'coming'">
									<span ng-hide="true">@{{contest.beforeContest = timeLeft((contest.start_contest) * 1000  - dateNow)}}</span>
									<b>
										เริ่มการแข่งขันใน
										<span ng-show="contest.beforeContest.day != null">@{{contest.beforeContest.day}} วัน</span> 
										<span ng-show="contest.beforeContest.hour != null">@{{contest.beforeContest.hour}} ชั่วโมง</span> 
										<span ng-show="contest.beforeContest.min != null">@{{contest.beforeContest.min}} นาที</span>
										<span ng-show="contest.beforeContest.sec != null">@{{contest.beforeContest.sec}} วินาที</span>
									</b>
								</h5>

								<h5 class="pull-right" ng-show="contest.status == 'running'">
									<span ng-hide="true">@{{contest.nowContest = timeLeft((contest.end_contest) * 1000  - dateNow)}}</span>
									<b>
										เหลือเวลาแข่งขัน
										<span ng-show="contest.nowContest.day != null">@{{contest.nowContest.day}} วัน</span> 
										<span ng-show="contest.nowContest.hour != null">@{{contest.nowContest.hour}} ชั่วโมง</span> 
										<span ng-show="contest.nowContest.min != null">@{{contest.nowContest.min}} นาที</span>
										<span ng-show="contest.nowContest.sec != null">@{{contest.nowContest.sec}} วินาที</span>
									</b>
								</h5>

								<div class="panel-title"><a href="@{{contest.type + '/' + contest.id}}"><b>@{{contest.name}}</b></a></b></div>
							</div>
						</div>
						<div class="panel-body" ng-init="contest.duration = timeLeft((contest.end_contest - contest.start_contest) * 1000)">

							
							โจทย์ทั้งหมด @{{contest.task}} ข้อ <br>
							
							<a class="btn btn-lg btn-success pull-right" ng-show="contest.status == 'running'" href="@{{contest.type + '/' + contest.id}}">เข้าสู่การแข่งขัน</a>
							
							เวลาในการแข่ง <span ng-show="contest.duration.day">@{{contest.duration.day}} วัน</span> <span ng-show="contest.duration.hour"> @{{contest.duration.hour}} ชั่วโมง </span><span ng-show="contest.duration.min">@{{contest.duration.min}} นาที</span><br>

							
							<span ng-hide="contest.status == 'running'">เริ่มแข่ง @{{contest.start_contest * 1000 | date: 'medium'}}<br></span>
							<span ng-show="contest.status == 'running'">หมดเวลาแข่งขัน @{{contest.end_contest * 1000 | date: 'medium'}}<br></span>

							
							<a class="btn btn-lg btn-danger pull-right" ng-show="contest.status == 'register' && contest.registered == '0' " href="register/@{{contest.id}}">สมัครแข่งขัน</a>
							<a class="btn btn-lg btn-danger pull-right disabled" ng-show="contest.status == 'register' && contest.registered == '1'">สมัครแล้ว</a>
							<span ng-hide="contest.status == 'comming' || contest.status == 'running'">เปิดรับสมัคร @{{contest.start_register * 1000 | date: 'medium'}} - @{{contest.end_register * 1000 | date: 'medium'}}<br></span>

							
							<b ng-show="contest.status == 'register'">
								<span ng-hide="true">@{{contest.beforeContest = timeLeft((contest.start_contest) * 1000  - dateNow)}}</span>
								เริ่มการแข่งขันใน
								<span ng-show="contest.beforeContest.day != null">@{{contest.beforeContest.day}} วัน</span> 
								<span ng-show="contest.beforeContest.hour != null">@{{contest.beforeContest.hour}} ชั่วโมง</span> 
								<span ng-show="contest.beforeContest.min != null">@{{contest.beforeContest.min}} นาที</span>
								<span ng-show="contest.beforeContest.sec != null">@{{contest.beforeContest.sec}} วินาที</span>
							</b>

						</div>
					</div>

				</div>
				<div class="col-md-4">
					@include('contest.partials.menu', ['active' => 'main'])
				</div>
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
