<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'main'])
		<div class="container" ng-controller="UserController as UserCtrl">
			<div class="row">
				<div class="col-md-8">
					<h2><i class="fa fa-users"></i> อันดับผู้ใช้</h2>

					<hr />

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
					<table class="table table-condensed table-hover" style="text-align:center" ng-init="rev = false; key = ['-rating', '-contest', '-pass']; UserCtrl.getUsers('rating', 'desc', 0, '')">
						<thead>
							<tr>
								<td><b><a href ng-click="rev = (key.toString() == ['-rating', '-contest', '-pass'].toString() ? !rev : false); key = ['-rating', '-contest', '-pass']">#</a></b></td>
								<td style="text-align:left"><b><a href ng-click="rev = (key == 'username' ? !rev : false); key = 'username'">ชื่อผู้ใช้</a></b></td>
								<td><b><a href ng-click="rev = (key == 'contest' ? !rev : true); key = 'contest'">การแข่งขัน</a></b></td>
								<td><b><a href ng-click="rev = (key == 'pass' ? !rev : true); key = 'pass'">โจทย์ที่ผ่านแล้ว</a></b></td>
								<td><b><a href ng-click="rev = (key == 'rating' ? !rev : true); key = 'rating'">ระดับ</a></b></td>
							</tr>
						</thead>

						<tbody>
							<tr ng-repeat="user in UserCtrl.users | filter: searchUsers | orderBy: key: rev" ng-class="{'info': user.me == '1'}">
								<td>
									<span ng-hide="user.place == UserCtrl.users.length+2">@{{user.place}}</span>
									<span ng-show="user.place == UserCtrl.users.length+2">-</span>
								</td>
								<td style="text-align:left;"><a href="profile/@{{user.username}}" ng-class="getUserRatingColorClass(user)">@{{user.display}}</a></td>
								<td>@{{user.contest}}</td>
								<td>@{{user.pass}}</td>
								<td>
									<span ng-hide="user.rating == 0">@{{user.rating}}</span>
									<span ng-show="user.rating == 0" class="label label-default">UNRATED</span>
								</td>
							</tr>
						</tbody>
					</table>
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
		</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
