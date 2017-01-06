<?php use App\Config ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Top 10</div>
	</div>

	<div class="panel-body" ng-controller="UserController as UserCtrl">
		<table class="table table-condensed table-hover" style="text-align:center" ng-init="UserCtrl.getUsers('rating', 'desc', 0, '')">
			<thead>
				<tr>
					<td><b>#</b></td>
					<td style="text-align:left"><b>ชื่อผู้ใช้</b></td>
					<td><b>ระดับ</b></td>
				</tr>
			</thead>

			<tbody>
				<tr ng-repeat="user in UserCtrl.users | orderBy: ['-rating', '-contest', '-pass'] | limitTo: 10" ng-class="{'info': user.me == '1'}">
					<td>
						<span ng-hide="user.place == UserCtrl.users.length+2">@{{user.place}}</span>
						<span ng-show="user.place == UserCtrl.users.length+2">-</span>
					</td>
					<td  style="text-align:left">
						<a href="profile/@{{user.username}}" ng-class="getUserRatingColorClass(user)">@{{user.display}}</a>
					</td>
					<td>
						<span ng-hide="user.rating == 0">@{{user.rating}}</span>
						<span ng-show="user.rating == 0" class="label label-default">UNRATED</span>
					</td>
				</tr>
			</tbody>
		</table>
		<span class="pull-right"><a href="users"><i class="fa fa-users"></i> อันดับผู้ใช้ทั้งหมด</a></span>
	</div>
</div>
