/* directives */

var app = angular.module("health2me");

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
                PatientsModel.currentPage += 1;
                PatientsModel.load();
            });
        }
    };
}]);

app.directive("firstPage", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.currentPage = 1;
                PatientsModel.load();
            });
        }
    };
}]);

app.directive("lastPage", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.currentPage = PatientsModel.totalPages;
                PatientsModel.load();
            });
        }
    };
}]);

app.directive("previousPage", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.currentPage -= 1;
                PatientsModel.load();
            });
        }
    };
}]);

app.directive("searchClearButton", ['PatientsModel', function(PatientsModel){
    return {
        restrict: "A",
        link: function( scope, element, attrs ) {
            element.bind( "click", function() {
                PatientsModel.searchQuery = "";
                PatientsModel.update();
            });
        }
    };
}]);

app.directive("selectPatient", ['PatientsModel', '$window', function(PatientsModel, $window){
    return {
        restrict: "A",
        scope: {
            patient_id: "@patId"
        },
        link: function( scope, element, attrs ) {
            element.bind('click', function()
            {
                $window.location.href = 'patientdetailMED-new.php?IdUsu='+scope.patient_id;
            });
            
        }
    };
}]);