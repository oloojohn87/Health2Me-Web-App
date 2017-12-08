<?php
header("Access-Control-Allow-Origin: *");
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
  $cadena = '';
  $UserID = $_GET['id'];
 
 
							date_default_timezone_set ("GMT");
							$date = new DateTime('now');
                        
                            $doc_result = $con->prepare("SELECT Name,Surname,phone,location FROM doctors WHERE id=?");
							$doc_result->bindValue(1, $UserID, PDO::PARAM_INT);
							$doc_result->execute();

                            if($doc_result->rowCount() > 0)
                            {
                                $doc_row = $doc_result->fetch(PDO::FETCH_ASSOC);
                                $result2 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
								$result2->bindValue(1, $UserID, PDO::PARAM_INT);
								$result2->execute();
								
                                $found = 'no';
                            
                                while(($row2 = $result2->fetch(PDO::FETCH_ASSOC)))
                                {
								
                                    $start = new DateTime($row2['week'].' '.$row2['start_time']);
                                    $end = new DateTime($row2['week'].' '.$row2['end_time']);
                                    $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                                    $time_interval = new DateInterval('PT'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 8, 2)).'H'.intval(substr((htmlspecialchars($row2['timezone'])), strlen((htmlspecialchars($row2['timezone']))) - 5, 2)).'M');
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
                                        $found = 'yes';
                                        //break;
										
                                    }else{
									
									if($found != 'yes'){
									$found = 'no';
									//break;
									}
									}
                                    
                                }
								}
								
								//echo $found;
			if($found == 'no'){		
			$cadena.='
			{
	        "show":"no"
	        }';    
			}else{
			$cadena.='
			{
	        "show":"yes"
	        }';  
			}
			
$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 
?>
