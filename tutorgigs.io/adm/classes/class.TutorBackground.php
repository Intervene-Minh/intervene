<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        TutorBackground
	* GENERATION DATE:  16.11.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.TutorBackground.php
	* FOR MYSQL TABLE:  tutorBackgroundCheck
	* FOR MYSQL DB:     ptwogorg_main
	* -------------------------------------------------------
	* CODE BY:
	* Kevin Pryce
	* for: P2G.org
	* -------------------------------------------------------
	*
	*/
	
	include_once("resources/class.database.php");
	
	// **********************
	// CLASS DECLARATION
	// **********************
	define("BG_None", 0);	
	define("BG_Approved", 1);
	define("BG_Pending", 2); 
	define("BG_Failed", 3); 
	define("BG_Clicked", 4);
	class TutorBackground
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $TutorID;   // (normal Attribute)
	var $Status;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
		

	
	//const BG_STATUS_ = 5;
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function TutorBackground()
	{
	
	$this->database = new Database();
	
	}
	
	
	// **********************
	// GETTER METHODS
	// **********************
	
	
	function getID()
	{
	return $this->ID;
	}
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getStatus()
	{
	return $this->Status;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getStatusName() {
		$bgStatus = "N/A";
		$bgNum = $this->getStatus();
		
		switch($bgNum) {
			case BG_None: 
				$bgStatus = "None";
				break;
			case BG_Approved:
				$bgStatus = "Approved";
				break;
			case BG_Pending:
				$bgStatus = "Pending";
				break;
			case BG_Failed:
				$bgStatus = "Failed";
				break;
			case BG_Clicked:
				$bgStatus = "Pending";
				break;
			default:
				break;
		}
		
		return $bgStatus;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
		$this->ID =  $val;
	}
	
	function setTutorID($val)
	{
		$this->TutorID =  $val;
	}
	
	function setStatus($val)
	{
		$this->Status =  $val;
	}
	
	function setCreatedOn($val)
	{
		$this->CreatedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($tutorID)
	{	
		$sql =  "SELECT * FROM tutorBackgroundCheck WHERE TutorID = $tutorID;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
			
		$this->populateSelf($row);
	}	
	
	function hasApplied($tutorID) {
		$sql =  "SELECT * FROM tutorBackgroundCheck WHERE TutorID = $tutorID LIMIT 1;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		
		if (!result) return false;
		
		$count = mysqli_num_rows($result);
		
		if ($count > 0)
			return true;
		else
			return false;
	}
	
	function create($tutorID, $status) {
		$this->TutorID = $tutorID;
		$this->Status = $status;
		$this->insert();
	}
	
	function populateSelf($row) {
	
		$this->ID = $row->ID;
		
		$this->TutorID = $row->TutorID;
		
		$this->Status = $row->Status;
		
		$this->CreatedOn = $row->CreatedOn;
	
	}
	
	function selectAll() {		
		$sql =  "SELECT * FROM tutorBackgroundCheck";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		return $result;
	}
	function countPending(){
		$sql =  "select count(*) FROM tutorBackgroundCheck where status=4 or status=2";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row=$result->fetch_row();
		return $row[0];
	}
		
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
		$sql = "DELETE FROM tutorBackgroundCheck WHERE ID = $id;";
		$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
		$this->ID = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO tutorBackgroundCheck ( TutorID,Status,CreatedOn ) VALUES ( '$this->TutorID','$this->Status', NOW() )";
		$result = $this->database->query($sql);
		$this->ID = mysqli_insert_id($this->database->link);
		$sql="update tutorProfiles set BackgroundCheck=2 where TutorId=".$this->TutorID;
		$this->database->query($sql);
		
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
		$sql = " UPDATE tutorBackgroundCheck SET  TutorID = '$this->TutorID',Status = '$this->Status',CreatedOn = '$this->CreatedOn' WHERE ID = $id ";
		
		$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>