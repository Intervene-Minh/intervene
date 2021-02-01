<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        StudentPaymentInfo
	* GENERATION DATE:  06.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.StudentPaymentInfo.php
	* FOR MYSQL TABLE:  studentPaymentInfo
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
	
	class StudentPaymentInfo
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $StudentID;   // (normal Attribute)
	var $Name;   // (normal Attribute)
	var $CardNo;   // (normal Attribute)
	var $ExprMo;   // (normal Attribute)
	var $ExprYr;   // (normal Attribute)
	var $CVV;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $LastUpdatedOn;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function StudentPaymentInfo()
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
	
	function getName()
	{
	return $this->Name;
	}
	
	function getCardNo()
	{
	return $this->CardNo;
	}
	
	function getExprMo()
	{
	return $this->ExprMo;
	}
	
	function getExprYr()
	{
	return $this->ExprYr;
	}
	
	function getCVV()
	{
	return $this->CVV;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getLastUpdatedOn()
	{
	return $this->LastUpdatedOn;
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
	
	function setName($val)
	{
	$this->Name =  $val;
	}
	
	function setCardNo($val)
	{
	$this->CardNo =  $val;
	}
	
	function setExprMo($val)
	{
	$this->ExprMo =  $val;
	}
	
	function setExprYr($val)
	{
	$this->ExprYr =  $val;
	}
	
	function setCVV($val)
	{
	$this->CVV =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setLastUpdatedOn($val)
	{
	$this->LastUpdatedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM studentPaymentInfo WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->StudentID = $row->StudentID;
	
	$this->Name = $row->Name;
	
	$this->CardNo = $row->CardNo;
	
	$this->ExprMo = $row->ExprMo;
	
	$this->ExprYr = $row->ExprYr;
	
	$this->CVV = $row->CVV;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->LastUpdatedOn = $row->LastUpdatedOn;
	
	}
	
	function selectByStudentID($sid)
	{
	
	$sql =  "SELECT * FROM studentPaymentInfo WHERE StudentID = $sid ORDER BY ID DESC;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->StudentID = $row->StudentID;
	
	$this->Name = $row->Name;
	
	$this->CardNo = $row->CardNo;
	
	$this->ExprMo = $row->ExprMo;
	
	$this->ExprYr = $row->ExprYr;
	
	$this->CVV = $row->CVV;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->LastUpdatedOn = $row->LastUpdatedOn;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM studentPaymentInfo WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO studentPaymentInfo ( StudentID,Name,CardNo,ExprMo,ExprYr,CVV,CreatedOn,LastUpdatedOn ) VALUES ( '$this->StudentID','$this->Name','$this->CardNo','$this->ExprMo','$this->ExprYr','$this->CVV',DATE_ADD(NOW(), INTERVAL 1 HOUR),DATE_ADD(NOW(), INTERVAL 1 HOUR) )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE studentPaymentInfo SET  StudentID = '$this->StudentID',Name = '$this->Name',CardNo = '$this->CardNo',ExprMo = '$this->ExprMo',ExprYr = '$this->ExprYr',CVV = '$this->CVV',CreatedOn = DATE_ADD(NOW(), INTERVAL 1 HOUR),LastUpdatedOn = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
	?>