<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        TutorSession
* GENERATION DATE:  08.05.2014
* CLASS FILE:       /home6/ptwogorg/public_html/adm/classgen/generated_classes/class.TutorSession.php
* FOR MYSQL TABLE:  tutorSessions
* FOR MYSQL DB:     ptwogorg_main
* -------------------------------------------------------
* 
* -------------------------------------------------------
*
*/

include_once("resources/class.database.php");

// **********************
// CLASS DECLARATION
// **********************

class TutorSession
{ // class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $ID;   // KEY ATTR. WITH AUTOINCREMENT

var $TutorID;   // (normal Attribute)
var $StudentID;   // (normal Attribute)
var $SubjectID;   // (normal Attribute)
var $Rate;   // (normal Attribute)
var $Hours;   // (normal Attribute)
var $HoursCompleted;   // (normal Attribute)
var $Description;   // (normal Attribute)
var $CreatedOn;   // (normal Attribute)
var $CreatedBy;   // (normal Attribute)
var $TutorAccept;   // (normal Attribute)
var $StudentAccept;   // (normal Attribute)
var $DateTutorAccept;   // (normal Attribute)
var $DateStudentAccept;   // (normal Attribute)
var $TutorReject;   // (normal Attribute)
var $StudentReject;   // (normal Attribute)
var $DateTutorReject;   // (normal Attribute)
var $DateStudentReject;   // (normal Attribute)
var $IsOpen;   // (normal Attribute)
var $IsActive;   // (normal Attribute)
var $DateClosed;   // (normal Attribute)
var $SessionNotes;   // (normal Attribute)
var $PromoCode;
var $WasCancelled;
var $WasCancelledBy;

var $total;
var $transaction_id;
var $transaction_status;

var $database; // Instance of class database


// **********************
// CONSTRUCTOR METHOD
// **********************

function TutorSession()
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

function getSubjectID()
{
return $this->SubjectID;
}

function getRate()
{
return $this->Rate;
}

function getHours()
{
return $this->Hours;
}
function getHoursCompleted()
{
return $this->HoursCompleted;
}

function getDescription()
{
return $this->Description;
}

function getCreatedOn()
{
return $this->CreatedOn;
}

function getCreatedBy()
{
return $this->CreatedBy;
}

function getTutorAccept()
{
return $this->TutorAccept;
}

function getStudentAccept()
{
return $this->StudentAccept;
}

function getDateTutorAccept()
{
return $this->DateTutorAccept;
}

function getDateStudentAccept()
{
return $this->DateStudentAccept;
}

function getTutorReject()
{
return $this->TutorReject;
}

function getStudentReject()
{
return $this->StudentReject;
}

function getDateTutorReject()
{
return $this->DateTutorReject;
}

function getDateStudentReject()
{
return $this->DateStudentReject;
}

function getIsOpen()
{
return $this->IsOpen;
}

function getIsActive()
{
	return $this->IsActive;
}

function getDateClosed()
{
	return $this->DateClosed;
}

function getSessionNotes()
{
	return $this->SessionNotes;
}

function getPromoCode()
{
	return $this->PromoCode;
}

function getWasCancelled()
{
	return $this->WasCancelled;
}
function getWasCancelledBy()
{
	return $this->WasCancelledBy;
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

function setSubjectID($val)
{
	$this->SubjectID =  $val;
}

function setRate($val)
{
	$this->Rate =  $val;
}

function setHours($val)
{
	$this->Hours =  $val;
}

function setHoursCompleted($val)
{
	$this->HoursCompleted =  $val;
}

function setDescription($val)
{
	$this->Description =  $val;
}

function setCreatedOn($val)
{
	$this->CreatedOn =  $val;
}

function setCreatedBy($val)
{
	$this->CreatedBy =  $val;
}

function setTutorAccept($val)
{
	$this->TutorAccept =  $val;
}

function setStudentAccept($val)
{
	$this->StudentAccept =  $val;
}

function setDateTutorAccept($val)
{
	$this->DateTutorAccept =  $val;
}

function setDateStudentAccept($val)
{
	$this->DateStudentAccept =  $val;
}

function setTutorReject($val)
{
	$this->TutorReject =  $val;
}

function setStudentReject($val)
{
	$this->StudentReject =  $val;
}

function setDateTutorReject($val)
{
	$this->DateTutorReject =  $val;
}

function setDateStudentReject($val)
{
	$this->DateStudentReject =  $val;
}

function setIsOpen($val)
{
	$this->IsOpen =  $val;
}

function setIsActive($val)
{
	$this->IsActive =  $val;
}

function setDateClosed($val)
{
	$this->DateClosed =  $val;
}

function setSessionNotes($val)
{
	$this->SessionNotes =  $val;
}

function setPromoCode($val)
{
	$this->PromoCode =  $val;
}

function setWasCancelled($val)
{
	$this->WasCancelled =  $val;
}
function setWasCancelledBy($val)
{
	$this->WasCancelledBy =  $val;
}

// **********************
// SELECT METHOD / LOAD
// **********************
function adminSelectAllSessions() {

	$sql =  "SELECT * FROM tutorSessions ";
	$result =  $this->database->query($sql);
	return $this->database->result;
	
}

function select($id) {

	$sql =  "SELECT * FROM tutorSessions WHERE ID = $id;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	$row = mysqli_fetch_object($this->database->result);
	
	$this->populateSelf($row);
}

function selectByStudentID($studentID) {

	$sql =  "SELECT * FROM tutorSessions WHERE StudentID = $studentID;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	return $result;
}

function selectByTutorID($tutorID) {

	$sql =  "SELECT * FROM tutorSessions WHERE TutorID = $tutorID;";
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	return $result;
}

function selectPendingSessions($uid, $strUserType) {
	//echo ($uid.' : '.$strUserType);
	if (strtolower($strUserType) == "student") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				tutors
				ON
				tutors.ID=tutorSessions.TutorID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.StudentID=".$uid."
				AND
				(tutorSessions.StudentAccept=0 OR tutorSessions.TutorAccept=0)
				AND
				tutorSessions.IsOpen=1 
				AND
				tutorSessions.IsActive=1
		";
	} else 
	if (strtolower($strUserType) == "tutor") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				students
				ON
				students.ID=tutorSessions.StudentID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.TutorID=".$uid."
				AND
				(tutorSessions.StudentAccept=0 OR tutorSessions.TutorAccept=0)
				AND
				tutorSessions.IsOpen=1 
				AND
				tutorSessions.IsActive=1
		";
	} else {
		return 0;
	}
	
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	return $result;
	
}

