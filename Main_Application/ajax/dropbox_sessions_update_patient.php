<?php
/*KYLE
 session_start();

require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$medid = $_SESSION['MEDID'];
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
 mysql_select_db("$dbname")or die("cannot select DB");

$session_name = $_POST["session"];
$names = explode(" ", $_POST["name"]);

$fixed = $_POST["id"];

$query = "SELECT * FROM usuarios WHERE Name='".$names[0]."' AND Surname='".$names[1]."' AND IdUsFIXED='".$fixed."'";
$res = mysql_query($query);
if ($row = mysql_fetch_row($res, MYSQL_ASSOC))
{
    $idusfixed = $row["IdUsFIXED"];
    $IdUsFIXEDNAME = $row["IdUsFIXEDNAME"];;
    $query1 = "SELECT * FROM dropbox_sessions_files WHERE id=".$medid." AND session_name='".$session_name."'";
    $res1 = mysql_query($query1);
    while($row1 = mysql_fetch_row($res1, MYSQL_ASSOC))
    {
        $query2 = "UPDATE lifepin SET IdUsFIXED='".$idusfixed."',IdUsFIXEDNAME='".$IdUsFIXEDNAME."' WHERE IdPin='".$row1['id_pin']."'";
        $res2 = mysql_query($query2);
    }   
    echo "Success";
}
else
{
    echo "Unable to Find Users";
}

?>