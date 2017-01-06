<?php use App\Config; ?>
<nav class="navbar navbar-inverse navbar-static-top" id="menu_bar">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{Config::root()}}/" style="color:white;">
				<div style="display: inline-block;">
					<img style="height:50px" src="{{Config::root()}}/img/the_new_logo.png">
					<?= Config::logo() ?>
				</div>
			</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			@if(Config::online() or Auth::isAdmin())
			<ul class="nav navbar-nav">
				<li ng-class="{active : 'contest' == '<?=$active?>'}"><a href="{{Config::root()}}/contest/">การแข่งขัน</a></li>
				<li ng-class="{active : 'task' == '<?=$active?>'}"><a href="{{Config::root()}}/task/">รวมโจทย์</a></li>
				<li ng-class="{active : 'discuss' == '<?=$active?>'}"><a href="{{Config::root()}}/discuss/">พูดคุย</a></li>
				@if(Auth::check())
					<li ng-class="{active : 'profile' == '<?=$active?>'}"><a href="{{Config::root()}}/profile/">ข้อมูลผู้ใช้</a></li>
					@if(Auth::isAdmin())
						<li ng-class="{active : 'admin' == '<?=$active?>'}"><a href="{{Config::root()}}/admin/">จัดการระบบ</a></li>
					@endif
				@else
				@endif
			</ul>
			@endif

			<div class="nav pull-right">
				@if(Auth::check())
					<a href="{{Config::root()}}/signout" class="navbar-btn btn btn-danger">ออกจากระบบ</a>
				@else
					@if(Config::allow_register())
					<a href="{{Config::root()}}/signup" class="navbar-btn btn btn-success">ลงทะเบียน</a>
					@endif
					<a href="{{Config::root()}}/signin" class="navbar-btn btn btn-primary">เข้าสู่ระบบ</a>
				@endif
			</div>
		</div><!-- /.navbar-collapse -->

	</div><!-- /.container-fluid -->
</nav>