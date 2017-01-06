<div class="modal fade" id="editInfo" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">แก้ไขข้อมูลทั่วไป</h4>
			</div>

			<form name="editUserInfo" ng-submit="editUserInfo.$valid && UserCtrl.saveUserInfo()" novalidate>
				<div class="modal-body" >

					<label>ที่อยู่อีเมล</label>
					<input type="email" class="form-control" ng-model="UserCtrl.editUserInfo.email" name="email" required>
					<h6>
						<span style="color:#888">รูปแบบอีเมลถูกต้อง</span>
						<span ng-show="editUserInfo.email.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
						<span ng-show="editUserInfo.email.$invalid && editUserInfo.email.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
					</h6>

					<br>

					<label>ชื่อที่ใช้แสดง</label>
					<input type="text" class="form-control" name="display" ng-model="UserCtrl.editUserInfo.display" maxlength="16" required>
					<h6>
						<span style="color:#888">ยาวอย่างน้อย 1 ตัวอักษร</span>
						<span ng-show="editUserInfo.display.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
						<span ng-show="editUserInfo.display.$invalid && editUserInfo.display.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
					</h6>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					<button type="submit" class="btn btn-primary pull-right">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>