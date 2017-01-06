<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'discuss'])
		<div class="container" ng-controller="DiscussController as DisCtrl" ng-init="DisCtrl.getPosts('comment_at', 'desc', 0, 20, '<?=$key?>'); DisCtrl.getPins('comment_at', 'desc', 0, null, '<?=$key?>');">

			<div class="row">
				<div class="col-md-8">

					<div class="pull-right">
						<button class="btn btn-lg btn-default" data-toggle="modal" data-target="#searchPost"><i class="fa fa-search"></i> ค้นหา</button>

						<div class="modal fade" id="searchPost" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<div class="has-feedback">
											<form ng-submit="DisCtrl.searchPost(searchPost, '')" style="margin:0">
												<input type="text" placeholder="กด Enter เมื่อต้องการยืนยันการค้นหา" class="form-control" id="searchPost" aria-describedby="inputSuccess3Status" ng-model="searchPost" ng-init="searchPost = '{{$key}}'">
											</form>
											<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						@if(Auth::check())
						<button ng-class="{'active': togglePost == 1}" class="btn btn-primary btn-lg" ng-init="togglePost = 0" ng-click="togglePost = (togglePost + 1) % 2"><i class="fa fa-plus-circle"></i> โพส</button>
						@endif
					</div>

					<h2><i class="fa fa-comments-o"></i> พูดคุย</h2>
					<hr>

					@if(Auth::check())
					<div class="panel panel-default" ng-show="togglePost == 1">
						<div class="panel-heading">
							<div class="panel-title">
								<input type="text" class="form-control" ng-model="newPost.title" placeholder="หัวข้อ">
							</div>
						</div>

						<div class="panel-body">
							<div class="row">
								<div class="col-md-1">
									<a href="../profile/@{{post.user.username}}" >
										<img src="../img/user/<?=Auth::user()->username?>.jpg" ng-show="'<?=Auth::user()->image?>' == '1'" style="max-width:50px; max-height:50px">
										<img src="../img/user/0.jpg" ng-hide="'<?=Auth::user()->image?>' == '1'" style="max-width:50px; max-height:50px">
									</a>
								</div>

								<div class="col-md-11">
									<p><text-angular ng-model="newPost.body"></text-angular></p>
									<p><button class="btn btn-primary pull-right" ng-click="DisCtrl.addPost(newPost)">เพิ่มโพส</button></p>
								</div>
							</div>
						</div>
					</div>
					@endif

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

							<tr ng-repeat="post in DisCtrl.pins" style="height:50">
								<td style="text-align:left; vertical-align:middle">
									<span class="fa fa-thumb-tack"></span>
									<a href="post/@{{post.id}}">
										@{{post.title | limitTo: 37}}
										<span ng-show="post.title.length > 37">...</span>
									</a>
									<div ta-bind ng-model="post.short_body" style="font-size:80%"></div>
								</td>
								<td style="text-align:left; vertical-align:middle">
									<a href="../profile/@{{post.user.username}}">
										<img src="../img/user/@{{post.user.username}}.jpg" ng-show="post.user.image == '1'" style="max-width:25px; max-height:25px">
										<img src="../img/user/0.jpg" ng-hide="post.user.image == '1'" style="max-width:25px; max-height:25px">
									</a>

									<a href="../profile/@{{post.user.username}}" ng-class="getUserRatingColorClass(post.user)">
										@{{post.user.display}}
									</a>
								</td>
								<td style="vertical-align:middle">@{{post.comments.length}}</td>
								<td style="vertical-align:middle">@{{post.view_data.length}}</td>
								<td style="vertical-align:middle">@{{post.commentDate | date: 'mediumDate'}}</td>
							</tr>
							
							<tr ng-repeat="post in DisCtrl.posts" style="height:50">
								<td style="text-align:left; vertical-align:middle"><a href="post/@{{post.id}}">
									@{{post.title | limitTo: 37}}
									<span ng-show="post.title.length > 37">...</span>
								</a></td>
								<td style="text-align:left; vertical-align:middle">
									<a href="../profile/@{{post.user.username}}">
										<img src="../img/user/@{{post.user.username}}.jpg" ng-show="post.user.image == '1'" style="max-width:25px; max-height:25px">
										<img src="../img/user/0.jpg" ng-hide="post.user.image == '1'" style="max-width:25px; max-height:25px">
									</a>

									<a href="../profile/@{{post.user.username}}" ng-class="getUserRatingColorClass(post.user)">
										@{{post.user.display}}
									</a>
								</td>
								<td style="vertical-align:middle">@{{post.comments.length}}</td>
								<td style="vertical-align:middle">@{{post.view_data.length}}</td>
								<td style="vertical-align:middle">@{{post.commentDate | date: 'mediumDate'}}</td>
							</tr>

						</tbody>
					</table>

					<center ng-init="DisCtrl.empty = 0; DisCtrl.loadPost = 0" ng-hide="DisCtrl.empty == 1">
						<button class="btn btn-info" ng-show="DisCtrl.loadPost == 0" ng-click="DisCtrl.getPosts('comment_at', 'desc', null, 20, '<?=$key?>')">แสดงโพสเพิ่มเติม</button>
						<button class="btn btn-info disabled" ng-show="DisCtrl.loadPost == 1"><i class="fa fa-spinner fa-pulse"></i> กำลังแสดง...</button>
					</center>


					<br>
				</div>

				<div class="col-md-4">
					@if(Auth::check())
						@include('discuss.partials.userInfo')
					@else
						@include('forms.signin')
					@endif
					@include('partials.facebook')
				</div>
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
