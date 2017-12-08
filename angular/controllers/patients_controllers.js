/* controllers */

var app = angular.module("health2me");

/* controller to load and handle the doctor's patients including the statistics of the doctor's patients */
app.controller('Patients', ['$scope', 'PatientsModel', 'StatisticsModel', 'UserModel', function($scope, PatientsModel, StatisticsModel, UserModel) 
{
    // Reference to itself
    var self = this;
    
    // Load the two services (Patients and Statistics) that this controller will control
    this.patients = PatientsModel;
    this.statistics = StatisticsModel;
    
    // if the UserModel has already been loaded, load the two services right away
    if(UserModel.id > 0)
    {
        self.patients.doctor_id = UserModel.id;
        self.patients.update();
        self.statistics.doctor_id = UserModel.id;
        self.statistics.update();
    }

    // "user_loaded" listener - listens to the "user_loaded" event from the User controller and updates itself accordingly
    $scope.$on("user_loaded", function(event, args) 
    {
        self.patients.doctor_id = args.id;
        self.patients.update();
        self.statistics.doctor_id = args.id;
        self.statistics.update();
    });
    
    $scope.$on("language_updated", function(event, args) 
    {
        console.log("Yo: " + args);
        if(args == 'en')
            self.statistics.gauge.label = "% Referred to me";
        else
            self.statistics.gauge.label = "% Referidos a mi";
    });

}]);