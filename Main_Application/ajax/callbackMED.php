<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $IdPac = $_GET['IdPac'];	
    
	
    $IdProtocolo = 1;


$host="54.243.39.237"; // Host name
$username="jvinals"; // Mysql username
$password="ardiLLA98"; // Mysql password
$db_name="monimed"; // Database name
// Connect to server and select databse.
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$sql=$con->prepare("SELECT * FROM protocolopautas where IdUser=? AND IdProtocolo=?");
$sql->bindValue(1, $IdPac, PDO::PARAM_INT);
$sql->bindValue(2, $IdProtocolo, PDO::PARAM_INT);

$result=$sql->execute();

$row = $sql->fetch(PDO::FETCH_ASSOC);

$Enfermedad = $row['Enfermedad'];

$sql=$con->prepare("SELECT * FROM usuarios where Identif=?");
$sql->bindValue(1, $IdPac, PDO::PARAM_INT);

$result=$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC);
$Nombre = $row['Name'];


?>
<Response>
    <Say language="es" voice="woman">Sistema de Seguimiento de Salud del Dr. Gonzalez Pérez (colegiado 43.947)</Say>
    <Say language="en" voice="woman">Remote Health</Say>
    <Say language="es" voice="woman">Vitas</Say>
    
    <Gather action="handle-user-input.php?IdPac=<?php echo $IdPac?>" numDigits="1">
        <Say language="es" voice="man" >Hola,  <?php echo $Nombre ?>.</Say>
        <Say language="es" voice="man" >Por favor, seleccione cómo está de su <?php echo $Enfermedad ?> puntuando de 1 a 5.</Say>
        <Say language="es" voice="man">Siendo 1 el peor estado posible.</Say>
        <Say language="es" voice="man">Y 5 el mejor.</Say>
        <Say language="es" voice="man">Para repetir, pulse 0.</Say>
    </Gather>

    <Say language="es" voice="woman">Gracias!. Su información ha sido grabada y estará a disposición de su médico en breve.</Say>
    <Say language="es" voice="woman">Saludos de Plazasalud 24.</Say>
    <Say language="es" voice="woman">Adios!</Say>
</Response>