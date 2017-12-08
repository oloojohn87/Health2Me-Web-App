/* controllers */

var app = angular.module("h2m_header", ['pascalprecht.translate']);

app.config(function($translateProvider) {
       	  
  	$translateProvider.useStaticFilesLoader({
  		prefix: '../languages/',
        suffix: '.json'
  	});
	
	$translateProvider.preferredLanguage('en');  
});

app.controller('HeaderController', function ($translate,$scope) {

  $scope.changeLanguage = function (langKey) {
    $translate.use(langKey);
  };	
	
  $scope.links = [
	{
		access: 0,
        name: 'Home',
        url: 'MainDashboard.php'},
    {
		access: 1,
        name: 'Members',
        url: 'patients.php'},
	{
		access: 1,
        name: 'Configuration',
        url: 'medicalConfiguration.php'},	
	{
		access:	0,
        name: 'Sign Out',
        url: 'logout.php'}
  ];
});
