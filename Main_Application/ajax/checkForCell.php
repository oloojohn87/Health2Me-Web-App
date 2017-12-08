<?php
ini_set('max_execution_time', 300);
require_once("environment_detailForLogin.php");

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
  
$query1 = $con->prepare("SELECT * FROM crm WHERE phone_type='' LIMIT 100");
	  $query1->execute();
	  while($user_data_array = $query1->fetch(PDO::FETCH_ASSOC)){

$phone = $user_data_array['phone'];
$id = $user_data_array['id'];

$phone = preg_replace('~\(~', '', $phone);
$phone = preg_replace('~\)~', '', $phone);
$phone = preg_replace('/\s+/', '', $phone);
$phone = preg_replace('~\-~', '', $phone);

//echo $phone;

$userAgent = "Googlebot/2.1 (http://www.googlebot.com/bot.html)";
$target_url = "www.phonedetective.com/PD.aspx?_act=results&_pho=".$phone."";

//echo $target_url;

// make the cURL request to $target_url
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$html= curl_exec($ch);
if (!$html) {
	echo "<br />cURL error number:" .curl_errno($ch);
	echo "<br />cURL error:" . curl_error($ch);
	exit;
}
//echo $html."<p>";
// parse the html into a DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($html);



// grab all the on the page
$xpath = new DOMXPath($dom);
$x=1;
foreach($xpath->query("//html//text()[1]") as $node){
//echo "</br>".$node->nodeValue;
if($x==11){
$phone_type=$node->textContent;
//echo $phone_type."<hr>";
}
$x++;


}



if($phone_type != ""){
$query = $con->prepare("UPDATE crm SET phone_type=? WHERE id=?");
$query->bindValue(1, $phone_type, PDO::PARAM_STR);
$query->bindValue(2, $id, PDO::PARAM_INT);
	  $query->execute();
//	  sleep(1);
}
//echo "</br>";

}
?>
