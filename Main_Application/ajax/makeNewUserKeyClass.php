<?php

class makeNewUserKeyClass{
	//PDO
	private $con;
	
	public $med_id;
	public $maker_type;
	public $owner;
	public $key;
	public $short_key;
	public $type;
	
	public function __construct($med_id, $owner, $type, $maker_type){
		require("environment_detailForLogin.php");
		
		//SET DB CONNECTION/
		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
		
		$this->med_id = $med_id;
		$this->type = $type;
		$this->maker_type = $maker_type;
		$this->owner = $owner;
		
		$this->short_key = $this->med_id.'-'.$this->generateHash(6);
		$this->key = $this->generateHash(255);
		$this->insertDatabaseKey();
	}
	
	//This generates 255 character hash...
	private function generateHash($char_count){
	$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$str = '';
	$length = $char_count;
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
	$hash = $str;
	
	return $hash;
	//END OF GENERATE HASH
	}
	
	private function insertDatabaseKey(){
		$query = $this->con->prepare("SELECT * FROM key_chain WHERE key_hash = ? OR short_hash = ?");
		$query->bindValue(1, $this->key, PDO::PARAM_STR);
		$query->bindValue(2, $this->short_key, PDO::PARAM_STR);
		$result = $query->execute();
		$count = $query->rowCount();
		
		if($count == 0){
			$query2 = $this->con->prepare("INSERT INTO key_chain SET maker=?, owner=?, key_hash=?, short_hash=?, maker_type=?, type=?");
			$query2->bindValue(1, $this->med_id, PDO::PARAM_INT);
			$query2->bindValue(2, $this->owner, PDO::PARAM_INT);
			$query2->bindValue(3, $this->key, PDO::PARAM_STR);
			$query2->bindValue(4, $this->short_key, PDO::PARAM_STR);
			$query2->bindValue(5, $this->maker_type, PDO::PARAM_STR);
			$query2->bindValue(6, $this->type, PDO::PARAM_STR);
			$result = $query2->execute();
		}else{
			$this->short_key = $this->med_id.'-'.$this->generateHash(25);
			$this->key = $this->generateHash(255);
			$this->insertDatabaseKey();
		}
	}
	
}

?>
