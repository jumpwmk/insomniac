<div class="panel panel-default">
	
	<div class="panel-heading">
		<div class="panel-title">ล็อกอิน!</div>
	</div>

	<div class="panel-body" ng-controller="AuthController">

		<form name="signin" ng-submit="signin.$valid && doSignin()" novalidate>
	
		<label>ชื่อผู้ใช้ หรือ อีเมล</label>
		<input  type="text" class="form-control" ng-model="username" name="username" required>
		<br>

		<label>รหัสผ่าน</label>
		<input type="password" class="form-control" ng-model="password" name="password" required>
		<br>

		<div class="pull-right">
			<!-- <a href="#">ลืมรหัสผ่าน</a> -->
			<input type="submit" class="btn btn-primary" value="ยืนยัน">
		</div>
	</form>

	</div>
</div>
<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>