<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        TutorProfile
	* GENERATION DATE:  11.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.TutorProfile.php
	* FOR MYSQL TABLE:  tutorProfiles
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
	
	class TutorProfile
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $TutorID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $Picture;   // (normal Attribute)
	var $picture_approved;
	var $Headline;   // (normal Attribute)
	var $Description;   // (normal Attribute)
	var $TravelRadius;   // (normal Attribute)
	var $Address1;   // (normal Attribute)
	var $Address2;   // (normal Attribute)
	var $City;   // (normal Attribute)
	var $State;   // (normal Attribute)
	var $ZipCode;   // (normal Attribute)
	var $phone;
	var $payment_method;
	var $Country;   // (normal Attribute)
	var $BackgroundCheck;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	var $IsComplete;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $Notes;   // (normal Attribute)
	var $Approved;   // (normal Attribute)
	var $Rating;   // (normal Attribute)
	var $IPAddress;   // (normal Attribute)
	var $ShowWelcome;   // (normal Attribute)
	var $DOB;   // (normal Attribute)
	
	var $database; // Instance of class database
	var $w9;
	var $payment;
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function TutorProfile()
	{
	
	$this->database = new Database();
	
	}
	
	
	// **********************
	// GETTER METHODS
	// **********************
	
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getPicture()
	{
	return $this->Picture;
	}
	
	function getHeadline()
	{
	return $this->Headline;
	}
	
	function getDescription()
	{
	return $this->Description;
	}
	
	function getTravelRadius()
	{
	return $this->TravelRadius;
	}
	
	function getAddress1()
	{
	return $this->Address1;
	}
	
	function getAddress2()
	{
	return $this->Address2;
	}
	
	function getCity()
	{
	return $this->City;
	}
	
	function getState()
	{
	return $this->State;
	}
	
	function getZipCode()
	{
	return $this->ZipCode;
	}
	
	function getCountry()
	{
	return $this->Country;
	}
	
	function getBackgroundCheck()
	{
	return $this->BackgroundCheck;
	}
	
	function getIsActive()
	{
	return $this->IsActive;
	}
	
	function getIsComplete()
	{
	return $this->IsComplete;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getNotes()
	{
	return $this->Notes;
	}
	
	function getApproved()
	{
	return $this->Approved;
	}
	
	function getRating()
	{
	return $this->Rating;
	}
	
	function getIPAddress()
	{
	return $this->IPAddress;
	}
	
	function getShowWelcome()
	{
	return $this->ShowWelcome;
	}
	
	function getDOB()
	{
	return $this->DOB;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setPicture($val)
	{
	$this->Picture =  $val;
	$this->picture_approved=0;
	}
	
	function setHeadline($val)
	{
	$this->Headline =  $val;
	}
	
	function setDescription($val)
	{
	$this->Description =  $val;
	}
	
	function setTravelRadius($val)
	{
	$this->TravelRadius =  $val;
	}
	
	function setAddress1($val)
	{
	$this->Address1 =  $val;
	}
	
	function setAddress2($val)
	{
	$this->Address2 =  $val;
	}
	
	function setCity($val)
	{
	$this->City =  $val;
	}
	
	function setState($val)
	{
	$this->State =  $val;
	}
	
	function setZipCode($val)
	{
	$this->ZipCode =  $val;
	}
	
	function setCountry($val)
	{
	$this->Country =  $val;
	}
	
	function setBackgroundCheck($val)
	{
	$this->BackgroundCheck =  $val;
	}
	
	function setIsActive($val)
	{
	$this->IsActive =  $val;
	}
	
	function setIsComplete($val)
	{
	$this->IsComplete =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setNotes($val)
	{
	$this->Notes =  $val;
	}
	
	function setApproved($val)
	{
	$this->Approved =  $val;
	}
	
	function setRating($val)
	{
	$this->Rating =  $val;
	}
	
	function setShowWelcome($val)
	{
	$this->ShowWelcome =  $val;
	}
	
	function setIPAddress($val)
	{
	$this->IPAddress =  $val;
	}
	
	function setDOB($val)
	{
	$this->DOB =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($tutorID)
	{
	
		$sql =  "SELECT * FROM tutorProfiles WHERE TutorID = $tutorID;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	}
	
	function populateSelf($row) {
	
	$this->TutorID = $row->TutorID;
	$this->picture_approved=$row->picture_approved;
	
	$this->Picture = $row->Picture;

	
	$this->Headline = $row->Headline;
	
	$this->Description = $row->Description;
	
	$this->TravelRadius = $row->TravelRadius;
	
	$this->Address1 = $row->Address1;
	
	$this->Address2 = $row->Address2;
	
	$this->City = $row->City;
	
	$this->State = $row->State;
	$this->phone=$row->phone;
	
	$this->ZipCode = $row->ZipCode;
	
	$this->Country = $row->Country;
	
	$this->BackgroundCheck = $row->BackgroundCheck;
	
	$this->IsActive = $row->IsActive;
	
	$this->IsComplete = $row->IsComplete;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->Notes = $row->Notes;
	
	$this->Approved = $row->Approved;
	
	$this->Rating = $row->Rating;
	
	$this->IPAddress = $row->IPAddress;
	
	$this->ShowWelcome = $row->ShowWelcome;
	
	$this->DOB = $row->DOB;
	$this->payment_method=$row->payment_method;
	$this->w9=$row->w9;
	$this->payment=$row->payment;
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($tutorID)
	{
	$sql = "DELETE FROM tutorProfiles WHERE TutorID = $tutorID;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	//$this->TutorID = ""; // don't clear key for autoincrement
	
	$sql = "INSERT INTO tutorProfiles ( TutorID, Picture,Headline,Description,TravelRadius,Address1,Address2,City,State,ZipCode,Country,BackgroundCheck,IsActive,IsComplete,CreatedOn,Notes,Approved,Rating,IPAddress,ShowWelcome,DOB,picture_approved ) VALUES ($this->TutorID, '$this->Picture','$this->Headline','$this->Description','$this->TravelRadius','$this->Address1','$this->Address2','$this->City','$this->State','$this->ZipCode','$this->Country','$this->BackgroundCheck','$this->IsActive','$this->IsComplete','$this->CreatedOn','$this->Notes','$this->Approved','$this->Rating','$this->IPAddress','$this->ShowWelcome','$this->DOB',$this->picture_approved )";
	$result = $this->database->query($sql);
	$this->TutorID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($tutorID)
	{
	
	
	
	$sql = " UPDATE tutorProfiles SET  Picture = '$this->Picture',Headline = '$this->Headline',Description = '$this->Description',TravelRadius = '$this->TravelRadius',Address1 = '$this->Address1',Address2 = '$this->Address2',City = '$this->City',State = '$this->State',ZipCode = '$this->ZipCode',Country = '$this->Country',BackgroundCheck = '$this->BackgroundCheck',IsActive = '$this->IsActive',IsComplete = '$this->IsComplete',CreatedOn = '$this->CreatedOn',Notes = '$this->Notes',Approved = '$this->Approved',Rating = '$this->Rating',IPAddress = '$this->IPAddress',ShowWelcome = '$this->ShowWelcome',DOB = '$this->DOB',picture_approved=$this->picture_approved, payment_method='$this->payment_method', phone='$this->phone', w9=$this->w9,payment=$this->payment WHERE TutorID = $tutorID ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	function save(){
		$this->update($this->TutorID);
	}
	function getUnapprovedImages(){
		$sql="Select TutorId, Picture, picture_approved from tutorProfiles where picture_approved =0 and Picture is not null";
		$result =  $this->database->query($sql);
	
		$result = $this->database->result;
		$images=array();
		while($row = mysqli_fetch_object($result)){
			if(!empty($row->Picture)){
				$images[]=$row;
			}
		}
		return $images;
	}
	function countPendingImages(){
		$sql="Select count(*) from tutorProfiles where picture_approved =0 and Picture is not null and Picture !=\"\"";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row=$result->fetch_row();
		return $row[0];
	}
	
	// **********************
	// CLEAR PROFILE PHOTO
	// **********************
	function ClearPhoto() {
		$path2Photo = $this->Picture;
		$deletedFolderName = "/_deleted/";
		
		// -- If the file exists... move to deleted folder
		if ( file_exists($path2Photo) ) {
			// rename moves file to deleted folder
			if ( rename($path2Photo, dirname($path2Photo).$deletedFolderName.basename($path2Photo)) )  {
				
				//echo "The file ".  basename($value). " has been cleared<br />";
				$this->setPicture(""); // clear db entry
				$this->update($this->TutorID); // update this record
				return true;
			} else {
				echo "cTP:383 - There was an error clearing the file, please try again!";
				echo dirname($path2Photo).$deletedFolderName.basename($path2Photo);
				return false;
				
			}
		}		
		echo dirname($path2Photo).$deletedFolderName.basename($path2Photo);
		return false;
	}
	public function getPhone() {
		return $this->phone;
	}
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}
	public function getPaymentMethod() {
		return $this->payment_method;
	}
	public function setPaymentMethod($payment_method) {
		$this->payment_method = $payment_method;
		return $this;
	}
	
	
	
	} // class : end
	
?>