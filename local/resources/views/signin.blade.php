<?php use App\Config; ?>
<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'signin'])

		<div class="container">
			<h1 style="color:#999; text-align:center">
				@if(!Config::online())
				<i class="fa fa-exclamation-triangle"></i> เว็บไซต์อยู่ระหว่างการปรับปรุง<br><br>
				@endif
			</h1>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					@include('forms.signin')
				</div>
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
