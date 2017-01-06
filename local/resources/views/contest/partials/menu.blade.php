<?php use App\Config; ?>
<div id="menu_sidebar">
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<div class='panel-title'>การแข่งขัน</div>
		</div>
		<div class='panel-body'>
			<ul class="nav nav-pills nav-stacked">
				<li ng-class="{active: '{{$active}}' == 'main'}"><a href="main">หน้าหลัก</a></li>
				<li ng-class="{active: '{{$active}}' == 'old'}"><a href="old">การแข่งขันที่จบแล้ว</a></li>
				@if(Auth::check())
				<hr>
				<li ng-class="{active: '{{$active}}' == 'registered'}"><a href="registered"><b>การแข่งขันที่สมัคร</b></a></li>
				@endif
			</ul>
		</div>
	</div>
</div>