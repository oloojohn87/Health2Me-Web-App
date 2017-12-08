/* directives */

var app = angular.module("health2me_directives", ["health2me_services"]);

app.directive("searchPatients", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.update();
            });
        }
    };
}]);

app.directive("toggleGroup", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.update();
            });
        }
    };
}]);

app.directive("nextPage", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.loadNextPage();
            });
        }
    };
}]);

app.directive("previousPage", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.loadPreviousPage();
            });
        }
    };
}]);

app.directive("selectPatient", ['PatientsModel', '$window', function(PatientsModel, $window){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            console.log(patient.identifier);
            
        }
    };
}]);