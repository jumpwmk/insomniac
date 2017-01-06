<?php 
use App\Config;
use App\Problem;
use App\Contest;
use Illuminate\Http\RedirectResponse;

$contest = Contest::find($contest_id);
if(!(Auth::isAdmin() or $contest->visible))
{
	echo '<i style="display:none">'.redirect('signin').'</i>';
	exit();
}

Contest::isTrueType($contest_id, 'normal'); 

$problem = Problem::whereRaw('contest_id = ? and `order` = ?',[$contest_id, $task_order])->first();
?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])

		<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

			<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

			<div class="row" ng-controller="NormalController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}'); ConCtrl.getTask('{{$contest_id}}', '{{$task_order}}')">
				<div class="col-md-8">

					<ul class="nav nav-tabs">
						<li ng-class="{'active': 'task' == '{{$active}}'}"><a href="../task"><b>โจทย์</b></a></li>
						<li ng-show="ConCtrl.contest.registered == '1'"  ng-class="{'active': 'result' == '{{$active}}'}"><a href="../result"><b>ผลตรวจ</b></a></li>
						<li ng-class="{'active': 'scoreboard' == '{{$active}}'}"><a href="../scoreboard"><b>ตารางคะแนนผู้เข้าแข่งขัน</b></a></li>
					</ul>

					@if(Auth::isAdmin())
					<div>
					@else
					<div ng-show="ConCtrl.contest.status == 'running' || ConCtrl.contest.status == 'old'">
					@endif
						<div class="row">
							<div class="col-md-8">
								<h3>@{{ConCtrl.task.info.name}}</h3>
								<a href="{{Config::root()}}/judge/docs/@{{ConCtrl.task.info.id}}.pdf" download="@{{ConCtrl.task.info.id}}.pdf"><b>ดาวน์โหลดไฟล์ PDF</b></a>
							</div>
							<div class="col-md-4" style="text-align:right">
								<br>
								<h5>เวลา: @{{ConCtrl.task.info.time}} วินาที</h5> 
								<h5>หน่วยความจำ: @{{ConCtrl.task.info.memory}} เมกะไบต์</h5>
							</div>
						</div>
						<hr>
						<object data="{{Config::root()}}/judge/docs/@{{ConCtrl.task.info.id}}.pdf" type="application/pdf" width="100%" height="100%"> 
							<p>คุณไม่มี plugin สำหรับเปิดไฟล์ pdf <a href="{{Config::root()}}/judge/docs/@{{ConCtrl.task.info.id}}.pdf" download="@{{ConCtrl.task.info.id}}.pdf"><b>ดาวน์โหลดไฟล์ PDF</b></a></p>  
						</object>
						<hr>
					</div>
					<div ng-hide="ConCtrl.contest.status == 'running' || ConCtrl.contest.status == 'old'">
						<br><br>
						<h1 style="color:#999; text-align:center">การแข่งขันยังไม่เริ่ม</h1>
						<br><br>
					</div>

				</div>
				<div class="col-md-4">

					@include('contest.partials.status', ['active' => 'contest'])

					@if(Auth::check())
					@if(Auth::isAdmin())
					<div>
					@else
					<div ng-show="ConCtrl.contest.registered == '1'">
					@endif
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>อัพโหลด</h4>
							</div>
							<div class="panel-body" ng-show="ConCtrl.contest.status == 'running'">
								<form class="form-horizontal" method="post" action="{{ secure_url('contest/normal/active/upload') }}" enctype="multipart/form-data">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									{!! Form::hidden('task_id',$problem->task_id) !!}
									{!! Form::hidden('contest_id',$contest_id) !!}
									{!! Form::hidden('task_order',$task_order) !!}
										{!! Form::file('code','',array('id'=>'','class'=>'')) !!}
										{!! Form::submit('ส่งโค้ด',array('class'=>'pull-right btn btn-sm btn-default')) !!}
										<h6 style="color: #999">ไฟล์นามสกุล .c, .cpp หรือ .cxx</h6>
								</form>
							</div>

							<div class="panel-body" ng-show="ConCtrl.contest.status == 'old' && ConCtrl.task.info.visible == '1'">
								<form class="form-horizontal" method="post" action="{{ secure_url('task/active/upload') }}" enctype="multipart/form-data">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									{!! Form::hidden('task_id',$problem->task_id) !!}
									{!! Form::hidden('contest_id',$contest_id) !!}
									{!! Form::hidden('task_order',$task_order) !!}
										{!! Form::file('code','',array('id'=>'','class'=>'')) !!}
										{!! Form::submit('ส่งโค้ด',array('class'=>'pull-right btn btn-sm btn-default')) !!}
										<h6 style="color: #999">ไฟล์นามสกุล .c, .cpp หรือ .cxx</h6>
								</form>
							</div>
						</div>

						<div class="panel panel-default" ng-init="ConCtrl.getSubmits('{{$contest_id}}', 0, 1000000)">
							<div class="panel-heading">
								<h4>ผลตรวจล่าสุด</h4>
							</div>
							<table class="table table-condensed table-hover" style="text-align:center">
								<thead>
									<tr>
										<td><b>#</b></td>
										<td><b>เวลาส่ง</b></td>
										<td><b>ผลตรวจ</b></td>
									</tr>
								</thead>
								<tbody> 
									<tr ng-repeat="submit in ConCtrl.submits | filter:{'task_id':'{{$problem->task_id}}'}:true | limitTo: 5" ng-class="{'success': submit.pass == 1}">
										<td><h6><a href ng-click="ConCtrl.setShowCode(submit)" data-toggle="modal" data-target="#showCode">@{{submit.id}}</a></h6></td>
										<td><h6>@{{submit.created_at}}</h6></td>
										<td ng-show="submit.result.length"><h6>@{{submit.result}}</h6></td>
										<td ng-hide="submit.result.length"><h6>compilation error</h6></td>
									</tr>
								</tbody>
							</table>

							<!-- show code -->

							<div class="modal fade" id="showCode" aria-hidden="true">
								<div class="modal-dialog" style="width:800px; height:70%">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title">#@{{ConCtrl.currentCode.id}}</h4>
										</div>
										<iframe ng-src="@{{'../../../../code/' + ConCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>