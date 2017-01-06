<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'main'])

		<div class="container">
			@include('partials.welcome')
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
