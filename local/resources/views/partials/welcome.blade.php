<?php use App\Config; ?>

<div class="row" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">
	<div class="col-md-8">

		<div ng-controller="DiscussController as DisCtrl" ng-init="DisCtrl.getPins('comment_at', 'DESC', null, null, null)" ng-show="DisCtrl.pins.length">

			<h2><span class="fa fa-thumb-tack"></span> ประกาศ</h2>
			<hr>

			<div class="panel panel-body panel-info" ng-repeat="post in DisCtrl.pins">

				<h4><a href="discuss/post/@{{post.id}}">@{{post.title}}</a></h4>
				<div ta-bind ng-model="post.mid_body"></div>
				<span class="pull-right">
					<table>
						<tr>
							<td style="width:35px">
								<a href="profile/@{{post.user.username}}">
									<img src="img/user/@{{post.user.username}}.jpg" ng-show="post.user.image == '1'" style="max-width:30px; max-height:30px">
									<img src="img/user/0.jpg" ng-hide="post.user.image == '1'" style="max-width:30px; max-height:30px">
								</a>
							</td>
							<td>
								<a href="profile/@{{post.user.username}}" ng-class="getUserRatingColorClass(post.user)">@{{post.user.display}}</a><br>
								<span style="color:#999; font-size:70%">@{{post.commentDate | date: 'mediumDate'}}</span>
							</td>
						</tr>
					</table>
				</span>
			
			</div>

		</div>


		<div ng-controller="ContestController as ConCtrl" ng-init="ConCtrl.getContests();" ng-show="contests.length">

			<h2><i class="fa fa-trophy"></i> การแข่งขัน</h2>
			<p class="pull-right">
				<a href="contest/">การแข่งขันทั้งหมด</a>
			</p>
			<hr>

			<!-- ng-class="{'panel-danger': contest.status == 'register', 'panel-warning': contest.status == 'coming', 'panel-success': contest.status == 'running', 'panel-default': contest.status == 'declare'}" -->
			<div ng-repeat="contest in contests | orderBy : 'start_contest'" class="panel panel-default">  
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

						<div class="panel-title"><a href="contest/@{{contest.type + '/' + contest.id}}"><b>@{{contest.name}}</b></a></div>
					</div>
				</div>
				<div class="panel-body" ng-init="contest.duration = timeLeft((contest.end_contest - contest.start_contest) * 1000)">

					
					โจทย์ทั้งหมด @{{contest.task}} ข้อ <br>
					
					<a class="btn btn-lg btn-default pull-right" ng-show="contest.status == 'running'" href="contest/@{{contest.type + '/' + contest.id}}">เข้าสู่การแข่งขัน</a>
					
					เวลาในการแข่ง <span ng-show="contest.duration.day">@{{contest.duration.day}} วัน</span> <span ng-show="contest.duration.hour"> @{{contest.duration.hour}} ชั่วโมง </span><span ng-show="contest.duration.min">@{{contest.duration.min}} นาที</span><br>

					
					<span ng-hide="contest.status == 'running'">เริ่มแข่ง @{{contest.start_contest * 1000 | date: 'medium'}}<br></span>
					<span ng-show="contest.status == 'running'">หมดเวลาแข่งขัน @{{contest.end_contest * 1000 | date: 'medium'}}<br></span>

					
					<a class="btn btn-lg btn-default pull-right" ng-show="contest.status == 'register' && contest.registered == '0' " href="contest/register/@{{contest.id}}">สมัครแข่งขัน</a>
					<a class="btn btn-lg btn-default pull-right disabled" ng-show="contest.status == 'register' && contest.registered == '1'">สมัครแล้ว</a>
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

		<div ng-init="TaskCtrl.getTasks('main')" ng-controller="TaskController as TaskCtrl" ng-show="TaskCtrl.tasks.length">
			<h2><i class="fa fa-puzzle-piece"></i> โจทย์ใหม่</h2>
			<p class="pull-right">
				<a href="task/">โจทย์ทั้งหมด</a>
			</p>
			<hr>

			<table class="table table-condensed table-hover" style="text-align:center" >
				<thead>
					<tr>
						<td><b>#</b></td>
						<td><b>เพิ่มเมื่อ</b></td>
						<td style="text-align:left"><b>ชื่อโจทย์</b></td>
						<td><b>ระดับ</b></td>
						<td><b>ผ่านแล้ว</b></td>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="task in TaskCtrl.tasks | limitTo: 10 | orderBy: '-created_at'" ng-class="{'success': task.me == '1', 'danger': task.me == '0'}">
						<td>@{{task.id}}</td>
						<td>@{{task.created_at}}</td>
						<td style="text-align:left"><a href="task/@{{task.id}}">@{{task.name}}</a></td>
						<td style="vertical-align:middle">
							<span ng-hide="task.rating" class="label label-default">UNRATED</span>
							<span ng-show="1 <= task.rating && task.rating <= 2" class="label label-success">EASY</span>
							<span ng-show="2 < task.rating && task.rating <= 3.5" class="label label-warning">MEDIUM</span>
							<span ng-show="3.5 < task.rating" class="label label-danger">HARD</span>
						</td>
						<td>@{{task.pass}}</td>
					</tr>
				</tbody>
			</table>

			<h1 style="text-align:center; color:#999" ng-show="TaskCtrl.tasks.length == 0"><br>ไม่มีโจทย์ในส่วนนี้</h1>
		</div>

		<div ng-controller="DiscussController as DisCtrl" ng-init="DisCtrl.getPosts('comment_at', 'DESC', 0, 10, null)" ng-show="DisCtrl.posts.length">

			<h2><i class="fa fa-comments-o"></i> พูดคุย</h2>
			<p class="pull-right">
				<a href="discuss/">การพูดคุยทั้งหมด</a>
			</p>
			<hr>

			<table class="table table-striped" style="text-align:center; ">
				<thead>
					<tr>
						<td style="text-align:left"><b>หัวข้อ</b></td>
						<td style="text-align:left; width:120"><b>ผู้ใช้</b></td>
						<td><b>ตอบ</b></td>
						<td><b>อ่าน</b></td>
						<td style="width:120"><b>ล่าสุด</b></td>
					</tr>
				</thead>

				<tbody>

					<tr ng-repeat="post in DisCtrl.posts" style="height:50">
						<td style="text-align:left; vertical-align:middle"><a href="discuss/post/@{{post.id}}">
							@{{post.title | limitTo: 37}}
							<span ng-show="post.title.length > 37">...</span>
						</a></td>
						<td style="text-align:left; vertical-align:middle">
							<a href="profile/@{{post.user.username}}">
								<img src="img/user/@{{post.user.username}}.jpg" ng-show="post.user.image == '1'" style="max-width:25px; max-height:25px">
								<img src="img/user/0.jpg" ng-hide="post.user.image == '1'" style="max-width:25px; max-height:25px">
							</a>

							<a href="profile/@{{post.user.username}}" ng-class="getUserRatingColorClass(post.user)">
								@{{post.user.display}}
							</a>
						</td>
						<td style="vertical-align:middle">@{{post.comments.length}}</td>
						<td style="vertical-align:middle">@{{post.view_data.length}}</td>
						<td style="vertical-align:middle">@{{post.commentDate | date: 'mediumDate'}}</td>
					</tr>

				</tbody>
			</table>
			
		</div>
		<br>

	</div>
	<div class="col-md-4"> 	
		@if(Auth::check())
			@include('partials.userInfo')
		@else
			@include('forms.signin')
		@endif
		@include('partials.top10')
		@include('partials.facebook')
	</div>
</div>
