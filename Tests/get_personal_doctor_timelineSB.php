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

$doc_id = $_POST['doc_id'];
$pat_id = $_POST['pat_id'];
$user_id = $_SESSION['BOTHID'];

$max = $_POST['max'];

$result = $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT MAX(id) FROM encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$items = array();

// add entries to the $items array
getDoctorViews($items, $pat_id, $doc_id, $user_id, $con);
getUploads($items, $pat_id, $doc_id, $user_id, $con);
getSummaryEdits($items, $pat_id, $doc_id, $user_id, $con);
getReferrals($items, $pat_id, $doc_id, $user_id, $con);
getMessages($items, $pat_id, $doc_id, $user_id, $con);
getConsultations($items, $pat_id, $doc_id, $user_id, $con);
getProbes($items, $pat_id, $doc_id, $user_id, $con);

// sort the results by date in descending form
krsort($items);

// cluster similar items that happened around the same time together
$summary_count = 0;
$summary_start_key = '';
$view_count = 0;
$view_start_key = '';
$doctor_upload_count = 0;
$doctor_upload_start_key = '';
$patient_upload_count = 0;
$patient_upload_start_key = '';
$delete_keys = array();
foreach($items as $key => $value)
{
    if($value['Type'] == 'Doctor Edited Summary')
    {
        $summary_count += 1;
        if($summary_count >= 2)
        {
            $minutes = round(abs(strtotime($key) - strtotime($summary_start_key)) / 60, 2);
            if($minutes <= 30.0) // less than 30 minutes
            {
                $items[$summary_start_key]['EndDate'] = $key;
                $items[$summary_start_key]['Description'] .= '<br/>'.$value['Description'];
            }
            array_push($delete_keys, $key);
        }
        else
        {
            $summary_start_key = $key;
        }
    }
    else
    {
        $summary_count = 0;
    }
    
    if($value['Type'] == 'Doctor Viewed Report')
    {
        $view_count += 1;
        if($view_count >= 2)
        {
            $minutes = round(abs(strtotime($key) - strtotime($view_start_key)) / 60, 2);
            if($minutes <= 30.0) // less than 30 minutes
            {
                $items[$view_start_key]['EndDate'] = $key;
                array_push($items[$view_start_key]['IdPin'], $value['IdPin'][0]);
                array_push($items[$view_start_key]['Image'], $value['Image'][0]);
                array_push($items[$view_start_key]['File'], $value['File'][0]);
            }
            array_push($delete_keys, $key);
        }
        else
        {
            $view_start_key = $key;
        }
    }
    else
    {
        $view_count = 0;
    }
    
    if($value['Type'] == 'Doctor Uploaded Report')
    {
        $doctor_upload_count += 1;
        if($doctor_upload_count >= 2)
        {
            $minutes = round(abs(strtotime($key) - strtotime($doctor_upload_start_key)) / 60, 2);
            if($minutes <= 30.0) // less than 30 minutes
            {
                $items[$doctor_upload_start_key]['EndDate'] = $key;
                array_push($items[$doctor_upload_start_key]['IdPin'], $value['IdPin'][0]);
                array_push($items[$doctor_upload_start_key]['Image'], $value['Image'][0]);
                array_push($items[$doctor_upload_start_key]['File'], $value['File'][0]);
            }
            array_push($delete_keys, $key);
        }
        else
        {
            $doctor_upload_start_key = $key;
        }
    }
    else
    {
        $doctor_upload_count = 0;
    }
    
    if($value['Type'] == 'Patient Uploaded Report')
    {
        $patient_upload_count += 1;
        if($patient_upload_count >= 2)
        {
            $minutes = round(abs(strtotime($key) - strtotime($patient_upload_start_key)) / 60, 2);
            if($minutes <= 30.0) // less than 30 minutes
            {
                $items[$patient_upload_start_key]['EndDate'] = $key;
                array_push($items[$patient_upload_start_key]['IdPin'], $value['IdPin'][0]);
                array_push($items[$patient_upload_start_key]['Image'], $value['Image'][0]);
                array_push($items[$patient_upload_start_key]['File'], $value['File'][0]);
            }
            array_push($delete_keys, $key);
        }
        else
        {
            $patient_upload_start_key = $key;
        }
    }
    else
    {
        $patient_upload_count = 0;
    }
}

// delete any duplicate entries in the $items array due to clustering
for($i = 0; $i < count($delete_keys); $i++)
{
    unset($items[($delete_keys[$i])]);
}

