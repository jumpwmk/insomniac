<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">
			<i class="fa fa-plus-circle"></i>
			เพิ่มผู้ใช้
		</div>
	</div>

	<div class="panel-body" >

		<form name="addUser" ng-submit="confirmPasswordValid && addUser.$valid && UserCtrl.addUser()" novalidate>

			<div class="alert alert-danger" ng-show="addUser_error_msg.length">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
				{{(addUser_error_msg)}}
			</div>
		
			<label>ชื่อผู้ใช้</label>
			<input  type="text" class="form-control" ng-model="username" name="username" ng-pattern="/^[A-Za-z0-9_]+$/"  maxlength="16" required>
			<h6>
				<span style="color:#888">อักษรภาษาอังกฤษ ตัวเลข และ "_" เท่านั้น</span>
				<span ng-show="addUser.username.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
				<span ng-show="addUser.username.$invalid && addUser.username.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
			</h6>

			<label>ที่อยู่อีเมล</label>
			<input type="email" class="form-control" ng-model="email" name="email" required>
			<h6>
				<span style="color:#888">รูปแบบอีเมลถูกต้อง</span>
				<span ng-show="addUser.email.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
				<span ng-show="addUser.email.$invalid && addUser.email.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
			</h6>

			<div ng-keyup="confirmPasswordValid = (password == confirmPassword) && password.length">
				<label>รหัสผ่าน</label>
				<input type="password" class="form-control" ng-model="password" name="password" ng-minlength="8" required>
				<h6>
					<span style="color:#888">ยาวอย่างน้อย 8 ตัวอักษร</span>
					<span ng-show="addUser.password.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
					<span ng-show="addUser.password.$invalid && addUser.password.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
				</h6>

				<label>ยืนยันรหัสผ่าน</label>
				<input type="password" class="form-control" ng-model="confirmPassword" name="confirmPassword" required>
				<h6 ng-init="confirmPasswordValid = false">
					<span style="color:#888">ตรงกับรหัสผ่าน</span>
					<span ng-show="confirmPasswordValid" style="color:green" class="glyphicon glyphicon-ok"></span>
					<span ng-show="!confirmPasswordValid && addUser.confirmPassword.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
				</h6>
			</div>

			<hr>

			<label>ชื่อที่ใช้แสดง</label>
			<input type="text" class="form-control" ng-model="display" name="display" maxlength="16">
			<br>
			<label class="checkbox-inline"><input type="checkbox" ng-model="admin"> กำหนดให้เป็นผู้ดูแลระบบ</label>

			<br>
			<input type="submit" class="btn btn-success pull-right" value="ยืนยัน">
		</form>
	</div>
</div>