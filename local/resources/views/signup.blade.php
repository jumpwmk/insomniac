<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'signup'])

		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					@include('forms.signup')
				</div>
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
