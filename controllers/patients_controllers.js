/* controllers */

var app = angular.module("health2me", []);

/* controller to load and handle the user that is currently logged in */
app.controller('User', ['$http', '$window', 'UserModel', function($http, $window, UserModel)
{
    this.user = new UserModel();
    this.user.load();
}]);

/* controller to load and handle the doctor's patients including the statistics of the doctor's patients */
app.controller('Patients', ['$scope', '$http', 'PatientsModel', 'StatisticsModel', function($scope, $http, PatientsModel, StatisticsModel) 
{
    // Reference to itself
    var self = this;
    
    // Load the two objects (Patients and Statistics) that this controller will control
    this.patients = new PatientsModel();
    this.statistics = new StatisticsModel();

    
    // EVENTS
    
    // "user_loaded" listener - listens to the "user_loaded" event from the User controller and updates itself accordingly
    $scope.$on("user_loaded", function(event, args) 
    {
        self.patients.doctor_id = args.id;
        self.patients.update();
        self.statistics.doctor_id = args.id;
        self.statistics.update();
    });
    
    // Called when the user clicks on the group toggle
    this.groupToggle = function()
    {
        self.patients.update();
    };
    
    // Called when the user clicks on the "search" button next to the search bar
    this.runSearchQuery = function()
    {
        self.patients.update();
    };
    
    // Called when the user clicks on the next page button
    this.nextPage = function()
    {
        self.patients.loadNextPage();
    }
    
    // Called when the user clicks on the previous page button
    this.previousPage = function()
    {
        self.patients.loadPreviousPage();
    }
    
    
    // HELPED FUNCTIONS
    
    // Used to calculate the class for the boxes in the statistics section based on the size of the numbers
    this.getSizeClass = function(s)
    {
        if(s >= 70)
        {
            return 'big';
        }
        else if(s >= 50)
        {
            return 'medium';
        }
        else if(s >= 30)
        {
            return 'small';
        }
        else
        {
            return 'xsmall';
        }
    };

}]);