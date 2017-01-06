<div class="modal fade" id="editTask" aria-hidden="true">
	<div class="modal-dialog" style="width:600px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">แก้ไข</h4>
			</div>
			<form name="editTask" ng-submit="editTask.$valid && TaskCtrl.editTask()" novalidate>
				<div class="modal-body" >
					
					<div class="form-horizontal">
						
						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">ชื่อโจทย์</label></div>
							<div class="col-sm-9"><input  type="text" class="form-control" ng-model="TaskCtrl.edit.name" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">ตัวอย่างข้อมูลทดสอบ</label></div>
							<div class="col-sm-9"><input type="text" class="form-control" ng-model="TaskCtrl.edit.pretestcase" ng-pattern="/^[0-9]+$/" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">จำนวนข้อมูลทดสอบ</label></div>
							<div class="col-sm-9"><input type="text" class="form-control" ng-model="TaskCtrl.edit.testcase" ng-pattern="/^[0-9]+$/" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">เวลา (วินาที)</label></div>
							<div class="col-sm-9"><input type="text" class="form-control" ng-model="TaskCtrl.edit.time" ng-pattern="/^(\d*[.])?\d+$/" placeholder="ใช้ทศนิยมได้" required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">หน่วยความจำ (MB)</label></div>
							<div class="col-sm-9"><input type="text" class="form-control" ng-model="TaskCtrl.edit.memory" ng-pattern="/^[0-9]+$/"  required></div>
						</div>

						<div class="form-group">
							<div class="col-sm-3 control-label"><label class="pull-right">แท็ก (ตัวเลือกเสริม)</label></div>
							<div class="col-sm-9"><input type="text" class="form-control" ng-init="TaskCtrl.edit.tags = ''" ng-model="TaskCtrl.edit.tags" placeholder="binary search, data structure, ... ใช้ ',' แบ่ง "></div>
						</div>

						<label><input type="checkbox" ng-model="TaskCtrl.edit.general_check" ng-checked="TaskCtrl.edit.general_check == true"> ใช้การตรวจแบบปกติ</label>
						|
						<label><input type="checkbox" ng-model="TaskCtrl.edit.visible" ng-checked="TaskCtrl.edit.visible == true"> อนุญาตให้ผู้ใช้เห็น</label>

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