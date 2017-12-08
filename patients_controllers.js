/* controllers */

var app = angular.module("health2me", ['health2me_services', 'health2me_directives']);

/* controller to load and handle the user that is currently logged in */
app.controller('User', ['$http', '$window', 'UserModel', function($http, $window, UserModel)
{
    this.user = UserModel;
    this.user.load();
}]);

/* controller to load and handle the doctor's patients including the statistics of the doctor's patients */
app.controller('Patients', ['$scope', 'PatientsModel', 'StatisticsModel', function($scope, PatientsModel, StatisticsModel) 
{
    // Reference to itself
    var self = this;
    
    // Load the two objects (Patients and Statistics) that this controller will control
    this.patients = PatientsModel;
    this.statistics = StatisticsModel;

    
    // EVENTS
    // "user_loaded" listener - listens to the "user_loaded" event from the User controller and updates itself accordingly
    $scope.$on("user_loaded", function(event, args) 
    {
        self.patients.doctor_id = args.id;
        self.patients.update();
        self.statistics.doctor_id = args.id;
        self.statistics.update();
    });

}]);