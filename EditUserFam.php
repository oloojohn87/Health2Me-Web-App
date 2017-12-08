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

if(isset($_POST['Delete']) && $_POST['Delete'] == true)
{
    $id = $_POST['User'];
    $result = $con->prepare("DELETE FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $id, PDO::PARAM_INT);
    $result->execute();
    $result = $con->prepare("DELETE FROM basicemrdata WHERE IdPatient = ?");
    $result->bindValue(1, $id, PDO::PARAM_INT);
    $result->execute();
    echo '1';
}
else if(isset($_POST['Grant_Access']) && $_POST['Grant_Access'] == true)
{
    $id = $_POST['User'];
    $access = $_POST['Access'];
    $result = $con->prepare("UPDATE usuarios SET grant_access = ? WHERE Identif = ?");
    $result->bindValue(1, $access, PDO::PARAM_INT);
    $result->bindValue(2, $id, PDO::PARAM_INT);
    $result->execute();
}
else if(isset($_POST['Get_info']) && $_POST['Get_info'] == true)
{
    $id = $_POST['User'];
    $result = $con->prepare("SELECT Name,Surname,DOB,telefono,email,relationship,Sexo,subsType,Orden,grant_access FROM usuarios U INNER JOIN basicemrdata B WHERE U.Identif = B.IdPatient AND U.Identif = ?");
    $result->bindValue(1, $id, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
}
else
{

    $Name = $_POST['Name'];
    $Surname = $_POST['Surname'];
    $DOB = $_POST['DOB'];
    $Gender = $_POST['Gender'];
    $Phone = str_replace('+','', $_POST['Phone']);
    $Phone = str_replace(' ','', $Phone);
    $Email = $_POST['Email'];
    $Order = $_POST['Order'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Relationship = ucwords($_POST['Relationship']);
    $Owner = $_POST['Owner'];
    $Mode = $_POST['Mode'];
    $User = $_POST['User'];
    
    
    $Admin = 'Delegate';
    $age = 100;
    if(strlen($DOB) > 0)
    {
        // get the new user's age
        $query_str = "SELECT floor(datediff(curdate(),?) / 365) as age";
        $result = $con->prepare($query_str);
        $result->bindValue(1, $DOB, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $age = $row['age'];
    }
    
    $owner_email = '';
    $owner_phone = '';
    $result = $con->prepare("SELECT email,telefono FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $Owner, PDO::PARAM_INT);
    $result->execute();
    $owner_row = $result->fetch(PDO::FETCH_ASSOC);
    $owner_email = $owner_row['email'];
    $owner_phone = $owner_row['telefono'];
    $exists = false;
    $result = $con->prepare("SELECT email FROM usuarios WHERE ownerAcc = ?");
    $result->bindValue(1, $Owner, PDO::PARAM_INT);
    $result->execute();
    $accs = array();
    while($acc_row = $result->fetch(PDO::FETCH_ASSOC))
    {
        array_push($accs, $acc_row['email']);
    }
    
    if($age < 18)
    {
        if(strlen($Phone) == 0)
        {
            $Phone = $owner_phone;
        }
        if(strlen($Email) == 0)
        {
            $count = 1;
            $count_str = '';
            if($count < 9)
                $count_str = '0';
            $count_str .= $count;
            $Email = $owner_email.'-0'.$count_str;
            while(in_array($Email, $accs))
            {
                $count += 1;
                $count_str = '';
                if($count < 9)
                    $count_str = '0';
                $count_str .= $count;
                $Email = $owner_email.'-0'.$count_str;
            }
        }
    }
    
    if($_POST['Admin'] == '1')
    {
        $Admin = 'Admin';
    }

    if(strlen($Name) == 0)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca un nombre';
        }
        else
        {
            echo 'Please enter a name';
        }
    }
    else if(strlen($Surname) == 0)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca un apellido';
        }
        else
        {
            echo 'Please enter a surname';
        }
    }
    else if(strlen($DOB) == 0)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca una fecha de nacimiento';
        }
        else
        {
            echo 'Please enter a date of birth';
        }
    }
    else if(strlen($Phone) == 0)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca un número de teléfono';
        }
        else
        {
            echo 'Please enter a phone number';
        }
    }
    else if(strlen($Phone) < 10)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Número de teléfono inválido';
        }
        else
        {
            echo 'Phone number invalid';
        }
    }
    else if($Gender == 'none')
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, seleccione un género';
        }
        else
        {
            echo 'Please select a gender';
        }
    }
    else if(strlen($Email) == 0)
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca un email';
        }
        else
        {
            echo 'Please enter an email';
        }
    }
    else if($Relationship == 'none')
    {
        
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, seleccione la relación con el dueño de la cuenta';
        }
        else
        {
            echo 'Please select a relationship to the owner';
        }
    }
    else if((strlen($Password) == 0 && $Mode == 1) || ($Mode == 2 && strlen($Password) == 0 && strlen($Password2) > 0))
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, introduzca una contraseña';
        }
        else
        {
            echo 'Please enter a password';
        }
    }
    else if((strlen($Password) < 8 && $Mode == 1) || ($Mode == 2 && (strlen($Password) > 0 || strlen($Password2) > 0) && strlen($Password) < 8))
    {
        
        if($_COOKIE['lang'] == 'th')
        {
            echo 'La contraseña debe tener al menos 8 caracteres';
        }
        else
        {
            echo 'Password must be at least 8 characters';
        }
    }
    else if((strlen($Password2) == 0 && $Mode == 1) || ($Mode == 2 && strlen($Password) > 0 && strlen($Password2) == 0))
    {
        
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Por favor, escriba la contraseña de nuevo';
        }
        else
        {
            echo 'Please retype the password';
        }
    }
    else if(($Password != $Password2 && $Mode == 1) || ($Mode == 2 && strlen($Password) > 0 && strlen($Password2) > 0 && $Password != $Password2))
    {
        if($_COOKIE['lang'] == 'th')
        {
            echo 'Las contraseñas no coinciden';
        }
        else
        {
            echo 'Passwords did not match';
        }
    }
    else
    {

        if(strlen($Order) == 0)
        {
            $Order = 0;
        }

        $gender_num = '1';
        if($Gender == 'female')
        {
            $gender_num = '0';
        }

        $IdUsFIXED = str_replace('-', '', $DOB).$gender_num.$Order;
        $IdUsFIXEDNAME = $Name.'.'.$Surname;

        $confirm_code=md5(uniqid(rand()));
        $IdUsRESERV= '';;
        $additional_string = '';
        if($Mode == 1 || ($Mode == 2 && strlen($Password) > 0 && strlen($Password2) > 0))
        {
            $hashresult = explode(":", create_hash($Password));
            $IdUsRESERV= $hashresult[3];
            $additional_string=$hashresult[2];
        }

        $result = $con->prepare("SELECT Identif,grant_access FROM usuarios WHERE email = ?");
        $result->bindValue(1, $Email, PDO::PARAM_STR);
        $result->execute();
        $num_rows = $result->rowCount();
        $grant_access = 0;
        $email_check = 1;
        if($num_rows > 0 && $Mode == 2)
        {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $grant_access = $row['grant_acess'];

            // Below line commented by Pallab  as because it was throwing error as "This user already exists and was not allowing to edit member details"
            //$row = $result->fetch(PDO::FETCH_ASSOC);

            if($row['Identif'] != $User)
            {
                $email_check = 0;
                
            }
        }
       
        else if($num_rows > 0)
        {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $grant_access = $row['grant_acess'];
            $email_check = 0;
        }
        
        
        
        if ($email_check == 0) 
        {
            if($_COOKIE['lang'] == 'th')
            {
                echo 'Este usuario ya tiene una cuenta';
            }
            else
            {
                echo 'This user already has an account';
            }
        }
        else
        
        {

            $grant_access = 'GrantAccess = NULL';
            if(isset($_POST['GrantAccess']) && $_POST['GrantAccess'] == 'HTI-COL')
                $grant_access = 'GrantAccess = \'HTI-COL\'';
            if($Mode == 1)
            {
                // adding new user into the usuarios table
                $query_str = "INSERT INTO usuarios SET IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, Sexo = ?, Orden = ?, Name = ?, Surname = ?, telefono = ?, email = ? , Verificado = '0' , confirmcode=?, plan='FAMILY', subsType = ?, ownerAcc = ?, signUpDate = NOW(), relationship = ?, IdUsRESERV = ?, salt=?, ".$grant_access;
            }
            else
            {
                // editing existing user
                $query_str = "UPDATE usuarios SET IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, Sexo = ?, Orden = ?, Name = ?, Surname = ?, telefono = ?, email = ? , Verificado = '0' , confirmcode=?, plan='FAMILY', subsType = ?, ownerAcc = ?, signUpDate = NOW(), relationship = ?, ".$grant_access;
                if(strlen($Password) > 0 && strlen($Password2) > 0)
                {
                    $query_str .= ", IdUsRESERV = ?, salt=?";
                }
                $query_str .= " WHERE Identif = ?";
            }
            
            $result = $con->prepare($query_str);
            $result->bindValue(1, $IdUsFIXED, PDO::PARAM_STR);
            $result->bindValue(2, $IdUsFIXEDNAME, PDO::PARAM_STR);
            $result->bindValue(3, $IdUsFIXEDNAME, PDO::PARAM_STR);
            $result->bindValue(4, $gender_num, PDO::PARAM_INT);
            $result->bindValue(5, $Order, PDO::PARAM_INT);
            $result->bindValue(6, $Name, PDO::PARAM_STR);
            $result->bindValue(7, $Surname, PDO::PARAM_STR);
            $result->bindValue(8, $Phone, PDO::PARAM_STR);
            $result->bindValue(9, $Email, PDO::PARAM_STR);
            $result->bindValue(10, $confirm_code, PDO::PARAM_STR);
            $result->bindValue(11, $Admin, PDO::PARAM_STR);
            $result->bindValue(12, $Owner, PDO::PARAM_INT);
            $result->bindValue(13, $Relationship, PDO::PARAM_STR);
            if($Mode == 1 || ($Mode == 2 && strlen($Password) > 0 && strlen($Password2) > 0))
            {
                $result->bindValue(14, $IdUsRESERV, PDO::PARAM_STR);
                $result->bindValue(15, $additional_string, PDO::PARAM_STR);
                if($Mode == 2)
                {
                    $result->bindValue(16, $User, PDO::PARAM_INT);
                }
            }
            else
            {
                $result->bindValue(14, $User, PDO::PARAM_INT);
            }
            $result->execute();
            $last_id = $con->lastInsertId();
            $new_user_id = '';
            if($Mode == 1)
            {
                $new_user_id = $last_id;
            }
            else
            {
                $new_user_id = $User;
            }

            // adding a new entry for the new user into the basicemrdata table
            if($Mode == 1)
            {
                $query_str = "INSERT INTO basicemrdata SET DOB = ?, phone = ?, IdPatient = ?";
            }
            else
            {
                $query_str = "UPDATE basicemrdata SET DOB = ?, phone = ? WHERE IdPatient = ?";
            }
            $result = $con->prepare($query_str);
            $result->bindValue(1, $DOB, PDO::PARAM_STR);
            $result->bindValue(2, $Phone, PDO::PARAM_STR);
            $result->bindValue(3, $new_user_id, PDO::PARAM_INT);
            $result->execute();

            echo 'GOOD_'.$new_user_id.'_'.$age.'_'.$grant_access;

            if($Mode == 1)
            {
                // LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

                require_once 'MBCaller.php';
                //$a = SendCallVERIF (34608754342);

                // ENVÍA EL EMAIL AL PACIENTE: ****************************************************

                require_once 'lib/swift_required.php';

                $aQuien = $Email;
                $Tema = 'Inmers Account Confirmation';

                //$adicional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><p>Your email: <span><h3>'.$email.'</h3></span><p><p>Your Number id: <span><h3>'.$IdUsFIXED.'</h3></span><p><p>Your Name id: <span><h3>'.$IdUsFIXEDNAME.'</h3></span></p><p>Please use your Name id for sign in purposes.</p>';

                $info_block = '<ul style="display:block;margin:15px 20px;padding:0;list-style:none;border-top:1px solid #eee">
                <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Email:</strong>   <a href="mailto:'.$Email.'" target="_blank">'.$Email.'</a></li>
                <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Number Id:</strong> '.$IdUsFIXED.' </li>
                <li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Name Id:</strong>       '.$IdUsFIXEDNAME.'</li>
                </ul>';


                $adicional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><br><p>For your records here is a copy of the information you submitted to us...</p>'.$info_block;

                $confirm_button = '<a href='.$domain.'/ConfirmaUserPac.php?token='.$confirm_code.' style="cursor:auto; color:#ffffff;display:inline-block;font-family:\'Helvetica\',Arial,sans-serif;width:auto;white-space:nowrap;min-height:32px;margin:5px 5px 0 0;padding:0 22px;text-decoration:none;text-align:center;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:0;border-radius:4px;vertical-align:top;background-color:#3498db" target="_blank"><span style="display:inline;font-family:\'Helvetica\',Arial,sans-serif;text-decoration:none;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:none;background-color:#3498db;color:#ffffff">Yes, confirm my account.</span></a>';


                $Sobre = $Tema;
                $Body = '<a href="#"><img src="'.$domain.'/images/health2me_horizontal.png"></a></p><p>Thank you for your interest in our services!</p><p><h1>Please confirm your account</h1></p><p>'.$confirm_button.'</p>'.$adicional;



                $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                  ->setUsername('newmedibank@gmail.com')
                  ->setPassword('ardiLLA98');

                $mailer = Swift_Mailer::newInstance($transporter);


                // Create the message
                $message = Swift_Message::newInstance()

                  // Give the message a subject
                  ->setSubject($Sobre)

                  // Set the From address with an associative array
                  ->setFrom(array('no-reply@health2.me' => 'health2.me'))

                  // Set the To addresses with an associative array
                  ->setTo(array($aQuien))

                  ->setBody($Body, 'text/html')

                  ;


                $result = $mailer->send($message);
            }
        }

    }

}


?>