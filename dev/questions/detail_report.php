<?php
/* * *
 * @ quiz_results
 * @ @Teacher --all student in attempt quiz
 * @Teacher 
 * @ all quiz ,  assgigned by Teacher 
 * 
 * @ detail_report
 * *** */

$page_name = "Exit quiz_results";
include("header_custom.php");


$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
//$classes_res = mysql_query('SELECT stu.class_id, COUNT(stu.id) as total_student, class.class_name '
//        . 'FROM students stu LEFT JOIN classes class '
//        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY stu.class_id');


if ($_POST['action'] == 'update_class_name') {
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}

$classes_res = mysql_query('SELECT class.id as class_id, class.grade_level_name as grade_name, count(stu.id) as total_student, class.class_name,class.created '
        . 'FROM classes class LEFT JOIN  students stu '
        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY class.id ORDER BY class.created DESC ');
/// Question in  Asseement 
$sql="SELECT COUNT(*) FROM `assessments_x_questions` WHERE `assesment_id` =".$_GET['assesment'];
$data=mysql_fetch_row(mysql_query($sql));
$ques_in_assment=$data[0];
//print_r($ques_in_assment)   ;die;

?>
<style>
    .table-manager-user {
        padding: 15px !important;
    }
</style>
<script type="text/javascript">
<?php if ($error != '') {
    echo "alert('{$error}');";
} ?>
</script> 
<div id="main" class="clear fullwidth">
    <div class=""  style=" margin-left:50px;">
        <div class="row">
          <h1 class="text-left text-primary">Overall Objective Summary</h1>   
          
           
           
                





                <!-- /.ct_heading -->
                <div class="clear">
                    

                    <div id="response-msg" class="alert alert-success" style="display:none;"></div>
<?php
// Overall Objective Summary/ mysql_query /
$objective_res =mysql_query('SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name,COUNT(a_x_s.assesment_id) AS obj_ques FROM terms t '
        . 'LEFT JOIN term_relationships rel ON rel.objective_id = t.id '
        . 'LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id '
        . 'WHERE a_x_s.assesment_id = \'' . $_GET['assesment'] . '\'  AND ( t.objective_type = "R" OR t.objective_type = "S" ) GROUP BY t.id ');

  // echo $objective_res ;   die;          
 // obj_ques :: Total Questions in objective 
$toatal_obj = mysql_num_rows($objective_res);
// echo  $objective_res ;
?>


                    <table class="table-manager-user col-md-4">
                        <tr>

                            <th>Objective</th>
                            <th width="25%">Mastery%</th>
                            <th width="15%">R/S</th>


                        </tr>
<?php
$i = 1;
while ($row = mysql_fetch_assoc($objective_res)) {
    // objective_type  short_code obj_short name
    $arr = array("objid" => $row['id'], "objective_type" => $row['objective_type'],
        "short_code" => $row['short_code'],
        "obj_short" => $row['obj_short'], "name" => $row['name'],"ques_in_obj"=>$row['obj_ques']); //
    $obj_arr[] = $arr;

  $ques_in_obj=$row['obj_ques'];  // >0 * 
    # student Total Corrected and Total attempted by in class&&assmwnt // mysql_query      
    $readinesss_score_res = mysql_query('SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, ((SUM( s_x_s.corrected ) / count( s_x_s.qn_id )) *100) AS percentage, student_id
FROM students_x_assesments s_x_s 
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id 
AND rel.objective_id = \'' . $row['id'] . '\'  
WHERE s_x_s.assessment_id =\'' . $_GET['assesment'] . '\' AND s_x_s.class_id = \'' . $_GET['cid'] . '\'   AND  rel.question_id = s_x_s.qn_id 
GROUP BY s_x_s.student_id');
   // echo $readinesss_score_res ; die;
    
    $toatal_stu_rows = mysql_num_rows($readinesss_score_res);

    // total corrected #
    // (all student corrected)/totStu*objOrgQuestionsInAssement
    $total_corrected = 0;
    $total_per_stu_ques = 0;
    while ($row_obj = mysql_fetch_assoc($readinesss_score_res)) {
        $total_corrected += $row_obj['correct'];
        $total_per_stu_ques += $row_obj['total'];
    }
    $mastery_percent = 0;
     $per=0;$col_bg="red"; // deff
     // QuestionInObj>0
    //if ($toatal_stu_rows > 0 && $total_per_stu_ques > 0) {
      if ($toatal_stu_rows > 0 && $toatal_stu_rows > 0&&$ques_in_obj>0) {
          $gtotal_ques=$toatal_stu_rows*$ques_in_obj;  // forAllStudentsInaObj
        $mastery_percent = (($total_corrected * 100) /$gtotal_ques);
        // $mastery_percent=  round($mastery_percent, 2);
        $per=$mastery_percent = round($mastery_percent);
      
    
    }
    
    if($per>=70)
        $col_bg="green";elseif($per>=60&&$per<=69)  $col_bg="yellow";
        elseif($per>=50&&$per<=59)$col_bg="orange";else $col_bg="red";
//echo $readinesss_score_res .'xx'; die;   
    ///////////////////////////////////               
    // objective_type
    //$mastery_percent=($mastery_percent>0)?$mastery_percent."%":0;  
        $mastery_percent=$mastery_percent."%";
    ?>
                            <tr id="<?php echo $row['id']; ?>">

                                <td>
                                    <strong class="text-danger">
                            <?= $row['name']; ?>
                                        <br/>
                                        <?php //= $total_corrected."/". $total_per_stu_ques  ?>      
                                    </strong>
                          <?php //=($ques_in_obj);//$toatal_stu_rows// $ques_in_obj?>    
                                
                                
                                </td>

                                    <td align="center" style="color:<?=($col_bg=="yellow")?"black":"#fff";?>;background-color:<?=$col_bg?>;"><?=$mastery_percent?></td>
                                <td align="center"> <?= $row['objective_type']; ?></td>


                            </tr>
    <?php
    $i++;
}
?>
                    </table>

                    <br/><br/>
                    
                        <?php
                        // echo '<pre>' ; print_r($obj_arr); die;
                        $total_objectives = count($obj_arr);
                        //$_GET['assesment'] $_GET['cid']
                        $sql = "SELECT DISTINCT(student_id) FROM `students_x_assesments` WHERE assessment_id=" . $_GET['assesment'] . " And class_id= " . $_GET['cid'];
                      
                        $results_stu = mysql_query($sql);
                        $tota_student = mysql_num_rows($results_stu);
                        //echo 'Total students -' . $tota_student;
                        ?>

                   


                    <!--      <h1>Student Objective Data</h1>-->





<?php //}  ?>
                    <div class="clearnone">&nbsp;</div>
                </div>
           
           		<!-- /#content -->
            
            
            
            
            
            
            
            <div class="clearnone">&nbsp;</div>
        </div>
        
        <div class="row">
            <h1 class="text-left text-primary">Student Objective Data</h1>
           <table class="table-manager-user col-md-12"  >
                        <tbody>
                            <tr>

                                <th style="width:150px;" ></th>
                    <?php $k = 0;
                    while ($k < $total_objectives) { ?>
                                <th style="width:60px;"><?= $obj_arr[$k]['objective_type'] ?></th>
                                    <?php $k++; } ?>

    
                                <th  style="width:100px; background-color:yellow">Overall</th>


                            </tr>  
                            <tr>

                                <th style="width:150px;" >Student Name</th>
<?php $k = 0;
while ($k < $total_objectives) { ?>
                                    <th><?= $obj_arr[$k]['obj_short'] ?></th>
    <?php $k++;
} ?>

                                <th  style=" width:100px;background-color:yellow"></th>


                            </tr>


<?php
while ($row = mysql_fetch_assoc($results_stu)) {
    // student_id
    $std_id = $row['student_id'];
    $student = mysql_fetch_assoc(mysql_query(" SELECT * FROM students WHERE id=" . $row['student_id']));

    $stu_name = $student['first_name'] . " " . $student['middle_name'] . " " . $student['last_name'];
    //$stu_name=(!empty($stu_name))?$stu_name:"Student_".$i;
    //////////////// Student detail          
    ?>  

                                <tr id="<?= $row['student_id'] ?>">

                                    <td>
                                        <strong class="text-primary"><?= $stu_name ?></strong>
                                    </td>


    <?php
    $k = 0;
    while ($k < $total_objectives) {
        ///  Calculate student percantage . 
        $newobjid = $obj_arr[$k]['objid']; 
       $tot_ques_in_obj=$obj_arr[$k]['ques_in_obj'];  // ques_in_obj
        $sql = " SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, "
                . "((SUM( s_x_s.corrected ) /$tot_ques_in_obj) *100) AS percentage, student_id "
                . "FROM students_x_assesments s_x_s "
                . "LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id ";
        $sql .= " WHERE s_x_s.assessment_id ='" . $_GET['assesment'] . "' "
                . "AND s_x_s.class_id = '" . $_GET['cid'] . "' "
                . "AND rel.question_id = s_x_s.qn_id "
                . "AND rel.objective_id = '$newobjid' "
                . "AND s_x_s.student_id='$std_id'  ";
         //echo  $sql ; die;
        $score_det = mysql_fetch_assoc(mysql_query($sql));
        $stud_score = $score_det['percentage']; //
        // $stud_score=(intval($stud_score)>0)?round($stud_score):0;
        $per=0;$col_bg="red"; // deff
      $per=$stud_score = round($stud_score);
        
        if($per>=70)
        $col_bg="green";elseif($per>=60&&$per<=69)  $col_bg="yellow";
        elseif($per>=50&&$per<=59)$col_bg="orange";else $col_bg="red";
        //  $stud_score=0;
        ///  Calculate student percantage .           
        ?>
                                        <td align="center" style="color:<?=($col_bg=="yellow")?"black":"#fff";?>;background-color:<?=$col_bg?>;">
        <?=$stud_score."%"; ?></td>
                                        <?php $k++;
                                    } ?>
                                        <?php 
                                        // mysql_fetch_assoc(mysql_query(
                                     $average=mysql_fetch_assoc(mysql_query('SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, ((SUM( corrected ) /'.$ques_in_assment.') *100) AS percentage
FROM students_x_assesments
WHERE assessment_id =\'' . $_GET['assesment'] . '\' AND class_id = \''.$_GET['cid'].'\'  AND student_id = \''.$std_id.'\'
')); //$average = mysql_fetch_assoc($score_res)
                                  //   echo $average ;die;
                                        ?>
                                    <td align="center" style=" background-color:yellow"><?php print round($average['percentage']).'%'; ?></td>


                                </tr>
<?php } ?>                


                        </tbody></table> 
            
        </div>    
<!--        End row-->
        
        
        
        
    </div>
    
    
<!--   gfgg -->

 
    
    
    
    
</div>		<!-- /#header -->



