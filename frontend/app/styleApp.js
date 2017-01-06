var app = angular.module('styleApp',[]);

app.controller('CodestyleController', function ($http, $scope, $filter, FileUploader){

	this.getStyles = function (){
		var tmp = this;
		tmp.styles = {};
		$http.get('active/getCodestyles').success(function (data){
			tmp.styles = data;
		});
	};

});