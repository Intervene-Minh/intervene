<?php
/*
	*
	* This class provides one central database-connection for
	* all p2g php applications.
	**
*/

  
class Database {
	
	var $debug = false;
	
	
 
	var $host;	
	var $password;	
	var $user;	
	var $database; 
	var $link;
	var $query;
	var $result;
	var $rows;
	
	
	 
 	// Contructor : begin
 	function Database() { 
		
		if ($this->debug == true) {
			print("db: Constructor: "."<br>");
		}
		
		// ********** P2G VALUES HERE **********
		

		include(dirname(__FILE__)."/../../db_config.php");
		$this->host = $host;               
		$this->password = $password;      
		$this->user = $username;         
		$this->database = $dbname;       
		$this->rows = 0;
		
		// **********************************************
		// **********************************************
	 
	}
	
	// Opens a link to the database :
	function OpenLink()  {
		
		if ($this->debug == true) {
			print("db: OpenLink: "."<br>");
		}
	
		$this->link = @mysqli_connect($this->host,$this->user,$this->password) or die(
			print "Class Database: Error while connecting to DB (link)"
		);
	} 
 
 	// Performs a Selection on database
	function SelectDB() {
	
		if ($this->debug == true) {
			print("db: SelectDB: "."<br>");
		}
		
		@mysqli_select_db($this->link, $this->database) or die (
			print "class.database (SelectDB): Error while selecting DB: "
		);
		
	}
 
 	// Closes the database
	function CloseDB() { 
		if ($this->debug == true) {
			print("db: Close: "."<br>");
		}
	 	mysqli_close($this->link);
	} 
	
	// Queries the database
	function Query($query) {
		
		if ($this->debug == true) {
			print("db: Query: ".$query."<br>");
		}
		 
		$this->OpenLink();
		$this->SelectDB();
		$this->query = $query;
		$this->result = mysqli_query($this->link, $query) or die (
			print "class.database (Query): Error: ".$query.mysqli_error($this->link)
		);
		 
		// $rows=mysql_affected_rows();
		
		//if(ereg("SELECT",$query)) {
		if(preg_match("/SELECT/",$query)) {
			$this->rows = mysqli_num_rows($this->result);
		}
		 
		$this->CloseDB();
		return $this->result;
	} 
  
 } 
 
?>
