<?

class Answer {
    
    // DB Settings
	private $tServer="localhost";
	private $tUser="ptwogorg_tutor";          
	private $tPass="tutor1234";
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "answers";   
    
    // ----------------------------------------------//
    
    public $ID;
    public $Answer;
    public $Type;
    public $CreatedOn;
    
        
    public function Answer ($loadID=null) {
        $this->Connect();
        
        // Load object if ID
        if ($loadID != null)
            $this->Load($loadID);   
    }
    
    // Loads an Answer by ID, Populates the Object, and returns the query result
    public function Load($aid) {
        $strQuery = "SELECT * FROM $this->table WHERE ID=$aid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
        return $result;
    }  
    
    public function LoadAllAnswers() {
        $strQuery = "SELECT * FROM $this->table";
        $result = Query($strQuery);
        
        return $result;
    }
    
    // Load ALL Answers by Type ID
    public function LoadAllAnswersByTypeID($typeID) {
        $strQuery = "SELECT * FROM $this->table WHERE Type=$typeID";
        $result = self::Query($strQuery);
        
        return $result;
    }
    
    // Create a new Answer
    public function Create($answer, $type, $createdOn) {
        $result = $this->Add($answer, $type, $createdOn);
        
        $lastID = $this->LastInsertID();
        Load($lastID);
        
        return $result;        
    }
    
    // Delete a Object
    public function Delete($aid) {
        $result = $this->Remove($aid);        
        return $result;
    }
    
    // Save the Object
    public function Save() {
        return $this->Update();
    }
    
  
    // -----------------------------------------------//
    
    private function Query( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
    }
    
    private function PopulateByResult( $result ) {
        while ($array = mysqli_fetch_array($result)) {
            $this->ID = $array['ID'];
            $this->Answer = $array['Answer'];
            $this->Type = $array['Type'];
            $this->CreatedOn = $array['CreatedOn'];            
        }
        return $result;
    }
    
    private function Add( $answer, $type, $createdOn ) {
        $strQuery = "INSERT INTO $this->table
                            (
            					Answer,
            					Type,						 
            					CreatedOn            					
            				) 
            		VALUES (											
            					'".$answer."',	
            					'".$type."',	
            					DATE_ADD(NOW(), INTERVAL 1 HOUR)            							
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
                    Answer='".$this->Answer."',
                    Type='".$this->Type."',
                    CreatedOn='".$this->CreatedOn."'                    
                WHERE ID=".$this->ID
            ;
            $result = $this->Query($strQuery);
            return $result;
        }        
    }
    
    private function Remove ( $aid ) {
        
        // 1. Delete Options/Answer from Question OptionIDs
        // 1b.If it is a correct answer, then reassign
        
        // 2. Delete Answer
        $strQuery = "DELETE FROM $this->table WHERE ID=$aid";
        $result = $this->Query($strQuery);
        
        return $result;
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