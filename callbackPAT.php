<?php
//    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $IdRef = $_GET['IdRef'];	

	$IdDocOrigin = $_GET['IdDocOrigin'];

    $NameDoctor = $_GET['NameDoctor'];
    $SurnameDoctor = $_GET['SurnameDoctor'];
    
    $NameDoctorRequest = $_GET['NameDoctorOrigin'];
    $SurnameDoctorRequest = $_GET['SurnameDoctorOrigin'];

    $NamePatient = $_GET['NamePatient'];
    $SurnamePatient = $_GET['SurnamePatient'];

    $IdProtocolo = 1;



/*
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctorslinkdoctors"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	


$sql="SELECT * FROM protocolopautas where IdUser='$IdPac' AND IdProtocolo='$IdProtocolo'";
$result=mysql_query($sql);

$row = mysql_fetch_array( $result );

$Enfermedad = $row['Enfermedad'];

$sql="SELECT * FROM usuarios where Identif='$IdPac'";
$result=mysql_query($sql);
$row = mysql_fetch_array( $result );
$Nombre = $row['Name'];
*/

?>
<Response>
    <Say language="en" voice="woman">Health 2 Me Patient Portal</Say>
    <Say language="en" voice="woman"></Say>
    <Say language="en" voice="woman"></Say>
    
    <Gather action="handle-user-inputPAT.php?IdRef=<?php echo $IdRef?>" numDigits="1">
        <Say language="en" voice="man" >Hello,  <?php echo ''; echo $NamePatient; ?>.</Say>
        <Say language="en" voice="man" >This is a request from  <?php echo 'Doctor'; echo $NameDoctorRequest; echo $SurnameDoctorRequest ?>.</Say>
        <Say language="en" voice="man">To connect with you through Health2Me portal.</Say>
        <Say language="en" voice="man">Please press 1 to Accept request or press 0 to reject.</Say>
        <Say language="en" voice="man"></Say>
    </Gather>

    <Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>
    <Say language="en" voice="woman">Gretings from Health 2 Me.</Say>
    <Say language="en" voice="woman">Bye!</Say>
</Response>