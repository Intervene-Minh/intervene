<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        Representative
	* GENERATION DATE:  22.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.Representative.php
	* FOR MYSQL TABLE:  representatives
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
	
	class Representative
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $FirstName;   // (normal Attribute)
	var $LastName;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	var $Notes;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function Representative()
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
	
	function getFirstName()
	{
	return $this->FirstName;
	}
	
	function getLastName()
	{
	return $this->LastName;
	}
	
	function getIsActive()
	{
	return $this->IsActive;
	}
	
	function getNotes()
	{
	return $this->Notes;
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
	
	function setFirstName($val)
	{
	$this->FirstName =  $val;
	}
	
	function setLastName($val)
	{
	$this->LastName =  $val;
	}
	
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	function setNotes($val)
	{
	$this->Notes =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function selectAllReps()
	{	
		$sql =  "SELECT * FROM representatives ";
		$result =  $this->database->query($sql);
		return $this->database->result;
	}
	
	function getNumTutorsByRepID($repID) {
		$sql =  "SELECT ID FROM tutors WHERE RepID = $repID";
		$result =  $this->database->query($sql);
		return mysqli_num_rows($this->database->result);
	}
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM representatives WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->FirstName = $row->FirstName;
	
	$this->LastName = $row->LastName;
	
	$this->IsActive = $row->IsActive;
	
	$this->Notes = $row->Notes;
	
	$this->CreatedOn = $row->CreatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM representatives WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO representatives ( FirstName,LastName,IsActive,Notes,CreatedOn ) VALUES ( '$this->FirstName','$this->LastName','$this->IsActive','$this->Notes','$this->CreatedOn' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE representatives SET  FirstName = '$this->FirstName',LastName = '$this->LastName',IsActive = '$this->IsActive',Notes = '$this->Notes',CreatedOn = '$this->CreatedOn' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>