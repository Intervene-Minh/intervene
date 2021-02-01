<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        StudentPayment
	* GENERATION DATE:  27.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.StudentPayment.php
	* FOR MYSQL TABLE:  studentPayments
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
	
	class StudentPayment
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $StudentID;   // (normal Attribute)
	var $SessionID;   // (normal Attribute)
	var $PaymentInfoID;   // (normal Attribute)
	var $Amount;   // (normal Attribute)
	var $TransactionID;   // (normal Attribute)
	var $PaidDate;   // (normal Attribute)
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function StudentPayment()
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
	
	function getSessionID()
	{
	return $this->SessionID;
	}
	
	function getPaymentInfoID()
	{
	return $this->PaymentInfoID;
	}
	
	function getAmount()
	{
	return $this->Amount;
	}
	
	function getTransactionID()
	{
	return $this->TransactionID;
	}
	
	function getPaidDate()
	{
	return $this->PaidDate;
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
	
	function setSessionID($val)
	{
	$this->SessionID =  $val;
	}
	
	function setPaymentInfoID($val)
	{
	$this->PaymentInfoID =  $val;
	}
	
	function setAmount($val)
	{
	$this->Amount =  $val;
	}
	
	function setTransactionID($val)
	{
	$this->TransactionID =  $val;
	}
	
	function setPaidDate($val)
	{
	$this->PaidDate =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
		$sql =  "SELECT * FROM studentPayments WHERE ID = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	}
	
	function selectAllByStudentID($studentID)
	{
	
		$sql =  "SELECT * FROM studentPayments WHERE StudentID = $studentID;";
		$result =  $this->database->query($sql);
		return $this->database->result;
		
	}
	
	function selectAllBySessionID($sessionID)
	{
	
		$sql =  "SELECT * FROM studentPayments WHERE SessionID = $sessionID;";
		$result =  $this->database->query($sql);
		return $this->database->result;
		
	}
	
	function populateSelf($row) {
		
		$this->ID = $row->ID;
		
		$this->StudentID = $row->StudentID;
		
		$this->SessionID = $row->SessionID;
		
		$this->PaymentInfoID = $row->PaymentInfoID;
		
		$this->Amount = $row->Amount;
		
		$this->TransactionID = $row->TransactionID;
		
		$this->PaidDate = $row->PaidDate;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM studentPayments WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{		
		$this->ID = ""; // clear key for autoincrement
		
		$sql = "INSERT INTO studentPayments ( StudentID,SessionID,PaymentInfoID,Amount,TransactionID,PaidDate ) VALUES ( '$this->StudentID','$this->SessionID','$this->PaymentInfoID','$this->Amount','$this->TransactionID',DATE_ADD(NOW(), INTERVAL 1 HOUR) )";
		$result = $this->database->query($sql);
		$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE studentPayments SET  StudentID = '$this->StudentID',SessionID = '$this->SessionID',PaymentInfoID = '$this->PaymentInfoID',Amount = '$this->Amount',TransactionID = '$this->TransactionID',PaidDate = '$this->PaidDate' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	
	} // class : end
	
?>