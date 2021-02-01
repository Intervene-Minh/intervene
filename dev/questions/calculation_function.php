  <?php
  function cdb_check_writing_attempt($course_id='26',$telpas_uid='')
 {
    $result=[];
    global $intervene_uid;
    global $TelPasUserID;
    $sql_completed_course="SELECT * FROM  Tel_CourseComplete WHERE  TelUserID= '$TelPasUserID' AND CourseID= '$course_id' ";
    $total_record=mysql_num_rows(mysql_query($sql_completed_course));
    
    if($total_record>=1)
    {
    
      $result['attempt_status']='completed';
      $result['number_question_attempted']='all';
      $result['course_id']=$course_id;
    }
    else
    { // not sure: 

        $sql_writing="SELECT * FROM telpas_student_writing_grades 
        WHERE telpas_student_id= '$TelPasUserID' AND course_id= '$course_id' ";
        $number_question_attempted=mysql_num_rows(mysql_query($sql_writing));
        $result['attempt_status']=$number_question_attempted>0?'in_progress':'not_started';
        $result['number_question_attempted']=$number_question_attempted;
        $result['course_id']=$course_id;
    }
    return $result;
 }


 function cdb_check_speaking_attempt($course_id='20')
    {
        $result=[];
        $result['type']='speaking_attempt';
        global $intervene_uid;
        global $TelPasUserID;
        $sql_completed_course="SELECT * FROM  Tel_CourseComplete WHERE  TelUserID= '$TelPasUserID' AND CourseID= '$course_id' ";
        $total_record=mysql_num_rows(mysql_query($sql_completed_course));
        if($total_record>=1){
            $result['attempt_status']='completed';
            $result['number_question_attempted']='all';
            $result['course_id']=$course_id;
        }else
        { 

            $sql_writing="SELECT * FROM telpas_student_speaking_grades 
            WHERE telpas_student_id= '$TelPasUserID' AND course_id= '$course_id' ";
            $number_question_attempted=mysql_num_rows(mysql_query($sql_writing));
            $result['attempt_status']=$number_question_attempted>0?'in_progress':'not_started';
            $result['number_question_attempted']=$number_question_attempted;
            $result['course_id']=$course_id;
        }
        return $result;
    }
/*get score function*/
 function _student_get_score($table,$studet_id,$course_id)
 {

        $sql_spearking_question="SELECT * FROM  $table WHERE course_id='".$course_id."' AND intervene_student_id=".$studet_id;
        $result=mysql_query($sql_spearking_question);
        $grade_total=0;
        $grade_scored_toatal=0;
        while ($row=mysql_fetch_assoc($result)) {
            # code...
            $grade_total=$grade_total+intval($row['max_grade_number']);
            $grade_scored_toatal=$grade_scored_toatal+intval($row['scored_grade_number']);
            // print_r($row); die; 
        }

        return $scored=(100*$grade_scored_toatal)/($grade_total);

}
?>