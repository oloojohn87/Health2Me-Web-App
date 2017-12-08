<?php 
ini_set('display_errors',1);  
error_reporting(E_ALL);
?>
<html>
 <head>
  <title>E-Prescribe</title>
 </head>
 <body>
 <?php 

 /*require("environment_detail.php");
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
  }		*/
/////////////////////////////THIS HANDLES USER DATA/DOCTOR DATA/////////////////////////
  /*$member = $_GET['IdUsu'];
  $doc = $_GET['medid'];
  $cname = $_GET['cname'];
  $pname = $_GET['pname'];
  $address1 = $_GET['address1'];
  $address2 = $_GET['address2'];
  $city = $_GET['city'];
  $state = $_GET['state'];
  $zip = $_GET['zip'];
  $phone = $_GET['phone'];
  $fax = $_GET['fax'];
  $loc_id = $_GET['id'];
  $practice = $_GET['practice'];
  $practice_id = $_GET['practiceid'];
  
  
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $member, PDO::PARAM_INT);
$result->execute();
  
$row = $result->fetch(PDO::FETCH_ASSOC);
  
$result2 = $con->prepare("SELECT * FROM doctors where id=?");
$result2->bindValue(1, $doc, PDO::PARAM_INT);
$result2->execute();
  
$row2 = $result2->fetch(PDO::FETCH_ASSOC);
  */
  
  
  
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//classes and SOAP Client were auto generated using WSDL2PHP, an open source project: http://wsdl2php.sf.net

	// CHANGE the URL to be your assigned testing server or the live server once your app is ready to go live

//require_once 'nusoap-0.9.5/lib/nusoap.php';
require_once 'TransactionPortalService.php';


/*$parameters = new DirectoryListStatementRequest();
$parameters->User = 'p18jamesh';
$parameters->Pass = '7499p18';
$parameters->Dsn = 'something';
$parameters->SourceId = 'SC';
$parameters->ResponseType = 'ALL';
$parameters->SubmitterID = 'PBYSUBID';*/

$java = "java:com.hpTransactionPortal.dataobjects";
$dirlistreq = new DirectoryListRequest();

$dirlistreq->User = new SoapVar('p18jamesh', XSD_STRING, null, null, null, $java);
$dirlistreq->Pass = new SoapVar('7499p18', XSD_STRING, null, null, null, $java);
$dirlistreq->Dsn = new SoapVar('p18', XSD_STRING, null, null, null, $java);
$dirlistreq->ResponseType = new SoapVar('ALL', XSD_STRING, null, null, null, $java);
$dirlistreq->SourceId = new SoapVar('HCC', XSD_STRING, null, null, null, $java);

$parameters = new requestDirectoryList();
    
$parameters->dirListReq = $dirlistreq;

$namespace = 'https://transportal.emedixus.com/TransactionPortal/TransactionPortalService?WSDL';

//var_dump(shell_exec('openssl s_client -ssl3 -connect transportal.emedixus.com:443'));

$opts = stream_context_create([
        'ssl'   => [    
                'verify_peer' => false,
                'allow_self_signed' => true,
                'ciphers' => 'RC4-MD5'
        ]
]
        );

$options = array(
        'trace' => true, 
        'exceptions' => true,      
        'cache_wsdl' => 0,
        //'stream_context' => $opts,
        'ssl_method' => SOAP_SSL_METHOD_SSLv3,
        'proxy_host' => 'https://transportal.emedixus.com',
        'proxy_port' => 443,
        'proxy_login' => 'p18jamesh',
        'proxy_password' => '7499p18',
    );

//$client = new TransactionPortalService($namespace, array('trace' => 1, 'cache_wsdl' => 0));

//libxml_disable_entity_loader(false);


try {    
    $client = new TransactionPortalService("eMedix_WSDL.wsdl");
    //'eMedix_WSDL.wsdl'
    var_dump($client->__getFunctions());
    
     
} catch(Exception $e) {

    echo "Failed: ".$e->getMessage();
}



/*
       


    $dirListReq = new StdClass;
    $dirListReq->User = new SoapVar('p18jamesh', XSD_STRING, null, null, null, $java);
    $dirListReq->Pass = new SoapVar('7499p18', XSD_STRING, null, null, null, $java);
    $dirListReq->Dsn = new SoapVar('p18', XSD_STRING, null, null, null, $java);
    $dirListReq->ResponseType = new SoapVar('ALL', XSD_STRING, null, null, null, $java);
    $dirListReq->SourceId = new SoapVar('HCC', XSD_STRING, null, null, null, $java);
      
    $params = new StdClass;

    $params->dirListReq = new SoapVar($dirListReq, SOAP_ENC_OBJECT, null, 'https://TransactionPortal/TransactionPortalService');*/

//$res = $client->requestDirectoryList($parameters);
//var_dump($res);

echo("<pre>"); //to format it legibly on your screen
var_dump($client->__getLastRequestHeaders()); //the headers of your last request
var_dump($client->__getLastRequest()); //your last request 
var_dump($client->__getLastResponse());

//var_dump($client->requestDirectoryListStatements($parameters));



                         /*array('login' => 'p18jamesh',
                                     'password' => '7499p18',
                                     'location' => 'http://schemas.xmlsoap.org/wsdl/soap/',
                                     'uri' => 'https://TransactionPortal/TransactionPortalService',
                                     'style' => SOAP_DOCUMENT,
                                     'use' => SOAP_LITERAL
                                    ));*/
//$client->setCredentials("p18jamesh", "7499p18");

//$result = $client->call("ResponseRequest", $parameters);
//$client->call("requestDirectoryList", $parameters); 

/*if($error) {
    echo "<h2>Constructor error</h2><pre>".$error."</pre>";
}



if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
} else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo "<h2>Main</h2>";
        echo $result;
    }
}
 
// show soap request and response
echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";*/

 ?> 

 </body>
</html>