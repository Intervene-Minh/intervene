<?php

class Category {
    
    // DB Settings
	private $tServer="localhost";
	private $tUser="intervenedevUser";          
	private $tPass='Te$btu$#4f56#';
	private $tDB="ptwogorg_main";
	public $connection = null;
    
    // Table settings
	private $table = "categories";   
    
    // ----------------------------------------------//
    public $ID;
    public $Name;    
    public $IsActive; 
        
    
    public function Category($loadID = null) {
        $this->Connect();
        
        // Load object if ID
        if ($loadID != null)
            $this->Load($loadID);       
    }
    
    // -----------------------------------------------//
    
    // Loads a Category by ID, Populates the Object, and returns the query result
    public function Load($cid) {
        $strQuery = "SELECT * FROM $this->table WHERE ID=$cid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
                
        return $result;
    }  
    
    public function LoadAllCategories() {
        $strQuery = "SELECT * FROM $this->table";
        $result = $this->Query($strQuery);
        
        return $result;
    }
            
    // Create a new Subject
    public function Create($name, $isActive=1) {
        $result = $this->Add($name, $isActive);
        
        $lastID = $this->LastInsertID();
        Load($lastID);
        
        return $result;        
    }
    
    // Delete a Subject
    public function Delete($cid) {
        $result = $this->Remove($cid);        
        return $result;
    }
    
    // Save the Object
    public function Save() {
        return $this->Update();
    }
    
    // DeActivate Object
    public function Deactivate() {
        $this->SetActive(false);
    }
    
    // Activate Object
    public function Activate() {
        $this->SetActive(true);
    }
    
    // -----------------------------------------------//
    
    private function Query( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
    }
    
    private function PopulateByResult( $result ) {
        while ($array = mysqli_fetch_array($result)) {
            $this->ID = $array['ID'];
            $this->Name = $array['Name'];            
            $this->IsActive = $array['IsActive'];            
        }
        return $result;
    }
    
    private function Add( $name, $isActive=1) {
        $strQuery = "INSERT INTO $this->table
                            (
            					Name,            										 
            					IsActive            					
            				) 
            		VALUES (											
            					'".$name."',            						
            					'".$isActive."'            							
            				)";
        $result = $this->Query($strQuery);
        return $result;
    }
    
    private function Update () {
        if (!empty($this->ID)) {
            $strQuery = "
                UPDATE 
                    $this->table
                SET 
                    Name='".$this->Name."',                    
                    IsActive='".$this->IsActive."'                    
                WHERE ID=".$this->ID
            ;
            $result = $this->Query($strQuery);
            return $result;
        }        
    }
    
    private function Remove ( $cid ) {
        
        // 1. Delete each Options/Answers from Test Questions for Subject
        
        // 2. Delete each Questions from Test for Subject
        
        // 3. Delete Test from Subject
        
        // 4. Delete Subjects in Category
        
        // 5. Delete Category
        $strQuery = "DELETE FROM $this->table WHERE ID=$cid";
        $result = $this->Query($strQuery);
        
        return $result;
    }
    
    private function SetActive($state) {        
        $this->IsActive = $state == true ? 1 : 0;
        $strQuery = "UPDATE $this->table SET IsActive=$this->IsActive WHERE ID=$this->ID";
        $result = $this->Query($strQuery);
    }
    
    
    // -----------------------------------------------//
    private function Connect() {
        $this->connection = mysqli_connect($this->tServer, $this->tUser, $this->tPass, $this->tDB) or 
		die('Connection Error: '. mysqli_connect_error() );
        mysqli_select_db($this->connection, $this->tDB);
    } 
    
    private function LastInsertID() {
        return mysqli_insert_id($this->connection);
    }
    
    
}

?>