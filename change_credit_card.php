<?php

require_once('stripe/stripe/lib/Stripe.php');
require "Services/Twilio.php";
require("environment_detail.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
 
$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);

Stripe::setApiKey($stripe['secret_key']);

$id = '';
if($_POST['type'] == '1')
{
    // doctor
    if($_POST['action'] == '1')
    {
        // add new bank account for doctor
        $id = $_POST['id'];
        $res = $con->prepare("SELECT stripe_id,IdMEDEmail,Name,Surname FROM doctors WHERE id=?");
		$res->bindValue(1, $id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $stripe_id = $row['stripe_id'];
        $customer = null;
        $Name = $row['Name']." ".$row['Surname'];
        if(isset($_POST['full_name']) && strlen($_POST['full_name']) > 0)
        {
            $Name = $_POST['full_name'];
        }
        if($stripe_id == null)
        {
            $customer = Stripe_Recipient::create(array(
                "name" => $Name,
                "type" => "individual",
                "bank_account" => $_POST['token']
            ));
            $results = $con->prepare("UPDATE doctors SET stripe_id=? WHERE id=?");
			$results->bindValue(1, $customer->id, PDO::PARAM_INT);
			$results->bindValue(2, $id, PDO::PARAM_INT);
			$results->execute();
			
        }
        else
        {
            $customer = Stripe_Recipient::retrieve($row["stripe_id"]);
            $customer->bank_account = $_POST['token'];
            $customer->name = $Name;
            $customer->save();
        }
        
        echo '1';
    }
    else if($_POST['action'] == '2')
    {
        // remove card for doctor
        $id = $_POST['id'];
        $res = $con->prepare("SELECT stripe_id,IdMEDEmail,Name,Surname FROM doctors WHERE id=?");
		$res->bindValue(1, $id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $stripe_id = $row['stripe_id'];
        $customer = null;
        if($stripe_id == null)
        {
            echo '0';
        }
        else
        {
            //$customer = Stripe_Customer::retrieve($row["stripe_id"]);
            $customer = Stripe_Recipient::retrieve($row["stripe_id"]);
            $customer->delete();
            $results = $con->prepare("UPDATE doctors SET stripe_id=null WHERE id=?");
			$results->bindValue(1, $id, PDO::PARAM_INT);
			$results->execute();
        }
        echo '1';
    }elseif($_POST['action'] == 3){
		
        // add new bank account for doctor
        $id = $_POST['id'];
        $res = $con->prepare("SELECT stripe_id,IdMEDEmail,Name,Surname FROM doctors WHERE id=?");
		$res->bindValue(1, $id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $stripe_id = $row['stripe_id'];
        $customer = null;
        if($stripe_id == null)
        {
            $customer = Stripe_Customer::create(array(
              "description" => "Customer for Dr. ".$row['Name']." ".$row['Surname'],
              "email" => $row['IdMEDEmail']
            ));
            $results = $con->prepare("UPDATE doctors SET stripe_id=? WHERE id=?");
			$results->bindValue(1, $customer->id, PDO::PARAM_INT);
			$results->bindValue(2, $id, PDO::PARAM_INT);
			$results->execute();
			
        }
        else
        {
            $customer = Stripe_Customer::retrieve($row["stripe_id"]);
        }
        $val = '1';
        
        try
        {
            $customer->cards->create(array("card" => $_POST['token']));
        }
        catch(Exception $e)
        {
            $val = '0';
        }
        echo $val;
        
	}
}
else if($_POST['type'] == '2')
{
    // patient
    
    if($_POST['action'] == '1')
    {
        // add new card for patient
        
        $id = $_POST['id'];
        $res = $con->prepare("SELECT stripe_id,email,Name,Surname FROM usuarios WHERE Identif=?");
		$res->bindValue(1, $id, PDO::PARAM_INT);
		$res->execute();
		
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $stripe_id = $row['stripe_id'];
        $customer = null;
        if($stripe_id == null)
        {
            $customer = Stripe_Customer::create(array(
              "description" => "Customer for ".$row['Name']." ".$row['Surname'],
              "email" => $row['email']
            ));
            $results = $con->prepare("UPDATE usuarios SET stripe_id='".$customer->id."' WHERE Identif=".$id);
			$results->bindValue(1, $id, PDO::PARAM_INT);
			$results->execute();
			
        }
        else
        {
            $customer = Stripe_Customer::retrieve($row["stripe_id"]);
        }
        $val = '1';
        
        try
        {
            $customer->cards->create(array("card" => $_POST['token']));
        }
        catch(Exception $e)
        {
            $val = '0';
        }
        echo $val;
    }
    else if($_POST['action'] == '2')
    {
        $id = $_POST['id'];
        
        // remove card for patient
        $res = $con->prepare("SELECT id FROM doctors WHERE consultation_pat = ? AND in_consultation = 1");
		$res->bindValue(1, $id, PDO::PARAM_INT);
		$res->execute();
        $num_rows = $res->rowCount();
        if($num_rows == 0)
        {
            $API_VERSION = '2010-04-01';
            $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
            $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
            // Instantiate a new Twilio Rest Client
            $client = new Services_Twilio($AccountSid, $AuthToken);
            
            $pats_in_consultation = array();
            foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
            {
                $conference_name = explode("_", $conference->friendly_name);
                $pat_id = intval($conference_name[1]);
                if(!in_array($pat_id, $pats_in_consultation))
                {
                    array_push($pats_in_consultation, $pat_id);
                }
            }
            if(in_array(intval($id), $pats_in_consultation))
            {
               $num_rows = 1; 
            }
        }
        
        if($num_rows == 0)
        {
            $res = $con->prepare("SELECT stripe_id,email,Name,Surname FROM usuarios WHERE Identif=?");
            $res->bindValue(1, $id, PDO::PARAM_INT);
            $res->execute();

            $row = $res->fetch(PDO::FETCH_ASSOC);
            $stripe_id = $row['stripe_id'];
            $customer = null;
            if($stripe_id == null)
            {
                echo '0';
            }
            else
            {
                $customer = Stripe_Customer::retrieve($row["stripe_id"]);
            }
            $card_id = $_POST['card_id'];
            $customer->cards->retrieve($card_id)->delete();
            echo '1';
        }
        else
        {
            echo 'IC';
        }
    }
    
}
?>
