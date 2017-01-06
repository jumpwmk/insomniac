<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">
			<i class="fa fa-plus-circle"></i>
			เพิ่มโจทย์
		</div>
	</div>

	<div class="panel-body" >

		<form name="addTask" ng-submit="addTask.$valid && TaskCtrl.addTask()" novalidate>

			<div class="alert alert-danger" ng-show="addTask_error_msg.length">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
				{{(addTask_error_msg)}}
			</div>
			
			<div class="form-horizontal">
				
				<p>
					<div><label>ชื่อโจทย์</label></div>
					<div class=""><input  type="text" class="form-control" ng-model="TaskCtrl.add.name" required></div></div>
				</p>

				<p>
					<div ><label>ตัวอย่างข้อมูลทดสอบ</label></div>
					<div class=""><input type="text" class="form-control" ng-model="TaskCtrl.add.pretestcase" ng-pattern="/^[0-9]+$/" required></div>
				</p>

				<p>
					<div ><label>จำนวนข้อมูลทดสอบ</label></div>
					<div class=""><input type="text" class="form-control" ng-model="TaskCtrl.add.testcase" ng-pattern="/^[0-9]+$/" required></div>
				</p>

				<p>
					<div class=""><label>เวลา (วินาที)</label></div>
					<div class=""><input type="text" class="form-control" ng-model="TaskCtrl.add.time" ng-pattern="/^(\d*[.])?\d+$/" placeholder="ใช้ทศนิยมได้" required></div>
				</p>

				<p>
					<div><label>หน่วยความจำ (MB)</label></div>
					<div class=""><input type="text" class="form-control" ng-model="TaskCtrl.add.memory" ng-pattern="/^[0-9]+$/"  required></div>
				</p>

				<p>
					<div ><label>แท็ก (ตัวเลือกเสริม)</label></div>
					<div class=""><input type="text" class="form-control" ng-init="TaskCtrl.add.tags = ''" ng-model="TaskCtrl.add.tags" placeholder="binary search, data structure, ... ใช้ ',' แบ่ง "></div>
				</p>

				<label>
					<input type="checkbox" ng-init="TaskCtrl.add.general_check = false" ng-model="TaskCtrl.add.general_check">
					ใช้การตรวจแบบปกติ
				</label>
				|
				<label>
					<input type="checkbox" ng-init="TaskCtrl.add.visible = false" ng-model="TaskCtrl.add.visible">
					อนุญาตให้ผู้ใช้เห็น
				</label>

				<hr>
				<input type="submit" class="btn btn-success pull-right" value="ยืนยัน">
			</div>
		</form>
	</div>
</div>