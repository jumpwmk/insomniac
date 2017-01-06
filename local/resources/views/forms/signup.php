<div class="panel panel-default">
	
	<div class="panel-heading">
		<div class="panel-title">สมัครเลย!</div>
	</div>

	<div class="panel-body" ng-controller="AuthController" >

		<form name="signup" ng-submit="confirmPasswordValid && signup.$valid && doSignup()" novalidate>
	
		<label>ชื่อผู้ใช้</label>
		<input  type="text" class="form-control" ng-model="username" name="username" ng-pattern="/^[A-Za-z0-9_]+$/" maxlength="16" required>
		<h6>
			<span style="color:#888">อักษรภาษาอังกฤษ ตัวเลข และ "_" เท่านั้น</span>
			<span ng-show="signup.username.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
			<span ng-show="signup.username.$invalid && signup.username.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
		</h6>

		<label>ที่อยู่อีเมล</label>
		<input type="email" class="form-control" ng-model="email" name="email" required>
		<h6>
			<span style="color:#888">รูปแบบอีเมลถูกต้อง</span>
			<span ng-show="signup.email.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
			<span ng-show="signup.email.$invalid && signup.email.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
		</h6>

		<div ng-keyup="confirmPasswordValid = (password == confirmPassword) && password.length">
			<label>รหัสผ่าน</label>
			<input type="password" class="form-control" ng-model="password" name="password" ng-minlength="8" required>
			<h6>
				<span style="color:#888">ยาวอย่างน้อย 8 ตัวอักษร</span>
				<span ng-show="signup.password.$valid" style="color:green" class="glyphicon glyphicon-ok"></span>
				<span ng-show="signup.password.$invalid && signup.password.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
			</h6>

			<label>ยืนยันรหัสผ่าน</label>
			<input type="password" class="form-control" ng-model="confirmPassword" name="confirmPassword" required>
			<h6 ng-init="confirmPasswordValid = false">
				<span style="color:#888">ตรงกับรหัสผ่าน</span>
				<span ng-show="confirmPasswordValid" style="color:green" class="glyphicon glyphicon-ok"></span>
				<span ng-show="!confirmPasswordValid && signup.confirmPassword.$dirty" style="color:red" class="glyphicon glyphicon-remove"></span>
			</h6>
		</div>
		<label><h6 style="margin:0; padding:0"><input ng-model="agree" type="checkbox"> ข้าพเจ้าได้อ่านและยอมรับ<a href data-toggle="modal" data-target="#agreement"><b><u>เงื่อนไขการใช้บริการ</u></b></a></h6></label>
		<input ng-init="agree = false" type="submit" class="btn btn-success pull-right" value="ยืนยัน" ng-class="{disabled: agree == false}">

	</form>

	</div>
</div>

<div class="modal fade" id="agreement" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">เงื่อนไขการใช้บริการ</h4>
			</div>
			<div class="modal-body">
				<ol>
					<li>ผู้ใช้ควรปฏิบัติตามข้อกำหนด และเงื่อนไขการให้บริการของเว็บไซต์โดยเคร่งครัด</li>
					<li>ผู้ใช้ทุกท่านมีสิทธิเท่าเทียมกัน และต้องเคารพสิทธิของผู้ใช้อื่น</li>
					<li>ห้ามแสดง หรือ นำเข้าข้อมูลทุกชนิดที่ผิดกฎหมาย หรือกระทำการใดๆที่ขัดต่อกฎหมาย</li>
					<li>ห้ามกระทำการใดๆ ที่ทำให้เกิดความเสียหายต่อข้อมูลผู้ใช้อื่น หรือ เกิดความเสียหายต่อเว็บไซต์ หากตรวจพบจะดำเนินคดีตามกฎหมายอย่างถึงที่สุด</li>
					<li>ห้ามกระทำการใดอันขัดต่อกฎหมาย หรือ ศีลธรรมอันดีของประชาชน โดยจะไม่ส่งเนื้อหารวมถึงการนำข้อความ รูปภาพ หรือ ภาพเคลื่อนไหว ที่ไม่เหมาะสม ไม่สุภาพ มีลักษณะเสียดสี ก่อให้เกิดความขัดแย้ง เป็นความลับ หรือเป็นเท็จ</li>
					<li>ห้ามกระทำการใดอันเป็นการล่วงละเมิดสถาบันชาติ ศาสนา พระมหากษัตริย์ หรือสิทธิส่วนบุคคลหรือสิทธิอื่นใดของบุคคลภายนอก</li>
					<li>ในกรณีที่ข้อมูลผู้ใช้ ถูกจารกรรมโดยวิธีการทางอิเล็กทรอนิกส์ (hack) หรือสูญหาย เสียหายอันเนื่องจากเหตุสุดวิสัยหรือไม่ว่ากรณีใดๆทั้งสิ้น ทางผู้ดูแลขอสงวนสิทธิในการปฏิเสธความรับผิดจากเหตุดังกล่าวทั้งหมด</li>
				</ol>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			</div>
		</div>
	</div>
</div>

<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>