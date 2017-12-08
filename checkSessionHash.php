<?php
class checkSessionHash{

//THIS HOLDS INFO FOR SESSION HASHING
private $hash_doctor;
private $id_doctor;
private $hash_member;
private $id_member;


//THIS CHECKS FOR DOC IF THERE IS HASH OR NOT AND ASSIGNS TO VARIABLES TO CHECK DATABASE LATER
public function setters(){
session_start();

if(isset($_SESSION['MEDID']) && isset($_SESSION['session_hash_doctor'])){
$this->id_doctor = $_SESSION['MEDID'];
$this->hash_doctor = $_SESSION['session_hash_doctor'];

}
if(isset($_SESSION['UserID']) && isset($_SESSION['session_hash_member'])){
$this->id_member = $_SESSION['UserID'];
$this->hash_member = $_SESSION['session_hash_member'];

}

if(isset($_SESSION['MEDID']) && !isset($_SESSION['session_hash_doctor'])){
unset($_SESSION['MEDID']);
die('You do not have a hash for your doctor session.');
}

if(isset($_SESSION['UserID']) && !isset($_SESSION['session_hash_member']) && !isset($_SESSION['MEDID'])){
unset($_SESSION['UserID']);
die('You do not have a hash for your member session.');
}

if(!isset($_SESSION['MEDID']) && !isset($_SESSION['UserID'])){
die("You have not logged in.");
}

//THIS CREATES SESSION HASH FOR AJAX CALLS WITH MOBILE APP....
if(isset($_GET['session_hash_doctor'])){
$this->hash_doctor = $_GET['session_hash_doctor'];
}
//THIS CREATES SESSION HASH FOR AJAX CALLS WITH MOBILE APP....
if(isset($_GET['session_hash_member'])){
$this->hash_doctor = $_GET['session_hash_member'];
}
//END OF SETTERS
}
///////////////////////////////////////////////////////////////////////////////////////////////

//THIS VERIFIES THAT THE HASH MATCHES SESSION//////////////////////////////////////////////
public function hashChecker($IgnoreMedID){
$env_var_db =array("dbhost"=>"health2.me",  
"dbname"=>"monimed4",
"dbuser"=>"monimed",
"dbpass"=>"ardiLLA98");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 require_once("displayExitClass.php");
 
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
//THIS SECTIONS CHECK DOC HASH......
if(isset($this->id_doctor) && isset($this->hash_doctor) && $IgnoreMedID != 'yes'){

$doc_id = $this->id_doctor;
$result = $con->prepare("SELECT session_hash FROM doctors where id=?"); 
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['session_hash'] != $this->hash_doctor){
$result = $con->prepare("INSERT INTO hacking_attempts SET type='DOCTOR', id_hacker = ?, hash = ?, datetime=NOW(), location = ?"); 
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->bindValue(2, $this->hash_doctor, PDO::PARAM_STR);
$result->bindValue(3, $_SERVER["REQUEST_URI"], PDO::PARAM_STR);
$result->execute();

//$exit_display = new displayExitClass();

//$exit_display->displayFunction(3);
//die();
}
//THIS SECTION CHECKS MEMBER HASH.......
}
if(isset($this->id_member) && isset($this->hash_member)){

$mem_id = $this->id_member;
$result = $con->prepare("SELECT session_hash FROM usuarios where Identif=?"); 
$result->bindValue(1, $mem_id, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

if($row['session_hash'] != $this->hash_member){
$result = $con->prepare("INSERT INTO hacking_attempts SET type='MEMBER', id_hacker = ?, hash = ?, datetime=NOW(), location=?"); 
$result->bindValue(1, $mem_id, PDO::PARAM_INT);
$result->bindValue(2, $this->hash_member, PDO::PARAM_STR);
$result->bindValue(3, $_SERVER["REQUEST_URI"], PDO::PARAM_STR);
$result->execute();

//$exit_display = new displayExitClass();

//$exit_display->displayFunction(3);
//die();
}
//END OF IF STATEMENT
}

//GENERATE NEW HASH......
$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$str = '';
$length = 255;
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count-1)];
    }
$new_hash_doctor = $str;

//GENERATE NEW HASH......
$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$str = '';
$length = 255;
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count-1)];
    }
$new_hash_member = $str;

//ADD NEW HASH TO DATABASE FOR DOCTOR....
if(isset($this->id_doctor) && isset($this->hash_doctor) && $IgnoreMedID != 'yes'){

$doc_id = $this->id_doctor;
$result = $con->prepare("UPDATE doctors SET session_hash = ? where id=?"); 
$result->bindValue(1, $new_hash_doctor, PDO::PARAM_STR);
$result->bindValue(2, $doc_id, PDO::PARAM_INT);
$result->execute();

//SETS NEW HASH SESSION....
$_SESSION['session_hash_doctor'] = $new_hash_doctor;

//ADD NEW HASH TO DATABSE FOR MEMBER.....
}
if(isset($this->id_member) && isset($this->hash_member)){

$mem_id = $this->id_member;
$result = $con->prepare("UPDATE usuarios SET session_hash = ? where Identif=?"); 
$result->bindValue(1, $new_hash_member, PDO::PARAM_STR);
$result->bindValue(2, $mem_id, PDO::PARAM_INT);
$result->execute();

//SETS NEW HASH SESSION....
$_SESSION['session_hash_member'] = $new_hash_member;

}


//END OF HASHCHECKER
}
///////////////////////////////////////////////////////////////////////////////////////////////////
  
  //END OF CLASS
  }
?>