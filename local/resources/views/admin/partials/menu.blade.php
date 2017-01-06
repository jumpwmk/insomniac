<?php use App\Config; ?>
<div id="menu_sidebar">
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<div class="panel-title">จัดการระบบ</div>
		</div>
		<div class='panel-body'>
			<ul class="nav nav-pills nav-stacked">
				<li ng-class="{active : 'main' == '<?=$active?>'}"><a href="{{Config::root()}}/admin/main">ภาพรวม</a></li>
				<li ng-class="{active : 'contest' == '<?=$active?>'}"><a href="{{Config::root()}}/admin/contest">การแข่งขัน</a></li>
				<li ng-class="{active : 'task' == '<?=$active?>'}"><a href="{{Config::root()}}/admin/task">โจทย์</a></li>
				<li ng-class="{active : 'user' == '<?=$active?>'}"><a href="{{Config::root()}}/admin/user">ผู้ใช้</a></li>
			</ul>
		</div>
	</div>
</div>