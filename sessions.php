<?php
require("environment_detail.php");
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);
//$id = session_id();
$post_var="";
if (isset($_GET['session_var'])) {
    $post_var = $_GET['session_var'];
    $session_var = $_SESSION[$post_var];

    echo $session_var;
}  
if (isset($_GET['domain'])) {
    
    echo $domain;
        
} 



?>