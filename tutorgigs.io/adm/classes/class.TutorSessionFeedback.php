<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        TutorSessionFeedback
	* GENERATION DATE:  07.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.TutorSessionFeedback.php
	* FOR MYSQL TABLE:  tutorSessionFeedback
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
	
	class TutorSessionFeedback
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $SessionID;   // (normal Attribute)
	var $SessionFeedback;   // (normal Attribute)
	var $TutorID;   // (normal Attribute)
	var $TutorRating;   // (normal Attribute)
	var $TutorFeedback;   // (normal Attribute)
	var $StudentID;   // (normal Attribute)
	var $P2GRating;   // (normal Attribute)
	var $P2GFeedback;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function TutorSessionFeedback()
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
	
	function getSessionID()
	{
	return $this->SessionID;
	}
	
	function getSessionFeedback()
	{
	return $this->SessionFeedback;
	}
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getTutorRating()
	{
	return $this->TutorRating;
	}
	
	function getTutorFeedback()
	{
	return $this->TutorFeedback;
	}
	
	function getStudentID()
	{
	return $this->StudentID;
	}
	
	function getP2GRating()
	{
	return $this->P2GRating;
	}
	
	function getP2GFeedback()
	{
	return $this->P2GFeedback;
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
	
	function setSessionID($val)
	{
	$this->SessionID =  $val;
	}
	
	function setSessionFeedback($val)
	{
	$this->SessionFeedback =  $val;
	}
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setTutorRating($val)
	{
	$this->TutorRating =  $val;
	}
	
	function setTutorFeedback($val)
	{
	$this->TutorFeedback =  $val;
	}
	
	function setStudentID($val)
	{
	$this->StudentID =  $val;
	}
	
	function setP2GRating($val)
	{
	$this->P2GRating =  $val;
	}
	
	function setP2GFeedback($val)
	{
	$this->P2GFeedback =  $val;
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
	
	$sql =  "SELECT * FROM tutorSessionFeedback WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->SessionID = $row->SessionID;
	
	$this->SessionFeedback = $row->SessionFeedback;
	
	$this->TutorID = $row->TutorID;
	
	$this->TutorRating = $row->TutorRating;
	
	$this->TutorFeedback = $row->TutorFeedback;
	
	$this->StudentID = $row->StudentID;
	
	$this->P2GRating = $row->P2GRating;
	
	$this->P2GFeedback = $row->P2GFeedback;
	
	$this->CreatedOn = $row->CreatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM tutorSessionFeedback WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	function save() { $this->insert(); }
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO tutorSessionFeedback ( SessionID,SessionFeedback,TutorID,TutorRating,TutorFeedback,StudentID,P2GRating,P2GFeedback,CreatedOn ) VALUES ( '$this->SessionID','$this->SessionFeedback','$this->TutorID','$this->TutorRating','$this->TutorFeedback','$this->StudentID','$this->P2GRating','$this->P2GFeedback', DATE_ADD(NOW(), INTERVAL 1 HOUR) )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE tutorSessionFeedback SET  SessionID = '$this->SessionID',SessionFeedback = '$this->SessionFeedback',TutorID = '$this->TutorID',TutorRating = '$this->TutorRating',TutorFeedback = '$this->TutorFeedback',StudentID = '$this->StudentID',P2GRating = '$this->P2GRating',P2GFeedback = '$this->P2GFeedback',CreatedOn = '$this->CreatedOn' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>