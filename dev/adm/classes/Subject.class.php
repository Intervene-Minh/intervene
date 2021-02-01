<?php

class Subject {
	
	// DB Settings
	private $tServer="localhost";
	private $tUser="intervenedevUser";		  
	private $tPass='Te$btu$#4f56#';
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "subjects";   
	
	// ----------------------------------------------//
	public $ID;
	public $Name;
	public $CategoryID;
	public $IsActive; 
				
		
	public function Subject($loadID = null) {
		$this->Connect();
		
		// Load object if ID
		if ($loadID != null)
			$this->Load($loadID);		
	}
	
	// -----------------------------------------------//
	
	// Loads a Subject by ID, Populates the Object, and returns the query result
	public function Load($sid) {
		$strQuery = "SELECT * FROM $this->table WHERE ID=$sid";
		$result = $this->Query($strQuery);
		
		$this->PopulateByResult($result);
		return $result;
	}  
	
	public function LoadAllSubjects() {
		$strQuery = "SELECT * FROM $this->table ORDER BY Name";
		$result = $this->Query($strQuery);
		
		return $result;
	}
	
	// Load ALL Subjects by Category ID
	public function LoadAllSubjectsByCategoryID($categoryID) {
		$strQuery = "SELECT * FROM $this->table WHERE CategoryID=$categoryID";
		$result = self::Query($strQuery);
		
		return $result;
	}
	
	// Create a new Subject
	public function Create($name, $categoryID, $isActive=1) {
		$result = $this->Add($name, $categoryID, $isActive);
		
		$lastID = $this->LastInsertID();
		Load($lastID);
		
		return $result;		
	}
	
	// Delete a Subject
	public function Delete($sid) {
		$result = $this->Remove($sid);		
		return $result;
	}
	
	// Save the Object
	public function Save() {
		return $this->Update();
	}
	
	// DeActivate Object
	public function Deactivate() {
		$this->SetActive(false);
	}
	
	// Activate Object
	public function Activate() {
		$this->SetActive(true);
	}
	
	// -----------------------------------------------//
	
	private function Query( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
	}
	
	private function PopulateByResult( $result ) {
		while ($array = mysqli_fetch_array($result)) {
			$this->ID = $array['ID'];
			$this->Name = $array['Name'];
			$this->CategoryID = $array['CategoryID'];
			$this->IsActive = $array['IsActive'];			
		}
		return $result;
	}
	
	private function Add( $name, $categoryID, $isActive=1) {
		$strQuery = "INSERT INTO $this->table
		(
			Name,
			CategoryID,						 
			IsActive								
		) 
		VALUES (											
			'".$name."',	
			'".$categoryID."',	
			'".$isActive."'										
		)";
		$result = $this->Query($strQuery);
		return $result;
	}

	private function Update () {
		if (!empty($this->ID)) {
			$strQuery = "
				UPDATE 
					$this->table
				SET 
					Name='".$this->Name."',
					CategoryID='".$this->CategoryID."',
					IsActive='".$this->IsActive."'					
				WHERE ID=".$this->ID
			;
			$result = $this->Query($strQuery);
			return $result;
		}		
	}
	
	private function Remove ( $sid ) {
		
		// 1. Delete each Options/Answers from Test Questions for Subject
		
		// 2. Delete each Questions from Test for Subject
		
		// 3. Delete Test from Subject
		
		// 4. Delete Subject
		$strQuery = "DELETE FROM $this->table WHERE ID=$sid";
		$result = $this->Query($strQuery);
		
		return $result;
	}
	
	private function SetActive($state) {		
		$this->IsActive = $state == true ? 1 : 0;
		$strQuery = "UPDATE $this->table SET IsActive=$this->IsActive WHERE ID=$this->ID";
		$result = $this->Query($strQuery);
	}
	
	
	// -----------------------------------------------//
	private function Connect() {
		include(dirname(__FILE__)."/../db_config.php");
		$this->tServer = $tServer;
		$this->tUser = $username;
		$this->tPass = $password;
		$this->tDB = $tDB;
		$this->connection = mysqli_connect($this->tServer, $this->tUser, $this->tPass, $this->tDB) or 
		die('Connection Error: '. mysqli_connect_error() );
		mysqli_select_db($this->connection, $this->tDB);
	} 
	
	private function LastInsertID() {
		return mysqli_insert_id($this->connection);
	}
	
}

?>
