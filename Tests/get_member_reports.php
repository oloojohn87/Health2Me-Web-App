<?php
session_start();
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}


$pat_id = $_POST['pat_id'];
$user_id = $_SESSION['BOTHID'];

$result = $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT MAX(id) FROM encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$items = array();

// add entries to the $items array
//getMemberNotifications($items, $pat_id, $con);
//$items = [1,2,3,4];
//getMemberReports($items, $pat_id, $con);
getUploads($items, $pat_id, $con);

// sort the results by date in descending form
krsort($items);

echo json_encode($items);



/*
 *
 *  FUNCTIONS
 *
 *  Example:
 *  
 *      $items['38'] = array("Type" => "3", ...);
 *
 */

function getMemberReports(&$items, $pat_id, &$con)
{
    // get all probe responses from this patient to this doctor
    $episodes = array();
    //$episodes_ = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? AND markfordelete != 1 ORDER BY Fecha");
    $episodes_ = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? AND markfordelete IS NULL ORDER BY Fecha ");
    $episodes_->bindValue(1, $pat_id, PDO::PARAM_INT);
    $episodes_->execute();
    $n = 0;
    while($row = $episodes_->fetch(PDO::FETCH_ASSOC))
    {
       $items[($n)] = array("id" => $row['IdPin'], "date" => $row['Fecha']);
       $n++;
    }

}

function getUploads(&$items, $pat_id, &$con)
{
    // get members or doctors added a report
    //$reports = $con->prepare("SELECT IdPin,FechaInput,IdCreator,RawImage FROM lifepin WHERE IdUsu = ? AND (IdCreator = ? OR IdCreator = ?) AND FechaInput >= DATE_SUB(NOW(), INTERVAL 6 MONTH) ORDER BY FechaInput DESC");
    $reports = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ?  AND markfordelete IS NULL ORDER BY Fecha ");
    $reports->bindValue(1, $pat_id, PDO::PARAM_INT);
    //$reports->bindValue(2, $pat_id, PDO::PARAM_INT);
    //$reports->bindValue(3, $doc_id, PDO::PARAM_INT);
    $reports->execute();

    $n=0;
    while($row = $reports->fetch(PDO::FETCH_ASSOC))
    {
        $type = 'Doctor Uploaded Report';
        if($row['IdCreator'] == $pat_id)
        {
            $type = 'Patient Uploaded Report';
        }
        $image_ = pathinfo($row['RawImage']);
        $image = $image_['filename'];
        if($image_['extension'] == 'jpg' || $image_['extension'] == 'jpeg' || $image_['extension'] == 'JPG' || $image_['extension'] == 'JPEG')
        {
            $image .= '.jpg';
        }
        else
        {
            $image .= '.png';
        }
        //$items[($row['FechaInput'])] = array("id" => $row['IdPin'], "date" => $row['Fecha'], "Type" => $type, "IdPin" => array($row['IdPin']), "EndDate" => $row['FechaInput'], "Image" => array($image), "File" => array($row['RawImage']));
        $items[($n)] = array("id" => $row['IdPin'], "date" => $row['Fecha'], "Type" => $type, "IdPin" => $row['IdPin'], "EndDate" => $row['FechaInput'], "Image" => array($image), "File" => array($row['RawImage']));
        $n++;
        
        // decrypt any necessary files, but first check if they haven't already been decrypted and if they exist
        if(!file_exists("temp/".$user_id."/PackagesTH_Encrypted/".$image))
        {
            if(file_exists("/PackagesTH_Encrypted/".$image))
            {
                $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$image." -out temp/".$user_id."/PackagesTH_Encrypted/".$image);
            }
            else
            {
                //$items[($row['FechaInput'])]["File"][0] = 'NF'; // No File
            }
        }
        if(!file_exists("temp/".$user_id."/Packages_Encrypted/".$row['RawImage']) && file_exists("Packages_Encrypted/".$row['RawImage']))
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$row['RawImage']." -out temp/".$user_id."/Packages_Encrypted/".$row['RawImage']);
        }
    }
}



?>
