<?php session_start();
## This file provides basic dropbox Authentication for the new cloud channel. This is not a final version and work need to be 
## to integrate it with initial account creation process.
## Include the Dropbox SDK libraries
require_once "dropbox-sdk/Dropbox/autoload.php";
require("environment_detail.php");

use \Dropbox as dbx;

$medID=$_GET['uniqueID'];
$EmailID=$_GET['EmailID'];
$appInfo = dbx\AppInfo::loadFromJsonFile("config.json");

$dbxConfig = new dbx\Config($appInfo, "PHP-Example/1.0");

$webAuth = new dbx\WebAuth($dbxConfig);
$callbackUrl = $domain."/CloudChannelReceiver.php?medID=$medID&EmailID=$EmailID";
list($requestToken, $authorizeUrl) = $webAuth->start($callbackUrl);
$_SESSION['dropbox-request-token'] = $requestToken->serialize();

header("Location: $authorizeUrl");


?>