function selectActiveSessions($uid, $strUserType) {
	//echo ($uid.' : '.$strUserType);
	if (strtolower($strUserType) == "student") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				tutors
				ON
				tutors.ID=tutorSessions.TutorID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.StudentID=".$uid."
				AND
				tutorSessions.StudentAccept=1 
				AND
				tutorSessions.TutorAccept=1 
				AND
				tutorSessions.IsOpen=1 
				AND
				tutorSessions.IsActive=1  
		";
	} else 
	if (strtolower($strUserType) == "tutor") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				students
				ON
				students.ID=tutorSessions.StudentID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.TutorID=".$uid."
				AND
				tutorSessions.TutorAccept=1 
				AND
				tutorSessions.StudentAccept=1 
				AND
				tutorSessions.IsOpen=1 
				AND
				tutorSessions.IsActive=1  
		";
	} else {
		return 0;
	}
	
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	return $result;
	
}

function selectClosedSessions($uid, $strUserType) {
	//echo ($uid.' : '.$strUserType);
	if (strtolower($strUserType) == "student") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				tutors
				ON
				tutors.ID=tutorSessions.TutorID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.StudentID=".$uid."									 
				AND
				tutorSessions.IsOpen=0 
				AND
				tutorSessions.IsActive=1   
		";
	} else 
	if (strtolower($strUserType) == "tutor") {
		$sql = "SELECT * FROM 
				tutorSessions
				JOIN
				students
				ON
				students.ID=tutorSessions.StudentID
				JOIN
				subjects
				ON
				subjects.ID=tutorSessions.SubjectID
				WHERE
				tutorSessions.TutorID=".$uid."									 
				AND
				tutorSessions.IsOpen=0 
				AND
				tutorSessions.IsActive=1   
 
		";
	} else {
		return 0;
	}
	
	$result =  $this->database->query($sql);
	$result = $this->database->result;
	
	return $result;
	
}

