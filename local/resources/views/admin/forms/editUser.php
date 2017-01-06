<div class="modal fade" id="editUser" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">แก้ไข</h4>
			</div>
			<form name="editUser" ng-submit="editUser.$valid && UserCtrl.editUser()" novalidate>
				<div class="modal-body">

					<label>ชื่อผู้ใช้</label>
					<input type="text" class="form-control" ng-model="UserCtrl.currentEditUser.username" name="username" ng-pattern="/^[A-Za-z0-9_]+$/" disabled><br>

					<label>ที่อยู่อีเมล</label>
					<input type="email" class="form-control" ng-model="UserCtrl.currentEditUser.email" name="email" required>
					<h6>
						<span style="color:#888">รูปแบบอีเมลถูกต้อง</span>
						<span ng-show="editUser.email.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
						<span ng-show="editUser.email.$invalid && editUser.email.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
					</h6>

					<label>ชื่อที่ใช้แสดง</label>
					<input type="text" class="form-control" ng-model="UserCtrl.currentEditUser.display" name="display">
					<br>
					<label class="checkbox-inline">
						<input type="checkbox" ng-checked="UserCtrl.currentEditUser.admin == true" ng-model="UserCtrl.currentEditUser.admin" ng-init="UserCtrl.currentEditUser.admin = false"> 
						กำหนดให้เป็นผู้ดูแลระบบ
					</label>

					<br>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					<button type="submit" class="btn btn-primary pull-right">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>