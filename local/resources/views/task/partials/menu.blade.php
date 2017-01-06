<?php use App\Config; ?>
<div id="menu_sidebar">
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<div class='panel-title'>กลุ่มโจทย์</div>
		</div>
		<div class='panel-body'>
			<ul class="nav nav-pills nav-stacked">
				<li ng-class="{'active': '{{$active}}' == 'all'}"><a href="all">ทั้งหมด</a></li>
				<li ng-class="{'active': '{{$active}}' == 'unrate'}"><a href="unrate">ยังไม่มีคะแนน</a></li>
				<li ng-class="{'active': '{{$active}}' == 'easy'}"><a href="easy">ระดับง่าย</a></li>
				<li ng-class="{'active': '{{$active}}' == 'medium'}"><a href="medium">ระดับกลาง</a></li>
				<li ng-class="{'active': '{{$active}}' == 'hard'}"><a href="hard">ระดับยาก</a></li>
				<hr>
				<li><a href="result"><b>ผลตรวจ</b></a></li>
			</ul>
		</div>
	</div>
</div>