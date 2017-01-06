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
?>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'contest'])

			<div class="container" ng-controller="MainController" ng-init="realTime(<?= time() ?>)">

				<!-- <center><h1 style="color:#999">อยู่ระหว่างการพัฒนา</h1></center> -->

				<div class="row" ng-controller="ContestController as ConCtrl" ng-init="ConCtrl.getContest('{{$contest_id}}');">
					<div class="col-md-8">

						<span ng-hide="true">@{{ConCtrl.contest.duration = timeLeft((ConCtrl.contest.end_contest - ConCtrl.contest.start_contest)*1000)}}</span>

						<h2><a href="../@{{ConCtrl.contest.type}}/@{{ConCtrl.contest.id}}">@{{ConCtrl.contest.name}}</a></h2>

						โจทย์ทั้งหมด @{{ConCtrl.contest.task}} ข้อ |

						เวลาในการแข่งขัน <span ng-show="ConCtrl.contest.duration.day">@{{ConCtrl.contest.duration.day}} วัน</span> <span ng-show="ConCtrl.contest.duration.hour">@{{ConCtrl.contest.duration.hour}} ชั่วโมง</span> <span ng-show="ConCtrl.contest.duration.min">@{{ConCtrl.contest.duration.min}} นาที</span> |
						
						เริ่มแข่ง @{{ConCtrl.contest.start_contest * 1000 | date: 'medium'}}<br>

						<hr>

						<div class="panel panel-default">

							<div class="panel-body">

								<!-- กฎกติกา การแข่งขัน -->
								<div ta-bind ng-model="ConCtrl.contest.detail"></div>
								
								@if(Auth::check())
								
								<div ng-show="ConCtrl.contest.status == 'register'">
								<hr>

									<div class="pull-right" ng-show="ConCtrl.contest.registered != '1'">
										<label>
											<input type="checkbox" ng-checked="accept == true" ng-model="accept">
											ข้าพเจ้ายอมรับกติกาการแข่งขันทั้งหมด
										</label>
										<a class="btn btn-primary" ng-class="{'disabled':accept != true}" href="@{{ConCtrl.contest.id}}/accept">ยืนยันการสมัคร</a>
									</div>


								</div>

								<div class="pull-right" ng-show="ConCtrl.contest.registered == '1'">
									<h4 style="color:#999">คุณสมัครแล้ว</h4>
								</div>
								@else
								<hr>
									<a class="pull-right" href="../../signin">เข้าสู่ระบบเพื่อสมัครแข่งขัน</a>
								@endif

							</div>
						</div>

					</div>
					<div class="col-md-4">

						@include('contest.partials.status',['active' => 'register'])

					</div>
				</div>
			</div>
	</body>

	<footer>
		@include('partials.footer')
	</footer>
</html>