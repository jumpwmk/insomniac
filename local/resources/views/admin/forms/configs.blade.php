<div ng-controller="AdminController as ConfigCtrl" ng-init="ConfigCtrl.getConfigs()">

	<form name="config" ng-submit="ConfigCtrl.editConfigs()">
		
		<p class="row">
			<div class="col-md-3">
				<label class="pull-right">Root directory</label>
			</div>
			<div class="col-sm-9">
				<input type="text" class="form-control" ng-model="ConfigCtrl.configs.root" disabled>
			</div>
		</p>

		<p class="row">
			<div class="col-sm-3">
				<label class="pull-right">ชื่อเว็บไซต์</label>
			</div>
			<div class="col-sm-9">
				<input type="text" class="form-control" ng-model="ConfigCtrl.configs.title">
			</div>
		</p>

		<p class="row">
			<div class="col-sm-3">
				<label class="pull-right">โลโก้</label>
			</div>
			<div class="col-sm-9">
				<input type="text" class="form-control" ng-model="ConfigCtrl.configs.logo">
			</div>
		</p>

		<p class="row">
			<div class="col-sm-3">
				<label class="pull-right">Custom HTML</label>
			</div>
			<div class="col-sm-9">
				<textarea class="form-control" rows="10" ng-model="ConfigCtrl.configs.custom"> </textarea>
			</div>
		</p>

		<p class="row">
			<div class="col-sm-3">
				<label class="pull-right">สถานะเว็บไซต์</label>
			</div>
			<div class="col-sm-9">
				<label class="radio-inline"><input type="radio" data-ng-model="ConfigCtrl.configs.online" value="1">ออนไลน์</label>
				<label class="radio-inline"><input type="radio" data-ng-model="ConfigCtrl.configs.online" value="0">ออฟไลน์</label>
			</div>
		</p>

		<p class="row">
			<div class="col-sm-3">
				<label class="pull-right">อนุญาติให้สมัคร</label>
			</div>
			<div class="col-sm-9">
				<input type="checkbox" ng-model="ConfigCtrl.configs.allow_register" ng-checked="ConfigCtrl.configs.allow_register == true">
			</div>
		</p>

		<p class="pull-right">
			<br>
			<input class="btn btn-default" value="รีเซ็ตค่า" type="button" ng-click="ConfigCtrl.resetConfigs()">
			<input class="btn btn-primary" value="บันทึก" type="submit">
		</p>

	</form>
</div>
<br>
<br>
<br>
<h2><i class="fa fa-server"></i> ตัวตรวจ</h2>
<hr>
@for($i = 1; $i <= 4; $i++)
<div class="form-horizontal" ng-controller="GraderController as GraderCtrl_{{$i}}" ng-init="GraderCtrl_{{$i}}.getGraderInfo({{$i}})">
	<p class="form-group form-group-sm">
		<div class="col-sm-3 control-label">
			<label class="pull-right">สถานะตัวตรวจที่ {{$i}}</label>
		</div>
		<div class="col-sm-3 control-label" style="text-align:left">
			<code ng-show="GraderCtrl_{{$i}}.info.working == 1" style="color:green; background-color:#d0ffd0">กำลังทำงาน</code>
			<code ng-show="GraderCtrl_{{$i}}.info.working != 0 && GraderCtrl_{{$i}}.info.working != 1" style="color:orange; background-color:#fff0d9">กำลังโหลด...</code>
			<code ng-show="GraderCtrl_{{$i}}.info.working == 0">ไม่ทำงาน</code>
		</div>
		<div class="col-sm-6">
			<div class="pull-right">
				<button ng-click="GraderCtrl_{{$i}}.start({{$i}})" class="btn btn-sm btn-success" ng-class="{disabled: GraderCtrl_{{$i}}.info.working || (GraderCtrl_{{$i}}.info.working != 0 && GraderCtrl_{{$i}}.info.working != 1)}">เริ่มทำงาน</button>
				<button ng-click="GraderCtrl_{{$i}}.stop({{$i}})" class="btn btn-sm btn-danger" ng-class="{disabled: !GraderCtrl_{{$i}}.info.working || (GraderCtrl_{{$i}}.info.working != 0 && GraderCtrl_{{$i}}.info.working != 1)}">หยุดทำงาน</button>
			</div>
		</div>
	</p>
</div>
@endfor
<br><br><br>
<toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>
