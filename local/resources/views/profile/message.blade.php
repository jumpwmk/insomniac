<?php use App\Config; ?>

<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>
	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'profile'])
		<div ng-controller="UserController as UserCtrl" class="container">
			
			<div class="row" ng-init="UserCtrl.getUserInfo('{{$user}}')" ng-controller="MessageController as MsgCtrl">
				<div class="col-md-8">

					@if($user != '' && $user != Auth::user()->username)
					<h2><i class="fa fa-send"></i> ข้อความของคุณ กับ @{{UserCtrl.userInfo.display}}</h2>
					@else
					<h2><i class="fa fa-send"></i> ข้อความส่วนตัวของคุณ</h2>
					@endif

					<hr>

					<div class="form-horizontal">
						<div class="form-group has-feedback">
						<label class="control-label col-md-3">ค้นหาข้อความ</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchMsg">
							<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
						</div>
						</div>
					</div>

					<table class="table" ng-init="UserCtrl.getUserMessage('{{$user}}')">
						<thead>
							<tr>
								<td style="width:100; vertical-align:middle"></td>
								<td style="vertical-align:middle"><a href ng-click="key = 'from_user.display'; rev = !rev"><b>ผู้ส่ง</b></a></td>
								<td style="vertical-align:middle"><a href ng-click="key = 'to_user.display'; rev = !rev"><b>ผู้รับ</b></a></td>
								<td style="text-align:center; vertical-align:middle"><a href ng-click="key = 'body'; rev = !rev"><b>ข้อความ</b></a></td>
								<td style="text-align:center; vertical-align:middle"><a href ng-click="key = 'created'; rev = !rev"><b>ส่งเมื่อ</b></a></td>
							</tr>
						</thead>

						<tbody ng-init="lim = 10">
							<tr ng-repeat="msg in UserCtrl.userMsgs | orderBy: key: rev | filter: searchMsg | limitTo: lim" ng-class="{'active': msg.read == '0' && msg.from_user.username != '<?=Auth::user()->username?>'}">
								<td style="width:100; vertical-align:middle"><button class="btn btn-info btn-xs" ng-click="MsgCtrl.readMessage(msg);" data-toggle="modal" data-target="#readMessage">อ่านข้อความ</button></td>
								<td style="vertical-align:middle">
									<a href="../../profile/@{{msg.from_user.username}}">
										<img src="../../img/user/@{{msg.from_user.username}}.jpg" ng-show="msg.from_user.image == '1'" style="max-width:25px; max-height:25px">
										<img src="../../img/user/0.jpg" ng-hide="msg.from_user.image == '1'" style="max-width:25px; max-height:25px">
									</a>

									<a href="../../profile/@{{msg.from_user.username}}" ng-class="getUserRatingColorClass(msg.from_user)">
										@{{msg.from_user.display}}
									</a>
								</td>
								<td style="vertical-align:middle">
									<a href="../../profile/@{{msg.to_user.username}}">
										<img src="../../img/user/@{{msg.to_user.username}}.jpg" ng-show="msg.to_user.image == '1'" style="max-width:25px; max-height:25px">
										<img src="../../img/user/0.jpg" ng-hide="msg.to_user.image == '1'" style="max-width:25px; max-height:25px">
									</a>

									<a href="../../profile/@{{msg.to_user.username}}" ng-class="getUserRatingColorClass(msg.to_user)">
										@{{msg.to_user.display}}
									</a>
								</td>
								<td style="vertical-align:middle"><div ta-bind ng-model="msg.short_body"></div></td>
								<td style="text-align:center; vertical-align:middle">@{{msg.created | date: 'mediumDate'}}</td>
							</tr>
						</tbody>
					</table>

					<center ng-hide="lim == 1000000000"><div class="btn btn-info" ng-click="lim = 1000000000">แสดงข้อความทั้งหมด</div></center>

					<div class="modal fade" id="readMessage" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<div class="modal-title">
										<b>ข้อความจาก @{{MsgCtrl.curReadMsg.from_user.display}} ถึง @{{MsgCtrl.curReadMsg.to_user.display}} เมื่อ @{{MsgCtrl.curReadMsg.created | date: 'medium'}}</b>
									</div>
								</div>
								<div class="modal-body">
									<div ta-bind ng-model="MsgCtrl.curReadMsg.body"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
								</div>
							</div>
						</div>
					</div>

					<br>

				</div>
				<div class="col-md-4">
					@include('profile.partials.menu', ['active' => 'message', 'user' => $user])
				</div>
			</div>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>