<?php use App\Config; ?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'task'])
		<div class="container">

			<div ng-init="TaskCtrl.infoTask('{{$active}}')" ng-controller="TaskController as TaskCtrl">

				<div ng-show="TaskCtrl.info.visible != null && TaskCtrl.info.visible != '1'">
					<center><h1 style="color:#999">โจทย์ข้อนี้ยังไม่อนุญาตให้ทำ</h1><br></center>
				</div>

				<div class="col-md-8" ng-show="TaskCtrl.info.visible == '1'">

					<ul class="nav nav-tabs">
						<li class="active"><a href="{{$active}}"><b>@{{TaskCtrl.info.name}}</b></a></li>
						<li><a href="{{$active}}/result"><b>ผลตรวจทั้งหมด</b></a></li>
						@if(Auth::check())
						<li><a href="{{$active}}/myresult"><b>ผลตรวจของคุณ</b></a></li>
						@endif
					</ul>

					<div class="row">
						<div class="col-md-8">
							<h3>
								<span ng-hide="TaskCtrl.info.rating" class="label label-default">@{{TaskCtrl.info.formattedId}}</span>
								<span ng-show="1 <= TaskCtrl.info.rating && TaskCtrl.info.rating <= 2" class="label label-success">@{{TaskCtrl.info.formattedId}}</span>
								<span ng-show="2 < TaskCtrl.info.rating && TaskCtrl.info.rating <= 3.5" class="label label-warning">@{{TaskCtrl.info.formattedId}}</span>
								<span ng-show="3.5 < TaskCtrl.info.rating" class="label label-danger">@{{TaskCtrl.info.formattedId}}</span>
								@{{TaskCtrl.info.name}}
							</h3>
							<a href="{{Config::root()}}/judge/docs/@{{TaskCtrl.info.id}}.pdf" download="@{{TaskCtrl.info.id}}.pdf"><b>ดาวน์โหลดไฟล์ PDF</b></a>
						</div>
						<div class="col-md-4" style="text-align:right">
							<br>
							<h5>เวลา: @{{TaskCtrl.info.time}} วินาที</h5> 
							<h5>หน่วยความจำ: @{{TaskCtrl.info.memory}} เมกะไบต์</h5>
						</div>
					</div>
					<hr>
					<object data="{{Config::root()}}/judge/docs/@{{TaskCtrl.info.id}}.pdf" type="application/pdf" width="100%" height="100%"> 
						<p>คุณไม่มี plugin สำหรับเปิดไฟล์ pdf <a href="{{Config::root()}}/judge/docs/@{{TaskCtrl.info.id}}.pdf" download="@{{TaskCtrl.info.id}}.pdf"><b>ดาวน์โหลดไฟล์ PDF</b></a></p>  
					</object>
					<hr>
				</div>

				<div class="col-md-4" ng-show="TaskCtrl.info.visible == '1'">

					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-title">คะแนนความยากง่าย</div>
						</div>
						<div class="panel-body" style="text-align:center">
							@if(Auth::check())
							<div class="panel panel-body panel-default">
							@else
							<div class="panel panel-body panel-default" style="margin:0">
							@endif
								<div style="font-size:200%" ng-hide="TaskCtrl.info.ratingCount < 5">@{{TaskCtrl.info.rating}}</div>
								<div ng-init="secret = 0" ng-mouseover="secret = 1" ng-mouseleave="secret = 0" style="font-size:150%" ng-show="TaskCtrl.info.ratingCount < 5" data-toggle="tooltip" data-placement="top" title="จำนวนผู้ใช้ที่ให้คะแนนยังมีไม่มาก ทำให้คะแนนยังไม่น่าเชื่อถือ">ยังไม่เปิดเผยคะแนน</div>
								@if(Auth::isAdmin())
								<div ng-show="secret == 1" style="color:#999">FOR ADMIN: @{{TaskCtrl.info.rating}}</div>
								@endif
								<script type="text/javascript">
								$(function () {
									$('[data-toggle="tooltip"]').tooltip()
								})
								</script>
								คะแนนความยากง่ายเฉลี่ย
							</div>
							@if(Auth::check())
							<div class="panel panel-body panel-default" style="margin:0">
								<div style="font-size:150%" ng-mouseleave="TaskCtrl.info.rated = TaskCtrl.oldRated">
									<span ng-repeat="star in [1, 2, 3, 4, 5]">
										<a href ng-mouseover="TaskCtrl.info.rated = star" ng-click="TaskCtrl.rateTask(star)"><i ng-hide="star <= TaskCtrl.info.rated" class="fa fa-star-o"></i><i ng-show="star <= TaskCtrl.info.rated" class="fa fa-star"></i></a>
									</span>
								</div>
								คะแนนความยากง่ายของคุณ
							</div>
							@endif
						</div>
					</div>
					
					@if(Auth::check())
					<div>

						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-title">อัพโหลด</div>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" method="post" action="{{ secure_url('task/active/upload') }}" enctype="multipart/form-data">
	 								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									{!! Form::hidden('task_id',$active) !!}
										{!! Form::file('code','',array('id'=>'','class'=>'')) !!}
										{!! Form::submit('ส่งโค้ด',array('class'=>'pull-right btn btn-sm btn-default')) !!}
										<h6 style="color: #999">ไฟล์นามสกุล .c, .cpp หรือ .cxx</h6>
									{!! Form::close() !!}
								</form>
							</div>
						</div>

						<div class="panel panel-default" ng-controller="SubmitController as SubmitCtrl" ng-init="SubmitCtrl.getTaskSubmits('myresult', {{$active}}, 0, 5)">
							<div class="panel-heading">
								<div class="panel-title">ผลตรวจล่าสุด</div>
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
									<tr ng-repeat="submit in SubmitCtrl.submits | filter:{task_id: {{$active}}} | limitTo: 5" ng-class="{'success': submit.pass == 1 && submit.task.visible == 1, 'warning': submit.task.visible != 1}">
										<td><h6><a href ng-click="SubmitCtrl.setShowCode(submit)" data-toggle="modal" data-target="#showCode">@{{submit.id}}</a></h6></td>
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
											<h4 class="modal-title">#@{{SubmitCtrl.currentCode.id}}</h4>
										</div>
										<iframe src="@{{'../code/' + SubmitCtrl.currentCode.id}}" frameborder="0" height="100%" width="100%"></iframe>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						@else
							@include('forms.signin')
						@endif

					</div>
				</div>

			</div>
			<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>
		</div>
</body>

<footer>
	@include('partials.footer')
</footer>
</html>
