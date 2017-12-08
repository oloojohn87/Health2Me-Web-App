<?php

set_time_limit(180);


 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
$pass=$row_enc['pass'];

//$query = mysql_query("SELECT * FROM lifepin WHERE IdUsu = 2055");
//while($row = mysql_fetch_array($query)){
//echo $row['IdUsu']."</br>".$row['RawImage']."</br>";

/*if(file_exists("temp/".$row['IdUsu']."/Packages_Encrypted/".$row['RawImage'])){
echo "RawFile Exists!</br>";
if(file_exists("temp/".$row['IdUsu']."/PackagesTH_Encrypted/".$row['RawImage']))
{
echo "TH Exists!</br>";
}else{*/

echo "USER!</br>";
$locFile = "Packages_Encrypted/";
$locFileTH = "PackagesTH_Encrypted/";
$ds = DIRECTORY_SEPARATOR; 
//$new_image_name = $row['RawImage'];
//$new_image_nameTH = $row['RawImage'];

$count=0;
$count1=0;
    
//$files = scandir($locFile);
/*foreach($files as $file) {
  //do your work here
    //echo "</p>";
    //echo $file;
    //echo "<p>";
    //$count++;
    $rawimage=$file;
    $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
    
    $rawimage='';
    if($file=='.' or $file=='..' or $extensionR=="php"){
        echo "</p>Skipping";
            echo $file;
            echo "<p>";
        continue;
    }else{
        $rawimage=$file;
    }
    
	

	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	else
	{
		$extension='png';
	}
	$filename = 'PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
    if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}else{
             echo "</p>Processing";
            echo $file;
            echo "<p>";
            decrypt_files($rawimage,$count,$pass);
            $count++;
    }
    //if($count==2)
     //   break;
}
*/


//encrypt files and store back to PackagesTH_Encrypted folder

$fixedthumpath="fixbrokenthumbs/PackagesTH_Encrypted/";
$files = scandir($fixedthumpath);
foreach($files as $file) {

    encrypt_file($file,$pass);


}

/*
function decrypt_files($rawimage,$count,$pass)
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	

	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	else
	{
		$extension='png';
	}
	$filename = 'PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{

            shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$rawimage." -out fixbrokenthumbs/Packages_Encrypted/".$rawimage);
                    

            $cadenaConvert2='convert "fixbrokenthumbs/Packages_Encrypted/'.$rawimage.'" -colorspace RGB -geometry 200 "fixbrokenthumbs/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension.'" 2>&1';
            echo "<p>IMage MAGIc ";
            echo $cadenaConvert2;	
            echo "</p>";
            $s = shell_exec($cadenaConvert2);
            if($s) echo "EXECUTE"; 
        
	}


} */


function encrypt_file($filename,$pass){

        shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -salt -in fixbrokenthumbs/PackagesTH_Encrypted/".$filename." -out fixbrokenthumbs/".$filename);
        echo "<p>files ";
        echo $filename;	
        echo "</p>";
         //shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
         //shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
         //shell_exec("rm temp/".$new_image_nameTH);



}

?>