<?php use App\Config ?>

<div class="panel panel-default" ng-controller="UserController as UserCtrl" ng-init="UserCtrl.getUserInfo('<?=Auth::user()->username?>')">
	
	<div class="panel-heading">
		<div class="panel-title">
			<a href="profile/" ng-class="getUserRatingColorClass(UserCtrl.userInfo)">@{{UserCtrl.userInfo.display}}</a>
		</div>
	</div>

	<div class="panel-body">
		<div ng-init="UserCtrl.getUserTask('<?=Auth::user()->username?>'); UserCtrl.getUserContest('<?=Auth::user()->username?>')" class="pull-right">
			ระดับ: <span ng-show="UserCtrl.contests.length != null && UserCtrl.contests.length != 0">@{{UserCtrl.userInfo.rating}}</span>
				<span ng-show="UserCtrl.contests.length == 0" class="label label-default">UNRATED</span><br>
			การแข่งขัน: @{{UserCtrl.contests.length}} <br>
			โจทย์ที่ทำทั้งหมด: @{{UserCtrl.tasks.recent.length}} <br>
			โจทย์ที่ผ่านแล้ว: @{{UserCtrl.tasks.pass.length}} <br>
			โจทย์ที่ยังไม่ผ่าน: @{{UserCtrl.tasks.notpass.length}}
			<br><br>
			<span class="pull-right"><a href="profile/"><i class="fa fa-user"></i> ข้อมูลผู้ใช้</a></span>
		</div>

		<img ng-show="UserCtrl.userInfo.image == '1'" ng-src="{{Config::root()}}/img/user/@{{UserCtrl.userInfo.username}}.jpg" style="max-width:120px; max-height:120px">
		<img ng-show="UserCtrl.userInfo.image != '1'" ng-src="{{Config::root()}}/img/user/0.jpg" style="max-width:120px; max-height:120px">
	</div>
</div>
