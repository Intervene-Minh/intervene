<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        EditablePageContent
	* GENERATION DATE:  29.10.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.EditablePageContent.php
	* FOR MYSQL TABLE:  editablePageContent
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
	
	class EditablePageContent
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $Title;   // (normal Attribute)
	var $Content;   // (normal Attribute)
	var $LastEditBy;   // (normal Attribute)
	var $UpdatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function EditablePageContent()
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
	
	function getTitle()
	{
	return $this->Title;
	}
	
	function getContent()
	{
	return $this->Content;
	}
	
	function getLastEditBy()
	{
	return $this->LastEditBy;
	}
	
	function getUpdatedOn()
	{
	return $this->UpdatedOn;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
	$this->ID =  $val;
	}
	
	function setTitle($val)
	{
	$this->Title =  $val;
	}
	
	function setContent($val)
	{
	$this->Content =  $val;
	}
	
	function setLastEditBy($val)
	{
	$this->LastEditBy =  $val;
	}
	
	function setUpdatedOn($val)
	{
	$this->UpdatedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM editablePageContent WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->Title = $row->Title;
	
	$this->Content = $row->Content;
	
	$this->LastEditBy = $row->LastEditBy;
	
	$this->UpdatedOn = $row->UpdatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM editablePageContent WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO editablePageContent ( Title,Content,LastEditBy,UpdatedOn ) VALUES ( '$this->Title','$this->Content','$this->LastEditBy',DATE_ADD(NOW(), INTERVAL 1 HOUR) )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE editablePageContent SET  Title = '$this->Title',Content = '$this->Content',LastEditBy = '$this->LastEditBy', UpdatedOn = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>