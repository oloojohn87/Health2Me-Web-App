<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// include our OAuth2 Server object
require_once('bootstrap.php');

// Handle a request for an OAuth2.0 Access Token and send the response to the client
//$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
$userid = 1;

if (!$token = $server->grantAccessToken($request, $response)) {
$response->send();
die();
}

var_dump($token);
?>
