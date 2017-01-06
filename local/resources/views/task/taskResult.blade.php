<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'task'])
		<div class="container">
				
			<div ng-controller="SubmitController as SubmitCtrl" ng-init="SubmitCtrl.getTaskSubmits('{{$active}}', '{{$task_id}}', 0, 25); skip = 25; loadSubmit = '1'">

				@if(Auth::isAdmin())
				<div class="pull-right">
					&nbsp;&nbsp;
					<button class="btn btn-sm btn-warning vis" onclick="$('.invis').show();$('.vis').hide();" style="display:none">เปิดโจทย์ลับ</button>
					<button class="btn btn-sm btn-default invis" onclick="$('.invis').hide();$('.vis').show();">ปิดโจทย์ลับ</button>
				</div>
				@endif
				<h4 class="pull-right"><i class="fa fa-check-circle"></i> ผลตรวจ</h4>
				<ul class="nav nav-tabs" ng-controller="TaskController as TaskCtrl" ng-init="TaskCtrl.infoTask('{{$task_id}}')">
					<li><a href="../../task/{{$task_id}}"><b>@{{TaskCtrl.info.name}}</b></a></li>
					<li ng-class="{active : 'result' == '<?=$active?>'}"><a href="result"><b>ผลตรวจทั้งหมด</b></a></li>
					@if(Auth::check())
					<li ng-class="{active : 'myresult' == '<?=$active?>'}"><a href="myresult"><b>ผลตรวจของคุณ</b></a></li>
					@endif
				</ul>
				<br>


				<table class="table table-condensed table-hover" style="text-align:center">
					<thead>
						<tr>
							<td ng-click="SubmitCtrl.sortSubmits('id')"><b><a href>#</a></b></td>
							<td ng-click="SubmitCtrl.sortSubmits('created_at')"><b><a href>เวลาส่ง</a></b></td>
							<td ng-click="SubmitCtrl.sortSubmits('user.display')"><b><a href>ผู้ใช้</a></b></td>
							<td ng-click="SubmitCtrl.sortSubmits('result')"><b><a href>ผลตรวจ</a></b></td>
							<td ng-click="SubmitCtrl.sortSubmits('time')"><b><a href>เวลา</a></b></td>
							<td ng-click="SubmitCtrl.sortSubmits('memory')" style="width:120px"><b><a href>หน่วยความจำ</a></b></td>
							@if(Auth::isAdmin())<td style="width:50px"></td>@endif
						</tr>
					</thead>
					<tbody>
						@if(Auth::isAdmin())
						<tr ng-repeat="submit in SubmitCtrl.submits" ng-class="{'success': submit.pass == 1 && submit.task.visible == 1, 'warning invis': submit.task.visible != 1}">
							@if($active == 'myresult' || Auth::isAdmin())
							<td><a href ng-click="SubmitCtrl.setShowCode(submit)" data-toggle="modal" data-target="#showCode">@{{submit.id}}</a></td>
							@else
							<td>@{{submit.id}}</td>
							@endif
							<td>@{{submit.created_at}}</td>
							<td><a href="../../profile/@{{submit.user.username}}" ng-class="getUserRatingColorClass(submit.user)">@{{submit.user.display}}</a></td>
							
							<td ng-show="submit.result.length">@{{submit.result}}</td>
							<td ng-hide="submit.result.length"><a href ng-click='SubmitCtrl.setShowError(submit.compile_result)' data-toggle="modal" data-target="#showError">compilation error</a></td>
							
							<td>@{{submit.time}} s</td>
							<td>@{{submit.memory}} KB</td>
							<td><button ng-show="submit.status == 'graded'" class="btn btn-sm btn-warning" ng-click='SubmitCtrl.rejudgeSubmit(submit)'><i class="fa fa-refresh"></i></button></td>
						</tr>
						@else
						<tr ng-repeat="submit in SubmitCtrl.submits" ng-class="{'success': submit.pass == 1 && submit.task.visible == 1}" ng-show="submit.task.visible == '1'">
							@if($active == 'myresult' || Auth::isAdmin())
							<td><a href ng-click="SubmitCtrl.setShowCode(submit)" data-toggle="modal" data-target="#showCode">@{{submit.id}}</a></td>
							@else
							<td>@{{submit.id}}</td>
							@endif
							<td>@{{submit.created_at}}</td>
							<td><a href="../../profile/@{{submit.user.username}}" ng-class="getUserRatingColorClass(submit.user)">@{{submit.user.display}}</a></td>
							
							<td ng-show="submit.result.length">@{{submit.result}}</td>
							<td ng-hide="submit.result.length"><a href ng-click='SubmitCtrl.setShowError(submit.compile_result)' data-toggle="modal" data-target="#showError">compilation error</a></td>
							
							<td>@{{submit.time}} s</td>
							<td>@{{submit.memory}} KB</td>
						</tr>
						@endif
					</tbody>
				</table>

				<center><button ng-show="loadSubmit == '1'" ng-click="SubmitCtrl.getTaskSubmits('{{$active}}', '{{$task_id}}', skip, 25); skip = skip + 25" class="btn btn-info">แสดงผลตรวจเพิ่มเติม</button></center><br>

				<!-- show error -->
				<div class="modal fade" id="showError" aria-hidden="true">
					<div class="modal-dialog" style="width:50%">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">compilation error</h4>
							</div>
							<div class="modal-body"><pre class="code">@{{SubmitCtrl.currentError}}</pre></div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
							</div>
						</div>
					</div>
				</div>

				<!-- show code -->
				<div class="modal fade" id="showCode" aria-hidden="true">
					<div class="modal-dialog" style="width:800px; height:70%">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">#@{{SubmitCtrl.currentCode.id}}</h4>
							</div>
							<iframe src="@{{'../../code/' + SubmitCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>