static function getNumActiveTutorSessionForStudent($studentID) {		
	$as = new TutorSession();		
	$rsl =  $as->selectActiveSessions($studentID, "Student");							
	$count = mysqli_num_rows($rsl);		
	return $count;
}

static function getNumActiveTutorSessionForTutor($tutorID) {		
	$as = new TutorSession();		
	$rsl =  $as->selectActiveSessions($tutorID, "Tutor");							
	$count = mysqli_num_rows($rsl);		
	return $count;
}
static function getNumPendingTutorSessionForTutor($tutorID) {
	$as = new TutorSession();
	$rsl =  $as->selectPendingSessions($tutorID, "Tutor");
	$count = mysqli_num_rows($rsl);
	return $count;
}


function populateSelf($row) {

	$this->ID = $row->ID;
	
	$this->TutorID = $row->TutorID;
	
	$this->StudentID = $row->StudentID;
	
	$this->SubjectID = $row->SubjectID;
	
	$this->Rate = $row->Rate;
	
	$this->Hours = $row->Hours;
	
	$this->HoursCompleted = $row->HoursCompleted;
	
	$this->Description = $row->Description;
	
	$this->CreatedOn = $row->CreatedOn;
	
	$this->CreatedBy = $row->CreatedBy;
	
	$this->TutorAccept = $row->TutorAccept;
	
	$this->StudentAccept = $row->StudentAccept;
	
	$this->DateTutorAccept = $row->DateTutorAccept;
	
	$this->DateStudentAccept = $row->DateStudentAccept;
	
	$this->TutorReject = $row->TutorReject;
	
	$this->StudentReject = $row->StudentReject;
	
	$this->DateTutorReject = $row->DateTutorReject;
	
	$this->DateStudentReject = $row->DateStudentReject;
	
	$this->IsOpen = $row->IsOpen;
	
	$this->IsActive = $row->IsActive;
	
	$this->DateClosed = $row->DateClosed;
	
	$this->SessionNotes = $row->SessionNotes;
	
	$this->PromoCode = $row->PromoCode;
	
	$this->WasCancelled = $row->WasCancelled;
	
	$this->WasCancelledBy = $row->WasCancelledBy;
	$this->total=$row->total;
	$this->transaction_id=$row->transaction_id;
	$this->transaction_status=$row->transaction_status;

}

// **********************
// DELETE
// **********************

function delete($id)
{
$sql = "DELETE FROM tutorSessions WHERE ID = $id;";
$result = $this->database->query($sql);

}

// **********************
// INSERT
// **********************
function save() { 
	
	if(isset($this->ID)){
		$this->update($this->ID);
	}else{
	$this->insert(); 
	}
}
function insert()
{
$this->ID = ""; // clear key for autoincrement

$sql = "INSERT INTO tutorSessions ( TutorID,StudentID,SubjectID,Rate,Hours,HoursCompleted, Description,CreatedOn,CreatedBy,TutorAccept,StudentAccept,DateTutorAccept,DateStudentAccept,TutorReject,StudentReject,DateTutorReject,DateStudentReject,IsOpen,IsActive,DateClosed,SessionNotes,PromoCode,WasCancelled, WasCancelledBy,total,transaction_id,transaction_status)
 VALUES ( '$this->TutorID','$this->StudentID','$this->SubjectID','$this->Rate','$this->Hours','$this->HoursCompleted','$this->Description','$this->CreatedOn','$this->CreatedBy','$this->TutorAccept','$this->StudentAccept','$this->DateTutorAccept','$this->DateStudentAccept','$this->TutorReject','$this->StudentReject','$this->DateTutorReject','$this->DateStudentReject','$this->IsOpen','$this->IsActive','$this->DateClosed','$this->SessionNotes','$this->PromoCode' ,'$this->WasCancelled', '$this->WasCancelledBy','$this->total','$this->transaction_id','$this->transaction_status')";
$this->ID = $this->database->insert($sql);


}

