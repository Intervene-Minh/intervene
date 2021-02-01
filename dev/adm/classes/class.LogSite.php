<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        LogSite
* GENERATION DATE:  16.06.2014
* CLASS FILE:       /home6/ptwogorg/public_html/adm/classgen/generated_classes/class.LogSite.php
* FOR MYSQL TABLE:  logSite
* FOR MYSQL DB:     ptwogorg_main
* -------------------------------------------------------

*/

include_once("resources/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class LogSite { // class : begin
	
	var $debug = false;
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	
	var $Type;   // (normal Attribute)
	var $Message;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $CreatedBy;   // (normal Attribute)
	var $IPAddress;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function LogSite() {
		
		// -----------------------------
			
		$this->database = new Database();
		$this->setIPAddress($_SERVER['REMOTE_ADDR']);
		$this->ui_user_agent = $_SERVER['HTTP_USER_AGENT'];
	}
	
	
	// **********************
	// GETTER METHODS
	// **********************
		
	function getID()
	{
	return $this->ID;
	}
	
	function getType()
	{
	return $this->Type;
	}
	
	function getMessage()
	{
	return $this->Message;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getCreatedBy()
	{
	return $this->CreatedBy;
	}
	
	function getIPAddress()
	{
	return $this->IPAddress;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
	$this->ID =  $val;
	}
	
	function setType($val)
	{
	$this->Type =  $val;
	}
	
	function setMessage($val)
	{
	$this->Message =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setCreatedBy($val)
	{
	$this->CreatedBy =  $val;
	}
	
	function setIPAddress($val)
	{
	$this->IPAddress =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	if ($this->debug == true) {
		print("Select: "."<br>");
	}
	$sql =  "SELECT * FROM logSite WHERE  = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->Type = $row->Type;
	
	$this->Message = $row->Message;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->CreatedBy = $row->CreatedBy;
	
	$this->IPAddress = $row->IPAddress;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
		if ($this->debug == true) {
		print("Delete: "."<br>");
	}
		
	$sql = "DELETE FROM logSite WHERE  = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT - returns lastInsertID
	// **********************
	
	function insert()
	{
		//'$this->CreatedOn',
		$this->ID = ""; // clear key for autoincrement
		
		$sql = "
			INSERT INTO logSite (
			ID,
			Type,
			Message,
			CreatedOn,
			CreatedBy,
			IPAddress 
			) VALUES ( 
			'$this->ID',
			'$this->Type',
			'$this->Message',
			DATE_ADD(NOW(),INTERVAL 1 HOUR),
			'$this->CreatedBy',
			'$this->IPAddress' 
			)";
			
		$result = $this->database->query($sql);
		return $this->ID = mysqli_insert_id($this->database->link);
		
	}
	
	function Save() {
		return $this->insert();	
	}
	
	// **********************
	// CREATE A Log Entry
	// **********************
	
	function Create($Type, $Message, $CreatedBy) {
		
		// 1. Populate new data
		$this->setType($Type);
		$this->setMessage($Message);
		$this->setCreatedBy($CreatedBy);
		$this->setIPAddress($_SERVER['REMOTE_ADDR']);
		// Note: 'CreatedOn' -> date is set on SAVE/INSERT by MYSQL
		
		// 2. Save the object and return		
		return $this->Save();
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	if ($this->debug == true) {
		print("Update: "."<br>");
	}
	
	
	$sql = " UPDATE logSite SET  ID = '$this->ID',Type = '$this->Type',Message = '$this->Message',CreatedOn = '$this->CreatedOn',CreatedBy = '$this->CreatedBy',IPAddress = '$this->IPAddress' WHERE  = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}


} // class : end

?>