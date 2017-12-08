<?php

class GetPatients{

//Following are the instance variables for getting the doctorId statistics and detailed patients data from separate class objects
            private $GetStatistics;
            private $GetPatientsData;
            private $doctorId;
    

//Constructor for contructing class an individual elements of statistics and patients data

            function __construct()
            {
                 $doctorId = $MEDID;
                 $getStats = new GetStatistics();
                 $getPatientsData = new GetPatientsData($doctorId);

            }
    
    
}

?>