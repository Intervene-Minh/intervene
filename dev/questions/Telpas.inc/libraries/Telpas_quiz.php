<?php
class Telpas_quiz{
 
    // database connection and table name
    private $conn;
    private $table_name = "Telpas_course_users";
 
    // object properties
    public $id;
    public $is_enroll;
    public $course_id;
    public $course_code;
    public $telpas_uuid;
    public $intervene_uuid;
    public $created_at;
    public $updated_at;
 
    public function __construct($db){
        $this->conn = $db;
    }
    /***
    @List Record
    */
    public function listAll(){
        $data=[];
        // $data='List all product';
         $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    created_at";  
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }
 
    // create product
    /***
    @Insert:Telpas_course_users

    **/
    public function create(){
        // posted values
        $this->is_enroll=1;
        $this->course_id=addslashes(strip_tags($this->course_id));
        $this->course_code=addslashes(strip_tags($this->course_code));
        $this->telpas_uuid=addslashes(strip_tags($this->telpas_uuid));
        $this->intervene_uuid=addslashes(strip_tags($this->intervene_uuid));
        // to get time-stamp for 'created' field
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at ='';
        // course_code
 
        // bind values 
        $data=array('is_enroll'=>1, 
                     'course_id'=>$this->course_id,
                     'intervene_uuid'=>$this->intervene_uuid,
                     'telpas_uuid'=>$this->telpas_uuid,
                     'created_at'=>$this->created_at,
    );
         $var_key=array_keys($data);
         $var_data=array_values($data);
        $user_type='students';
        $Sql="INSERT INTO ".$this->table_name ."(is_enroll,course_id,intervene_uuid,telpas_uuid,created_at) 
        VALUES ('".$this->is_enroll."', 
                 '".$this->course_id."',
                  '".$this->intervene_uuid."',
                  '".$this->telpas_uuid."',

                '".$this->created_at."' ) ";

         //echo $Sql; die; 
        $stmt = $this->conn->prepare($Sql);
        if($stmt->execute()){

            return true;
        }else{
            return false;
        }
 
    }
    // used for paging products
public function countAll(){
 
    $query = "SELECT id FROM " . $this->table_name . "";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
 
    $num = $stmt->rowCount();
 
    return $num;
}
    //
}
?>