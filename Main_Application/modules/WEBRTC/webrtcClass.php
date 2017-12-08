<?php

class webrtcClass{
	//PDO
	private $con;
	
	//WEBRTC Credentials
	private $rtc_id = 'bombartier';
	private $rtc_key = '4a213061-f2b0-4ec9-bd2a-0c5e0ce0c2e1';
	private $rtc_domain = 'dev.health2.me';
	private $rtc_room = 'default';
	
	public function __construct(){
		require('../../environment_detailForLogin.php');
		$dbhost = $env_var_db['dbhost']; 
		$dbname = $env_var_db['dbname'];
		$dbuser = $env_var_db['dbuser'];
		$dbpass = $env_var_db['dbpass'];
		
		//SET DB CONNECTION/
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
		
		$this->grabCredentials();
	}
	
	public function grabCredentials(){
		$url = 'https://api.xirsys.com/getIceServers';
		$data = array('ident' => $this->rtc_id, 'secret' => $this->rtc_key, 'domain' => $this->rtc_domain, 'application' => 'default', 'room' => $this->rtc_room, 'secure' => 1);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		var_dump($result);
	}
}

?>
