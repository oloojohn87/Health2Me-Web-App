/* This will configure the routes for the app */
var health2me = angular.module('health2me');

health2me.config(['$routeProvider', '$translateProvider',
function($routeProvider, $translateProvider) {
    $routeProvider.
    when('/patients', {
        templateUrl: 'angular_patients.html',
        controller: 'Patients',
        controllerAs: 'patientsCtrl'
    }).
    otherwise({
        redirectTo: '/patients'
    });
    
    $translateProvider.useStaticFilesLoader({
  		prefix: '../languages/',
        suffix: '.json'
  	});
	
	$translateProvider.preferredLanguage('en');
}]);