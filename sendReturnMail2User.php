<?php

require("environment_detailForLogin.php");
require("NotificationClass.php");
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


$filenames = $_GET['filenames'];
$filenameReportDate = $_GET['filenameReportDate'];
$filenameReportType = $_GET['filenameReportType'];
$emailUser = $_GET['emailUser'];
$emailDoc = $_GET['emailDoc'];

$notifications = new Notifications();

$tipo = array();

////echo '******* ReportType at 0: '.$filenameReportType[0];
// Retrieving the value of type set by user from filenameReportType, finding the agrup from the tipopin table and setting it to tipo 

for($i = 0; $i < sizeof($filenameReportType);$i++)
{
  if($filenameReportType[$i] == "OTHER TESTS")
  {
    
    array_push($tipo,70);
  }
  
  else
  {
      //$groupname = "'".$filenameReportType[$i]."'"; 
      //$query = mysql_query("select agrup from tipopin where groupname = $groupname");
      //$row = mysql_fetch_array($query);
      if($filenameReportType[$i] == "LABORATORY TESTS")
      {
          array_push($tipo,20);
      }
      
      else
      {
      
       if($filenameReportType[$i] == "SUMMARY AND DEMOGRAPHICS")
       {
          array_push($tipo,60);
       }
        
       else
       {
         if($filenameReportType[$i] == "DOCTORS NOTES")
         {
           array_push($tipo,30);
         }
        else
        {
         if($filenameReportType[$i] == "LIVE DATA")
         {
            array_push($tipo,74);         
         }
        else
        {
              if($filenameReportType[$i] == "PATIENTS NOTES")
              {
                array_push($tipo,76);
              }
            else
            {
               if($filenameReportType[$i] == "IMAGING TESTS")
               {
                 array_push($tipo,1);
               }
            }
        }
        }
       }
      }
  }

}

//echo '******* ReportType at 0: '.$tipo[0];

$query1 = $con->prepare("select email,identif from usuarios where email = ?");
$query1->bindValue(1, $emailUser, PDO::PARAM_STR);
$query1->execute();

$row1 = $query1->fetch(PDO::FETCH_ASSOC);

$patient_id = $row1['identif'];

$query2 = $con->prepare("select id from doctors where idmedemail = ?");
$query2->bindValue(1, $emailDoc, PDO::PARAM_STR);
$query2->execute();

$row2 = $query2->fetch(PDO::FETCH_ASSOC);
$doctorId = $row2['id'];



for ($i = 0 ; $i < count($filenames) ; $i++)
{
    echo $filenameReportDate[$i];
    
    echo "File Report Type is".$filenameReportType[$i];
    echo is_string($filenameReportDate[$i]);
    //$splittedStr = explode("-",$filenameReportDate[$i]);
     
    //echo "SendreturnMail2User".$filenameReportDate[$i];
    //$fechaTempo = "'".$splittedStr[2]."/".$splittedStr[1]."/".$splittedStr[0]."'";
    
     //echo $i.' '.$fechaTempo;
    
     // $date = date('Y-m-d');
    
      //echo $date;
    echo "Filename is ".$filenames[$i];
    echo "Tipo is ".$tipo[$i]." Next is idpin    ";
    
    $query = $con->prepare("select max(idpin) as maxId from lifepin where idusu = ? and orig_filename = ?");
	$query->bindValue(1, $patient_id, PDO::PARAM_INT);
	$query->bindValue(2, $filenames[$i], PDO::PARAM_STR);
	$query->execute();
	
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $idpin = $row['maxId'];
    
    echo $idpin;
     $query = $con->prepare("update lifepin set Fecha = ?, Tipo=?,IdCreator = ? where IdPin = ?");
	 $query->bindValue(1, $filenameReportDate[$i], PDO::PARAM_STR);
	 $query->bindValue(2, $tipo[$i], PDO::PARAM_INT);
	 $query->bindValue(3, $doctorId, PDO::PARAM_INT);
	 $query->bindValue(4, $idpin, PDO::PARAM_INT);
	 $query->execute();
    
    if($i == 0)
    {
        $notifications->add('REPUPL', $doctorId, true, $patient_id, false, null);
    }
	 
    
     unlink("C:\\xampp\\htdocs\\dropzone_uploads\\temporaryForFilePreview\\".$filenames[$i]);
}



?>