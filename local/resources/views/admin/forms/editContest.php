<div class="modal fade" id="editContest" aria-hidden="true">
	<div class="modal-dialog" style="width:600px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">แก้ไข</h4>
			</div>
			<form name="editContest" ng-submit="editContest.$valid && ContestCtrl.editContest()" novalidate>
				<div class="modal-body">
					
					<div class="form-horizontal" ng-init="ContestCtrl.setInitDate()">
						
						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">ชื่อการแข่งขัน</label></div>
							<div class="col-sm-9"><input  type="text" class="form-control" ng-model="ContestCtrl.edit.name" name="name" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">ประเภทการแข่ง</label></div>
							<div class="col-sm-9"><div><select ng-options='type for type in ["normal", "testrun","partial_feedback", "acm_contest"]' class="form-control" ng-model="ContestCtrl.edit.type" required></select></div></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">จำนวนโจทย์</label></div>
							<div class="col-sm-9"><input type="number" min="1" class="form-control" ng-model="ContestCtrl.edit.task" ng-init="ContestCtrl.edit.task = 1" name="task" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">เริ่มการรับสมัคร</label></div>
							<div class="col-sm-9"><input type="datetime" class="form-control" ng-model="ContestCtrl.edit.start_register" ng-init="ContestCtrl.edit.start_register = date" name="start_register" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">สิ้นสุดการรับสมัคร</label></div>
							<div class="col-sm-9"><input type="datetime" class="form-control" ng-model="ContestCtrl.edit.end_register" ng-init="ContestCtrl.edit.end_register = date" name="end_register" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">เริ่มการแข่งขัน</label></div>
							<div class="col-sm-9"><input type="datetime" class="form-control" ng-model="ContestCtrl.edit.start_contest" ng-init="ContestCtrl.edit.start_contest = date" name="start_contest" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">สิ้นสุดการแข่งขัน</label></div>
							<div class="col-sm-9"><input type="datetime" class="form-control" ng-model="ContestCtrl.edit.end_contest" ng-init="ContestCtrl.edit.end_contest = date" name="end_contest" placeholder="YYYY-MM-DD HH:MM:SS" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3"><label class="pull-right">อนุญาตให้ผู้ใช้เห็น</label></div>
							<div class="col-sm-1"><input type="checkbox" ng-checked="ContestCtrl.edit.visible == true" ng-model="ContestCtrl.edit.visible"></div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					<button type="submit" class="btn btn-primary pull-right">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>