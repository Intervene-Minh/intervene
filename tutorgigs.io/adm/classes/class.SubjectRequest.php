<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        SubjectRequest
	* GENERATION DATE:  25.10.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.SubjectRequest.php
	* FOR MYSQL TABLE:  subjectRequests
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
	
	class SubjectRequest
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $RequestorID;   // (normal Attribute)
	var $RequestorType;   // (normal Attribute)
	var $Subject;   // (normal Attribute)
	var $Description;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function SubjectRequest()
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
	
	function getRequestorID()
	{
	return $this->RequestorID;
	}
	
	function getRequestorType()
	{
	return $this->RequestorType;
	}
	
	function getSubject()
	{
	return $this->Subject;
	}
	
	function getDescription()
	{
	return $this->Description;
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
	
	function setRequestorID($val)
	{
	$this->RequestorID =  $val;
	}
	
	function setRequestorType($val)
	{
	$this->RequestorType =  $val;
	}
	
	function setSubject($val)
	{
	$this->Subject =  $val;
	}
	
	function setDescription($val)
	{
	$this->Description =  $val;
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
	
	$sql =  "SELECT * FROM subjectRequests WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->RequestorID = $row->RequestorID;
	
	$this->RequestorType = $row->RequestorType;
	
	$this->Subject = $row->Subject;
	
	$this->Description = $row->Description;
	
	$this->CreatedOn = $row->CreatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM subjectRequests WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO subjectRequests ( RequestorID,RequestorType,Subject,Description,CreatedOn ) VALUES ( '$this->RequestorID','$this->RequestorType','$this->Subject','$this->Description',DATE_ADD(NOW(), INTERVAL 1 HOUR) )";
	$result = $this->database->query($sql);
	//return mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE subjectRequests SET  RequestorID = '$this->RequestorID',RequestorType = '$this->RequestorType',Subject = '$this->Subject',Description = '$this->Description',CreatedOn = '$this->CreatedOn' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
}	
?>