// **********************
// CLOSE
// **********************
function cancelSessionByStudent()
{
$sql = " UPDATE tutorSessions SET StudentReject = 1, DateStudentReject = DATE_ADD(NOW(), INTERVAL 1 HOUR), IsOpen = 0,DateClosed = DATE_ADD(NOW(), INTERVAL 1 HOUR), WasCancelled = 1, WasCancelledBy = 'Student' WHERE ID = $this->ID";

$result = $this->database->query($sql);

}
function cancelSessionByTutor()
{
$sql = " UPDATE tutorSessions SET TutorReject = 1, DateTutorReject = DATE_ADD(NOW(), INTERVAL 1 HOUR), IsOpen = 0,DateClosed = DATE_ADD(NOW(), INTERVAL 1 HOUR), WasCancelled = 1, WasCancelledBy = 'Tutor' WHERE ID = $this->ID";

$result = $this->database->query($sql);
}

function closeSessionByStudent()
{
$sql = " UPDATE tutorSessions SET StudentReject = 1, DateStudentReject = DATE_ADD(NOW(), INTERVAL 1 HOUR), IsOpen = 0,DateClosed = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE ID = $this->ID";

$result = $this->database->query($sql);
}

function closeSessionByTutor()
{
$sql = " UPDATE tutorSessions SET TutorReject = 1, DateTutorReject = DATE_ADD(NOW(), INTERVAL 1 HOUR), IsOpen = 0,DateClosed = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE ID = $this->ID";

$result = $this->database->query($sql);
}

// **********************
// UPDATE
// **********************

function update($id)
{



$sql = " UPDATE tutorSessions SET  TutorID = '$this->TutorID',StudentID = '$this->StudentID',SubjectID = '$this->SubjectID',Rate = '$this->Rate',Hours = '$this->Hours',HoursCompleted = '$this->HoursCompleted',Description = '$this->Description',CreatedOn = '$this->CreatedOn',CreatedBy = '$this->CreatedBy',TutorAccept = '$this->TutorAccept',StudentAccept = '$this->StudentAccept',DateTutorAccept = '$this->DateTutorAccept',DateStudentAccept = '$this->DateStudentAccept',TutorReject = '$this->TutorReject',StudentReject = '$this->StudentReject',DateTutorReject = '$this->DateTutorReject',DateStudentReject = '$this->DateStudentReject',IsOpen = '$this->IsOpen',IsActive = '$this->IsActive',DateClosed = '$this->DateClosed',SessionNotes = '$this->SessionNotes',PromoCode = '$this->PromoCode',WasCancelled = '$this->WasCancelled', total='$this->total',transaction_id='$this->transaction_id',transaction_status='$this->transaction_status' WHERE ID = $id ";

$result = $this->database->query($sql);



}
	public function getTotal() {
		return $this->total;
	}
	public function setTotal($total) {
		$this->total = $total;
		return $this;
	}
	public function getTransactionId() {
		return $this->transaction_id;
	}
	public function setTransactionId($transaction_id) {
		$this->transaction_id = $transaction_id;
		return $this;
	}
	public function getTransactionStatus() {
		return $this->transaction_status;
	}
	public function setTransactionStatus($transaction_status) {
		$this->transaction_status = $transaction_status;
		return $this;
	}


} // class : end

?>