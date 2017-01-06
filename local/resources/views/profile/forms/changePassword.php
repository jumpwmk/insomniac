<div class="modal fade" id="changePassword" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">เปลี่ยนรหัสผ่าน</h4>
			</div>

			<form name="changePassword" ng-submit="confirmPasswordValid && changePassword.$valid && UserCtrl.changePassword()" novalidate>
				<div class="modal-body" >
					
					<label>รหัสผ่านปัจจุบัน</label>
					<input type="password" class="form-control" ng-model="UserCtrl.changePass.old" required>
					
					<hr>
					<div ng-keyup="confirmPasswordValid = (UserCtrl.changePass.new == UserCtrl.changePass.confirmNew) && UserCtrl.changePass.new.length">
						<label>รหัสผ่านใหม่</label>
						<input type="password" class="form-control" ng-model="UserCtrl.changePass.new" name="password" ng-minlength="8" required>
						<h6>
							<span style="color:#888">ยาวอย่างน้อย 8 ตัวอักษร</span>
							<span ng-show="changePassword.password.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
							<span ng-show="changePassword.password.$invalid && changePassword.password.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
						</h6>

						<label>ยืนยันรหัสผ่านใหม่</label>
						<input type="password" class="form-control" ng-model="UserCtrl.changePass.confirmNew" name="confirmPassword" required>
						<h6 ng-init="confirmPasswordValid = false">
							<span style="color:#888">ตรงกับรหัสผ่าน</span>
							<span ng-show="confirmPasswordValid" style="color:green" class="glyphicon glyphicon-ok"></span>
							<span ng-show="!confirmPasswordValid && changePassword.confirmPassword.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
						</h6>
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