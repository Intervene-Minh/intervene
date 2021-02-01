<?


class Question {
    
    // DB Settings
	private $tServer="localhost";
	private $tUser="ptwogorg_tutor";          
	private $tPass="tutor1234";
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "questions";   
    
    // ----------------------------------------------//
    public $ID;
    public $Question;
    public $TestID;
    public $OptionIDs;
    public $AnswerID;
    public $IsActive; 
    public $CreatorID;
    public $CreatedOn;
    
    
    public function Question($loadID) {
        $this->Connect();
        
        // Load object if ID
        if ($loadID != null)
            $this->Load($loadID);  
    }
    
    // -----------------------------------------------//
    
    // Loads a Question by ID, Populates the Object, and returns the query result
    public function Load($qid) {
        $strQuery = "SELECT * FROM $this->table WHERE ID=$qid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
                
        return $result;
    }  
    // Load a Question by AnswerID
    public function LoadByAnswerID($aid) {
        $strQuery = "SELECT * FROM $this->table WHERE AnswerID=$aid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
        
        return $result;
    }
    
    public function LoadAllQuestionsByTestID($tid) {
        $strQuery = "SELECT * FROM $this->table WHERE TestID=$tid";
        $result = $this->Query($strQuery);
                        
        return $result;
    }
    
    public function LoadAllQuestionsByCreatorID($cid) {
        $strQuery = "SELECT * FROM $this->table WHERE CreatorID=$cid";
        $result = $this->Query($strQuery);
                        
        return $result;
    }
    
    
    
    public function LoadAllQuestions() {
        $strQuery = "SELECT * FROM $this->table";
        $result = $this->Query($strQuery);
        
        return $result;
    }
    
    public function GetNumAnswers() {
        $arr = explode("," , $this->OptionIDs); 
        return sizeOf($arr);
    }    
    
    
    // Delete a Object
    public function Delete($qid) {
        $result = $this->Remove($qid);        
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
    
    
    private function Query( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection));
		return $result;
    }
    
    private function PopulateByResult( $result ) {
        while ($array = mysqli_fetch_array($result)) {
            $this->ID = $array['ID'];
            $this->Question = $array['Question'];            
            $this->TestID = $array['TestID']; 
            $this->OptionIDs = $array['OptionIDs'];
            $this->AnswerID = $array['AnswerID'];
            $this->CreatorID = $array['CreatorID'];
            $this->IsActive = $array['IsActive'];
            $this->CreatedOn = $array['CreatedOn'];           
        }
        return $result;
    }
    
    
    
    // Add Record
    private function Add( $question, $testID, $optionIDs, $answerID, $isActive=1, $creatorID, $createdON) {
        $strQuery = "INSERT INTO $this->table
                            (
            					Question,
                                TestID,
                                OptionIDs,
                                AnswerID,
                                IsActive,
                                CreatorID,
                                CreatedOn           					
            				) 
            		VALUES (											
            					'".$question."',   
                                '".$testID."',  
                                '".$optionIDs."',  
                                '".$answerID."',           						
            					'".$isActive."',
                                '".$creatorID."',  
                                DATE_ADD(NOW(),INTERVAL 1 HOUR)'            							
            				)";
        $result = $this->Query($strQuery);
        return $result;
    }
    // Update
    private function Update () {
        if (!empty($this->ID)) {
            $strQuery = "
                UPDATE 
                    $this->table
                SET 
                    Question='".$this->Question."',                    
                    TestID='".$this->TestID."',
                    OptionIDs='".$this->OptionIDs."',
                    AnswerID='".$this->AnswerID."',
                    IsActive='".$this->IsActive."',
                    CreatorID='".$this->CreatorID."',
                    CreatedOn='".$this->CreatedOn."'                    
                WHERE ID=".$this->ID
            ;
            $result = $this->Query($strQuery);
            return $result;
        }        
    }
    
    // Remove
    private function Remove ( $qid ) {
        
        // 1. Delete each Options/Answers from Test Questions 
        
        // 2. Delete Questions 
        
        $strQuery = "DELETE FROM $this->table WHERE ID=$qid";
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