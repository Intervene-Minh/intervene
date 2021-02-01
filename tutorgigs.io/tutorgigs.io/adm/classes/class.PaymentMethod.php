<?php
require_once ('resources/database.php');
class PaymentMethod extends Database {
	private $id;
	private $method;
	private $account;
	private $address;
	private $city;
	private $state;
	private $zip;
	private $tutor_id;
	
	public function insert() {
		$sql = "insert into paymentmethod (tutor_id,method,account,address,city,state,zip) values
			(:tutorid,:method,:account,:address,:city,:state,:zip)";
		try {
			$db = $this->getConnection ();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( "tutorid", $this->tutor_id );
			$stmt->bindParam ( "method", $this->method );
			$stmt->bindParam ( "account", $this->account );
			$stmt->bindParam ( "address", $this->address );
			$stmt->bindParam ( "city", $this->city );
			$stmt->bindParam ( "state", $this->state );
			$stmt->bindParam ( "zip", $this->zip );
			
			$stmt->execute ();
		} catch ( PDOException $e ) {
			return false;
		}
		return true;
	}
	public function getMethod() {
		return $this->method;
	}
	public function setMethod($method) {
		$this->method = $method;
		
	}
	public function getId() {
		return $this->Id;
	}
	public function setId($id) {
		$this->id = $id;
			
	}
	public function getAccount() {
		return $this->account;
	}
	public function setAccount($a) {
		$this->account = $a;
			
	}public function getAddress() {
		return $this->address;
	}
	public function setAddress($addr) {
		$this->address = $addr;
			
	}public function getCity() {
		return $this->city;
	}
	public function setCity($c) {
		$this->city = $c;
			
	}public function getState() {
		return $this->state;
	}
	public function setState($s) {
		$this->state = $s;
			
	}public function getZip() {
		return $this->zip;
	}
	public function setZip($z) {
		$this->zip = $z;
			
	}public function getTutorId() {
		return $this->tutor_id;
	}
	public function setTutorId($tid) {
		$this->tutor_id = $tid;
			
	}
	
}