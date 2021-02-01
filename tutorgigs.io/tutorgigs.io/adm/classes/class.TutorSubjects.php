<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        TutorSubjects
	* GENERATION DATE:  11.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.TutorSubjects.php
	* FOR MYSQL TABLE:  tutorSubjects
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
	
	class TutorSubjects
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $SubjectID;   // (normal Attribute)
	var $TutorID;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	var $Certified;   // (normal Attribute)
	var $CertifiedOn;   // (normal Attribute)
	var $Rate;   // (normal Attribute)
	var $Experience;   // (normal Attribute)
	var $Approved;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function TutorSubjects()
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
	
	function getSubjectID()
	{
	return $this->SubjectID;
	}
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getIsActive()
	{
	return $this->IsActive;
	}
	
	function getCertified()
	{
	return $this->Certified;
	}
	
	function getCertifiedOn()
	{
	return $this->CertifiedOn;
	}
	
	function getRate()
	{
	return $this->Rate;
	}
	
	function getExperience()
	{
	return $this->Experience;
	}
	
	function getApproved()
	{
	return $this->Approved;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setID($val)
	{
	$this->ID =  $val;
	}
	
	function setSubjectID($val)
	{
	$this->SubjectID =  $val;
	}
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	function setCertified($val)
	{
	$this->Certified =  $val;
	}
	
	function setCertifiedOn($val)
	{
	$this->CertifiedOn =  $val;
	}
	
	function setRate($val)
	{
	$this->Rate =  $val;
	}
	
	function setExperience($val)
	{
	$this->Experience =  $val;
	}
	
	function setApproved($val)
	{
	$this->Approved =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM tutorSubjects WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	$this->ID = $row->ID;
	
	$this->SubjectID = $row->SubjectID;
	
	$this->TutorID = $row->TutorID;
	
	$this->IsActive = $row->IsActive;
	
	$this->Certified = $row->Certified;
	
	$this->CertifiedOn = $row->CertifiedOn;
	
	$this->Rate = $row->Rate;
	
	$this->Experience = $row->Experience;
	
	$this->Approved = $row->Approved;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM tutorSubjects WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO tutorSubjects ( SubjectID,TutorID,IsActive,Certified,CertifiedOn,Rate,Experience,Approved ) VALUES ( '$this->SubjectID','$this->TutorID','$this->IsActive','$this->Certified','$this->CertifiedOn','$this->Rate','$this->Experience','$this->Approved' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE tutorSubjects SET  SubjectID = '$this->SubjectID',TutorID = '$this->TutorID',IsActive = '$this->IsActive',Certified = '$this->Certified',CertifiedOn = '$this->CertifiedOn',Rate = '$this->Rate',Experience = '$this->Experience',Approved = '$this->Approved' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>