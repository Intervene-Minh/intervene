<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        W9TaxInfo
	* GENERATION DATE:  07.10.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.W9TaxInfo.php
	* FOR MYSQL TABLE:  w9info
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
	
	class W9TaxInfo
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $TutorID;   // (normal Attribute)
	var $FirstName;   // (normal Attribute)
	var $LastName;   // (normal Attribute)
	var $BusinessName;   // (normal Attribute)
	var $Address;   // (normal Attribute)
	var $Address2;   // (normal Attribute)
	var $City;   // (normal Attribute)
	var $State;   // (normal Attribute)
	var $Zip;   // (normal Attribute)
	var $Phone;   // (normal Attribute)
	var $TIN;   // (normal Attribute)
	var $Agree;   // (normal Attribute)
	var $Signature;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $UpdatedOn;   // (normal Attribute)
	var $IsActive;   // (normal Attribute)
	var $taxclass;
	var $llctype;
	var $taxother;
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function W9TaxInfo()
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
	
	function getFirstName()
	{
	return $this->FirstName;
	}
	
	function getLastName()
	{
	return $this->LastName;
	}
	
	function getBusinessName()
	{
	return $this->BusinessName;
	}
	
	function getAddress()
	{
	return $this->Address;
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
	
	function getZip()
	{
	return $this->Zip;
	}
	
	function getPhone()
	{
	return $this->Phone;
	}
	
	function getTIN()
	{
	return $this->TIN;
	}
	
	function getAgree()
	{
	return $this->Agree;
	}
	
	function getSignature()
	{
	return $this->Signature;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getUpdatedOn()
	{
	return $this->UpdatedOn;
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
	
	function setFirstName($val)
	{
	$this->FirstName =  $val;
	}
	
	function setLastName($val)
	{
	$this->LastName =  $val;
	}
	
	function setBusinessName($val)
	{
	$this->BusinessName =  $val;
	}
	
	function setAddress($val)
	{
	$this->Address =  $val;
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
	
	function setZip($val)
	{
	$this->Zip =  $val;
	}
	
	function setPhone($val)
	{
	$this->Phone =  $val;
	}
	
	function setTIN($val)
	{
	$this->TIN =  $val;
	}
	
	function setAgree($val)
	{
	$this->Agree =  $val;
	}
	
	function setSignature($val)
	{
	$this->Signature =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setUpdatedOn($val)
	{
	$this->UpdatedOn =  $val;
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
	
		$sql =  "SELECT * FROM w9info WHERE ID = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	
	}
	
	function selectByTutorID($tutorID)
	{
	
		$sql =  "SELECT * FROM w9info WHERE TutorID = $tutorID;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	
	}
	
	function hasInfo($tutorID)
	{
	
		$sql =  "SELECT * FROM w9info WHERE TutorID = $tutorID;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		$count = mysqli_num_rows($result);
		
		if ($count >= 1) 
			return true;
		else 
			return false;	
	}
	
	
	function populateSelf($row) {
	
	$this->ID = $row->ID;
	
	$this->TutorID = $row->TutorID;
	
	$this->FirstName = $row->FirstName;
	
	$this->LastName = $row->LastName;
	
	$this->BusinessName = $row->BusinessName;
	
	$this->Address = $row->Address;
	
	$this->Address2 = $row->Address2;
	
	$this->City = $row->City;
	
	$this->State = $row->State;
	
	$this->Zip = $row->Zip;
	
	$this->Phone = $row->Phone;
	
	$this->TIN = $row->TIN;
	
	$this->Agree = $row->Agree;
	
	$this->Signature = $row->Signature;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->UpdatedOn = $row->UpdatedOn;
	
	$this->IsActive = $row->IsActive;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM w9info WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO w9info ( TutorID,FirstName,LastName,BusinessName,Address,Address2,City,State,Zip,Phone,TIN,Agree,Signature,CreatedOn,UpdatedOn,IsActive,tax_class,llc_type,tax_other ) VALUES ( '$this->TutorID','$this->FirstName','$this->LastName','$this->BusinessName','$this->Address','$this->Address2','$this->City','$this->State','$this->Zip','$this->Phone','$this->TIN','$this->Agree','$this->Signature',DATE_ADD(NOW(), INTERVAL 1 HOUR),'$this->UpdatedOn','$this->IsActive','$this->taxclass','$this->llctype','$this->taxother' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	return $this->ID;
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE w9info SET  TutorID = '$this->TutorID',FirstName = '$this->FirstName',LastName = '$this->LastName',BusinessName = '$this->BusinessName',Address = '$this->Address',Address2 = '$this->Address2',City = '$this->City',State = '$this->State',Zip = '$this->Zip',Phone = '$this->Phone',TIN = '$this->TIN',Agree = '$this->Agree',Signature = '$this->Signature',CreatedOn = '$this->CreatedOn',UpdatedOn = DATE_ADD(NOW(), INTERVAL 1 HOUR),IsActive = '$this->IsActive', tax_class='$this->taxclass',llc_type='$this->llctype',tax_other='$this->llctype' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	public function getTaxclass() {
		return $this->taxclass;
	}
	public function setTaxclass($taxclass) {
		$this->taxclass = $taxclass;
		return $this;
	}
	public function getLlctype() {
		return $this->llctype;
	}
	public function setLlctype($llctype) {
		$this->llctype = $llctype;
		return $this;
	}
	public function getTaxother() {
		return $this->taxother;
	}
	public function setTaxother($taxother) {
		$this->taxother = $taxother;
		return $this;
	}
	
	
	
	} // class : end
	
?>