<?php


    class telemedicineMedClass{
        
        // PUBLIC PROPERTIES
        
        public $doc_id;
        public $pat_id;
        public $doctor_name;
        public $patient_name;
        
        // PRIVATE PROPERTIES
        
        private $con;
        
        private $dbhost;
        private $dbname;
        private $dbuser;
        private $dbpass;
        private $hardcode;
        private $enc_pass;
        
        function __construct($pat)
        {
            
            require("../environment_detail.php");
            
            session_start();
            
            // ENVIRONMENT PROPERTIES

            $this->dbhost = $env_var_db["dbhost"];
            $this->dbname = $env_var_db["dbname"];
            $this->dbuser = $env_var_db["dbuser"];
            $this->dbpass = $env_var_db["dbpass"];
            $this->hardcode = $env_var_db["hardcode"];
            
            // CHECK IF DOCTOR HAS ACCESS
            
            if($_SESSION['Acceso'] != '23432')
                $this->exit_onfail(1);
            
            $this->doc_id = $_SESSION['MEDID'];
            $this->pat_id = $pat;
            
            // DATABASE CONNECTION
            
            $this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
            if (!$this->con)
            {
			     die('Could not connect: ' . mysql_error());
			}
            
            // ENCRYPTION PASSWORD
            
            $result = $this->con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
            $result->execute();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $this->enc_pass = $row['pass'];
		
            
            
            $session_hash = '';
            
            // GET DOCTOR INFO

            $doc_ = $this->con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname, session_hash FROM doctors WHERE id = ?");
            $doc_->bindValue(1, $this->doc_id, PDO::PARAM_INT);
            $doc_->execute();
            if($doc_->rowCount() >= 1)
            {
                while($doc_row = $doc_->fetch(PDO::FETCH_ASSOC))
                {
                    $this->doctor_name = $doc_row['fullname'];
                    $session_hash = $doc_row['session_hash'];
                }
            }
            else
            {
                $this->exit_onfail(2);
            }
            
            // SESSION HASH
            
            if(isset($this->doc_id) && isset($session_hash) && isset($_SESSION['session_hash_doctor']))
            {

                if($session_hash != $_SESSION['session_hash_doctor'])
                {
                    $result = $this->con->prepare("INSERT INTO hacking_attempts SET type='DOCTOR', id_hacker = ?, hash = ?, datetime=NOW(), location = ?, hash2 = ?"); 
                    $result->bindValue(1, $this->doc_id, PDO::PARAM_INT);
                    $result->bindValue(2, $session_hash, PDO::PARAM_STR);
                    $result->bindValue(3, $_SERVER["REQUEST_URI"], PDO::PARAM_STR);
                    $result->bindValue(4, $_SESSION['session_hash_doctor'], PDO::PARAM_STR);
                    $result->execute();

                    //$this->exit_onfail(3);
                }
                else
                {
                    //ADD NEW HASH TO DATABASE FOR DOCTOR....
                    $new_session_hash = '';//$this->generateHash();

                    $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    $length = 255;
                    $count = strlen($charset);
                    while ($length--) 
                    {
                        $new_session_hash .= $charset[mt_rand(0, $count-1)];
                    }

                    $result = $this->con->prepare("UPDATE doctors SET session_hash = ? where id=?"); 
                    $result->bindValue(1, $new_session_hash, PDO::PARAM_STR);
                    $result->bindValue(2, $this->doc_id, PDO::PARAM_INT);
                    $result->execute();

                    //SETS NEW HASH SESSION....
                    $_SESSION['session_hash_doctor'] = $new_session_hash;
                }
            }
            
            // GET PATIENT NAME

            $pat_ = $this->con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname FROM usuarios WHERE Identif = ?");
            $pat_->bindValue(1, $this->pat_id, PDO::PARAM_INT);
            $pat_->execute();
            while($pat_row = $pat_->fetch(PDO::FETCH_ASSOC))
            {
                $this->patient_name = $pat_row['fullname'];
            }
        }
        
        private function exit_onfail($exitType)
        {
            echo "<!DOCTYPE html>
            <html lang='en'  class='body-error'><head>
            <meta charset='utf-8'>
            <title>health2.me</title>
            <!--<meta name='viewport' content='width=device-width, initial-scale=1.0'>-->
            <meta name='description' content=''>
            <meta name='author' content=''>

            <!-- Le styles -->
            <link href='".$this->hardcode."css/style.css' rel='stylesheet'>
            <link href='".$this->hardcode."css/bootstrap.css' rel='stylesheet'>  
            <link rel='stylesheet' href='".$this->hardcode."css/icon/font-awesome.css'>
            <link rel='stylesheet' href='".$this->hardcode."css/bootstrap-responsive.css'>

            <!-- Le fav and touch icons -->
            <link rel='shortcut icon' href='".$this->hardcode."images/icons/favicon.ico'>    
            </head>
            <body>
            <!--Header Start-->
            <div class='header' >
            <a href='".$this->hardcode."index.html' class='logo'><h1>I</h1></a>
            <div class='pull-right'></div>
            </div>
            <!--Header END-->
            <div class='error-bg'>
              <div class='error-s'>
                <!--<div class='error-number'>Health2me</div>-->
                <div class='error-number'><img src='".$this->hardcode."images/health2meLOGO.png' width='350' /img></div>";
                if($exitType == 4){
                echo "<div class='error-number' style='font-size:20px; margin-top:0px; padding:0px; border:0ox;'>unlocking health</div>";
                echo "<!--<div class='error-number' style='font-size:20px; margin:0px; padding:0px; border:0px;'>social health networking</div>-->";
                }else{
                echo "<div class='error-number' style='font-size:20px; margin-top:15px;'>unlocking health</div>";
                echo "<!--<div class='error-number' style='font-size:20px; margin-top:15px;'>social health networking</div>-->";
                }

                echo "<div class='error-text' style='margin-top:10px;'>version 1.1</div>";

                if($exitType == 1){
                echo "<div class='error-text'>Incorrect credentials for login.</div>";
                }elseif($exitType == 2){
                echo "<div class='error-text'>MEDICAL USER NOT VALID. Incorrect credentials for login.</div>";
                }elseif($exitType == 3){
                echo "<div class='error-text'>USER DATA INCOMPLETE. No Doctor assigned to this User.</div>";
                }elseif($exitType == 4){
                echo "<div class='error-text' style='margin-top:10px;'>You have already activated dropbox cloud Channel.</div>";
                }elseif($exitType == 5){
                echo "<div class='error-text' style='margin-top:10px;'>You do not have permission to access this page.</div>";
                }elseif($exitType == 6){
                echo "<div class='error-text' style='margin-top:10px;'>Password reset error. Please contact Health2me support</div>";  
                }elseif($exitType == 7){
                echo "<div class='error-text' style='margin-top:10px;'>The member information you entered has already been created.  Please refer to forgot password link on login page.</div>";  
                }
                if($exitType != 4){
                echo "<a class='error-text' href='".$this->hardcode."index.html' style='color: #2eb82e; text-decoration: underline;'><center>Click here to return Inmers Homepage</center></a>";
                }

              echo "</div>
             </div>
          </body>
        </html>
        ";
        die;
	   }
    };

?>