/* controllers */

var app = angular.module("h2m_header", []);

app.controller('HeaderController', function ($scope) {
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

/* controller to load and handle the user that is currently logged in */
app.controller('User', ['$http', '$window', 'UserModel', function($http, $window, UserModel)
{
    this.user = new UserModel();
    this.user.load();
}]);

