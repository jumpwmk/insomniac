<?php use App\Config; ?>
<div id="menu_sidebar">
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<div class='panel-title'>รายการ</div>
		</div>
		<div class='panel-body'>
			<ul class="nav nav-pills nav-stacked">
				<li ng-class="{active: '{{$active}}' == 'main'}"><a href="{{Config::root()}}/profile/{{$user}}">ข้อมูลทั่วไป</a></li>
				@if(Auth::check())
					@if((Auth::user()->username == $user || $user == '') && $active == 'main')
						<li><a href data-toggle="modal" data-target="#editInfo" ng-click="UserCtrl.setEditUserInfo()">แก้ไขข้อมูลทั่วไป</a></li>
						<li><a href data-toggle="modal" data-target="#changePassword" ng-click="UserCtrl.clearChangePassword()">เปลี่ยนรหัสผ่าน</a></li>
						<li><a href data-toggle="modal" data-target="#changeImage">เปลี่ยนรูปโปรไฟล์</a></li>
						<li><a href data-toggle="modal" data-target="#changeCodestyle" ng-click="UserCtrl.setChangeStyle()">เปลี่ยนธีมโค้ด</a></li>
					@endif
					<li ng-class="{active: '{{$active}}' == 'message'}"><a href="{{Config::root()}}/profile/message/{{$user}}">
						ข้อความส่วนตัว
						@if(Auth::user()->username == $user || $user == '')
						<span class="badge" ng-init="MsgCtrl.unReadMessage()" ng-show="MsgCtrl.unread > 0">
							@{{MsgCtrl.unread}}
						</span>
						@endif
					</a></li>
				@endif
				<hr>
				<li ng-class="{active: '{{$active}}' == 'contest'}"><a href="{{Config::root()}}/profile/contest/{{$user}}">การแข่งขันที่เข้าร่วม</a></li>
				<li ng-class="{active: '{{$active}}' == 'task'}"><a href="{{Config::root()}}/profile/task/{{$user}}">โจทย์ที่เคยทำ</a></li>
				<li ng-class="{active: '{{$active}}' == 'discuss'}"><a href="{{Config::root()}}/profile/discuss/{{$user}}">การพูดคุยของผู้ใช้</a></li>
			</ul>
		</div>
	</div>
</div>