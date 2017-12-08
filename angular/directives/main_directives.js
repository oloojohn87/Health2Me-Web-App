var app = angular.module("health2me");

app.directive("languageButton", ['$translate', function($translate){
    return {
        restrict: "A",
        scope: {
            lang: "@lang"
        },
        link: function( scope, element, attrs ) {
            element.bind('click', function()
            {
                if(scope.lang == 'es')
                {
                    var language = 'es';
                    $("#lang1").html("<i class=\"icon-caret-down\" style=\"float: right; margin-right: 8px; margin-top: 5px;\"></i>Espa&ntilde;ol");
                }
                else{
                    var language = 'en';
                    $("#lang1").html("<i class=\"icon-caret-down\" style=\"float: right; margin-right: 8px; margin-top: 5px;\"></i>English");
                }
            });
            
        }
    };
}]);