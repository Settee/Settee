<?php Class Database{

	public $db;
	static $connexion = array();

	function __construct(){
		if(isset(Database::$connexion['pdo'])){
			$this->db = Database::$connexion['pdo'];
			return true;
		}
		try{
			$pdo = new PDO('mysql:host='.CONFIG::HOST.';dbname='.CONFIG::DB,CONFIG::USER,CONFIG::PASSWD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			Database::$connexion['pdo'] = $pdo;
			$this->db = $pdo;
		}catch(PDOexception $e){
			die("Database is unreachable, please wait a few minutes");
		}
	}

	function sqlquery($sql,$type=null){
		try{
			if($type == 'query'){
				$pre = $this->db->prepare($sql);
				$pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);
			}else{
				$pre = $this->db->prepare($sql);
				$pre->execute();
			}
		}catch(PDOException $e){
			//die($e);
			die("Database is unreachable, please wait a few minutes");
		}
	}

	function secure($data){
		return trim($this->db->quote($data),"'");
	}
}