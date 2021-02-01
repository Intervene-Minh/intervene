<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        Student
* GENERATION DATE:  16.06.2014
* CLASS FILE:       /home6/ptwogorg/public_html/adm/classgen/generated_classes/class.Student.php
* FOR MYSQL TABLE:  students
* FOR MYSQL DB:     ptwogorg_main
* -------------------------------------------------------

* -------------------------------------------------------
*
*/

include_once("resources/class.database.php");
include_once("resources/databasePDO.php");
// **********************
// CLASS DECLARATION
// **********************

class Student
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $ID;   // KEY ATTR. WITH AUTOINCREMENT

var $FirstName;   // (normal Attribute)
var $LastName;   // (normal Attribute)
var $ZipCode;   // (normal Attribute)
var $Email;   // (normal Attribute)
var $Gender;   // (normal Attribute)
var $RepID;   // (normal Attribute)
var $Notes;   // (normal Attribute)
var $Agree;   // (normal Attribute)
var $HasVerifiedEmail;   // (normal Attribute)
var $VerificationCode;   // (normal Attribute)
var $VerifiedEmailOn;   // (normal Attribute)
var $CreatedOn;   // (normal Attribute)
var $IPAddress;   // (normal Attribute)
var $PromoCode;   // (normal Attribute)
var $ParentFirstName;   // (normal Attribute)
var $ParentLastName;   // (normal Attribute)
var $ParentEmail;   // (normal Attribute)
var $HasParentVerifiedEmail;   // (normal Attribute)
var $ParentVerificationCode;   // (normal Attribute)
var $ParentVerifiedEmailOn;   // (normal Attribute)

var $database; // Instance of class database


// **********************
// CONSTRUCTOR METHOD
// **********************

function Student()
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

function getFirstName()
{
return $this->FirstName;
}

function getLastName()
{
return $this->LastName;
}

function getZipCode()
{
return $this->ZipCode;
}

function getEmail()
{
return $this->Email;
}

function getGender()
{
return $this->Gender;
}

function getRepID()
{
return $this->RepID;
}

function getNotes()
{
return $this->Notes;
}

function getAgree()
{
return $this->Agree;
}
function getFullName()
{
return $this->FirstName.' '.$this->LastName;
}
function getShortName()
{
return $this->FirstName.' '.substr($this->LastName,0,1).'.';
}
function getHasVerifiedEmail()
{
return $this->HasVerifiedEmail;
}

function getVerificationCode()
{
return $this->VerificationCode;
}

function getVerifiedEmailOn()
{
return $this->VerifiedEmailOn;
}

function getCreatedOn()
{
return $this->CreatedOn;
}

function getIPAddress()
{
return $this->IPAddress;
}
function getPromoCode()
	{
	return $this->PromoCode;
	}
	
	function getParentFirstName()
	{
	return $this->ParentFirstName;
	}
	
	function getParentLastName()
	{
	return $this->ParentLastName;
	}
	
	function getParentEmail()
	{
	return $this->ParentEmail;
	}
	
	function getHasParentVerifiedEmail()
	{
	return $this->HasParentVerifiedEmail;
	}
	
	function getParentVerificationCode()
	{
	return $this->ParentVerificationCode;
	}
	
	function getParentVerifiedEmailOn()
	{
	return $this->ParentVerifiedEmailOn;
	}
// **********************
// SETTER METHODS
// **********************


function setID($val)
{
$this->ID =  $val;
}

function setFirstName($val)
{
$this->FirstName =  $val;
}

function setLastName($val)
{
$this->LastName =  $val;
}

function setZipCode($val)
{
$this->ZipCode =  $val;
}

function setEmail($val)
{
$this->Email =  $val;
}

function setGender($val)
{
$this->Gender =  $val;
}

function setRepID($val)
{
$this->RepID =  $val;
}

function setNotes($val)
{
$this->Notes =  $val;
}

function setAgree($val)
{
$this->Agree =  $val;
}

function setHasVerifiedEmail($val)
{
$this->HasVerifiedEmail =  $val;
}

function setVerificationCode($val)
{
$this->VerificationCode =  $val;
}

function setVerifiedEmailOn($val)
{
$this->VerifiedEmailOn =  $val;
}

function setCreatedOn($val)
{
$this->CreatedOn =  $val;
}

