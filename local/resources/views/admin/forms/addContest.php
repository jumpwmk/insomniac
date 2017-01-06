<div class="panel panel-default">

	<div class="panel-heading">
		<div class="panel-title">
			<i class="fa fa-plus-circle"></i>
			สร้างการแข่งขัน
		</div>
	</div>

	<div class="panel-body" >

		<form name="addContest" ng-submit="addContest.$valid && ContestCtrl.addContest()" novalidate>

			<div class="alert alert-danger" ng-show="addContest_error_msg.length">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
				{{(addContest_error_msg)}}
			</div>
			
			<div class="form-horizontal" ng-init="ContestCtrl.setInitDate()">
				
				<p>
					<div><label>ชื่อการแข่งขัน</label></div>
					<div><input  type="text" class="form-control" ng-model="ContestCtrl.add.name" name="name" required></div>
				</p>

				<p>
					<div><label>ประเภทการแข่ง</label></div>
					<div><select ng-options='type for type in ["normal", "testrun", "partial_feedback", "acm_contest"]' class="form-control" ng-model="ContestCtrl.add.type" required></select></div>
				</p>

				<p>
					<div><label>จำนวนโจทย์</label></div>
					<div><input type="number" min="1" class="form-control" ng-model="ContestCtrl.add.task" ng-init="ContestCtrl.add.task = 1" name="task" required></div>
				</p>

				<p>
					<div><label>เริ่มการรับสมัคร</label></div>
					<div><input type="datetime" class="form-control" ng-model="ContestCtrl.add.start_register" ng-init="ContestCtrl.add.start_register = date" name="start_register" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
				</p>

				<p>
					<div><label>สิ้นสุดการรับสมัคร</label></div>
					<div><input type="datetime" class="form-control" ng-model="ContestCtrl.add.end_register" ng-init="ContestCtrl.add.end_register = date" name="end_register" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
				</p>

				<p>
					<div><label>เริ่มการแข่งขัน</label></div>
					<div><input type="datetime" class="form-control" ng-model="ContestCtrl.add.start_contest" ng-init="ContestCtrl.add.start_contest = date" name="start_contest" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
				</p>

				<p>
					<div><label>สิ้นสุดการแข่งขัน</label></div>
					<div><input type="datetime" class="form-control" ng-model="ContestCtrl.add.end_contest" ng-init="ContestCtrl.add.end_contest = date" name="end_contest" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
				</p>
				
				<label>
					<input type="checkbox" ng-init="ContestCtrl.add.visible = false" ng-model="ContestCtrl.add.visible">
					อนุญาตให้ผู้ใช้เห็น
				</label>

				<hr>

			</div>
			<input type="submit" class="btn btn-success pull-right" value="ยืนยัน">
		</form>
	</div>
</div>