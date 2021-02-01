<?php
/*
*
* -------------------------------------------------------
* CLASSNAME:        Student
* GENERATION DATE:  07.11.2016
* FOR MYSQL TABLE:  notifications
* FOR MYSQL DB:     ptwogorg_main
* -------------------------------------------------------

* -------------------------------------------------------
*
*/

include_once("resources/class.database.php");

// **********************
// CLASS DECLARATION
// **********************
class Notification { 
// class : begin


// **********************
// ATTRIBUTE DECLARATION
// **********************

var $id;   // KEY ATTR. WITH AUTOINCREMENT

var $notification_type;
var $notification_header;
var $notification_body;
var $notification_from;
var $notification_to;
var $added_on;
var $status;
var $marked_read_on;
var $notification_from_id;
var $notification_to_id;

var $database; // Instance of class database
var $table = "notifications";
var $table_with_alise = "notifications n";

const TYPE_MEG = 'MESSAGE'; 
const TYPE_SESSION = 'SESSION'; 

// **********************
// CONSTRUCTOR METHOD
// **********************
function Notification() {
	$this->database = new Database();
}

// **********************
// GETTER METHODS
// **********************
function getID() {
	return $this->id;
}

function getNotificationType() {
	return $this->notification_type;
}

function getNotificationHeader() {
	return $this->notification_header;
}

function getNotificationBody() {
	return $this->notification_body;
}

function getNotificationFrom() {
	return $this->notification_from;
}

function getNotificationTo() {
	return $this->notification_to;
}

function getAddedOn() {
	return $this->added_on;
}

function getStatus() {
	return $this->status;
}

function getMarkedReadOn() {
	return $this->marked_read_on;
}


// **********************
// SETTER METHODS
// **********************
function setID($val) {
	$this->id = $val;
}

function setNotificationType($val) {
	$this->notification_type = $val;
}

function setNotificationHeader($val) {
	$this->notification_header = $val;
}

function setNotificationBody($val) {
	$str = substr($val, 0, 120);
	$str = addslashes($str)." ...";
	$this->notification_body = $str;
}

function setNotificationFrom($val) {
	$this->notification_from = $val;
}

function setNotificationTo($val) {
	$this->notification_to = $val;
}

function setAddedOn($val) {
	$this->added_on = $val;
}

function setStatus($val) {
	$this->status = $val;
}

function setMarkedReadOn($val) {
	$this->marked_read_on = $val;
}

function setNotificationFromId($val) {
	$this->notification_from_id = $val;
}

function setNotificationToId($val) {
	$this->notification_to_id = $val;
}

function select($notification_id) {
	$sql = "SELECT * FROM notifications id = ".$notification_id;
	$notification =  $this->database->query($sql);
	$notification = $this->database->result;
	$row = mysqli_fetch_object($notification);

	$this->id = $notification->id;
	$this->notification_type = $notification->notification_type;
	$this->notification_header = $notification->notification_header;
	$this->notification_body = $notification->notification_body;
	$this->notification_from = $notification->notification_from;
	$this->notification_to = $notification->notification_to;
	$this->added_on = $notification->added_on;
	$this->status = $notification->status;
	$this->marked_read_on = $notification->marked_read_on;
}

function add() {
	$this->id = ""; // clear key for autoincrement

	$sql = "INSERT INTO ".$this->table." (
				notification_type, 
				notification_header,
				notification_body,
				notification_from,
				notification_to,
				added_on,
				status,
				notification_from_id,
				notification_to_id
			) 
			VALUES (
				'".$this->notification_type."',
				'".addslashes(strip_tags($this->notification_header))."',
				'".addslashes(strip_tags($this->notification_body))."',
				'".$this->notification_from."',
				'".$this->notification_to."',
				NOW(),
				'0',
				'".$this->notification_from_id."',
				'".$this->notification_to_id."'
			)";
	$result = $this->database->query($sql);
}

function marked_as_read($notification_id = 0) {
	$sql = "UPDATE ".$this->table." SET status = 1, marked_read_on = NOW() WHERE id = ".$notification_id;
	$result = $this->database->query($sql);
}
}