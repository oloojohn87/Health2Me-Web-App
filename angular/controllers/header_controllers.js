/* controllers */

var app = angular.module("h2m_header", ['health2me_services']);

app.controller('HeaderController', function ($scope, UserModel) {
  this.user = new UserModel();
  this.user.load();
	
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

