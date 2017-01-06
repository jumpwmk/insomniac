<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'admin'])
		<div class="container">

			<div class="row" ng-controller="AdminController as ContestCtrl" ng-init="ContestCtrl.getContests()" >
				<div class="col-md-8">

					<h2><i class="fa fa-line-chart"></i> ระดับผู้ใช้จากการแข่งขัน</h2>
					<hr>

					<div class="form-horizontal">
						<p class="form-group form-group-sm">
							<div class="col-sm-5 control-label">
								<label class="pull-right">อัพเดทระดับผู้ใช้จากการแข่งขันทั้งหมด</label>
							</div>
							<div class="col-sm-7">
								<div class="pull-right">
									<button ng-click="ContestCtrl.updateRating()" class="btn btn-primary">อัพเดททั้งหมด</button>
								</div>
							</div>
						</p>

						<p class="form-group form-group-sm">
							<div class="col-sm-5 control-label">
								<label class="pull-right">อัพเดทระดับผู้ใช้จากการแข่งขัน</label>
							</div>
							<div class="col-sm-6">
								<label>
									<select class="form-control" ng-model="ratingContest" ng-options="contest.name for contest in ContestCtrl.contests | orderBy: '-id'">
										<option value="">-- เลือกการแข่งขัน --</option>
									</select>
								</label>
							</div>
							<div class="col-sm-1">
								<div class="pull-right">
									<button ng-click="ContestCtrl.updateContestRating(ratingContest)" class="btn btn-primary">อัพเดท</button>
								</div>
							</div>
						</p>
					</div>
					<br><br>

					<h2>
						<i class="fa fa-trophy"></i>
						การแข่งขัน
					</h2>
					<hr>
					<div id="contests">
						<div class="form-horizontal">
							<div class="form-group has-feedback">
							<label class="control-label col-md-3">ค้นหาการแข่งขัน</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchContests">
								<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
								<span id="inputSuccess3Status" class="sr-only">(success)</span>
							</div>
							</div>
						</div>

						<table class="table table-condensed table-hover" style="text-align:center">
							<thead>
								<tr>
									<td ng-click="curSrt = 'id'; rev = !rev"><b><a href>#</a></b></td>
									<td ng-click="curSrt = 'name'; rev = !rev"><b><a href>ชื่อ</a></b></td>
									<td ng-click="curSrt = 'type'; rev = !rev"><b><a href>ประเภท</a></b></td>
									<td ng-click="curSrt = 'task'; rev = !rev"><b><a href>โจทย์</a></b></td>
									<td ng-click="curSrt = 'start_register'; rev = !rev"><b><a href>เริ่มสมัคร</a></b></td>
									<td ng-click="curSrt = 'end_register'; rev = !rev"><b><a href>ปิดสมัคร</a></b></td>
									<td ng-click="curSrt = 'start_contest'; rev = !rev"><b><a href>เริ่มแข่ง</a></b></td>
									<td ng-click="curSrt = 'end_contest'; rev = !rev"><b><a href>ปิดแข่ง</a></b></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="contest in ContestCtrl.contests | filter: searchContests | orderBy: curSrt: rev" ng-class="{success: contest.visible == '1'}">
									
									<td>@{{contest.id}}</td>
									<td><a href="{{ url('/') }}/contest/@{{contest.type}}/@{{contest.id}}">@{{contest.name}}<a></td>
									<td>@{{contest.type}}</td>
									<td>@{{contest.task}}</td>
									<td><h6>@{{contest.start_register}}</h6></td>
									<td><h6>@{{contest.end_register}}</h6></td>
									<td><h6>@{{contest.start_contest}}</h6></td>
									<td><h6>@{{contest.end_contest}}</h6></td>
									<td>
										<div class="dropdown">
										<a href class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
											<span class="glyphicon glyphicon-cog"></span>
										</a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
											<li><a href data-toggle="modal" data-target="#editContest" ng-click="ContestCtrl.setEditContest(contest)">แก้ไขการแข่งขัน</a></li>
											<li><a href data-toggle="modal" data-target="#taskContest" ng-click="ContestCtrl.setTaskContest(contest)">โจทย์ที่ใช้แข่งขัน</a></li>
											<li><a href data-toggle="modal" data-target="#detailContest" ng-click="ContestCtrl.setDetailContest(contest)">อธิบายการแข่งขัน</a></li>
											<li><a href="../contest/@{{contest.type}}/@{{contest.id}}/config">ตั้งค่าขั้นสูง</a></li>
											<li><a href data-toggle="modal" data-target="#removeContest" ng-click="ContestCtrl.setRemoveContest(contest)">ลบ</a></li>
										</ul>
									</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<br>

					<!-- editContest -->
					@include('admin.forms.editContest')

					<!-- task of contest -->
					@include('admin.forms.taskContest')

					<!-- set detail of contest -->
					@include('admin.forms.detailContest')

					<!-- remove Task -->
					<div class="modal fade" id="removeContest" aria-hidden="true">
						<div class="modal-dialog" style="width:400px">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">ลบการแข่งขัน</h4>
								</div>
								<div class="modal-body">แน่ใจหรือไม่ที่จะลบการแข่งขัน "@{{ContestCtrl.currentRemoveContest.name}}"</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
									<button type="submit" class="btn btn-primary pull-right" ng-click="ContestCtrl.removeContest()" data-dismiss="modal">ลบโจทย์</button>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-md-4">
					@include('admin.partials.menu', ['active' => 'contest'])
					@include('admin.forms.addContest')
				</div>

				<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>
			</div>

		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>