<?php
error_reporting(E_ALL);
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$id_doc=$_GET['idDoc'];

$Array = array();
$sortedArray = array();
if(isset($_GET['notifIDs']))
{
    $String=$_GET['notifIDs'];

    $Array = explode(", ", $String);
    $Array = array_filter($Array);
}

if(!empty($id_doc)) {
    
    
    if (!empty($Array) > 0){
        $group = $_GET['group'];
        $upd = $con->prepare("UPDATE doctors SET notification_group = ? WHERE id = ?");
        $upd->bindValue(1, $group, PDO::PARAM_INT);
        $upd->bindValue(2, $id_doc, PDO::PARAM_INT);
        $upd->execute();
        
        $del = $con->prepare("DELETE FROM notification_docSetting WHERE idMed = ?");
        $del->bindValue(1, $id_doc, PDO::PARAM_INT);
        $del->execute();
        
        foreach ($Array as $elem) {
            $category = substr($elem, 0, 3); //first 3 characters are the category
            $method = substr($elem, 3); //and the rest is the method
            if(array_key_exists($category, $sortedArray)) {
                $origValMethod = $sortedArray[$category]; //get the previous method value
                $sortedArray[$category] = $origValMethod.', '.$method; //append the new method along with the previous value
            }
            else $sortedArray[$category] = $method;
        }


        $sql = "INSERT INTO notification_docSetting (idMed, category, methods) VALUES ";
        $insertQuery = array();
        $insertData = array();

        foreach($sortedArray as $key => $value) {
            $insertQuery[] = '(?,?,?)';
            $insertData[] = $id_doc;
            $insertData[] = $key; 
            $insertData[] = $value; 
        }

        if (!empty($insertQuery)) {
            $sql .= implode(', ', $insertQuery);
            $sql .= " ON DUPLICATE KEY UPDATE methods = VALUES(methods)";
            try {
                $q = $con->prepare($sql);
                $q->execute($insertData);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    else if(isset($_GET['display'])) {
        try {
            $q = $con->prepare('SELECT category, methods FROM notification_docSetting WHERE idMed = ?');
            $q->bindValue(1, $id_doc, PDO::PARAM_INT);
            $q->execute();
            
            $group = $con->prepare('SELECT notification_group FROM doctors WHERE id = ?');
            $group->bindValue(1, $id_doc, PDO::PARAM_INT);
            $group->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            $result = array();
            while($row = $q->fetch(PDO::FETCH_ASSOC)) {
                $result[$row['category']] = $row['methods'];
            }
            $group_row = $group->fetch(PDO::FETCH_ASSOC);
            $result['group'] = $group_row['notification_group'];
            echo json_encode($result);
        }
    }
    else
    {
        $group = $_GET['group'];
        $upd = $con->prepare("UPDATE doctors SET notification_group = ? WHERE id = ?");
        $upd->bindValue(1, $group, PDO::PARAM_INT);
        $upd->bindValue(2, $id_doc, PDO::PARAM_INT);
        $upd->execute();
        
        $del = $con->prepare("DELETE FROM notification_docSetting WHERE idMed = ?");
        $del->bindValue(1, $id_doc, PDO::PARAM_INT);
        $del->execute();
    }
}
?>