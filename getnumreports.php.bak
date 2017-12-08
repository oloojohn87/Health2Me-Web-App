
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
window.lang.change('th');
//alert('th');
}

if(langType == 'en'){
window.lang.change('en');
//alert('en');
}
	

</script>-->

<?php
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$queUsu = $_GET['Usuario'];
$queMed = $_GET['IdMed'];
$NumF = $_GET['NumF'];
$GetP = $_GET['GetP'];
$GetO = $_GET['GetO'];
$order = $_GET['GetOrder'];
$sort = $_GET['GetSort'];
$search= $_GET['GetSearch'];

if($search=="")
{
	$searchcriteria=" and ";
	//die("Nothing");
}
else
{
	$searchcriteria = " and idusfixedname like '%".$search."%' and ";
	//die($searchcriteria);
}

if($sort=="desc")
{
	$img_tag = '<img src="images/descending.png" height="10" width="10"></img>';
}
else
{
	$img_tag = '<img src="images/ascending.png" height="10" width="10"></img>';
}

switch($order)
{
	case 0 : $ord = "order by idpin ".$sort;
			 $table_head = '<th id="col0" width="1" style="text-align: center;">Id '.$img_tag.'</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;

	case 1 : $ord = "order by fecha ".$sort;
			 $table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;"><span lang="en">Origin</span> '.$img_tag.'</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;

	case 2 : $ord = "order by fechainput ".$sort;
			$table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;"><span lang="en">Report Date</span> '.$img_tag.'</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;

	case 3 : //removed column UserID ... Hence this case will never be used
			 $ord = "order by idusu ".$sort;
			 $table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;
			
	case 4 : $ord = "order by idusfixedname ".$sort;
			$table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;"><span lang="en">Patient</span> '.$img_tag.'</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;
			
	case 5 : $ord = "order by tipo ".$sort;
			$table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;"><span lang="en">Type</span> '.$img_tag.'</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;
	
	case 7 : $ord = "order by canal ".$sort;
			$table_head = '<th id="col0" width="1" style="text-align: center;">Id</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;"><span lang="en">Channel</span> '.$img_tag.'</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;
	
	default : $ord = "order by idpin ".$sort;
			$table_head = '<th id="col0" width="1" style="text-align: center;">Id '.$img_tag.'</th><th id="col1" width="55" style="text-align: center;" lang="en">Origin</th><th id="col2" width="20" style="text-align: center;" lang="en">Report Date</th><th id="col4" width="20" style="text-align: center;" lang="en">Patient</th><th id="col5" width="20" style="text-align: center;" lang="en">Type</th><th id="col6" width="20" style="text-align: center;" lang="en">Parsing State</th><th id="col7" width="25" style="text-align: center;" lang="en">Channel</th><th id="col8" width="20" style="text-align: center;" lang="en">Next Action</th></tr></thead>';
			break;
}



if ($GetO == 0 and $GetP == 0)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id  $searchcriteria  idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) ) $ord LIMIT $Limite");
	$sql="SELECT count(*) as num FROM lifepin l,tipopin t WHERE l.tipo=t.id  $searchcriteria  idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) ) $ord ";
	
}
else if($GetO == 0 and $GetP == 1)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) )and vs=0 $ord LIMIT $Limite");
	$sql="SELECT count(*) as num FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria idpin in (select distinct(idpin) from  lifepin where idmed='$queMed' or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where Iddoctor='$queMed')) )and vs=0 $ord ";
	
}
else if($GetO == 1 and $GetP == 0)
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' $ord LIMIT $Limite");
	$sql="SELECT count(*) as num FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' $ord ";
	
}
else
{
	//die("SELECT idpin,idusu,FechaInput,fecha,t.abreviation as nombreeng,suggestedid,date(suggesteddate1) as suggesteddate,canal,idusfixed,idusfixedname,vs,fs,a_s,creatortype,idcreator FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' AND vs=0 $ord LIMIT $Limite");
	$sql="SELECT count(*) as num FROM lifepin l,tipopin t WHERE l.tipo=t.id $searchcriteria IdMed='$queMed' AND vs=0 $ord ";
	
}


try {
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$employees = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($employees) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}




?>