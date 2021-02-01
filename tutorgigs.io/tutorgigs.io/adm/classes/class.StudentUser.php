<?php
	/*
	*
	* -------------------------------------------------------
	* CLASSNAME:        StudentUser
	* GENERATION DATE:  15.09.2014
	* CLASS FILE:       /home6/ptwogorg/public_html/site/classes/class.StudentUser.php
	* FOR MYSQL TABLE:  studentUsers
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
	
	class StudentUser
	{ // class : begin
	
	
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	
	var $ID;   // KEY ATTR. WITH AUTOINCREMENT
	
	var $id;   // (normal Attribute)
	var $username;   // (normal Attribute)
	var $password;   // (normal Attribute)
	var $salt;   // (normal Attribute)
	var $email;   // (normal Attribute)
	var $studentID;   // (normal Attribute)
	var $lastlogon;   // (normal Attribute)
	var $ipaddress;   // (normal Attribute)
	var $lastlogout;   // (normal Attribute)
	var $IsSignedIn;   // (normal Attribute)
	var $IsResettingPassword;
	var $TempPassword;
	
	var $database; // Instance of class database
	
	
	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	
	function StudentUser()
	{
	
	$this->database = new Database();
	
	}
	
	
	// **********************
	// GETTER METHODS
	// **********************
	
	
	function getid()
	{
	return $this->id;
	}
	
	function getusername()
	{
	return $this->username;
	}
	
	function getpassword()
	{
	return $this->password;
	}
	
	function getsalt()
	{
	return $this->salt;
	}
	
	function getemail()
	{
	return $this->email;
	}
	
	function getstudentID()
	{
	return $this->studentID;
	}
	
	function getlastlogon()
	{
	return $this->lastlogon;
	}
	
	function getipaddress()
	{
	return $this->ipaddress;
	}
	
	function getlastlogout()
	{
	return $this->lastlogout;
	}
	
	function getIsSignedIn()
	{
	return $this->IsSignedIn;
	}
	function getIsResettingPassword()
	{
	return $this->IsResettingPassword;
	}
	function getTempPassword()
	{
	return $this->TempPassword;
	}
	
	// **********************
	// SETTER METHODS
	// **********************
	
	
	function setid($val)
	{
	$this->id =  $val;
	}
	
	function setusername($val)
	{
	$this->username =  $val;
	}
	
	function setpassword($val) {
		// -- PASSWORD ENCRYPTION --
		
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
        $password = hash('sha256', $val . $salt); 
		
        for ($round = 0; $round < 65536; $round++) { 
			$password = hash('sha256', $password . $salt); 
		}
		
		$this->setsalt($salt);
		$this->password =  $password;
	}
	
	private function setsalt($val)
	{
		$this->salt =  $val;
	}
	
	function setemail($val)
	{
	$this->email =  $val;
	}
	
	function setstudentID($val)
	{
	$this->studentID =  $val;
	}
	
	function setlastlogon($val)
	{
	$this->lastlogon =  $val;
	}
	
	function setipaddress($val)
	{
	$this->ipaddress =  $val;
	}
	
	function setlastlogout($val)
	{
	$this->lastlogout =  $val;
	}
	
	function setIsSignedIn($val)
	{
	$this->IsSignedIn =  $val;
	}
	function setIsResettingPassword($val)
	{
	$this->IsResettingPassword =  $val;
	}
	function setTempPassword($val)
	{
	$this->TempPassword =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	
	function select($id)
	{
	
		$sql =  "SELECT * FROM studentUsers WHERE ID = $id;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	}
	
	function selectByEmail($emailAddr)
	{
	
		$sql =  "SELECT * FROM studentUsers WHERE email='".$emailAddr."';";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	}
	
	function selectByStudentID($studentID)
	{
	
		$sql =  "SELECT * FROM studentUsers WHERE studentID = $studentID;";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		$row = mysqli_fetch_object($result);
		
		$this->populateSelf($row);
	}
	
	function populateSelf($row) {
	
	$this->id = $row->id;
	
	$this->username = $row->username;
	
	$this->password = $row->password;
	
	$this->salt = $row->salt;
	
	$this->email = $row->email;
	
	$this->studentID = $row->studentID;
	
	$this->lastlogon = $row->lastlogon;
	
	$this->ipaddress = $row->ipaddress;
	
	$this->lastlogout = $row->lastlogout;
	
	$this->IsSignedIn = $row->IsSignedIn;
	
	$this->IsResettingPassword = $row->IsResettingPassword;
	
	$this->TempPassword = $row->TempPassword;
	
	}
	
	// **********************
	// DELETE
	// **********************
	
	function delete($id)
	{
	$sql = "DELETE FROM studentUsers WHERE ID = $id;";
	$result = $this->database->query($sql);
	
	}
	
	// **********************
	// INSERT
	// **********************
	
	function insert()
	{
	$this->ID = ""; // clear key for autoincrement
	
	$sql = "INSERT INTO studentUsers ( id,username,password,salt,email,studentID,lastlogon,ipaddress,lastlogout,IsSignedIn, IsResettingPassword, TempPassword ) VALUES ( '$this->id','$this->username','$this->password','$this->salt','$this->email','$this->studentID','$this->lastlogon','$this->ipaddress','$this->lastlogout','$this->IsSignedIn','$this->IsResettingPassword','$this->TempPassword' )";
	$result = $this->database->query($sql);
	$this->ID = mysqli_insert_id($this->database->link);
	
	}
	
	// **********************
	// UPDATE
	// **********************
	
	function update($id)
	{
	
	
	
	$sql = " UPDATE studentUsers SET  id = '$this->id',username = '$this->username',password = '$this->password',salt = '$this->salt',email = '$this->email',studentID = '$this->studentID',lastlogon = '$this->lastlogon',ipaddress = '$this->ipaddress',lastlogout = '$this->lastlogout',IsSignedIn = '$this->IsSignedIn', IsResettingPassword='$this->IsResettingPassword',TempPassword='$this->TempPassword' WHERE ID = $id ";
	
	$result = $this->database->query($sql);
	
	
	
	}
	
	function save() {
		$this->update($this->id);
	}
	
	
	} // class : end
	
?>