<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// include our OAuth2 Server object
require_once('bootstrap.php');

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// Handle a request for an OAuth2.0 Access Token and send the response to the client
	$server->verifyResourceRequest(OAuth2\Request::createFromGlobals());
    $server->getResponse()->send();
//    die;
//}

echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

?>
