/* services */

var app = angular.module("health2me");

// Patients Model - The class that represents the data for the doctor's patients
app.factory("PatientsModel", function($http) {
    return {
        doctor_id: -1,
        items: [],
        isGroup: false,
        searchQuery: "",
        currentPage: 1,
        totalPages: 1,
        itemsPerPage: 20,
        loaded: false,
        loading: false,
        load: function()
        {
            var self = this;
            self.loading = true;
            self.items = [];
            $http.get("getFullUsersImproved.php?doc="+self.doctor_id+"&group="+self.isGroup+"&page="+self.currentPage+"&itemsPerPage="+self.itemsPerPage+"&searchQuery="+self.searchQuery).success(function(data)
            {
                self.items = data.data;
                if(data.total_pages > 1)
                {
                    self.totalPages = data.total_pages;
                }
                self.loaded = true;
                self.loading = false;
            });
        },
        update: function()
        {
            this.currentPage = 1;
            this.totalPages = 1;
            this.load();

        }
    };
});

// Statistics Model - The class that represents the data for the Statistics Section
app.factory("StatisticsModel", function($http) {
    return {
        doctor_id: -1,
        members: 0,
        members_size: 10,
        reach: 0,
        reach_size: 10,
        new_info: 0,
        new_info_from_reach: 0,
        gauge_value: 0,
        loaded: false,
        gauge: new JustGage(
        {
            id: "gauge", 
            value: 0, 
            min: 0,
            max: 100,
            title: " ",
            label: ''
        }),
        update: function()
        {
            var self = this;
            if(self.doctor_id > -1)
            {
                $http.get('getPatientsStatistics.php?doc='+this.doctor_id).success(function(data) 
                {
                    self.members = data.members;
                    self.members_size = data.members_size;
                    self.reach = data.reach;
                    self.reach_size = data.reach_size;
                    self.new_info = data.new_info;
                    self.new_info_from_reach = data.new_info_from_reach;
                    self.gauge_value = data.gauge_value;
                    self.loaded = true;
                    self.gauge.refresh(data.gauge_value);
                });
            }
        },
        getSizeClass: function(s)
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
        }
    };
});