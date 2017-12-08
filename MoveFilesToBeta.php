<?php
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
  /*
$result = $con->prepare("SELECT * FROM lifepin where RawImage != ''");
$result->execute();

$count = $result->rowCount();

echo "<b>".$count."</b></br></br>";
while($row = $result->fetch(PDO::FETCH_ASSOC)){
echo $row['IdUsu']."</br>".$row['RawImage']."</br>";
if (!file_exists('/var/www/vhost1/temp/'.$row['IdUsu'].'')) {
    $make_dir = mkdir('/var/www/vhost1/temp/'.$row['IdUsu'].'', 0777, true);
}else{
$make_dir = false;
}
if($make_dir){
echo "Made directory Main</br>";
}
if (!file_exists('/var/www/vhost1/temp/'.$row['IdUsu'].'/Packages_Encrypted')) {
    $make_dir2 = mkdir('/var/www/vhost1/temp/'.$row['IdUsu'].'/Packages_Encrypted', 0777, true);
	
if($make_dir2){
echo "Made directory Packages_Encrypted</br>";
}
}else{
echo "Packages_Encrypted Directory Exists</br>";
}

if (!file_exists('/var/www/vhost1/temp/'.$row['IdUsu'].'/PackagesTH_Encrypted')) {
    $make_dir2 = mkdir('/var/www/vhost1/temp/'.$row['IdUsu'].'/PackagesTH_Encrypted', 0777, true);
	
if($make_dir2){
echo "Made directory PackagesTH_Encrypted</br>";
}
}else{
echo "Packages TH_Encrypted Directory Exists</br>";
}

if (file_exists('/var/www/vhost1/Packages_Encrypted/'.$row['RawImage'].'') && !file_exists('/var/www/vhost1/temp/'.$row['IdUsu'].'/Packages_Encrypted/'.$row['RawImage'].'')) {
$copy = copy('/var/www/vhost1/Packages_Encrypted/'.$row['RawImage'].'','/var/www/vhost1/temp/'.$row['IdUsu'].'/Packages_Encrypted/'.$row['RawImage'].'');
}else{
$copy = false;
}
if($copy){
echo "success main file</br>";
}else{
echo "fail main file</br>";
}

if (file_exists('/var/www/vhost1/Packages_Encrypted/'.$row['RawImage'].'') && !file_exists('/var/www/vhost1/temp/'.$row['IdCreator'].'/Packages_Encrypted/'.$row['RawImage'].'')) {
$copy3 = copy('/var/www/vhost1/Packages_Encrypted/'.$row['RawImage'].'','/var/www/vhost1/temp/'.$row['IdCreator'].'/Packages_Encrypted/'.$row['RawImage'].'');
}else{
$copy3 = false;
}
if($copy3){
echo "success main file DOC</br>";
}else{
echo "fail main file DOC</br>";
}

if (file_exists('/var/www/vhost1/PackagesTH_Encrypted/'.$row['RawImage'].'') && !file_exists('/var/www/vhost1/temp/'.$row['IdUsu'].'/PackagesTH_Encrypted/'.$row['RawImage'].'')) {
$copy2 = copy('/var/www/vhost1/PackagesTH_Encrypted/'.$row['RawImage'].'','/var/www/vhost1/temp/'.$row['IdUsu'].'/PackagesTH_Encrypted/'.$row['RawImage'].'');
}else{
$copy2 = false;
}
if(file_exists('/var/www/vhost1/PackagesTH_Encrypted/'.$row['RawImage'].'')){
echo "File Exists</br>";
}else{
echo "File Does Not Exist</br>";
}
if($copy2){
echo "success TH file</br></br>";
}else{
echo "fail TH file</br></br>";
}

if (file_exists('/var/www/vhost1/PackagesTH_Encrypted/'.$row['RawImage'].'') && !file_exists('/var/www/vhost1/temp/'.$row['IdCreator'].'/PackagesTH_Encrypted/'.$row['RawImage'].'')) {
$copy5 = copy('/var/www/vhost1/PackagesTH_Encrypted/'.$row['RawImage'].'','/var/www/vhost1/temp/'.$row['IdCreator'].'/PackagesTH_Encrypted/'.$row['RawImage'].'');
}else{
$copy5 = false;
}
if($copy5){
echo "success TH file DOC</br></br>";
}else{
echo "fail TH file DOC</br></br>";
}

}*/
?>