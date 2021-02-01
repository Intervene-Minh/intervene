<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        TutorRating
	* GENERATION DATE:  07.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.TutorRating.php
	* FOR MYSQL TABLE:  tutorRatings
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
	
	class TutorRating
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $TutorID;   // (normal Attribute)
	var $StudentID;   // (normal Attribute)
	var $Rating;   // (normal Attribute)
	var $Review;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $IsFlagged;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function TutorRating()
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
	
	function getStudentID()
	{
	return $this->StudentID;
	}
	
	function getRating()
	{
	return $this->Rating;
	}
	
	function getReview()
	{
	return $this->Review;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getIsFlagged()
	{
	return $this->IsFlagged;
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
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setStudentID($val)
	{
	$this->StudentID =  $val;
	}
	
	function setRating($val)
	{
	$this->Rating =  $val;
	}
	
	function setReview($val)
	{
	$this->Review =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	function setIsFlagged($val)
	{
	$this->IsFlagged =  $val;
	}
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{	
		$sql =  "SELECT * FROM tutorRatings WHERE ID = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);	
	}
	
	function loadByTutorID($tutorID)
	{	
		$sql =  "SELECT * FROM tutorRatings WHERE TutorID = $tutorID AND IsActive=1;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		populateSelf($row);	
	}
	function getAllRatingsForTutor($tutorID)
	{	
		$sql =  "SELECT * FROM tutorRatings WHERE TutorID = $tutorID AND IsActive=1;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		//$row = mysqli_fetch_object($result);
		
		return $result;	
	}
	function getAllRatingsByStudent($studentID)
	{	
		$sql =  "SELECT * FROM tutorRatings WHERE StudentID = $studentID AND IsActive=1;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		//$row = mysqli_fetch_object($result);
		
		return $result;	
	}
	function getReviewCountForTutor($tutorID) {
		$sql = "SELECT * from tutorRatings WHERE TutorID = $tutorID AND IsActive=1;";
		$this->database->Query($sql);
		return mysqli_num_rows($this->database->result);
	}
	function getReviewCountForStudent($studentID) {
		$sql = "SELECT * from tutorRatings WHERE StudentID = $studentID AND IsActive=1;";
		$this->database->Query($sql);
		return mysqli_num_rows($this->database->result);
	}
	function getAverageForTutor($tutorID) {
		$sql = "SELECT TutorID, AVG(Rating) FROM tutorRatings WHERE TutorID = $tutorID AND IsActive=1;"; 
		$result =  $this->database->query($sql);
		$row = mysqli_fetch_array($this->database->result);
		
		return $row['AVG(Rating)'] * $this->getReviewCountForTutor($tutorID);		
	}
	function getRawAverageForTutor($tutorID) {
		$sql = "SELECT TutorID, AVG(Rating) FROM tutorRatings WHERE TutorID = $tutorID AND IsActive=1;"; 
		$result =  $this->database->query($sql);
		$row = mysqli_fetch_array($this->database->result);
		return $row['AVG(Rating)'];		
	}
	function getRawAverageForStudent($studentID) {
		$sql = "SELECT StudentID, AVG(Rating) FROM tutorRatings WHERE StudentID = $studentID AND IsActive=1;"; 
		$result =  $this->database->query($sql);
		$row = mysqli_fetch_array($this->database->result);
		return $row['AVG(Rating)'];		
	}
	
	function populateSelf($row) {
	
		$this->ID = $row->ID;
		
		$this->TutorID = $row->TutorID;
		
		$this->StudentID = $row->StudentID;
		
		$this->Rating = $row->Rating;
		
		$this->Review = $row->Review;
		
		$this->CreatedOn = $row->CreatedOn;
		
		$this->IsFlagged = $row->IsFlagged;
		
		$this->IsActive = $row->IsActive;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM tutorRatings WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	function save() { $this->insert(); }
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO tutorRatings ( TutorID,StudentID,Rating,Review,CreatedOn,IsFlagged, IsActive ) VALUES ( '$this->TutorID','$this->StudentID','$this->Rating','$this->Review',DATE_ADD(NOW(), INTERVAL 1 HOUR),'$this->IsFlagged','$this->IsActive' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE tutorRatings SET  TutorID = '$this->TutorID',StudentID = '$this->StudentID',Rating = '$this->Rating',Review = '$this->Review',CreatedOn = '$this->CreatedOn',IsFlagged = '$this->IsFlagged',IsActive = '$this->IsActive' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>