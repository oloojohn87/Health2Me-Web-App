<?php php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

//Connecting to the database

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

//Finding the no of consultations
$interval;
$query;

if (interval == 7)
{
    $query = mysql_query("select count(id) as consultations from appointments where date between date_sub(now(), interval 7 day)");
}
elseif(interval == 30)
{
    $query = mysql_query("select count(id) as consultationsfrom appointments where date between date_sub(now(), interval 30 day)");
}
elseif(interval == 365)
{
    $query = mysql_query("select count(id) as consultations from appointments where date between date_sub(now(), interval 365 day)")
}

$row = mysql_fetch_array($query);
$noOfConsultations = $row['consultations'];


//Finding the number of patients/customers

if (interval == 7)
{
    $query = mysql_query("select count(distinct(patientId)) as patients from appointments where date between date_sub(now(), interval 7 day)");
}
elseif(interval == 30)
{
    $query = mysql_query("select count(distinct(patientId)) as patients from appointments where date between date_sub(now(), interval 30 day)");
}
elseif(interval == 365)
{
    $query = mysql_query("select count(distinct(patientId)) as patients from appointments where date between date_sub(now(), interval 365 day)")
}

$row = mysql_fetch_array($query);
$noOfPatients = $row['patients'];
