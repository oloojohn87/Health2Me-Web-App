/* services */

var app = angular.module("health2me");

// User Model - The class that represents the data for the user that is currently signed in
app.factory("UserModel", function($http, $window, $rootScope) {
    return {
        id: 0,
        first_name: "",
        last_name: "",
        email: "",
        identicon: "",
        privilege: 0,
        canAccessDashboard: 0,
        load: function()
        {
            var self = this;
            $http.get('getDoctor.php').success(function(data) 
            {
                if(data == 'NULL')
                {
                    // no doctor signed in, redirect user to login page
                    $window.location.href = 'index.html';
                }
                else
                {
                    self.id = data.id;
                    self.first_name = data.first_name;
                    self.last_name = data.last_name;
                    self.identicon = data.identicon;
                    self.privilege = data.privilege;
                    self.canAccessDashboard = data.canAccessDashboard;
                    
                    // let the rest of the app know that the user was loaded with a reference to the user object
                    $rootScope.$broadcast('user_loaded', self);
                }
            });
        }
    };
});