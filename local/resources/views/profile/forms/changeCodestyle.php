<div class="modal fade" id="changeCodestyle" aria-hidden="true" ng-controller="CodestyleController as StyleCtrl">
	<div class="modal-dialog" style="width:340px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">เปลี่ยนธีมโค้ด</h4>
			</div>

			<div class="modal-body" ng-init="StyleCtrl.getStyles()">

				<select ng-options="style.name for style in StyleCtrl.styles" ng-model="UserCtrl.changeStyle" class="form-control"></select>
				<br>
				<center>
					<iframe src="{{'../codestyle/' + UserCtrl.changeStyle.file_name}}" frameborder="0" height="154px" width="300px" style="border: dotted 3px lightgray;"></iframe>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="submit" class="btn btn-primary pull-right" data-dismiss="modal" ng-click="UserCtrl.saveStyle()">บันทึก</button>
			</div>
		</div>
	</div>
</div>