<?php
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

$queUsu = $_GET['Usuario'];

$result = $con->prepare("SELECT * FROM messages WHERE ToId=? LIMIT 10");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

echo '<div class="comment-info">';
echo '<div class="comment-avatar"><img src="images/users/1.jpg" alt=""></div>';
echo '<div class="comment-text"><strong>Alan Cook:</strong> Wow. It\'s a great article!';
echo '<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>';
echo '</div>';
echo '<div class="clear"></div>';
echo '</div>';

}

echo '</tbody></table>';    
    

?>