/* services */

var app = angular.module("health2me_services", []);

/* this service is used as a message bus to communicate events and data between controllers across the application */
app.factory("MessageBus", function($rootScope) {
    return {
        broadcast : function(event, data) 
        {
            $rootScope.$broadcast(event, data);
        }
    };
});

// User Model - The class that represents the data for the user that is currently signed in
app.factory("UserModel", function($http, $window, MessageBus) {
    var obj = function() {
        this.id = 0;
        this.first_name = "";
        this.last_name = "";
        this.email = "";
        this.identicon = "";
        this.privilege = 0;
        this.canAccessDashboard = 0;
        this.load = function()
        {
            var self = this;
            $http.get('../../../../../getDoctor.php').success(function(data) 
            {
                if(data == 'NULL')
                {
                    // no doctor signed in, redirect user to login page
                    $window.location.href = '../../../../index.html';
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
                    MessageBus.broadcast('user_loaded', self);
                }
            });
        };
        
        return this;
    };
    
    return obj;
});

// Patients Model - The class that represents the data for the doctor's patients
app.factory("PatientsModel", function($http) {
    return {
        doctor_id: -1,
        items: [],
        isGroup: false,
        searchQuery: "",
        currentPage: 1,
        totalPages: 1,
        itemsPerPage: 25,
        loaded: false,
        load: function()
        {
            var self = this;
            $http.get("../../../../../getFullUsersImproved.php?doc="+self.doctor_id+"&group="+self.isGroup+"&page="+self.currentPage+"&itemsPerPage="+self.itemsPerPage+"&searchQuery="+self.searchQuery).success(function(data)
            {
                self.items = data;
                self.loaded = true;
            });
        },
        update: function()
        {
            this.currentPage = 1;
            this.totalPages = 1;
            this.load();

        },
        loadNextPage: function()
        {
            this.currentPage += 1;
            this.load();
        },
        loadPreviousPage: function()
        {
            this.currentPage -= 1;
            this.load();
        }
    };
    
    return PatientsObj;
});

// Statistics Model - The class that represents the data for the Statistics Section
app.factory("StatisticsModel", function($http) {
    var StatisticsObj = function(){
        this.doctor_id = -1;
        this.members = 0;
        this.members_size = 10;
        this.reach = 0;
        this.reach_size = 10;
        this.new_info = 0;
        this.new_info_from_reach = 0;
        this.gauge_value = 0;
        this.loaded = false;
        this.gauge = new JustGage(
        {
            id: "gauge", 
            value: 0, 
            min: 0,
            max: 100,
            title: " ",
            label: '% Refered to me'
        });
        this.update = function()
        {
            var self = this;
            if(self.doctor_id > -1)
            {
                $http.get('/Laravel/public/index.php/ajax/doctors?doc='+self.doctor_id).success(function(data) 
                {
                    console.log(self.doctor_id);
                    self.members = data.totalPats;
                    self.members_size = 100;
                        //data.members_size;
                    self.reach = data.totalReach;
                    self.reach_size = 200;
                        //data.reach_size;
                    self.new_info = data.fromReach1;
                    self.new_info_from_reach = data.fromReach2;
                    self.gauge_value = data.percentageReferred;
                    self.loaded = true;
                    self.gauge.refresh(self.gauge_value);
                    console.log('success: ');
                    console.log(data);
                }).error(function(data, status, headers, config, statusText) {
                    self.loaded = true;
                    self.members = status;
                    console.log('error: ');
                    cosole.log(config);
                });
            }
        };
        
        return this;
    };
    
    return StatisticsObj;
});