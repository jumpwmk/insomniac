<!DOCTYPE html>
<html ng-app="mainApp">
	<head>
		@include('partials.header')
	</head>

	<body ng-cloak>
		@include('partials.menubar', ['active' => 'admin'])
		<div class="container">

			<div class="row">
				<div class="col-md-8">

					<h2><i class="fa fa-cog"></i> ตั้งค่าระบบ</h2><hr>
					@include('admin.forms.configs')


				</div>
				<div class="col-md-4">
					@include('admin.partials.menu', ['active' => 'main'])
				</div>
			</div>
		</div>

		<footer>
			@include('partials.footer')
		</footer>
	</body>
</html>
