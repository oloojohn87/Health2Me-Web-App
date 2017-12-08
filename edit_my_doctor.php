<?php
require("environment_detail.php");
require("PasswordHash.php");
require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

if(isset($_POST['delete_id']))
{
    $id = $_POST['delete_id'];
    $res = $con->prepare("DELETE FROM my_doctors WHERE id = ?");
    $res->bindValue(1, $id, PDO::PARAM_INT);
    $res->execute();
    echo 'GD';
}
else
{
    if(strlen($_POST['name']) == 0 || strlen($_POST['surname']) == 0)
    {
        echo 'NN';
    }
    else
    {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $hospital = $_POST['hospital'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $phone = str_replace('+', '', $_POST['phone']);
        $phone = str_replace(' ', '', $phone);
        $speciality = $_POST['speciality'];
        $idpac = $_POST['idpac'];
        $iddoc = $_POST['iddoc'];
        $add = $_POST['add'];



        $next_spot = 3;
        $hospital_spot = 0;
        $address_spot = 0;
        $city_spot = 0;
        $state_spot = 0;
        $zip_spot = 0;
        $country_spot = 0;
        $email_spot = 0;
        $phone_spot = 0;
        $speciality_spot = 0;
        $query = '';
        if($add)
            $query = "INSERT INTO my_doctors SET Name = ?, Surname = ?";
        else
            $query = "UPDATE my_doctors SET Name = ?, Surname = ?";
        if(strlen($hospital) > 0)
        {
            $query .= ", HospitalName = ?";
            $hospital_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($address) > 0)
        {
            $query .= ", Address = ?";
            $address_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($city) > 0)
        {
            $query .= ", City = ?";
            $city_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($state) > 0)
        {
            $query .= ", State = ?";
            $state_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($zip) > 0)
        {
            $query .= ", Zip = ?";
            $zip_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($country) > 0)
        {
            $query .= ", Country = ?";
            $country_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($email) > 0)
        {
            $query .= ", Email = ?";
            $email_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($phone) > 0)
        {
            $query .= ", Phone = ?";
            $phone_spot = $next_spot;
            $next_spot += 1;
        }
        if(strlen($speciality) > 0)
        {
            $query .= ", Speciality = ?";
            $speciality_spot = $next_spot;
            $next_spot += 1;
        }
        $query .= ", IdPac = ?";
        if(!$add)
            $query .= " WHERE id = ?";
        $res = $con->prepare($query);
        $res->bindValue(1, $name, PDO::PARAM_STR);
        $res->bindValue(2, $surname, PDO::PARAM_STR);
        if($hospital_spot > 0)
        {
            $res->bindValue($hospital_spot, $hospital, PDO::PARAM_STR);
        }
        if($address_spot > 0)
        {
            $res->bindValue($address_spot, $address, PDO::PARAM_STR);
        }
        if($city_spot > 0)
        {
            $res->bindValue($city_spot, $city, PDO::PARAM_STR);
        }
        if($state_spot > 0)
        {
            $res->bindValue($state_spot, $state, PDO::PARAM_STR);
        }
        if($zip_spot > 0)
        {
            $res->bindValue($zip_spot, $zip, PDO::PARAM_STR);
        }
        if($country_spot > 0)
        {
            $res->bindValue($country_spot, $country, PDO::PARAM_STR);
        }
        if($email_spot > 0)
        {
            $res->bindValue($email_spot, $email, PDO::PARAM_STR);
        }
        if($phone_spot > 0)
        {
            $res->bindValue($phone_spot, $phone, PDO::PARAM_STR);
        }
        if($speciality_spot > 0)
        {
            $res->bindValue($speciality_spot, $speciality, PDO::PARAM_STR);
        }
        $res->bindValue($next_spot, $idpac, PDO::PARAM_INT);
        if(!$add)
            $res->bindValue($next_spot + 1, $iddoc, PDO::PARAM_INT);
        $res->execute();
        echo 'GD';
    }
}
?>