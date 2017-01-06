<div class="modal fade" id="fileTask" aria-hidden="true">
	<div class="modal-dialog" style="width:700px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{{TaskCtrl.currentFileTask.name}}</h4>
			</div>

			<style>
				.my-drop-zone { border: dotted 3px lightgray; }
				.nv-file-over { border: dotted 3px #1c77ba; } /* Default class applied to drop zones on over */
				.exist {color: green;}
				.not-exist {color: red;}
			</style>

			<!-- Example: nv-file-drop="" uploader="{Object}" options="{Object}" filters="{String}" -->
			<div class="modal-body">
				<h4>ไฟล์โจทย์ <button ng-click="TaskCtrl.setfileTask(TaskCtrl.currentFileTask)" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-refresh"></span></button></h4><hr>
				<div class="panel panel-default">
					<table class="table table-condensed" style="text-align:center">
						<thead><tr>
							<td><b>เนื้อหาโจทย์</b></td>
							<td ng-hide="TaskCtrl.currentFileTask.general_check"><b>โค้ดตรวจ</b></td>
							<td ng-click="TaskCtrl.showFileTask = 'pin'"><b>ตัวอย่าง in</b></td>
							<td ng-click="TaskCtrl.showFileTask = 'psol'"><b>ตัวอย่าง sol</b></td>
							<td ng-click="TaskCtrl.showFileTask = 'in'"><b>in จริง</b></td>
							<td ng-click="TaskCtrl.showFileTask = 'sol'"><b>sol จริง</b></td>
						</tr></thead>
						<tbody><tr>
							<td>{{TaskCtrl.infoFileTask.doc}}</td>
							<td ng-hide="TaskCtrl.currentFileTask.general_check">{{TaskCtrl.infoFileTask.checkcode}}</td>
							<td ng-click="TaskCtrl.showFileTask = 'pin'"><a href>{{TaskCtrl.infoFileTask.pin}}</a></td>
							<td ng-click="TaskCtrl.showFileTask = 'psol'"><a href>{{TaskCtrl.infoFileTask.psol}}</a></td>
							<td ng-click="TaskCtrl.showFileTask = 'in'"><a href>{{TaskCtrl.infoFileTask.in}}</a></td>
							<td ng-click="TaskCtrl.showFileTask = 'sol'"><a href>{{TaskCtrl.infoFileTask.sol}}</a></td>
						</tr></tbody>
					</table>
				</div>

				<div class="well" ng-show="TaskCtrl.showFileTask == 'pin'">ตัวอย่าง in ที่ยังไม่ได้อัพโหลดได้แก่ <b>{{TaskCtrl.infoFileTask.pin_list}}</b></div>
				<div class="well" ng-show="TaskCtrl.showFileTask == 'psol'">ตัวอย่าง sol ที่ยังไม่ได้อัพโหลดได้แก่ <b>{{TaskCtrl.infoFileTask.psol_list}}</b></div>
				<div class="well" ng-show="TaskCtrl.showFileTask == 'in'">in จริงที่ยังไม่ได้อัพโหลดได้แก่ <b>{{TaskCtrl.infoFileTask.in_list}}</b></div>
				<div class="well" ng-show="TaskCtrl.showFileTask == 'sol'">sol จริงที่ยังไม่ได้อัพโหลดได้แก่ <b>{{TaskCtrl.infoFileTask.sol_list}}</b></div>

				<script type="text/javascript">
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				})
				</script>

				<h4>อัพโหลด์ไฟล์</h4><hr>
				<!-- Example: nv-file-select="" uploader="{Object}" options="{Object}" filters="{String}" -->
				<b class="pull-right">มีทั้งหมด {{ uploader.queue.length }} ไฟล์</b>
				<input type="file" nv-file-select="" uploader="uploader" options="{url: '../judge/upload.php?task_id='+TaskCtrl.currentFileTask.id+'&testcase='+TaskCtrl.currentFileTask.testcase+'&pretestcase='+TaskCtrl.currentFileTask.pretestcase+'&general_check='+TaskCtrl.currentFileTask.general_check}" multiple/>
				<br>
				<div nv-file-drop nv-file-over uploader="uploader" options="{url: '../judge/upload.php?task_id='+TaskCtrl.currentFileTask.id+'&testcase='+TaskCtrl.currentFileTask.testcase+'&pretestcase='+TaskCtrl.currentFileTask.pretestcase+'&general_check='+TaskCtrl.currentFileTask.general_check}" class="well my-drop-zone" style="text-align:center; background-color:#fcfcfc">
					<div ng-hide="uploader.queue.length"><br><h1>ลากไฟล์มาวางที่นี่</h1><br><br></div>
					<div ng-show="uploader.queue.length">
						<br>
						<table class="table table-condensed">
							<tbody>
								<tr ng-repeat="item in uploader.queue">
									<td><b>{{ item.file.name }}</b></td> 
									<td><span class="pull-right">{{ item.file.size/1024/1024|number:3 }} MB</span></td>
									<td style="width:100px"><div class="progress" style="margin-bottom: 0;"><div class="progress-bar active" ng-class="{progress-bar-striped: item.progress != 100}" role="progressbar" aria-valuenow="item.progress" aria-valuemin="0" aria-valuemax="100" ng-style="{ 'width': item.progress + '%' }"></div></div></td>
									<td style="width:215px">
										<button type="button" class="btn btn-success btn-xs" ng-click="item.upload();" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
											<span class="glyphicon glyphicon-upload"></span> อัพโหลด
										</button>
										<button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
											<span class="glyphicon glyphicon-ban-circle"></span> ยกเลิก
										</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
											<span class="glyphicon glyphicon-trash"></span> ลบทั้ง
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="progress">
					<div class="progress-bar active" ng-class="{'progress-bar-striped': uploader.progress != 100}" role="progressbar" aria-valuenow="uploader.progress" aria-valuemin="0" aria-valuemax="100" ng-style="{ 'width': uploader.progress + '%' }">
						<span ng-show="uploader.progress > 0">{{ uploader.progress + '%' }}</span>
					</div>
				</div>

				<button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll();" ng-disabled="!uploader.getNotUploadedItems().length">
					<span class="glyphicon glyphicon-upload"></span> อัพโหลดทั้งหมด
				</button>
				<button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
					<span class="glyphicon glyphicon-ban-circle"></span> ยกเลิกทั้งหมด
				</button>
				<button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
					<span class="glyphicon glyphicon-trash"></span> ลบทั้งหมด
				</button>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			</div>
		</div>
	</div>
</div>