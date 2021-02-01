<?

/**
 * Test
 * 
 * @package 
 * @author Kevin Pryce
 * @copyright 2014
 * @version $Id$
 * @access public
 */
 
require("Question.class.php");
class Test {
    
    // DB Settings
	private $tServer="localhost";
	private $tUser="ptwogorg_tutor";          
	private $tPass="tutor1234";
	private $tDB="ptwogorg_main";
	public $connection = null;
	
	// Table settings
	private $table = "tests";   
    
    // ----------------------------------------------//
    public $ID;
    public $Name;
    public $SubjectID;
    public $IsActive;
    public $CreatedBy;
    public $CreatedOn;
    public $LastUpdate;
    
    
    // ----------------------------------------------//
    public function Test ($loadID = null) {
        // Connect to DB
        $this->Connect();
        // Load object if ID
        if ($loadID != null)
            $this->Load($loadID);
    }
    
    // ----------------------------------------------//
    
    // Create a new Test, Populate and return this object
    public function Create($name, $subjectID, $isActive=1, $createdBy) {
        $result = $this->Add($name, $subjectID, $isActive, $createdBy);
        
        $lastID = $this->LastInsertID();
        $this->Load($lastID);
        
        return $result;        
    }
    
    // Save the Object
    public function Save() {
        return $this->Update();
    }
    
    
    // Delete a Test
    public function Delete($tid) {
        $result = $this->Remove($tid);        
        return $result;
    }
    
    // Loads a Test by ID, Populates the Object, and returns the query result
    public function Load($tid) {
        $strQuery = "SELECT * FROM $this->table WHERE ID=$tid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
        return $result;
    }    
    public function LoadBySubjectID($sid) {
        $strQuery = "SELECT * FROM $this->table WHERE SubjectID=$sid";
        $result = $this->Query($strQuery);
        
        $this->PopulateByResult($result);
        return $result;
    }    
    
    public static function LoadAllTests() {
        $strQuery = "SELECT * FROM $this->table";
        $result = $this->Query($strQuery);
        
        return $result;
    }
    
    public static function LoadAllTestsBySubjectID($subjectID) {
        $strQuery = "SELECT * FROM $this->table WHERE SubjectID=$subjectID";
        $result = $this->Query($strQuery);
        
        return $result;
    }
    
    public function getNumOfQuestions() {
        $rsObj = new Question();
        $rsQ = $rsObj->LoadAllQuestionsByTestID($this->ID);
        $numQuestions = mysqli_num_rows($rsQ);
        return $numQuestions;
    }
    
    public static function CountAll() {
        $res = $this->LoadAllTests();
        $numRows = mysqli_num_rows($res);
        return $numRows;
    }
    
    
    public function Deactivate() {
        $this->SetActive(false);
    }
    public function Activate() {
        $this->SetActive(true);
    }
    
    
    // ----------------------------------------------//
    
    
    
    // ----------------------------------------------//
    private function Query( $query ) {
		$result = mysqli_query($this->connection, $query ) or die (mysqli_error($this->connection).$query);
		return $result;
    }
    
    private function Add( $name, $subjectID, $isActive=1, $createdBy ) {
        $strQuery = "INSERT INTO $this->table
                            (
            					Name,
            					SubjectID,						 
            					IsActive,
            					CreatedBy,
            					CreatedOn
            				) 
            		VALUES (											
            					'".$name."',	
            					'".$subjectID."',	
            					'".$isActive."',
            					'".$createdBy."',	
            					DATE_ADD(NOW(),INTERVAL 1 HOUR)			
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
                    SubjectID='".$this->SubjectID."',
                    IsActive='".$this->IsActive."',
                    CreatedBy='".$this->CreatedBy."',
                    CreatedOn='".$this->CreatedOn."'  
                WHERE ID=".$this->ID
            ;
            $result = $this->Query($strQuery);
            return $result;
        }        
    }
    
    private function Remove ( $tid ) {
        // 1. Delete each Options/Answers from Test Questions
        
        // 2. Delete each Questions from Test
        
        // 3. Delete Test
        $strQuery = "DELETE FROM $this->table WHERE ID=$tid";
        $result = $this->Query($strQuery);
        
        return $result;
    }
    
    private function PopulateByResult( $result ) {
        while ($array = mysqli_fetch_array($result)) {
            $this->ID = $array['ID'];
            $this->Name = $array['Name'];
            $this->SubjectID = $array['SubjectID'];
            $this->IsActive = $array['IsActive'];
            $this->CreatedBy = $array['CreatedBy'];
            $this->CreatedOn = $array['CreatedOn'];
            $this->LastUpdate = $array['LastUpdate'];
        }
        return $result;
    }
    
    private function SetActive($state) {        
        $this->IsActive = $state == true ? 1 : 0;
        $strQuery = "UPDATE $this->table SET IsActive=$this->IsActive WHERE ID=$this->ID";
        $result = $this->Query($strQuery);
    }
    
    private function LastInsertID() {
        return mysqli_insert_id($this->connection);
    }
    
    private function Connect() {
        $this->connection = mysqli_connect($this->tServer, $this->tUser, $this->tPass, $this->tDB) or 
			die('Connection Error: '. mysqli_connect_error() );
		mysqli_select_db($this->connection, $this->tDB);
    }
}
?>