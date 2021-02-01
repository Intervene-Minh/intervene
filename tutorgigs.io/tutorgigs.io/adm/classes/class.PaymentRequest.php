<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        PaymentRequest
	* GENERATION DATE:  19.08.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.PaymentRequest.php
	* FOR MYSQL TABLE:  PaymentRequests
	* FOR MYSQL DB:     ptwogorg_main
	* -------------------------------------------------------
	* CODE BY:
	* Kevin Pryce
	* for: P2G.org
	* -------------------------------------------------------
	*
	*/
	
	include_once("resources/class.database.php");
	include_once("resources/databasePDO.php");
	
	// **********************
	// CLASS DECLARATION
	// **********************
	
	class PaymentRequest
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $SessionID;   // (normal Attribute)
	var $TutorID;   // (normal Attribute)
	var $HoursToPay;   // (normal Attribute)
	var $Description;   // (normal Attribute)
	var $StudentAccept;   // (normal Attribute)
	var $CreatedOn;   // (normal Attribute)
	var $Approved;   // (normal Attribute)
	var $ApprovedBy;   // (normal Attribute)
	var $ApprovedOn;   // (normal Attribute)
	var $Paid;
	var $PaidOn;
	var $TransactionId;
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function PaymentRequest()
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
	
	function getTutorID()
	{
	return $this->TutorID;
	}
	
	function getHoursToPay()
	{
	return $this->HoursToPay;
	}
	
	function getDescription()
	{
	return $this->Description;
	}
	
	function getStudentAccept()
	{
	return $this->StudentAccept;
	}
	
	function getCreatedOn()
	{
	return $this->CreatedOn;
	}
	
	function getApproved()
	{
	return $this->Approved;
	}
	
	function getApprovedBy()
	{
	return $this->ApprovedBy;
	}
	
	function getApprovedOn()
	{
	return $this->ApprovedOn;
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
	
	function setTutorID($val)
	{
	$this->TutorID =  $val;
	}
	
	function setHoursToPay($val)
	{
	$this->HoursToPay =  $val;
	}
	
	function setDescription($val)
	{
	$this->Description =  $val;
	}
	
	function setStudentAccept($val)
	{
	$this->StudentAccept =  $val;
	}
	
	function setCreatedOn($val)
	{
	$this->CreatedOn =  $val;
	}
	
	function setApproved($val)
	{
	$this->Approved =  $val;
	}
	
	function setApprovedBy($val)
	{
	$this->ApprovedBy =  $val;
	}
	
	function setApprovedOn($val)
	{
	$this->ApprovedOn =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
	$sql =  "SELECT * FROM PaymentRequests WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->SessionID = $row->SessionID;
	
	$this->TutorID = $row->TutorID;
	
	$this->HoursToPay = $row->HoursToPay;
	
	$this->Description = $row->Description;
	
	$this->StudentAccept = $row->StudentAccept;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->Approved = $row->Approved;
	
	$this->ApprovedBy = $row->ApprovedBy;
	
	$this->ApprovedOn = $row->ApprovedOn;
	$this->Paid=$row->Paid;
	$this->PaidOn=$row->PaidOn;
	$this->TransactionId=$row->TransactionId;
	
	}
	
	function selectAllByTutorID($tutorID)
	{
	
	$sql =  "SELECT * FROM PaymentRequests WHERE TutorID = $tutorID ORDER BY CreatedOn DESC;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	//$row = mysqli_fetch_object($result); // removes first row.. don't need
	return $result;
	}
	
	function selectTotalsByTutorID($tutorID){
		$sql="Select pr.id, pr.Approved,pr.Paid, TutorID,From PaymentRequests pr join TutorSessions s on s.id=pr.SessionID";
	}
	function selectPaidByTutorId($tutorID){
		$sql =  "SELECT pr.*,s.rate, s.rate*pr.HoursToPay as total FROM  PaymentRequests pr join tutorSessions s on s.id=pr.SessionID where pr.TutorID = $tutorID and Paid=1 ORDER BY pr.CreatedOn DESC";
		$db=new DatabasePDO();
		return $db->Select($sql);
	}
	function selectPendingByTutorId($tutorID){
		$sql =  "SELECT pr.*,s.rate, s.rate*pr.HoursToPay as total FROM  PaymentRequests pr join tutorSessions s on s.id=pr.SessionID where pr.TutorID = $tutorID and Approved=0 and Paid=0 ORDER BY pr.CreatedOn DESC";
	$db=new DatabasePDO();
			
		return $db->Select($sql);
	}
	function selectApprovedByTutorId($tutorID){
		$sql =  "SELECT pr.*,s.rate, s.rate*pr.HoursToPay as total FROM  PaymentRequests pr join tutorSessions s on s.id=pr.SessionID where pr.TutorID = $tutorID and Approved=1 and Paid=0 ORDER BY pr.CreatedOn DESC";
		$db=new DatabasePDO();
		
		
		return $db->Select($sql);
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM PaymentRequests WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO PaymentRequests ( SessionID,TutorID,HoursToPay,Description,StudentAccept,CreatedOn,Approved,ApprovedBy,ApprovedOn ) VALUES ( '$this->SessionID','$this->TutorID','$this->HoursToPay','$this->Description','$this->StudentAccept',DATE_ADD(NOW(),INTERVAL 1 HOUR),'$this->Approved','$this->ApprovedBy','$this->ApprovedOn' )";
	$this->ID = $this->database->insert($sql);
		return $this->ID;
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE PaymentRequests SET  SessionID = '$this->SessionID',TutorID = '$this->TutorID',HoursToPay = '$this->HoursToPay',Description = '$this->Description',StudentAccept = '$this->StudentAccept',CreatedOn = '$this->CreatedOn',Approved = '$this->Approved',ApprovedBy = '$this->ApprovedBy',ApprovedOn = '$this->ApprovedOn' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	public function getPaid() {
		return $this->Paid;
	}
	public function setPaid($Paid) {
		$this->Paid = $Paid;
		return $this;
	}
	public function getPaidOn() {
		return $this->PaidOn;
	}
	public function setPaidOn($PaidOn) {
		$this->PaidOn = $PaidOn;
		return $this;
	}
	public function getTransactionId() {
		return $this->TransactionId;
	}
	public function setTransactionId($TransactionId) {
		$this->TransactionId = $TransactionId;
		return $this;
	}
	
	
	
	} // class : end
	
?>