function setIPAddress($val)
{
$this->IPAddress =  $val;
}
function setPromoCode($val)
	{
	$this->PromoCode =  $val;
	}
	
	function setParentFirstName($val)
	{
	$this->ParentFirstName =  $val;
	}
	
	function setParentLastName($val)
	{
	$this->ParentLastName =  $val;
	}
	
	function setParentEmail($val)
	{
	$this->ParentEmail =  $val;
	}
	
	function setHasParentVerifiedEmail($val)
	{
	$this->HasParentVerifiedEmail =  $val;
	}
	
	function setParentVerificationCode($val)
	{
	$this->ParentVerificationCode =  $val;
	}
	
	function setParentVerifiedEmailOn($val)
	{
	$this->ParentVerifiedEmailOn =  $val;
	}
// **********************
// SELECT METHOD / LOAD
// **********************
function adminSelectAllStudents() {
	$sql =  "SELECT * FROM students";

	$result =  $this->database->query($sql);
	return $this->database->result;	
}
function selectAll(){
	$sql =  "SELECT s.*,sp.city,sp.state,sp.zipCode FROM students s join studentProfiles sp on sp.studentID=s.ID";
	$db= new DatabasePDO();
	$result =  $db->Select($sql);
	return $result;
}
function select($studentID)
{

	$sql =  "SELECT * FROM students WHERE ID = $studentID;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	$row = mysqli_fetch_object($result);
	
	
	$this->ID = $row->ID;
	
	$this->FirstName = $row->FirstName;
	
	$this->LastName = $row->LastName;
	
	$this->ZipCode = $row->ZipCode;
	
	$this->Email = $row->Email;
	
	$this->Gender = $row->Gender;
	
	$this->RepID = $row->RepID;
	
	$this->Notes = $row->Notes;
	
	$this->Agree = $row->Agree;
	
	$this->HasVerifiedEmail = $row->HasVerifiedEmail;
	
	$this->VerificationCode = $row->VerificationCode;
	
	$this->VerifiedEmailOn = $row->VerifiedEmailOn;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->IPAddress = $row->IPAddress;
$this->PromoCode = $row->PromoCode;
	
	$this->ParentFirstName = $row->ParentFirstName;
	
	$this->ParentLastName = $row->ParentLastName;
	
	$this->ParentEmail = $row->ParentEmail;
	
	$this->HasParentVerifiedEmail = $row->HasParentVerifiedEmail;
	
	$this->ParentVerificationCode = $row->ParentVerificationCode;
	
	$this->ParentVerifiedEmailOn = $row->ParentVerifiedEmailOn;
}



// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM students WHERE ID = $id;";
$result = $this->database->query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->ID = ""; // clear key for autoincrement

$sql = "INSERT INTO students ( FirstName,LastName,ZipCode,Email,Gender,RepID,Notes,Agree,HasVerifiedEmail,VerificationCode,VerifiedEmailOn,CreatedOn,IPAddress,PromoCode,ParentFirstName,ParentLastName,ParentEmail,HasParentVerifiedEmail,ParentVerificationCode,ParentVerifiedEmailOn) VALUES ( '$this->FirstName','$this->LastName','$this->ZipCode','$this->Email','$this->Gender','$this->RepID','$this->Notes','$this->Agree','$this->HasVerifiedEmail','$this->VerificationCode','$this->VerifiedEmailOn','$this->CreatedOn','$this->IPAddress','$this->PromoCode','$this->ParentFirstName','$this->ParentLastName','$this->ParentEmail','$this->HasParentVerifiedEmail','$this->ParentVerificationCode','$this->ParentVerifiedEmailOn' )";
$result = $this->database->query($sql);
$this->ID = mysqli_insert_id($this->database->link);

}

// **********************
// UPDATE
// **********************

function update($id)
{



$sql = " UPDATE students SET  FirstName = '$this->FirstName',LastName = '$this->LastName',ZipCode = '$this->ZipCode',Email = '$this->Email',Gender = '$this->Gender',RepID = '$this->RepID',Notes = '$this->Notes',Agree = '$this->Agree',HasVerifiedEmail = '$this->HasVerifiedEmail',VerificationCode = '$this->VerificationCode',VerifiedEmailOn = '$this->VerifiedEmailOn',CreatedOn = '$this->CreatedOn',IPAddress = '$this->IPAddress',PromoCode = '$this->PromoCode',ParentFirstName = '$this->ParentFirstName',ParentLastName = '$this->ParentLastName',ParentEmail = '$this->ParentEmail',HasParentVerifiedEmail = '$this->HasParentVerifiedEmail',ParentVerificationCode = '$this->ParentVerificationCode',ParentVerifiedEmailOn = '$this->ParentVerifiedEmailOn' WHERE ID = $id ";

$result = $this->database->query($sql);



}


} // class : end

?>