// if the result set has more than $max items, keep only the most recent $max items
if(count($items) > $max)
{
    array_splice($items, $max);
}


$result = array();

// the header for the result json object, this will be the first index of the array to be returned and will contain basic information about the timeline
$header = array("patient" => $pat_id, "doctor" => $doc_id, "user" => $user_id, "element" => $_POST['element']);
array_push($result, $header);

// add the final items in the $items array to the $result array to be returned, merging the key and value of the entries in the $items array
if($user_id == $doc_id || $user_id == $pat_id)
{
    foreach($items as $key => $value)
    {
        $date = new DateTime($key);
        $item_date = $date->format('M j, Y g:i A');
        if(isset($value['EndDate']))
        {
            $date = new DateTime($value['EndDate']);
            $end_date = $date->format('M j, Y g:i A');
            $value['EndDate'] = $end_date;
        }
        $add = array_merge(array("Date" => $item_date), $value);
        array_push($result, $add);
    }
}
echo json_encode($result);



/*
 *
 *  FUNCTIONS
 *
 *  These are the functions to add entries to the $items array, which is the main array to generate the timeline.
 *  The key must be the date of the entry and the value must be another associative array with any key-value pairs
 *  that are necessary to display the entry in the front end, but it must contain at least a "Type" property to 
 *  specify the type of entry.
 *
 *  For example:
 *  
 *      $items['2014-05-13 19:45:00'] = array("Type" => "Doctor Viewed Report", ...);
 *
 */


function getDoctorViews(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    $idpins = array();
    $idpins_ = array();
    $raw_images = array();

    // get all idpins from the lifepin table that are associated with this user, store them in $idpins_ and the $raw_image associative array where key is IdPin and value is the image file
    $reports = $con->prepare("SELECT IdPin,RawImage FROM lifepin WHERE IdUsu = ?");
    $reports->bindValue(1, $pat_id, PDO::PARAM_INT);
    $reports->execute();
    while($row = $reports->fetch(PDO::FETCH_ASSOC))
    {
        array_push($idpins_, $row['IdPin']);
        $raw_images[($row['IdPin'])] = $row['RawImage'];
    }

    // obtain the idpins associated with this doctor from the bpinview table
    $reports = $con->prepare("SELECT IDPIN,DateTimeSTAMP FROM bpinview USE INDEX(I2) WHERE VIEWIdMed = ? AND Content = 'Report Viewed' ORDER BY DateTimeSTAMP DESC");
    $reports->bindValue(1, $doc_id, PDO::PARAM_INT);
    $reports->execute();

    while($row = $reports->fetch(PDO::FETCH_ASSOC))
    {
        if(in_array($row['IDPIN'], $idpins_))
        {
            $image_ = pathinfo($raw_images[($row['IDPIN'])]);
            $image = $image_['filename'];
            if($image_['extension'] == 'jpg' || $image_['extension'] == 'jpeg' || $image_['extension'] == 'JPG' || $image_['extension'] == 'JPEG')
            {
                $image .= '.jpg';
            }
            else
            {
                $image .= '.png';
            }
            $items[($row['DateTimeSTAMP'])] = array("Type" => 'Doctor Viewed Report', "EndDate" => $row['DateTimeSTAMP'], "IdPin" => array($row['IDPIN']), "Image" => array($image), "File" => array($raw_images[($row['IDPIN'])]));
            
            // decrypt any necessary files, but first check if they haven't already been decrypted and if they exist
            if(!file_exists("temp/".$user_id."/PackagesTH_Encrypted/".$image))
            {
                if(file_exists("/PackagesTH_Encrypted/".$image))
                {
                    $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$image." -out temp/".$user_id."/PackagesTH_Encrypted/".$image);
                }
                else
                {
                    $items[($row['DateTimeSTAMP'])]["File"] = 'NF'; // No File
                }
            }
            if(!file_exists("temp/".$user_id."/Packages_Encrypted/".$raw_images[($row['IDPIN'])]) && file_exists("Packages_Encrypted/".$raw_images[($row['IDPIN'])]))
            {
                $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$raw_images[($row['IDPIN'])]." -out temp/".$user_id."/Packages_Encrypted/".$raw_images[($row['IDPIN'])]);
            }
        }

    }
}

