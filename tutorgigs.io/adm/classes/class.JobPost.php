<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        JobPost
	* GENERATION DATE:  21.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.JobPost.php
	* FOR MYSQL TABLE:  jobPosts
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
	
	class JobPost
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $StudentID;   // (normal Attribute)
	var $SubjectID;   // (normal Attribute)
	var $Other;   // (normal Attribute)
	var $Description;   // (normal Attribute)
	var $IsAvailable;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function JobPost()
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
	
	function getStudentID()
	{
	return $this->StudentID;
	}
	
	function getSubjectID()
	{
	return $this->SubjectID;
	}
	
	function getOther()
	{
	return $this->Other;
	}
	
	function getDescription()
	{
	return $this->Description;
	}
	
	function getIsAvailable()
	{
	return $this->IsAvailable;
	}
	
	function getIsActive()
	{
	return $this->IsActive;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
	$this->ID =  $val;
	}
	
	function setStudentID($val)
	{
	$this->StudentID =  $val;
	}
	
	function setSubjectID($val)
	{
	$this->SubjectID =  $val;
	}
	
	function setOther($val)
	{
	$this->Other =  $val;
	}
	
	function setDescription($val)
	{
	$this->Description =  $val;
	}
	
	function setIsAvailable($val)
	{
	$this->IsAvailable =  $val;
	}
	
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM jobPosts WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	$this->populateSelf($row);
	 
	}
	
	function selectSearch() {
	
		$sql =  "
				SELECT * FROM jobPosts					
				WHERE
				IsActive = 1
				AND
				IsAvailable = 1	
				ORDER BY jobPosts.CreatedOn DESC
		";
		$result =  $this->database->query($sql);
		$result = $this->database->result;	
		return $this->database->result; 
	}
	
	function selectAllForStudent($studentID) {
			
		$sql =  "SELECT * FROM jobPosts WHERE StudentID = $studentID AND IsActive = 1;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		//$row = mysqli_fetch_object($result);
		
		return $this->database->result;
	 
	}
	
	function selectAllAvailableJobs() {
			
		$sql =  "SELECT * FROM jobPosts WHERE IsActive = 1 AND IsAvailable = 1";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		//$row = mysqli_fetch_object($result);
		
		return $this->database->result;
	 
	}
	
	function populateSelf($row) {
		
	$this->ID = $row->ID;
	
	$this->StudentID = $row->StudentID;
	
	$this->SubjectID = $row->SubjectID;
	
	$this->Other = $row->Other;
	
	$this->Description = $row->Description;
	
	$this->IsAvailable = $row->IsAvailable;
	
	$this->IsActive = $row->IsActive;
	
	$this->CreatedOn = $row->CreatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM jobPosts WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO jobPosts ( StudentID,SubjectID,Other,Description,IsAvailable,IsActive,CreatedOn ) VALUES ( '$this->StudentID','$this->SubjectID','$this->Other','$this->Description','$this->IsAvailable','$this->IsActive','$this->CreatedOn' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE jobPosts SET  StudentID = '$this->StudentID',SubjectID = '$this->SubjectID',Other = '$this->Other',Description = '$this->Description',IsAvailable = '$this->IsAvailable',IsActive = '$this->IsActive',CreatedOn = '$this->CreatedOn' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>