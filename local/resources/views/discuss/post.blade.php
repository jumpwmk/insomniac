<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'discuss'])
		<div class="container" ng-controller="DiscussController as DisCtrl" ng-init="DisCtrl.getPost(<?=$post_id?>);">
			<div class="row">
				<div class="col-md-8">

					<h2><i class="fa fa-comments-o"></i> พูดคุย</h2>
					<hr>
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-title">
								<span ng-show="DisCtrl.post.pin == '1'" class="fa fa-thumb-tack"></span>
								@{{DisCtrl.post.title}}
								@if(Auth::check())

								@if(Auth::isAdmin())
								<span class="pull-right">
									<a href ng-click="DisCtrl.togglePin()"><span class="fa fa-thumb-tack"></span></a>
								@else
								<span class="pull-right" ng-show="'{{Auth::user()->id}}' == DisCtrl.post.user_id">
								@endif
									<a href data-toggle="modal" data-target="#editPost" ng-click="DisCtrl.setEditDiscuss(DisCtrl.post)"><i class="fa fa-cog"></i></a>
									<a href data-toggle="modal" data-target="#removePost" ng-click="DisCtrl.curRemDis = DisCtrl.post"><i class="fa fa-trash"></i></a>
								</span>

								<!-- edit discuss -->
								<div class="modal fade" id="editPost" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<div class="modal-title">
													<input type="text" class="form-control" ng-model="DisCtrl.curEditDis.title" placeholder="หัวข้อ">
												</div>
											</div>
											<div class="modal-body">
												<text-angular ng-model="DisCtrl.curEditDis.body"></text-angular>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
												<button type="submit" class="btn btn-primary pull-right" ng-click="DisCtrl.editDiscuss()" data-dismiss="modal">ยืนยัน</button>
											</div>
										</div>
									</div>
								</div>

								<!-- remove discuss -->
								<div class="modal fade" id="removePost" aria-hidden="true">
									<div class="modal-dialog" style="width:400px">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">ลบข้อความ</h4>
											</div>
											<div class="modal-body">แน่ใจหรือไม่ที่จะลบข้อความนี้?</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
												<button type="submit" class="btn btn-primary pull-right" ng-click="DisCtrl.removeDiscuss(DisCtrl.curRemDis, 'back')" data-dismiss="modal">ลบข้อความ</button>
											</div>
										</div>
									</div>
								</div>

								@endif
							</div>
						</div>

						<div class="panel-body">
							<div class="row">
								<div class="col-md-1">
									<a href="../../profile/@{{DisCtrl.post.user.username}}" >
										<img src="../../img/user/@{{DisCtrl.post.user.username}}.jpg" ng-show="DisCtrl.post.user.image == '1'" style="max-width:50px; max-height:50px">
										<img src="../../img/user/0.jpg" ng-hide="DisCtrl.post.user.image == '1'" style="max-width:50px; max-height:50px">
									</a>
								</div>
								<div class="col-md-11">
									<a href="../../profile/@{{DisCtrl.post.user.username}}" ng-class="getUserRatingColorClass(DisCtrl.post.user)"><b>@{{DisCtrl.post.user.display}}</b></a><br>
									<span style="color:#999; font-size:80%">โพสเมื่อ @{{DisCtrl.post.created | date: 'medium'}}<br></span>
									<span style="color:#999; font-size:80%">ความคิดเห็นล่าสุด @{{DisCtrl.post.commentDate | date: 'medium'}}</span>
								</div>
							</div>
							<p>
								<p>
									<div ta-bind ng-model="DisCtrl.post.body"></div>
								</p>
							</p>
						</div>

						<div class="panel-footer">

							@if(!Auth::check())
							<center ng-hide="DisCtrl.post.comments.length" style="color:#999">
								ยังไม่มีการตอบกลับ
							</center>
							@endif

							<div class="row" ng-repeat="comment in DisCtrl.post.comments" ng-hide="comment.remove == 1">
								<div class="col-md-1">
									<a href="../../profile/@{{comment.user.username}}" >
										<img src="../../img/user/@{{comment.user.username}}.jpg" ng-show="comment.user.image == '1'" style="max-width:50px; max-height:50px">
										<img src="../../img/user/0.jpg" ng-hide="comment.user.image == '1'" style="max-width:50px; max-height:50px">
									</a>
								</div>

								<div class="col-md-11">
									<div class="panel panel-body panel-dafault" ng-hide="comment.isEdit == 1" ng-init="comment.isEdit = 0">
										<a href="../../profile/@{{comment.user.username}}" ng-class="getUserRatingColorClass(comment.user)"><b>@{{comment.user.display}}</b></a> @{{comment.body}}

										@if(Auth::check())

										@if(Auth::isAdmin())
										<span class="pull-right">
										@else
										<span class="pull-right" ng-show="'{{Auth::user()->id}}' == comment.user_id">
										@endif
											<a href ng-click="DisCtrl.setEditDiscuss(post); comment.isEdit = 1"><i class="fa fa-cog"></i></a>
											<a href data-toggle="modal" data-target="#removeComment" ng-click="DisCtrl.curRemDis = comment"><i class="fa fa-trash"></i></a>
										</span>

										<!-- remove discuss -->
										<div class="modal fade" id="removeComment" aria-hidden="true">
											<div class="modal-dialog" style="width:400px">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">ลบข้อความ</h4>
													</div>
													<div class="modal-body">แน่ใจหรือไม่ที่จะลบข้อความนี้?</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
														<button type="submit" class="btn btn-primary pull-right" ng-click="DisCtrl.removeDiscuss(DisCtrl.curRemDis, null)" data-dismiss="modal">ลบข้อความ</button>
													</div>
												</div>
											</div>
										</div>

										@endif

									</div>

									@if(Auth::check())
									<div ng-show="comment.isEdit == 1">
										@if(Auth::isAdmin())
										<div>
										@else
										<div ng-show="'{{Auth::user()->id}}' == comment.user_id">
										@endif
											<p><textarea class="form-control" ng-model="comment.body"></textarea></p>
											<p><button class="btn btn-primary pull-right" ng-click="DisCtrl.editComment(comment)">แก้ไขความคิดเห็น</button></p>
										</div>
										<br>
										<br>
									</div>
									@endif
								</div>
							</div>

							@if(Auth::check())
							<div class="row">
								<div class="col-md-1">
									<a href="../../profile/@{{post.user.username}}" >
										<img src="../../img/user/<?=Auth::user()->username?>.jpg" ng-show="'<?=Auth::user()->image?>' == '1'" style="max-width:50px; max-height:50px">
										<img src="../../img/user/0.jpg" ng-hide="'<?=Auth::user()->image?>' == '1'" style="max-width:50px; max-height:50px">
									</a>
								</div>

								<div class="col-md-11">
									<p><textarea class="form-control" ng-model="DisCtrl.newComment"></textarea></p>
									<p><button class="btn btn-primary pull-right" ng-click="DisCtrl.addComment()">เพิ่มความคิดเห็น</button></p>
								</div>
							</div>
							@endif

						</div>
					</div>

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
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>