<?php use App\Config; ?>

<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="UserController as UserCtrl">
		@include('partials.menubar', ['active' => 'profile'])
		<div class="container" ng-controller="SubmitController as SubmitCtrl">
			
			<div class="row" ng-init="UserCtrl.getUserInfo('{{$user}}')" ng-controller="MessageController as MsgCtrl">
				<div class="col-md-8" ng-init="UserCtrl.getUserDiscuss('{{$user}}');">
					<h2><i class="fa fa-comments-o"></i> การพูดคุยของผู้ใช้</h2><hr>

					<div class="form-horizontal">
						<div class="form-group has-feedback">
						<label class="control-label col-md-3">ค้นหาโพส</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchPosts">
							<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
						</div>
						</div>
					</div>

					<table class="table table-hover table-condensed" style="text-align:center; ">
						<thead>
							<tr>
								<td style="text-align:left"><a href ng-click="key = 'title'; rev = !rev"><b>หัวข้อ</b></a></td>
								<td><a href ng-click="key = 'comments.length'; rev = !rev"><b>ตอบ</b></a></td>
								<td><a href ng-click="key = 'view_data.length'; rev = !rev"><b>อ่าน</b></a></td>
								<td style="width:120"><a href ng-click="key = 'commentDate'; rev = !rev"><b>ล่าสุด</b></a></td>
							</tr>
						</thead>

						<tbody>
							
							<tr ng-repeat="post in UserCtrl.userDiscuss.posts | filter: searchPosts | orderBy: key: rev">
								<td style="text-align:left; vertical-align:middle"><a href="{{Config::root()}}/discuss/post/@{{post.id}}">
									@{{post.title | limitTo: 37}}
									<span ng-show="post.title.length > 37">...</span>
								</a></td>
								<td style="vertical-align:middle">@{{post.comments.length}}</td>
								<td style="vertical-align:middle">@{{post.view_data.length}}</td>
								<td style="vertical-align:middle">@{{post.commentDate | date: 'mediumDate'}}</td>
							</tr>

						</tbody>
					</table>

				</div>
				<div class="col-md-4">
					@include('profile.partials.menu', ['active' => 'discuss', 'user' => $user])
				</div>
			</div>

			@if(Auth::check())
				@if(Auth::user()->username == $user || $user == '')
				<!-- show code -->
				<div class="modal fade" id="showCode" aria-hidden="true">
					<div class="modal-dialog" style="width:800px; height:70%">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">#@{{SubmitCtrl.currentCode.id}}</h4>
							</div>
							<iframe src="@{{'../../code/' + SubmitCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
							</div>
						</div>
					</div>
				</div>
				@endif
			@endif

		</div>

	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>