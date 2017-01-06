<div class="modal fade" id="taskContest" aria-hidden="true">
	<div class="modal-dialog" style="width:800px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">โจทย์ในการแข่งขัน : {{ContestCtrl.curTaskContest.name}}</h4>
			</div>
			<form name="editContest" ng-submit="taskContest.$valid && ContestCtrl.taskContest()" novalidate>
				<div class="modal-body">
					
					<div class="row">
						<div class="col-md-6">

							<div class="panel panel-default">

								<div class="panel-body" >

									<b>โจทย์ที่ใช้แข่ง</b><hr>

									<table class="table table-condensed table-hover">
										<thead>
											<tr>
												<td><b>ลำดับที่</b></td>
												<td><b>ชื่อโจทย์</b></td>
												<td></td>
											</tr>
										</thead>

										<tbody>
											<tr style="cursor:pointer" ng-repeat="task in ContestCtrl.realTaskContest" ng-class="{success: task.visible == '1'}" ng-click="toggle('detail_real_'+task.order)">
												<td>{{task.order}}</td>
												<td style="text-align:left">
													<a href="../task/{{task.id}}">{{task.name}}</a>
													<h5 id="detail_real_{{task.order}}" style="display:none">
														ข้อมูลทดสอบ: {{task.testcase}} ( +{{task.pretestcase}} )<br>
														เวลา: {{task.time}} วินาที<br>
														หน่วยความจำ: {{task.memory}} MB <br>
													</h5>
												</td>
												<td>
													<button class="btn btn-sm btn-danger" ng-click="ContestCtrl.removeTaskContest(task)"><b>ลบ</b></button>
												</td>
											</tr>
										</tbody>
									</table>

								</div>

							</div>

						</div>
						<div class="col-md-6">
							<div class="panel panel-default">

								<div class="panel-body" >
									<div class="form-horizontal">
										<div class="form-group has-feedback">
										<label class="control-label col-md-4">ค้นหาโจทย์</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="inputSuccess3" aria-describedby="inputSuccess3Status" ng-model="searchTasks">
											<span class="glyphicon glyphicon-search form-control-feedback" aria-hidden="true"></span>
											<span id="inputSuccess3Status" class="sr-only">(success)</span>
										</div>
										</div>
									</div>
									<table class="table table-condensed table-hover" ng-init="ContestCtrl.getTasks()" style="text-align:center">
										<thead>
											<tr>
												<td ng-click="ContestCtrl.sortTasks('id')"><b><a href>#</a></b></td>
												<td ng-click="ContestCtrl.sortTasks('name')" style="text-align:left"><b><a href>ชื่อโจทย์</a></b></td>
												<td></td>
											</tr>
										</thead>
										<tbody>
											<tr style="cursor:pointer" ng-repeat="task in ContestCtrl.tasks | filter: searchTasks" ng-class="{success: task.visible == '1'}" ng-click="toggle('detail_'+task.id)">
												<td>{{task.id}}</td>
												<td style="text-align:left">
													<a href="../task/{{task.id}}">{{task.name}}</a>
													<h5 id="detail_{{task.id}}" style="display:none">
														ข้อมูลทดสอบ: {{task.testcase}} ( +{{task.pretestcase}} )<br>
														เวลา: {{task.time}} วินาที<br>
														หน่วยความจำ: {{task.memory}} MB <br>
													</h5>
												</td>
												<td style="width:150px">
													<div class="form-inline" >
														<div class="form-group">
															<select ng-model="select.$index" ng-options="x for x in xrange(1, ContestCtrl.curTaskContest.task)" class="form-control input-sm">
																<option value="">-ลำดับ-</option>
															</select>
														</div>
														<button class="btn btn-sm btn-primary" ng-click="ContestCtrl.addTaskContest(task, select.$index)"><b>เพิ่ม</b></button>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					<button type="submit" class="btn btn-primary pull-right" ng-click="ContestCtrl.saveTaskContest()">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>