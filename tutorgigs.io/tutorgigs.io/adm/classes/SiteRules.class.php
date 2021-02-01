<?php

class SiteRules {
	// DB Settings
	private $tServer="localhost";
	private $tUser="intervenedevUser";          
	private $tPass='Te$btu$#4f56#';
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "siteRules";
	
	// IDs
	public $maxTestAttemptsID = 1;
	public $passingTestScoreID = 2;
	public $globalRateID = 3;
	
	/* ----------------------------------------------- */
	
	public function SiteRules() {
		// Connect to tutor database
		$this->connection = mysqli_connect($this->tServer, $this->tUser, $this->tPass, $this->tDB) or 
			die('Connection Error: '. mysqli_connect_error() );
		mysqli_select_db($this->connection, $this->tDB);		
	}
	
	/* ----------------------------------------------- */
	public function getMaxTestAttempts() {
		// Get Value of Max Test Attempts Global Rules		
		return $this->getValue($this->maxTestAttemptsID);		
	}
	
	public function getPassingTestScore() {
		// Get Value of Passing Score from Global Rules		
		return $this->getValue($this->passingTestScoreID);	
	}
	
	public function getGlobalRate() {
		// Get Value of Passing Score from Global Rules		
		return $this->getValue($this->globalRateID);	
	}
	/* ----------------------------------------------- */
	
	public function CreateRule($rule, $val, $notes = ' ', $IsActive = 1) {
		$query = "
				INSERT INTO 
				siteRules 
				(
					Rule, 
					Value, 
					Notes,
					IsActive                 
				) 
				VALUES
				( 
					'".$rule."', 
					'".$val."', 
					'".$notes."', 
					'".$IsActive."' 
				)";
				
		return $this->Create($query); 
	}
	
	public function EditRule($rID, $rule, $val, $notes = ' ', $IsActive = 1) {
		$query = "
				UPDATE 
					siteRules 
				SET 
					Rule='".$rule."',
					Value='".$val."',
					Notes='".$notes."' 
				WHERE ID=".$rID
		;
		return $this->Update($query);
	}
	
	public function DeleteRule($id) {
		$query = "DELETE FROM $this->table WHERE ID=$id";
		return $this->Delete($query);
	}
	
	/* ----------------------------------------------- */
	
	public function getValue( $id ) {
		$query = "SELECT Value FROM $this->table WHERE ID=$id";
		$result = $this->Select($query);
		
		$row = mysqli_fetch_assoc($result);
		return $row['Value'];
	}
	
	public function getRule( $id ) {
		$query = "SELECT Rule FROM $this->table WHERE ID=$id";
		$result = $this->Select($query);
		
		$row = mysqli_fetch_assoc($result);
		return $row['Rule'];
	}
	
	public function getNotes( $id ) {
		$query = "SELECT Notes FROM $this->table WHERE ID=$id";
		$result = $this->Select($query);
		
		$row = mysqli_fetch_assoc($result);
		return $row['Notes'];
	}
	
	public function getIsActive( $id ) {
		$query = "SELECT IsActive FROM $this->table WHERE ID=$id";
		$result = $this->Select($query);
		
		$row = mysqli_fetch_assoc($result);
		return $row['IsActive'];
	}
	
	public function getNumOfRules( ) {
		$query = "SELECT * FROM $this->table";
		$result = $this->Select($query);		
		$num = mysqli_num_rows($result);
		return $num;
	}
	
	public function getAllRules( ) {
		$query = "SELECT * FROM $this->table";
		$result = $this->Select($query);
		return $result;
	}
	
	public function setValue ( $id , $val ) {
		$query = "UPDATE $this->table SET Value=$val WHERE ID=$id";
		$result = $this->Update($query);

		return $result;
	}
	
	public function setRule ( $id , $rule ) {
		$query = "UPDATE $this->table SET Rule=$rule WHERE ID=$id";
		$result = $this->Update($query);

		return $result;
	}
	
	public function setNotes ( $id , $notes ) {
		$query = "UPDATE $this->table SET Notes=$notes WHERE ID=$id";
		$result = $this->Update($query);

		return $result;
	}	
	
	public function setIsActive ( $id , $isActive ) {
		$query = "UPDATE $this->table SET IsActive=$isActive WHERE ID=$id";
		$result = $this->Update($query);

		return $result;
	}	
	
	/* ----------------------------------------------- */
	
	private function Select( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
	}	
	
	private function Update( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
	}
	
	private function Create( $query) {
		$result = mysqli_query($this->connection, $query) or die (mysqli_error($this->connection));
		return $result;
	}
	
	private function Delete( $query) {
		$result = mysqli_query($this->connection, $query) or die (mysqli_error($this->connection));
		return $result;
	}
}
?>