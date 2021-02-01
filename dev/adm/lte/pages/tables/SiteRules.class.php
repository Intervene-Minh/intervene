<?php

class SiteRules {
	// DB Settings
	private $tServer="localhost";
	private $tUser="ptwogorg_tutor";          
	private $tPass="tutor1234";
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "siteRules";
	// IDs
	private $maxTestAttemptsID = 1;
	private $passingTestScoreID = 2;
	
	
	
	public function SiteRules() {
		// Connect to tutor database
		$this->connection = mysqli_connect($this->tServer, $this->tUser, $this->tPass, $this->tDB) or 
			die('Connection Error: '. mysqli_connect_error() );
		mysqli_select_db($this->connection, $this->tDB);		
	}
	
	public function getMaxTestAttempts() {
		// Get Value of Max Test Attempts Global Rules		
		return $this->getValue($this->maxTestAttemptsID);		
	}
	
	public function getPassingTestScore() {
		// Get Value of Passing Score from Global Rules		
		return $this->getValue($this->passingTestScoreID);	
	}
	
	private function getValue( $id ) {
		$query = "SELECT * FROM $this->table WHERE ID=$id";
		$result = $this->Select($query);
		
		$row = mysqli_fetch_assoc($result);
		return $row['Value'];
	}
	
	private function Select( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
	}	
}
?>