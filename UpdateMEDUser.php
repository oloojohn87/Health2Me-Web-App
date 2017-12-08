<?php
 require("environment_detail.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();
$docID = $_SESSION['MEDID'];
$queUsu = $_POST['EnviaUserid'];
if(isset($_POST['telemed']) && $_POST['telemed'] == true)
{
    if(isset($_POST['hr_rate']))
    {
        $rate = $_POST['hr_rate'];
        $result = $con->prepare("UPDATE doctors SET hourly_rate=? WHERE id=?");
		$result->bindValue(1, $rate, PDO::PARAM_STR);
		$result->bindValue(2, $queUsu, PDO::PARAM_INT);
		$result->execute();
        
        //$pusher->trigger('doctorAppointmentScheduler', 'hourly_rate_changed', $docID);
        $push->send($docID, 'doctorAppointmentScheduler', 'hourly_rate_changed');
    }
}
else
{

    $queModo = $_POST['EnviaModo']; //1: Actualizar,  2: CREAR
    $queTipoUsuario = $_POST['EnviaTipoUsuario']; //1: MEDICO,  2: PACIENTE
    
    $queDoB = $_POST['dp32'];
    //reformat
 
    list($year, $month, $day) = explode('-', $queDoB);
    $queDoB = $year.$month.$day; 
   
   
    $queGender = $_POST['gender'];
    $queOrden = $_POST['orderOB'];
    $queIdUsFIXED = $_POST['VIdUsFIXED'];
    
    $queUserName = $_POST['Vname'];
    $queUserSurname = $_POST['Vsurname'];
    //$queUserPassword = $_POST['Vpassword'];
    $queIdUsFIXEDNAME = $_POST['VIdUsFIXEDNAME'];
    
    $queUserEmail = $_POST['Vemail'];
    
    // email verification:
    // first, check if the given email is already the doctor's email
    $error = '';
    $result = $con->prepare("SELECT IdMEDEmail FROM doctors WHERE id = ?");
    $result->bindValue(1, $queUsu, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $current_doctor_email = $row['IdMEDEmail'];
    if($current_doctor_email != $queUserEmail)
    {
        // if it is don't do anything, but if it is, check if the given email is being used by another doctor
        $result = $con->prepare("SELECT id FROM doctors WHERE IdMEDEmail = ?");
        $result->bindValue(1, $queUserEmail, PDO::PARAM_STR);
        $result->execute();
        $count_rows = $result->rowCount();
        if($count_rows > 0)
        {
            // if there is at least one result, that means another doctor is already using this email. so change the update value to his current email
            $queUserEmail = $current_doctor_email;
            $error = 'This email is already being used by another user.';
        }
        
        
    }
    
    
    
    
    
    $queUserTelefono = $_POST['Vphone'];
    //strip out + and extra spaces
    $queUserTelefono  = str_replace("+", "", $queUserTelefono);
    $queUserTelefono = trim($queUserTelefono);
    $speciality = $_POST['speciality'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $Hname = null;
    $Haddress = null;
    $location = '';
    if($country == 'Select Country')
    {
        $country = '';
    }
    if($state == 'Select State')
    {
        $state = '';
    }
    if(isset($_POST["Hname"]) && strlen($_POST["Hname"]) > 0)
    {
        $Hname = $_POST["Hname"];
    }
    if(isset($_POST["Haddress"]) && strlen($_POST["Haddress"]) > 0)
    {
        $Haddress = $_POST["Haddress"];
    }
    if(strlen($state) > 0 && $state !== '')
    {
        $location = $state.', ';
    }
    if(strlen($country) > 0)
    {
        $location .= str_replace(",", ":", $country);
    }
    if($speciality == 'none')
    {
        $speciality = '';
    }
    
    
    echo "MODO: ";
    echo $queModo;
    echo "   --- Usuario: ";
    echo $queUsu;
    //c
    $index = 1;
		$res = $con->prepare("DELETE FROM certifications WHERE doc_id=?");
		$res->bindValue(1, $queUsu, PDO::PARAM_INT);
		$res->execute();
    echo 'breakpoint 1';
    
    // check if at least one certification is set to primary.
    $certification_primary_val = 0;
    $index = 1;
    while($index <= intval($_POST['certifications_max_count']))
    {
        if(isset($_POST['certification_primary_'.$index]))
        {
            $certification_primary_val += intval($_POST['certification_primary_'.$index]);
        }
        $index += 1;
    }
    // if not, set the first certification found to be the primary
    $set_first_certification_to_primary = false;
    if($certification_primary_val == 0)
    {
        $set_first_certification_to_primary = true;
    }
    $index = 1;
    $certification_count = 0;
    while($index <= intval($_POST['certifications_max_count']))
    {
        if(isset($_POST['certification_name_'.$index]))
        {
            $name = $_POST['certification_name_'.$index];
            $date = '';
            $file = '';
            if(isset($_POST['certification_date_'.$index]))
            {
                $date = $_POST['certification_date_'.$index];
            }
            echo 'breakpoint 2';
            if(isset($_POST['certification_filename_'.$index]))
            {             
                $file = 'CertificationImages/'.$_POST['certification_filename_'.$index];
                $temp_file = 'CertificationImages/'.$_POST['certification_filename_'.$index];
                $file_index = 1;
                echo 'breakpoint 3';
                while(file_exists($temp_file) && strlen($_POST['certification_filedata_'.$index]) > 0)
                {
                    
                    $temp_file = str_replace(".", "_".$file_index.".", $file);
                    $file_index += 1;
                }
                $file = $temp_file;
            }
            echo 'breakpoint 4';
            if(strlen($date) > 0)
            {
                $certification_count += 1;
                $query = "INSERT INTO certifications SET doc_id=?, name=?, start_date=?, isPrimary=";
                if($_POST['certification_primary_'.$index] == '1' || ($set_first_certification_to_primary && $certification_count == 1))
                {
                    $query .= '1';
                }
                else
                {
                    $query .= '0';
                }
                if(strlen($file) > 0)
                {
                    $query .= ", image='".$file."'";
                    if(strlen($_POST['certification_filedata_'.$index]) > 0)
                    {
                        $real_file = file_get_contents("data://".$_POST['certification_filedata_'.$index]);//base64_decode($_POST['certification_filedata_'.$index]);
                        $fp = fopen($file, 'w');
                        fwrite($fp, $real_file);
                        fclose($fp);                       
                    }
                }
				$query2 = $con->prepare($query);
				$query2->bindValue(1, $queUsu, PDO::PARAM_INT);
				$query2->bindValue(2, $name, PDO::PARAM_STR);
				$query2->bindValue(3, $date, PDO::PARAM_STR);
				$query2->execute();
            }
        }
        $index += 1;
    }
    if ($queModo == '1')
    {
        /*$q = mysql_query("UPDATE DOCTORS SET  Gender = '$queGender', OrderOB = '$queOrden', IdMEDFIXED='$queIdUsFIXED', Name = '$queUserName', Surname = '$queUserSurname', IdMEDRESERV = '$queUserPassword',  IdMEDFIXEDNAME = '$queIdUsFIXEDNAME', IdMEDEmail = '$queUserEmail', phone = '$queUserTelefono', speciality = '$speciality', location = '$location'  WHERE id='$queUsu'");*/
        
/*<<<<<<< HEAD
        $q = $con->prepare("UPDATE DOCTORS SET  Gender = ?, OrderOB = ?, IdMEDFIXED = ?, Name = ?, Surname = ?,  IdMEDFIXEDNAME = ?, IdMEDEmail = ?, phone = ?, speciality = ?, location = ?  WHERE id=?");
        $q->bindValue(1, $queGender, PDO::PARAM_INT);
		$q->bindValue(2, $queOrden, PDO::PARAM_INT);
		$q->bindValue(3, $queIdUsFIXED, PDO::PARAM_INT);
		$q->bindValue(4, $queUserName, PDO::PARAM_STR);
		$q->bindValue(5, $queUserSurname, PDO::PARAM_STR);
		$q->bindValue(6, $queIdUsFIXEDNAME, PDO::PARAM_STR);
		$q->bindValue(7, $queUserEmail, PDO::PARAM_STR);
		$q->bindValue(8, $queUserTelefono, PDO::PARAM_INT);
		$q->bindValue(9, $speciality, PDO::PARAM_STR);
		$q->bindValue(10, $location, PDO::PARAM_STR);
		$q->bindValue(11, $queUsu, PDO::PARAM_INT);
		$q->execute();
		
=======*/
        $result = $con->prepare("UPDATE doctors SET Gender = ?, OrderOB = ?, DOB = ?, Name = ?, Surname = ?,  IdMEDFIXEDNAME = ?, IdMEDEmail = ?, phone = ?, speciality = ?, location = ?, hospital_name = ?, hospital_addr = ? WHERE id = ?");
		$result->bindValue(1, $queGender, PDO::PARAM_INT);
		$result->bindValue(2, $queOrden, PDO::PARAM_INT);
        $result->bindValue(3, $queDoB, PDO::PARAM_STR);
        $result->bindValue(4, $queUserName, PDO::PARAM_STR);
        $result->bindValue(5, $queUserSurname, PDO::PARAM_STR);
        $result->bindValue(6, $queIdUsFIXEDNAME, PDO::PARAM_STR);
        $result->bindValue(7, $queUserEmail, PDO::PARAM_STR);
        $result->bindValue(8, $queUserTelefono, PDO::PARAM_STR);
        $result->bindValue(9, $speciality, PDO::PARAM_STR);
        $result->bindValue(10, $location, PDO::PARAM_STR);
        $result->bindValue(11, $Hname, PDO::PARAM_STR);
        $result->bindValue(12, $Haddress, PDO::PARAM_STR);
        $result->bindValue(13, $queUsu, PDO::PARAM_INT);
        $result->execute();
        //$q = mysql_query("UPDATE DOCTORS SET  Gender = '$queGender', OrderOB = '$queOrden', IdMEDFIXED='$queIdUsFIXED', Name = '$queUserName', Surname = '$queUserSurname',  IdMEDFIXEDNAME = '$queIdUsFIXEDNAME', IdMEDEmail = '$queUserEmail', phone = '$queUserTelefono', speciality = '$speciality', location = '$location', hospital_name = '$Hname', hospital_addr = '$Haddress'  WHERE id='$queUsu'");
        
//>>>>>>> origin/development
        //$pusher->trigger('doctorAppointmentScheduler', 'configuration_changed', $docID);
        $push->send($docID, 'doctorAppointmentScheduler', 'configuration_changed');
    }
    else
    {
       /* $q = mysql_query("UPDATE DOCTORS SET  Gender = '$queGender', OrderOB = '$queOrden', IdMEDFIXED='$queIdUsFIXED', Name = '$queUserName', Surname = '$queUserSurname', IdMEDRESERV = '$queUserPassword',  IdMEDFIXEDNAME = '$queIdUsFIXEDNAME', IdMEDEmail = '$queUserEmail', phone = '$queUserTelefono'  WHERE id='$queUsu'"); */
        
/*<<<<<<< HEAD
        $q = $con->prepare("UPDATE DOCTORS SET  Gender = ?, OrderOB = ?, IdMEDFIXED=?, Name = ?, Surname = '?,  IdMEDFIXEDNAME = ?, IdMEDEmail = ?, phone = ?  WHERE id='$queUsu'"); 
		$q->bindValue(1, $queGender, PDO::PARAM_INT);
		$q->bindValue(2, $queOrden, PDO::PARAM_INT);
		$q->bindValue(3, $queIdUsFIXED, PDO::PARAM_INT);
		$q->bindValue(4, $queUserName, PDO::PARAM_STR);
		$q->bindValue(5, $queUserSurname, PDO::PARAM_STR);
		$q->bindValue(6, $queIdUsFIXEDNAME, PDO::PARAM_STR);
		$q->bindValue(7, $queUserEmail, PDO::PARAM_STR);
		$q->bindValue(8, $queUserTelefono, PDO::PARAM_INT);
		$q->bindValue(9, $queUsu, PDO::PARAM_INT);
		$q->execute();
		
	}
=======*/
        $result = $con->prepare("UPDATE doctors SET  Gender = ?, OrderOB = ?, IdMEDFIXED = ?, Name = ?, Surname = ?,  IdMEDFIXEDNAME = ?, IdMEDEmail = ?, phone = ?, speciality = ?, location = ?, hospital_name = ?, hospital_addr = ?  WHERE id = ?");
		$result->bindValue(1, $queGender, PDO::PARAM_INT);
		$result->bindValue(2, $queOrden, PDO::PARAM_INT);
        $result->bindValue(3, $queIdUsFIXED, PDO::PARAM_STR);
        $result->bindValue(4, $queUserName, PDO::PARAM_STR);
        $result->bindValue(5, $queUserSurname, PDO::PARAM_STR);
        $result->bindValue(6, $queIdUsFIXEDNAME, PDO::PARAM_STR);
        $result->bindValue(7, $queUserEmail, PDO::PARAM_STR);
        $result->bindValue(8, $queUserTelefono, PDO::PARAM_STR);
        $result->bindValue(9, $Hname, PDO::PARAM_STR);
        $result->bindValue(10, $Haddress, PDO::PARAM_STR);
        $result->bindValue(11, $queUsu, PDO::PARAM_INT);
        $result->execute();
        //$q = mysql_query("UPDATE DOCTORS SET  Gender = '$queGender', OrderOB = '$queOrden', IdMEDFIXED='$queIdUsFIXED', Name = '$queUserName', Surname = '$queUserSurname',  IdMEDFIXEDNAME = '$queIdUsFIXEDNAME', IdMEDEmail = '$queUserEmail', phone = '$queUserTelefono', hospital_name = '$Hname', hospital_addr = '$Haddress'  WHERE id='$queUsu'"); 
    }
//>>>>>>> origin/development
    
    $salto="location:medicalConfiguration.php";
    if(strlen($error) > 0)
    {
        $salto .= "?mes=".$error;
    }
    header($salto);
}

?>