function getUploads(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get members or doctors added a report
    $reports = $con->prepare("SELECT IdPin,FechaInput,IdCreator,RawImage FROM lifepin WHERE IdUsu = ? AND (IdCreator = ? OR IdCreator = ?) AND FechaInput >= DATE_SUB(NOW(), INTERVAL 6 MONTH) ORDER BY FechaInput DESC");
    $reports->bindValue(1, $pat_id, PDO::PARAM_INT);
    $reports->bindValue(2, $pat_id, PDO::PARAM_INT);
    $reports->bindValue(3, $doc_id, PDO::PARAM_INT);
    $reports->execute();

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
        $items[($row['FechaInput'])] = array("Type" => $type, "IdPin" => array($row['IdPin']), "EndDate" => $row['FechaInput'], "Image" => array($image), "File" => array($row['RawImage']));
        
        // decrypt any necessary files, but first check if they haven't already been decrypted and if they exist
        if(!file_exists("temp/".$user_id."/PackagesTH_Encrypted/".$image))
        {
            if(file_exists("/PackagesTH_Encrypted/".$image))
            {
                $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$image." -out temp/".$user_id."/PackagesTH_Encrypted/".$image);
            }
            else
            {
                $items[($row['FechaInput'])]["File"][0] = 'NF'; // No File
            }
        }
        if(!file_exists("temp/".$user_id."/Packages_Encrypted/".$row['RawImage']) && file_exists("Packages_Encrypted/".$row['RawImage']))
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$row['RawImage']." -out temp/".$user_id."/Packages_Encrypted/".$row['RawImage']);
        }
    }
}

function getSummaryEdits(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get edits that the doctor made to the patient's summary
    $summary = $con->prepare("SELECT Timestamp,Description FROM p_log WHERE IdAuthor = ? AND IdUsu = ? AND Timestamp >= DATE_SUB(NOW(), INTERVAL 6 MONTH)");
    $summary->bindValue(1, $doc_id, PDO::PARAM_INT);
    $summary->bindValue(2, $pat_id, PDO::PARAM_INT);
    $summary->execute();

    while($row = $summary->fetch(PDO::FETCH_ASSOC))
    {
        $items[($row['Timestamp'])] = array("Type" => 'Doctor Edited Summary', "EndDate" => $row['Timestamp'], "Description" => $row['Description']);
    }
}

function getReferrals(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get referrals that the doctor made for the patient
    $referral = $con->prepare("SELECT Fecha,IdMED2 FROM doctorslinkdoctors WHERE IdMed = ? AND IdPac = ? AND Fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)");
    $referral->bindValue(1, $doc_id, PDO::PARAM_INT);
    $referral->bindValue(2, $pat_id, PDO::PARAM_INT);
    $referral->execute();

    while($row = $referral->fetch(PDO::FETCH_ASSOC))
    {
        $referral_name = $con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
        $referral_name->bindValue(1, $row['IdMED2'], PDO::PARAM_INT);
        $referral_name->execute();
        $referral_name_row = $referral_name->fetch(PDO::FETCH_ASSOC);

        $items[($row['Fecha'])] = array("Type" => 'Doctor Referred', "IdDoc" => $row['IdMED2'], "Name" => $referral_name_row['Name'].' '.$referral_name_row['Surname']);
    }
}

