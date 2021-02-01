<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        JobApp
	* GENERATION DATE:  22.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.JobApp.php
	* FOR MYSQL TABLE:  jobApplications
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
	
	class JobApp
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $JobPostID;   // (normal Attribute)
	var $TutorID;   // (normal Attribute)
	var $Comments;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $WasSelected;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function JobApp()
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
	
	function getJobPostID()
	{
	return $this->JobPostID;
	}
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getComments()
	{
	return $this->Comments;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getWasSelected()
	{
	return $this->WasSelected;
	}
	
	function getIsActive()
	{
	return $this->IsActive;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
	$this->ID =  $val;
	}
	
	function setJobPostID($val)
	{
	$this->JobPostID =  $val;
	}
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setComments($val)
	{
	$this->Comments =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setWasSelected($val)
	{
	$this->WasSelected =  $val;
	}
	
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function selectAllByJobPostID($jobPostID)
	{
	
		$sql =  "SELECT * FROM jobApplications WHERE JobPostID = $jobPostID AND IsActive = 1;";
		$result =  $this->database->query($sql);
		return $this->database->result;
	
	}
	function selectAllByTutorID($tutorID)
	{
	
		$sql =  "SELECT * FROM jobApplications WHERE JobPostID = $jobPostID AND IsActive = 1;";
		$result =  $this->database->query($sql);
		return $this->database->result;	
	}
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM jobApplications WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->JobPostID = $row->JobPostID;
	
	$this->TutorID = $row->TutorID;
	
	$this->Comments = $row->Comments;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->WasSelected = $row->WasSelected;
	
	$this->IsActive = $row->IsActive;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM jobApplications WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}

	/// sets null if blank
	function _set_null_default_value($value) {
		if ($value == '') {
			return "NULL";
		} else {
			return "'".$value."'";
		}
	}

	function _set_zero_default_value($value) {
		if ($value == '') {
			return 0;
		} else {
			return "'".$value."'";
		}
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert() {
		$this->ID = ""; // clear key for autoincrement

		
		//print "was selected is " . $this->WasSelected."\n";
		//exit(0);
		$was_selected = $this->_set_zero_default_value($this->WasSelected);
		
		$sql = "INSERT INTO jobApplications ( JobPostID,TutorID,Comments,CreatedOn,WasSelected,IsActive ) VALUES ( '$this->JobPostID','$this->TutorID','$this->Comments',DATE_ADD(NOW(), INTERVAL 1 HOUR),$was_selected,'$this->IsActive' )";
		$result = $this->database->query($sql);
		$this->ID = mysqli_insert_id($this->database->link);
		
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE jobApplications SET  JobPostID = '$this->JobPostID',TutorID = '$this->TutorID',Comments = '$this->Comments',CreatedOn = '$this->CreatedOn',WasSelected = '$this->WasSelected',IsActive = '$this->IsActive' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>
