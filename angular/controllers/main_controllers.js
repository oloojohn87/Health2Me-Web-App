/* controllers */

var app = angular.module("health2me", ['ngRoute', 'pascalprecht.translate']);

/* controller to load and handle the user that is currently logged in */
app.controller('User', ['$http', '$window', 'UserModel', '$translate', '$rootScope', function($http, $window, UserModel, $translate, $rootScope)
{
    this.changeLanguage = function (langKey) {
        $translate.use(langKey);
    };
    
    this.user = UserModel;
    this.user.load();
}]);