<div class="modal fade" id="detailContest" aria-hidden="true">
	<div class="modal-dialog" style="width:800px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">กำหนดการอธิบายการแข่งขัน : {{ContestCtrl.curDetailContest.name}}</h4>
			</div>
			<form name="detailContest" novalidate>
				<div class="modal-body">
					
					<text-angular ng-model="ContestCtrl.curDetailContest.detail"></text-angular>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					<button type="submit" class="btn btn-primary pull-right" ng-click="ContestCtrl.saveDetailContest()">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>