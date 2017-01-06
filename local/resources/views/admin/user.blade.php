<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'admin'])
		<div class="container" ng-controller="AdminController as UserCtrl">

			<div class="row">
				<div class="col-md-8">
					
					<h2>
						<i class="fa fa-user"></i>
						รายชื่อ
					</h2>
					<hr>
					<div id="users">
						<div class="form-horizontal">
							<div class="form-group has-feedback">
							<label class="control-label col-md-3">ค้นหาผู้ใช้</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchUsers">
									<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
									<span id="inputSuccess3Status" class="sr-only">(success)</span>
								</div>
							</div>
						</div>
						<table class="table table-condensed table-hover" ng-init="UserCtrl.getUsers()" style="text-align:center">
							<thead>
								<tr>
									<td ng-click="UserCtrl.sortUsers('id')"><b><a href>#</a></b></td>
									<td ng-click="UserCtrl.sortUsers('username')"><b><a href>ชื่อผู้ใช้</a></b></td>
									<td ng-click="UserCtrl.sortUsers('email')"><b><a href>อีเมล</a></b></td>
									<td ng-click="UserCtrl.sortUsers('display')"><b><a href>ชื่อที่ใช้แสดง</a></b></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="user in UserCtrl.users | filter: searchUsers" ng-class="{info: user.admin == '1'}">
									<td>@{{user.id}}</td>
									<td>@{{user.username}}</td>
									<td>@{{user.email}}</td>
									<td>@{{user.display}}</td>
									<td>
										<div class="dropdown">
										<a href class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
											<span class="glyphicon glyphicon-cog"></span>
										</a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
											<li><a href data-toggle="modal" data-target="#editUser" ng-click="UserCtrl.setEditUser(user)">แก้ไข</a></li>
											<li><a href data-toggle="modal" data-target="#removeUser" ng-click="UserCtrl.setRemoveUser(user)">ลบ</a></li>
										</ul>
									</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<br>

					<!-- edit user -->
					@include('admin.forms.editUser')

					<!-- remove user -->
					<div class="modal fade" id="removeUser" aria-hidden="true">
						<div class="modal-dialog" style="width:400px">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">ลบผู้ใช้</h4>
								</div>
								<div class="modal-body">แน่ใจหรือไม่ที่จะลบผู้ใช้ "@{{UserCtrl.currentRemoveUser.username}}"</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
									<button type="submit" class="btn btn-primary pull-right" ng-click="UserCtrl.removeUser()" data-dismiss="modal">ลบผู้ใช้</button>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						$('.collapse').collapse('show');
					</script>

				</div>
				<div class="col-md-4">
					@include('admin.partials.menu', ['active' => 'user'])
					@include('admin.forms.addUser')
				</div>
			</div>

			<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>