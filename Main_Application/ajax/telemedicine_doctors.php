<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
    <link rel="stylesheet" type="text/css" href="css/googleAPIFamilyCabin.css">
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    	<style>
		.ui-progressbar {
		position: relative;
		}
		.progress-label {
		position: absolute;
		left: 50%;
		top: 4px;
		font-weight: bold;
		text-shadow: 1px 1px 0 #fff;
		}
	</style>
	<style>
	#overlay {
	  background-color: none;
	  position: auto;
	  top: 0; right: 0; bottom: 0; left: 0;
	  opacity: 1.0; /* also -moz-opacity, etc. */
	  
    }
	#messagecontent {
	  white-space: pre-wrap;   
	}
	</style>
	  <style>
		#progressbar .ui-progressbar-value {
		background-color: #ccc;
		}
	  </style>

    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

  </head>

  <body style="background: #F9F9F9;">
      <div id="content" style="background: #F9F9F9; padding-left:0px;">
    
        <!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERID" Value="<?php echo $UserID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           

            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>

            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">

     </ul>

     
     </div>
     </div>
        <div class="content">
            <div class="grid" class="grid span4" style="width:1000px; height:675px; margin: 0 auto; margin-top:30px; padding-top:30px;">
                <h2 style="background-color: #22AEFF; color: #FFF; border-radius: 10px; text-align: center; width: 350px; margin: auto; margin-bottom: 30px;">Telemedicine Doctors</h2>
                <?php
    
                 require("environment_detail.php");
                 $dbhost = $env_var_db['dbhost'];
                 $dbname = $env_var_db['dbname'];
                 $dbuser = $env_var_db['dbuser'];
                 $dbpass = $env_var_db['dbpass'];
                
                //KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
                mysql_select_db("$dbname")or die("cannot select DB");

                $doc_id = -1;
                if(isset($_GET['id']))
                {
                    $doc_id = $_GET['id'];
                }

                $query = "SELECT * FROM doctors WHERE telemed=1";
                if($doc_id != -1)
                {
                    $query .= " AND id=".$doc_id;
                }
                
                $res = mysql_query($query);
                date_default_timezone_set('GMT'); 
                $date = new DateTime('now');
                $count = 0;
                while($row = mysql_fetch_assoc($res))
                {
                    $count += 1;
                    $result2 = mysql_query("SELECT * FROM timeslots WHERE doc_id=".$row['id']);
                    $available = false;
                    while(($row2 = mysql_fetch_assoc($result2)) && !$available)
                    {
                        $start = new DateTime($row2['week'].' '.$row2['start_time']);
                        $end = new DateTime($row2['week'].' '.$row2['end_time']);
                        $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                        $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                        if(substr($row2['timezone'], 0 , 1) != '-')
                        {
                            $time_interval->invert = 1;
                        }
                        $start->add($date_interval);
                        $end->add($date_interval);
                        $start->add($time_interval);
                        $end->add($time_interval);
                        if($start <= $date && $end >= $date)
                        {
                            // doctor is available
                            $available = true;
                        }
                        
                    }
                    echo '<div style="width: 98%; padding: 1%; height: 15px; ';
                    if($count % 2 == 1)
                    {
                        echo 'background-color: #E6E6E6';
                    }
                    else
                    {
                        echo 'background-color: #FAFAFA';
                    }
                    echo '">';
                    echo '<div style="width: 200px; float: left;">Doctor '.$row['Name'].' '.$row['Surname'].'</div>';
                    echo '<div style="width: 150px; float: left;">Available Now: ';
                    if($available)
                    {
                        echo '<span style="color: #00B900">Yes</span>';
                    }
                    else
                    {
                        echo '<span style="color: #F00">No</span>';
                    }
                    echo '</div>';
                    
                    echo '<div style="width: 150px; float: left;">In Consultation: ';
                    if(intval($row['in_consultation']) > 0)
                    {
                        echo '<span style="color: #00B900">Yes</span>';
                    }
                    else
                    {
                        echo '<span style="color: #F00">No</span>';
                    }
                    echo '</div>';
                    
                    echo '<div style="width: 180px; float: left;">Area: ';
                    echo '<span style="color: #00B900">'.$row['location'].'</span>';
                    echo '</div>';

                    echo '<div style="width: 280px; float: left;">Last consultation: ';
                    $datetime = DateTime::createFromFormat('U', $row['cons_req_time']);
                    echo $datetime->format("F d, Y h:i A");
                    echo '</div>';
                    
                    echo '</div>';
                }
                
                
                
                
                ?>
            </div>
        </div>
    </div>

    
      
    </body>
</html>