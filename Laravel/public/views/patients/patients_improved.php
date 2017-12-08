<!doctype html>
<html lang="en" ng-app="health2me">
    <head>
        <meta charset="utf-8">
        <title>Health2Me</title>
        
        <!-- Stylesheets -->
        <link rel="stylesheet" href="../../../../css/bootstrap.css" />
        <link rel="stylesheet" href="../../../../css/style.css" />
        <link rel="stylesheet" href="../../../../css/icon/font-awesome.css" />
        <link rel="stylesheet" href="../../../../css/patients_improved.css" />
        <link rel='stylesheet' href='../../../../css/toggle-switch.css'>
        
        <!-- Scripts -->
        <script src="../../../../js/jquery.min.js"></script>
        <script src="../../../../js/jquery-ui.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
        <script src="../../js/angular/services/patients_services.js"></script>
        <script src="../../js/angular/directives/patients_directives.js"></script>
        <script src="../../js/angular/controllers/patients_controllers.js"></script>
        <script src="../../../../js/bootstrap.min.js"></script>
        <script src="../../../../js/raphael.2.1.0.min.js"></script>
        <script src="../../../../js/justgage.1.0.1.min.js"></script>
        
    </head>
    <body style="background: #F9F9F9;" ng-controller="User as userCtrl">
        
        <!-- HEADER -->
        <!-- div ng-include="'../../../../h2m_header.html'"></div-->
        
        <div class="header" >

            <!-- Logo -->
            <a href="index.html" class="logo"><h1>health2.me</h1></a>

            <!-- Right Menu Dropdown -->
            <div class="pull-right">
                <div class="btn-group pull-right" >
                    <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
                        <span class="name-user"><strong lang="en">Welcome</strong> Dr. {{userCtrl.user.first_name}} {{userCtrl.user.last_name}}</span> 
                        <span class="avatar" style="background-color: #FFF;"><img ng-src="{{userCtrl.user.identicon}}" alt="" ></span> 
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" id="prof_dropdown" role="menu">
                        <div class="item_m"><span class="caret"></span></div>
                        <ul class="clear_ul">
                            <li><a href="../../../../MainDashboard.php"><i class="icon-globe" lang="en"></i> Home</a></li>
                            <li><a href="../../../../medicalConfiguration.php"><i class="icon-cog" lang="en"></i> Settings</a></li>
                            <li><a href="../../../../logout.php"><i class="icon-off" lang="en"></i> Sign Out</a></li>

                        </ul>
                    </div>
                </div> 
            </div>

        </div>
        
        <!-- Main Menu -->
        <div class="speedbar">
            <div class="speedbar-content">
                <ul class="menu-speedbar">
                    <li><a href="../../../../MainDashboard.php" lang="en">Home</a></li>
                    <li ng-show="userCtrl.user.canAccessDashboard"><a lang="en" href="../../../../dashboard.php"  lang="en">Dashboard</a></li>
                    <li><a href="../../../../patients.php" class="act_link"  lang="en" >Members</a></li>
                    <li ng-show="userCtrl.user.privilege == 1"><a href="../../../../medicalConnections.php"  lang="en">Doctor Connections</a></li>
                    <li ng-show="userCtrl.user.privilege == 1"><a href="../../../../PatientNetwork.php"  lang="en">Member Network</a></li>
                    <li><a href="../../../../medicalConfiguration.php" lang="en">Configuration</a></li>
                    <li><a href="../../../../logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div id="content">
            
            
        
            <!-- Content -->
            <div id="content" ng-controller="Patients as patientsCtrl">
                
                <div class="grid main_grid">
                    <!-- Statistics Box -->
                    <div class="row-fluid">
                        
                        
                        <div class="grid statistics_box">
                            
                            
                            <div class="loading_statistics" ng-hide="patientsCtrl.statistics.loaded">
                                <div class="loading_indicator">
                                    <img src="../../../../images/load/29.gif" />
                                </div>
                                Loading Statistics, Pleast Wait...
                                
                            </div>
                            
                            <div ng-show="userCtrl.user.privilege == 1" class="gauge_box">
                                <div id="gauge" class="gauge"></div>
                            </div>	
                            <div class="statistics_box_right_section" ng-show="patientsCtrl.statistics.loaded">
                                <div class="statistics_box_entry">

                                    <div class="statistics_box_number_container">
                                        <p class="statistics_box_number" style="color: #6978FA"  ng-class="patientsCtrl.getSizeClass(patientsCtrl.statistics.members_size)">
                                            {{patientsCtrl.statistics.members}}
                                        </p>
                                    </div>

                                    <div class="statistics_box_label_container" style="background-color: #6978FA; border: 1px solid #6978FA;" >
                                        <p class="statistics_box_label" lang="en">Members</p>
                                    </div>	

                                </div>

                                <div class="statistics_box_entry">

                                    <div class="statistics_box_number_container"> 
                                        <p class="statistics_box_number" style="color: #FFC831"  ng-class="patientsCtrl.getSizeClass(patientsCtrl.statistics.reach_size)">
                                            {{patientsCtrl.statistics.reach}}
                                        </p>
                                    </div>

                                    <div class="statistics_box_label_container" style="background-color: #FFC831; border: 1px solid #FFC831;">
                                        <p class="statistics_box_label" lang="en">Reach</p>
                                    </div>	

                                </div>

                                <div class="statistics_box_entry" style="width: 200px;">

                                    <div class="statistics_box_number_container">
                                        <p class="statistics_box_label2">{{patientsCtrl.statistics.new_info}}%</p>
                                        <p class="statistics_box_label3">{{patientsCtrl.statistics.new_info_from_reach}}% From Reach</p>
                                    </div>

                                    <div class="statistics_box_label_container" style="background-color: #73BB3B; border: 1px solid #73BB3B;">
                                        <p class="statistics_box_label" lang="en">New Information</p>
                                    </div>	

                                </div>
                            </div> 
                            
                        </div>
                    </div>


                    <!-- Search bar to search for patients -->
                    <div class="patients_table_wrapper">
                        <div class="search_bar">
                            <input type="text" ng-model="patientsCtrl.patients.searchQuery" placeholder="Search Members" style="margin-left: 10px" />
                            
                            <button class="search_bar_button" search-patients>Search</button>

                            <div style="float:right; margin-right:20px;">
                                <label class="checkbox toggle candy blue" onclick="" style="width:100px;">
                                    <input type="checkbox" id="RetrievePatient" name="RetrievePatient" ng-model="patientsCtrl.patients.isGroup" toggle-group />
                                    <p>
                                        <span title="Search in only in group" lang="en">Group</span>
                                        <span title="Search all patients" lang="en">All</span>
                                    </p>

                                    <a class="slide-button"></a>
                                </label>
                            </div>
                        </div>

                        <!-- Table to display patient results -->
                        <table id="patients_table" cellspacing="0" cellpadding="0">

                            <!-- Table header row -->
                            <tr class="patients_table_header">
                                <th style="width: 20%">Identifier</th>
                                <th style="width: 20%">First Name</th>
                                <th style="width: 20%">Last Name</th>
                                <th style="width: 20%">User Name</th>
                                <th style="width: 20%">Total Reports</th>
                            </tr>

                            <!-- Repeat a row for every patient in the patients model -->
                            <tr ng-repeat="patient in patientsCtrl.patients.items" class="patient_row" ng-class="{'even': $even, 'odd': $odd}" select-patient pat-Id="{{patient.identifier}}">
                                <td>{{patient.identifier}}</td>
                                <td>{{patient.first_name}}</a></td>
                                <td>{{patient.last_name}}</a></td>
                                <td>{{patient.user_name}}</td>
                                <td>{{patient.reports}} / {{patient.total_reports}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="pagination">
                        <button class="pagination_button" style="float: left;" ng-disabled="patientsCtrl.patients.currentPage <= 1" previous-page>
                            <i class="icon-caret-left"></i>
                        </button>
                        <button class="pagination_button" style="float: right;" ng-disabled="patientsCtrl.patients.currentPage >= patientsCtrl.patients.totalPages" next-page>
                            <i class="icon-caret-right"></i>
                        </button>
                        {{patientsCtrl.patients.currentPage}} of {{patientsCtrl.patients.totalPages}}
                    </div>

                </div>
                
            </div>
            
            <!-- FOOTER -->
            <div ng-include src="'../../../../h2m_footer.html'"></div>
        </div>
        
        
    </body>
</html>