<?php use App\Config ?>

<div class="modal fade" id="changeImage" aria-hidden="true" ng-controller="UploadController">
	<div class="modal-dialog" style="width:400px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">เปลี่ยนรูปโปรไฟล์</h4>
			</div>

			<form class="form-horizontal" method="post" action="{{ secure_url('profile/active/upload') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="modal-body">

					<b>รูปปัจจุบัน</b>

					<hr>

					<div style="text-align:center">
						<img ng-show="UserCtrl.userInfo.image == '1'" ng-src="{{Config::root()}}/img/user/@{{UserCtrl.userInfo.username}}.jpg" style="max-width:100%; max-height:375px">
						<img ng-show="UserCtrl.userInfo.image != '1'" ng-src="{{Config::root()}}/img/user/0.jpg" style="max-width:100%; max-height:375px">
					</div>

					<hr>

					<h6 style="color: #999" class="pull-right">เฉพาะไฟล์รูปภาพ</h6>
					{!! Form::file('img','',array('id'=>'','class'=>'')) !!}

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
					{!! Form::submit('บันทึก',array('class'=>'pull-right btn btn-primary')) !!}
				</div>
			</form>
		</div>
	</div>
</div>