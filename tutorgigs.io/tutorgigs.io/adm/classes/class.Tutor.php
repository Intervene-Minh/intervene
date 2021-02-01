<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        Tutor
* GENERATION DATE:  16.06.2014
* CLASS FILE:       /home6/ptwogorg/public_html/adm/classgen/generated_classes/class.Tutor.php
* FOR MYSQL TABLE:  tutors
* FOR MYSQL DB:     ptwogorg_main
* -------------------------------------------------------

* -------------------------------------------------------
*
*/

include_once("resources/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class Tutor
{ // class : begin

// DB Settings
	private $tServer="localhost";
	private $tUser="ptwogorg_tutor";          
	private $tPass="tutor1234";
	private $tDB="ptwogorg_main";
	public $connection = null;

// **********************
// ATTRIBUTE DECLARATION
// **********************

var $ID;   // KEY ATTR. WITH AUTOINCREMENT

var $FirstName;   // (normal Attribute)
var $LastName;   // (normal Attribute)
var $ZipCode;   // (normal Attribute)
var $Email;   // (normal Attribute)
var $Gender;   // (normal Attribute)
var $Major;   // (normal Attribute)
var $College;   // (normal Attribute)
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

// Table settings
private $table = "tutors";
private $tableTutorSubjects = "tutorSubjects";
private $tableTutorsProfiles = "tutorProfiles";

// Sort Labels for Select
public static $sortingLabels = array(
	"Sort By: Relevance", 
	"Rate: Low to High", 
	"Rate: High to Low", 
	"Rating: Low to High",
	"Rating: High to Low",
);

// Sorting SQL Commands
public static $sortingSQL = array(
	"lastlogon DESC", 
	"Rate ASC", 
	"Rate DESC", 
	"Rating ASC",
	"Rating DESC"
);
	
// **********************
// CONSTRUCTOR METHOD
// **********************

function Tutor()
{

$this->database = new Database();

$this->database->OpenLink();
$this->database->SelectDB();

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

function getFullName()
{
return $this->FirstName.' '.$this->LastName;
}
function getShortName()
{
return $this->FirstName.' '.substr($this->LastName,0,1).'.';
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

function getMajor()
{
return $this->Major;
}

function getCollege()
{
return $this->College;
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

function setMajor($val)
{
$this->Major =  $val;
}

function setCollege($val)
{
$this->College =  $val;
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

public function LoadAll() {
	
	$strQuery = "
		SELECT * FROM 
			tutors		
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutors.ID AND
			tutorProfiles.IsActive=1 AND 
			tutorProfiles.Approved=1
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		ORDER BY
			tutorUsers.lastlogon 
		DESC

	";
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}

public function LoadAllTutorUserProfiles() {
	
	$strQuery = "
		SELECT * FROM 
			tutors		
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutors.ID
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		ORDER BY
			tutorUsers.lastlogon 
		DESC

	";
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}

public function LoadAll_sortByRating() {
	
	$strQuery = "
		SELECT * FROM 
			tutors		
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutors.ID AND
			tutorProfiles.IsActive=1 AND 
			tutorProfiles.Approved=1
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		ORDER BY
			tutorProfiles.Rating 
		DESC

	";
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}

public function LoadAll_sort($sortID) {
	$sort = self::$sortingSQL[(int)$sortID];
	$strQuery = "
		SELECT * FROM 
			tutors		
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutors.ID AND
			tutorProfiles.IsActive=1 AND 
			tutorProfiles.Approved=1
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		ORDER BY ".$sort;
		
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}


// Load ALL Subjects by KeyWord
public function LoadAllBySubjectKeyWord($keyword) {

	$strQuery = "
		SELECT * FROM 
		subjects
		JOIN
			categories
		ON 
			categories.ID=subjects.CategoryID
		JOIN
			tutorSubjects
		ON
			tutorSubjects.SubjectID=subjects.ID
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutorSubjects.TutorID
			AND
			tutorProfiles.IsActive=1 
			AND 
			tutorProfiles.Approved=1
		JOIN
			tutors
		ON
			tutors.ID=tutorProfiles.TutorID
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		WHERE		
			Certified = 1
		AND	
			tutorSubjects.IsActive=1
		AND	
			subjects.IsActive=1
		AND
			(
			(subjects.Name = '%".$keyword."%'
			OR			
			subjects.Name LIKE '%".$keyword."%' 
			OR 
			subjects.Keywords LIKE '%".$keyword."%' )
			OR(
			categories.Name = '%".$keyword."%' 
			OR
			categories.Name LIKE '%".$keyword."%' 
			OR
			categories.Keywords LIKE '%".$keyword."%' )
			OR
			tutorSubjects.Experience LIKE '%$keyword%'
			OR(
			tutorProfiles.Description LIKE '%".$keyword."%'
			OR
			tutorProfiles.Headline LIKE '%".$keyword."%')		
			)
						
		GROUP BY tutorProfiles.TutorID
	";
	
	// subjects.Name SOUNDS LIKE '%".$keyword."%' OR subjects.Name  LIKE CONCAT('%', '".$keyword."', '%')
	//echo("t-".$strQuery);
	
	// Get the result from db object after query
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}

public function LoadAllBySubjectKeyWord_sort($keyword, $sortID) {
	
	$strQuery = "
		SELECT * FROM 
		subjects
		JOIN
			categories
		ON 
			categories.ID=subjects.CategoryID
		JOIN
			tutorSubjects
		ON
			tutorSubjects.SubjectID=subjects.ID
		JOIN
			tutorProfiles
		ON
			tutorProfiles.TutorID=tutorSubjects.TutorID
			AND
			tutorProfiles.IsActive=1 
			AND 
			tutorProfiles.Approved=1
		JOIN
			tutors
		ON
			tutors.ID=tutorProfiles.TutorID
		JOIN
			tutorUsers
		ON
			tutorUsers.tutorID=tutorProfiles.TutorID
		WHERE 
			subjects.IsActive=1
		AND
			tutorSubjects.IsActive=1 AND tutorSubjects.Certified=1
			
		AND
			(
			(subjects.Name = '%".$keyword."%'
			OR			
			subjects.Name LIKE '%".$keyword."%' 
			OR 
			subjects.Keywords LIKE '%".$keyword."%' )
			OR(
			categories.Name = '%".$keyword."%' 
			OR
			categories.Name LIKE '%".$keyword."%' 
			OR
			categories.Keywords LIKE '%".$keyword."%' )
			OR
			tutorSubjects.Experience LIKE '%$keyword%'
			OR(
			tutorProfiles.Description LIKE '%".$keyword."%'
			OR
			tutorProfiles.Headline LIKE '%".$keyword."%')		
			)
		GROUP BY 
			tutorProfiles.TutorID
		ORDER BY ".self::$sortingSQL[(int)$sortID];
	
	//subjects.Name SOUNDS LIKE '%".$keyword."%' OR subjects.Name  LIKE CONCAT('%', '".$keyword."', '%')
	//echo("t-".$strQuery);
	
	// Get the result from db object after query
	$result = $this->database->query($strQuery);
	$result = $this->database->result;
	
	return $result;
}

function select($id)
{

$sql =  "SELECT * FROM $this->table WHERE ID = $id;";
$result =  $this->database->query($sql);
$result = $this->database->result;
$row = mysqli_fetch_object($result);


$this->ID = $row->ID;

$this->FirstName = $row->FirstName;

$this->LastName = $row->LastName;

$this->ZipCode = $row->ZipCode;

$this->Email = $row->Email;

$this->Gender = $row->Gender;

$this->Major = $row->Major;

$this->College = $row->College;

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
$sql = "DELETE FROM $this->table WHERE ID = $id;";
$result = $this->database->query($sql);

}

// **********************
// INSERT
// **********************

function insert()
{
$this->ID = ""; // clear key for autoincrement

$sql = "INSERT INTO $this->table ( FirstName,LastName,ZipCode,Email,Gender,Major,College,RepID,Notes,Agree,HasVerifiedEmail,VerificationCode,VerifiedEmailOn,CreatedOn,IPAddress,PromoCode,ParentFirstName,ParentLastName,ParentEmail,HasParentVerifiedEmail,ParentVerificationCode,ParentVerifiedEmailOn ) VALUES ( '$this->FirstName','$this->LastName','$this->ZipCode','$this->Email','$this->Gender','$this->Major','$this->College','$this->RepID','$this->Notes','$this->Agree','$this->HasVerifiedEmail','$this->VerificationCode','$this->VerifiedEmailOn','$this->CreatedOn','$this->IPAddress','$this->PromoCode','$this->ParentFirstName','$this->ParentLastName','$this->ParentEmail','$this->HasParentVerifiedEmail','$this->ParentVerificationCode','$this->ParentVerifiedEmailOn' )";
$result = $this->database->query($sql);
$this->ID = mysqli_insert_id($this->database->link);

}

// **********************
// UPDATE
// **********************

function update($id)
{



$sql = " UPDATE $this->table SET  FirstName = '$this->FirstName',LastName = '$this->LastName',ZipCode = '$this->ZipCode',Email = '$this->Email',Gender = '$this->Gender',Major = '$this->Major',College = '$this->College',RepID = '$this->RepID',Notes = '$this->Notes',Agree = '$this->Agree',HasVerifiedEmail = '$this->HasVerifiedEmail',VerificationCode = '$this->VerificationCode',VerifiedEmailOn = '$this->VerifiedEmailOn',CreatedOn = '$this->CreatedOn',IPAddress = '$this->IPAddress',PromoCode = '$this->PromoCode',ParentFirstName = '$this->ParentFirstName',ParentLastName = '$this->ParentLastName',ParentEmail = '$this->ParentEmail',HasParentVerifiedEmail = '$this->HasParentVerifiedEmail',ParentVerificationCode = '$this->ParentVerificationCode',ParentVerifiedEmailOn = '$this->ParentVerifiedEmailOn' WHERE ID = $id ";

$result = $this->database->query($sql);



}

function getCount() {
	return count(self::$sortingLabels);
}

} // class : end

?>