function getMessages(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get messages between user and doctor
    $messages = $con->prepare("SELECT message_id,tofrom,content,fecha,replied FROM message_infrastructureuser WHERE (sender_id = ? OR receiver_id = ?) AND patient_id = ? AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
    $messages->bindValue(1, $doc_id, PDO::PARAM_INT);
    $messages->bindValue(2, $doc_id, PDO::PARAM_INT);
    $messages->bindValue(3, $pat_id, PDO::PARAM_INT);
    $messages->execute();

    while($row = $messages->fetch(PDO::FETCH_ASSOC))
    {
        $type = "Doctor sent message to patient";
        if($row['tofrom'] == 'to') // message is from patient to doctor
            $type = "Patient sent message to doctor";
        $items[($row['fecha'])] = array("Type" => $type, "Message" => str_replace("\n", "<br/>", $row['content']), "ID" => $row['message_id'], "Replied" => $row['replied']);
    }
}

function getConsultations(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get consultations between user and doctor
    $consultations = $con->prepare("SELECT DateTime,Type FROM consults WHERE Doctor = ? AND Patient = ? AND Status = 'Completed' AND DateTime >= DATE_SUB(NOW(), INTERVAL 6 MONTH)");
    $consultations->bindValue(1, $doc_id, PDO::PARAM_INT);
    $consultations->bindValue(2, $pat_id, PDO::PARAM_INT);
    $consultations->execute();

    while($row = $consultations->fetch(PDO::FETCH_ASSOC))
    {
        $image_ = pathinfo($row['Summary_PDF']);
        $summary_image = $image_['filename'];
        if($image_['extension'] == 'jpg' || $image_['extension'] == 'jpeg' || $image_['extension'] == 'JPG' || $image_['extension'] == 'JPEG')
        {
            $summary_image .= '.jpg';
        }
        else
        {
            $summary_image .= '.png';
        }
        $image_ = pathinfo($row['Data_File']);
        $notes_image = $image_['filename'];
        if($image_['extension'] == 'jpg' || $image_['extension'] == 'jpeg' || $image_['extension'] == 'JPG' || $image_['extension'] == 'JPEG')
        {
            $notes_image .= '.jpg';
        }
        else
        {
            $notes_image .= '.png';
        }
        $items[($row['DateTime'])] = array("Type" => $row['Type'], "Summary" => $summary_image, "Notes" => $notes_image, "Summary_File" => $row['Summary_PDF'], "Notes_File" => $row['Data_File']);
        
        // decrypt any necessary files, but first check if they haven't already been decrypted and if they exist
        if(!file_exists("temp/".$user_id."/PackagesTH_Encrypted/".$summary_image))
        {
            if(file_exists("PackagesTH_Encrypted/".$summary_image))
            {
                $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$summary_image." -out temp/".$user_id."/PackagesTH_Encrypted/".$summary_image);
            }
            else
            {
                $items[($row['DateTime'])]['Summary_File'] = 'NF'; // No File
            }
        }
        if(!file_exists("temp/".$user_id."/PackagesTH_Encrypted/".$notes_image))
        {
            if(file_exists("PackagesTH_Encrypted/".$notes_image))
            {
                $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$notes_image." -out temp/".$user_id."/PackagesTH_Encrypted/".$notes_image);
            }
            else
            {
                $items[($row['DateTime'])]['Notes_File'] = 'NF'; // No File
            }
        }
        if(!file_exists("temp/".$user_id."/Packages_Encrypted/".$row['Summary_PDF']) && file_exists("Packages_Encrypted/".$row['Summary_PDF']))
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$row['Summary_PDF']." -out temp/".$user_id."/Packages_Encrypted/".$row['Summary_PDF']);
        }
        if(!file_exists("temp/".$user_id."/Packages_Encrypted/".$row['Data_File']) && file_exists("Packages_Encrypted/".$row['Data_File']))
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$row['Data_File']." -out temp/".$user_id."/Packages_Encrypted/".$row['Data_File']);
        }
    }
}

function getProbes(&$items, $pat_id, $doc_id, $user_id, &$con)
{
    // get all probe responses from this patient to this doctor
    $probeids = array();
    $probe_ = $con->prepare("SELECT probeID FROM probe WHERE doctorID = ? AND patientID = ?");
    $probe_->bindValue(1, $doc_id, PDO::PARAM_INT);
    $probe_->bindValue(2, $pat_id, PDO::PARAM_INT);
    $probe_->execute();
    while($row = $probe_->fetch(PDO::FETCH_ASSOC))
    {
        array_push($probeids, $row['probeID']);
    }

    if(count($probeids) > 0)
    {
        $probe = $con->prepare("SELECT response,responseTIME FROM proberesponse WHERE probeID IN (".implode(",", $probeids).")");
        $probe->execute();
        while($row = $probe->fetch(PDO::FETCH_ASSOC))
        {
            // before adding the probes, first translate them from their number values to text with a span to specify a color for the text (i.e. 1 -> Very Bad)
            $probe_response = '<span style="color: #E51C22">Very Bad</span>';
            if($row['response'] == 2)
                $probe_response = '<span style="color: #E58120">Bad</span>';
            else if($row['response'] == 3)
                $probe_response = '<span style="color: #333">Normal</span>';
            else if($row['response'] == 4)
                $probe_response = '<span style="color: #22AEFF">Good</span>';
            else if($row['response'] == 5)
                $probe_response = '<span style="color: #54bc00">Very Good</span>';
            
            $items[($row['responseTIME'])] = array("Type" => 'Probe', "Response" => $probe_response);
        }
    }
}
?>
