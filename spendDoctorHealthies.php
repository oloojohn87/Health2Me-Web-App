<?php
require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

$user_id = $_POST['user_id'];
$doc_id = $_POST['doc_id'];
$quantity = $_POST['quantity'];
$months = $_POST['months'];

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $user_id, PDO::PARAM_INT);
$res->execute();

$user_row = $res->fetch(PDO::FETCH_ASSOC);
$amount = $quantity;
$customer = '';



$description = "Charge for probe initiated by member on ".date('F j, Y');

if(isset($_POST['doc_id']))
{
    $res2 = $con->prepare("SELECT * FROM doctors WHERE id = ?");
    $res2->bindValue(1, $doc_id, PDO::PARAM_INT);
    $res2->execute();

    $doc_row = $res2->fetch(PDO::FETCH_ASSOC);

    $description = "Charge for probe initiated by doctor ".$doc_row['Name']." ".$doc_row['Surname']." for member ".$user_row['Name']." ".$user_row['Surname']." on ".date('F j, Y');
}
else
{
    die('You must specify a doctor for this probe.');
}

if($doc_row['credits'] >= $amount)
{
    $transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='doctor', service=?, currency='tokens', service_type='probe', service_id = ?, months = ?");
    $transaction->bindValue(1, $doc_row['id'], PDO::PARAM_INT);
    $transaction->bindValue(2, (1000 * $months), PDO::PARAM_INT);
    $transaction->bindValue(3, ($doc_row['Name']." ".$doc_row['Surname']), PDO::PARAM_STR);
    $transaction->bindValue(4, $description, PDO::PARAM_STR);
    $transaction->bindValue(5, $user_row['Identif'], PDO::PARAM_INT);
    $transaction->bindValue(6, $months, PDO::PARAM_INT);
    $transaction->execute();

    $update_healthies = $con->prepare("UPDATE doctors SET credits = ? WHERE id = ?");
    $update_healthies->bindValue(1, ($doc_row['credits'] - (1000* $months)), PDO::PARAM_INT);
    $update_healthies->bindValue(2, $doc_row['id'], PDO::PARAM_INT);
    $update_healthies->execute();
    
    echo $amount;
}
else
{
    echo 0;
}
/*        
            $description = "Charge for probe initiated by member on ".date('F j, Y');
            
            if(isset($_POST['doc_id'])){
				$res2 = $con->prepare("SELECT * FROM doctors WHERE id = ?");
				$res2->bindValue(1, $doc_id, PDO::PARAM_INT);
				$res2->execute();

				$doc_row = $res2->fetch(PDO::FETCH_ASSOC);
				
				$description = "Charge for probe initiated by doctor ".$doc_row['Name']." ".$doc_row['Surname']." for member ".$user_row['Name']." ".$user_row['Surname']." on ".date('F j, Y');
				}else{
					die('You must specify a doctor for this probe.');
				}
            
			
				$transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='doctor', service=?, currency='tokens', service_type='probe', service_id = ?, months = ?, base_price = ?");
				$transaction->bindValue(1, $doc_row['id'], PDO::PARAM_INT);
				$transaction->bindValue(2, $amount, PDO::PARAM_INT);
				$transaction->bindValue(3, ($doc_row['Name']." ".$doc_row['Surname']), PDO::PARAM_STR);
				$transaction->bindValue(4, $description, PDO::PARAM_STR);
				$transaction->bindValue(5, $user_row['Identif'], PDO::PARAM_INT);
				$transaction->bindValue(6, $months, PDO::PARAM_INT);
				$transaction->bindValue(7, $doc_row['tracking_price'], PDO::PARAM_INT);
				$transaction->execute();
				
				$update_healthies = $con->prepare("UPDATE doctors SET credits = ? WHERE id = ?");
				$update_healthies->bindValue(1, ($doc_row['credits'] - $amount), PDO::PARAM_INT);
				$update_healthies->bindValue(2, $doc_row['id'], PDO::PARAM_INT);
				$update_healthies->execute();

			echo $amount;
        
*/    

    

?>
