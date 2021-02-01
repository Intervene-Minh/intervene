<?php
class Database {
	private $host;
	private $user;
	private $pass;
	private $db_name;
	protected $db_connection;
	public function getConnection() {

		include(dirname(__FILE__)."/../../db_config.php");
		$this->host = $host;
		$this->pass = $password;
		$this->user = $username;
		$this->db_name = $dbname;


		$this->db_connection = new PDO ( "mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass );
		$this->db_connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $this->db_connection;
	}

	public function Select($sql,$params=null){

		$conn=$this->getConnection();
		$stmt=$conn->prepare($sql);
		if(!empty($params)){
			$stmt->execute($params);
		}else{
		$stmt->execute();
		}
		$results=$stmt->fetchAll(PDO::FETCH_OBJ);

		//$conn->close();
		return $results;
	}
	public function SelectFirst($sql,$params=null){
		$conn=$this->getConnection();
		$stmt=$conn->prepare($sql);
		if(!empty($params)){
			$stmt->execute($params);
		}else{
		$stmt->execute();
		}
		$results=$stmt->fetch(PDO::FETCH_OBJ);

		//$conn->close();
		return $results;
	}
	public function update($sql,$params=null){
		$conn=$this->getConnection();
		$stmt=$conn->prepare($sql);
		if(!empty($params)){
			$stmt->execute($params);
		}else{
		$stmt->execute();
		}
		return $conn->lastInsertId();
	}
	public function delete($sql,$params=null){
		$conn=$this->getConnection();
		$stmt=$conn->prepare($sql);
		if(!empty($params)){
			$stmt->execute($params);
		}else{
			$stmt->execute();
		}
		return ;
	}
	public function closeConnection() {
		$this->db_connection->close ();
	}
}
