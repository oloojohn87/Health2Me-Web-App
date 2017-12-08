<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

//Below SQL queries were commented by Pallab
/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	*/

// Connect to server and select databse.
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$queUsu = $_GET['Usuario'];
$queMed = $_GET['IdMed'];
//$NReports = $_GET['NReports'];
//$queUsu = 32;

$cadena='{
    "timeline":
    {
        "headline":"Medical Reports Timeline",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"'.$domain.'/images/ReportsGeneric.png",
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");
//Below SQl quereis were commented by Pallab
/*$result = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$queUsu' ORDER BY FechaInput DESC LIMIT 50");

$numero=mysql_num_rows($result) ;*/

$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? ORDER BY FechaInput DESC LIMIT 50");
$result->bindValue(1,$queUsu,PDO:PARAM_INT);
$result->execute();
$numero = $result->rowCount();

$n=0;

while ($row = $result->fetch(PDO::FETCH_ASSOC)) //Replaced $row = mysql_fetch_array($result)
{

$cadena = $cadena.'
            {
                "startDate":"'.$row['Fecha'].'",
                "endDate":"'.$row['Fecha'].'",
                "headline":"Headline Goes Here",
                "text":"<p>Body text goes here, some HTML is OK</p>",
                "tag":"This is Optional",
                "asset": {
                    "media":"'.$domain.'/Packages/'.$row['RawImage'].'",
                    "thumbnail":"optional-32x32px.jpg",
                    "credit":"Credit Name Goes Here",
                    "caption":"Caption text goes here"
                    }
            },
';


}

$cadena = $cadena.'
             {
                "startDate":"2012,12,21",
                "endDate":"2012,12,21",
                "headline":"Esta es mi Prueba",
                "text":"<p>Aqui va algún texto en formato p</p>",
                "tag":"Esto es opcional",
                "asset": {
                    "media":"http://fractralfoot.files.wordpress.com/2009/08/lisfranc-injury-x-ray-side-view.jpg",
                    "thumbnail":"http://fractralfoot.files.wordpress.com/2009/08/lisfranc-injury-x-ray-side-view.jpg",
                    "credit":"Texto de crédito",
                    "caption":"Texto a pie de imagen"
                    }
            }          
       ],
        "era": [
            {
                "startDate":"2013,12,10",
                "endDate":"2013,12,11",
                "headline":"Headline Goes Here",
                "text":"<p>Body text goes here, some HTML is OK</p>",
                "tag":"This is Optional"
            }

        ]
    }
}';

$jsondata = json_encode($cadena);

echo "***********************************************************************************";
echo $cadena;
echo "***********************************************************************************";


//$cadena = str_replace('\n','',$cadena);
//$cadena = str_replace('\r','',$cadena);
//$cadena = str_replace(' ','',$cadena);

$countfile="jsondata2.txt";
$fp = fopen($countfile, 'w');
fwrite($fp, $cadena);
fclose($fp);


/*

                "startDate":"2012,12,21",
                "endDate":"2012,12,21",
                "headline":"Esta es mi Prueba",
                "text":"<p>Aqui va algún texto en formato p</p>",
                "tag":"Esto es opcional",
                "asset": {
                    "media":"http://fractralfoot.files.wordpress.com/2009/08/lisfranc-injury-x-ray-side-view.jpg",
                    "thumbnail":"http://fractralfoot.files.wordpress.com/2009/08/lisfranc-injury-x-ray-side-view.jpg",
                    "credit":"Texto de crédito",
                    "caption":"Texto a pie de imagen"
                    }



while ($row = mysql_fetch_array($result)) {

$cadena $row['IdUsu'];
echo '  -   ';
echo $row['IdPin'];
echo '  -   ';
echo $row['RawImage'];
echo '  -   ';
echo $row['Fecha'];
echo '  -   ';
echo "<br>\n"; 	

}
    
*/

?>