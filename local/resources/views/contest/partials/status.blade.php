<?php use App\Config; ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title"><a href="{{Config::root()}}/contest/@{{ConCtrl.contest.type}}/@{{ConCtrl.contest.id}}"><b>@{{ConCtrl.contest.name}}</b></a></div>
	</div>

	<div class="panel-body">

		<h4 ng-show="ConCtrl.contest.status == 'old'" style="text-align:center" class="panel panel-default panel-body">
			<span style="font-size:175%">สิ้นสุดการแข่งขัน</span>
		</h4>

		<h4 ng-show="ConCtrl.contest.status == 'declare'" style="text-align:center" class="panel panel-default panel-body">
			<span style="font-size:175%">ยังไม่รับสมัคร</span>
		</h4>

		<h4 ng-show="ConCtrl.contest.status == 'register'" style="text-align:center" class="panel panel-default panel-body">
			<span ng-hide="true">@{{ConCtrl.contest.nowRegister = timeLeft((ConCtrl.contest.end_register) * 1000 - dateNow)}}</span>
			<p style="font-size:175%">เหลือเวลาสมัคร</p><hr>
			<span ng-show="ConCtrl.contest.nowRegister.day != null">@{{ConCtrl.contest.nowRegister.day}} วัน</span> 
			<span ng-show="ConCtrl.contest.nowRegister.hour != null">@{{ConCtrl.contest.nowRegister.hour}} ชั่วโมง</span> 
			<span ng-show="ConCtrl.contest.nowRegister.min != null">@{{ConCtrl.contest.nowRegister.min}} นาที</span>
			<span ng-show="ConCtrl.contest.nowRegister.sec != null">@{{ConCtrl.contest.nowRegister.sec}} วินาที</span>
		</h4>

		<h4 ng-show="ConCtrl.contest.status == 'coming'" style="text-align:center" class="panel panel-default panel-body">
			<span ng-hide="true">@{{ConCtrl.contest.beforeContest = timeLeft((ConCtrl.contest.start_contest) * 1000  - dateNow)}}</span>
			<p style="font-size:175%">เริ่มการแข่งขันใน</p><hr>
			<span ng-show="ConCtrl.contest.beforeContest.day != null">@{{ConCtrl.contest.beforeContest.day}} วัน</span> 
			<span ng-show="ConCtrl.contest.beforeContest.hour != null">@{{ConCtrl.contest.beforeContest.hour}} ชั่วโมง</span> 
			<span ng-show="ConCtrl.contest.beforeContest.min != null">@{{ConCtrl.contest.beforeContest.min}} นาที</span>
			<span ng-show="ConCtrl.contest.beforeContest.sec != null">@{{ConCtrl.contest.beforeContest.sec}} วินาที</span>
		</h4>

		<h4 ng-show="ConCtrl.contest.status == 'running'" style="text-align:center" class="panel panel-default panel-body">
			<span ng-hide="true">@{{ConCtrl.contest.nowContest = timeLeft((ConCtrl.contest.end_contest) * 1000  - dateNow)}}</span>
			<p style="font-size:175%">เหลือเวลาแข่งขัน</p><hr>
			<span ng-show="ConCtrl.contest.nowContest.day != null">@{{ConCtrl.contest.nowContest.day}} วัน</span> 
			<span ng-show="ConCtrl.contest.nowContest.hour != null">@{{ConCtrl.contest.nowContest.hour}} ชั่วโมง</span> 
			<span ng-show="ConCtrl.contest.nowContest.min != null">@{{ConCtrl.contest.nowContest.min}} นาที</span>
			<span ng-show="ConCtrl.contest.nowContest.sec != null">@{{ConCtrl.contest.nowContest.sec}} วินาที</span>
		</h4>

		<ul class="nav nav-pills nav-stacked">
			<li ng-class="{active: '{{$active}}' == 'contest'}"><a href="{{Config::root()}}/contest/@{{ConCtrl.contest.type}}/@{{ConCtrl.contest.id}}">
				การแข่งขัน
			</a></li>
			<li ng-class="{active: '{{$active}}' == 'register'}"><a href="{{Config::root()}}/contest/register/@{{ConCtrl.contest.id}}">
				กติกาและการสมัคร
				<span class="badge">สมัครแล้ว @{{ConCtrl.contest.contestant}} คน</span>
			</a></li>
			@if(Auth::isAdmin())
			<hr>

			<li ng-class="{active: '{{$active}}' == 'config'}"><a href="{{Config::root()}}/contest/@{{ConCtrl.contest.type}}/@{{ConCtrl.contest.id}}/config">
				<b>ตั้งค่าการแข่งขันขั้นสูง</b>
			</a></li>
			@endif
		</ul>

	</div>
</div>

<div ng-show="ConCtrl.contest.registered != '1'" class="panel panel-default panel-body" style="text-align:center">
	<h3 style="color:#999">คุณยังไม่ได้สมัครการแข่งขันนี้</h3>
</div>

