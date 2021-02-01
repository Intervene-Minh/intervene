<?php
/***
 * @ quiz_results
 * @ @Teacher --all student in attempt quiz
 * @Teacher 
 * @ all quiz ,  assgigned by Teacher 
 * @ Class filter 
   //Teacher classwise intervention in which student apear.
    $q_quiz=" SELECT ses.slot_id,ses.quiz_id
FROM int_slots_x_student_teacher ses
LEFT JOIN students s
ON ses.student_id=s.id
WHERE ses.quiz_id>0 AND ses.int_teacher_id= '884' AND s.class_id=421
GROUP BY ses.slot_id;
";
 * 
 * ****/

$page_name="Exit quiz_results";
//include("header.php");
include("header_custom.php");
$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
@extract($_GET); //@extract($_POST);
 //print_r($_POST); die;
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
//$classes_res = mysql_query('SELECT stu.class_id, COUNT(stu.id) as total_student, class.class_name '
//        . 'FROM students stu LEFT JOIN classes class '
//        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY stu.class_id');

$_GET['assesment']=3;$_GET['cid']=4;
 $score_res = ('SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, ((SUM( corrected ) / count( qn_id )) *100) AS percentage, student_id
FROM students_x_assesments
WHERE assessment_id =\'' . $_GET['assesment'] . '\' AND class_id = \''.$_GET['cid'].'\'  
GROUP BY student_id');
// echo  '<pre>'; print_r($score_res); die;
 
 
    //print_r($score_percentage_group); die;

////////////////////////////////////////
if($_POST['action'] == 'update_class_name') {
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}

$classes_res = mysql_query('SELECT class.id as class_id, class.grade_level_name as grade_name, count(stu.id) as total_student, class.class_name,class.created '
        . 'FROM classes class LEFT JOIN  students stu '
        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY class.id ORDER BY class.created DESC ');

///

$res = mysql_query('SELECT class.*, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');
//// 1st Class id 
$res2=('SELECT class.id,class.class_name, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' LIMIT 1');
 $class_det= mysql_fetch_assoc(mysql_query($res2));
 // Default: class
 if(isset($_POST['class'])){//Filter class
 $class=$_POST['class'];    
 }else $class=$class_det['id'];//Default
 
 //echo  $class ; die;
?>
<style>
     .table-manager-user {
    padding: 15px !important;
}
           </style>
           
   <script>
function myFunction() {
    window.open("session_feedaback.php");
}
</script>        
           
           <script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
       </script> 
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
    <div id="sidebar" class="col-md-4">
         <?php  include("sidebar.php"); ?>
            </div>
          <h3><?php  //=$page_name?></h3>
            <!-- /#sidebar -->
            <div id="content" class="col-md-8">

                
<form id="search-users" method="POST" action="" class="col-md-4">
				
    
                     <p>
                                   
                                    <select name="class" class="required textbox"  onchange="this.form.submit()">
                                           
                                        <option value="">Choose Class</option>
                                        <?php
                                        if (mysql_num_rows($res) > 0) {
                                            while ($result = mysql_fetch_assoc($res)) {
                                                $selected = ($result['id'] ==$class) ? 'selected' :NULL;
                                                echo '<option value="' . $result['id'] . '"' . $selected . '>'.$result['grade_name'].' : ' . $result['class_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
    
					</form>




                <!-- /.ct_heading -->
                    <div class="clear">
                        <?php
                        if (0) {
                            echo '<p class="error">' . $error . '</p>';
                        } else {
                            ?>
                            
                        
                        <div id="response-msg" class="alert alert-success" style="display:none;"></div>
                            <?php 
                            $teacher_id=$user_id; //
                            $total_quiz=0;  // ROWS,
                            $total_st_appeared=0; //COL //in all session by TeacherAllClass
                           
                         
                  // $q_quiz="SELECT * FROM `int_schools_x_sessions_log` WHERE 1 AND teacher_id=399 ";
                            
                       $q_quiz=" SELECT qz.objective_name,ses.* FROM int_schools_x_sessions_log ses LEFT JOIN int_quiz qz ON ses.quiz_id=qz.id ";

              //  $q_quiz.=" WHERE ses.quiz_id>0 ";// Qui    

        //  $q_quiz.=" WHERE ses.teacher_id='$teacher_id' AND ses.quiz_id>0 ";// Quiz asgigned in 
               //echo  $q_quiz; die;
                //  if(isset($class)&&!empty($class))
                    //$q_quiz.=" AND ses.class_id='$class' ";
                $q_quiz=" SELECT ses.slot_id,ses.quiz_id
FROM int_slots_x_student_teacher ses
LEFT JOIN students s
ON ses.student_id=s.id
WHERE ses.quiz_id>0 AND ses.int_teacher_id= '$teacher_id' ";

     if(isset($class)&&!empty($class)){
      $q_quiz.=" AND s.class_id='$class' ";
     }
        $q_quiz.=" GROUP BY ses.slot_id ";   
        #######################        

          //echo   $q_quiz ;  //  die;
                $res_quiz= mysql_query($q_quiz);  
             $total_quiz=mysql_num_rows($res_quiz); // Total sessoins Quiz

                              while ($line= mysql_fetch_assoc($res_quiz)) {
                                $sesId=$line['slot_id'];

                                 $q_quiz=" SELECT qz.objective_name,ses.* FROM int_schools_x_sessions_log ses LEFT JOIN int_quiz qz ON ses.quiz_id=qz.id WHERE ses.id=".$sesId;
                                 $row= mysql_fetch_assoc(mysql_query($q_quiz));


                                // $quiz_arr[$row['quiz_id']]=$row['objective_name'];
                            $ques_in_quiz=0;
                            $sql="SELECT COUNT(*) AS quiz_ques FROM `int_quiz_x_questions` WHERE `quiz_id` ='".$line['quiz_id']."' ";
                             $det= mysql_fetch_assoc(mysql_query($sql));


                             $ques_in_quiz=$det['quiz_ques'];

                                  $arr=array("quiz_id"=>$line['quiz_id'],"name"=>$row['objective_name'],
                                      "sesid"=>$sesId,"quiz_ques"=>$ques_in_quiz); //
                              $ses_quiz[]=$arr;
                                  
                            }
                            
                          
                        //print_r($ses_quiz); die;
                         
                       // TotalStudents X Total Session Quiz///
                       
                            
                            
                             
                        
                        //echo 'total quiz '.$total_quiz;
                  // $q_student="SELECT DISTINCT(student_id) FROM students_x_quiz WHERE teacher_id='$teacher_id' ";

               // $q_student="SELECT s.*,sq.student_id FROM students_x_quiz AS sq JOIN students AS s ON sq.student_id=s.id"
               //          . " WHERE sq.teacher_id='$teacher_id' ";

                
                   $q_student="SELECT s.*,sq.student_id FROM students_x_quiz AS sq JOIN students AS s ON sq.student_id=s.id"
                        . " WHERE s.teacher_id='$teacher_id' ";



                       // . "AND sq.class_id='301' "

                      if(isset($class)&&!empty($class)){
                           $q_student.=" AND s.class_id='$class' "; 
                      }
                  
                   
                   $q_student.= "GROUP BY sq.student_id  ORDER BY s.first_name ASC;";      
                         // Filter by Class
                  //echo $q_student; 

                       
                  
                        
                       //echo  $q_student;
                        // Filter by Class
                         $res_student= mysql_query($q_student); //  total student apeared
                       
                       $total_st_appeared=mysql_num_rows($res_student); 
                     //  echo ' total student'.$total_st_appeared;// die;
                         // mysql_num_rows
                       
        ?>






                        <style>
                            th, td{padding: 15px !important;}
                            </style>
                        <div class="table-responsive999">   
                        <table style="
overflow: auto;" class="table-manager-user table-responsive">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <?php $k=0;while($k<$total_quiz){?>
                                    <th>OBJ</th>
                                    <th >Session Report<?=$k+1?></th>
                                   
                                    <th >Quiz Score<?=($k+1)?></th>
                                    <?php $k++; }?>
                                </tr>
                                <?php 
                              // if (mysql_num_rows($classes_res) > 0) {
                                
                                
                                
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($res_student)) {
                                  
              //  $student=mysql_fetch_assoc(mysql_query(" SELECT * FROM students WHERE id=".$row['student_id']));
                                      $std_id=$row['student_id'];
                              $stu_name=$row['first_name']." ".$row['middle_name']." ".$row['last_name'];
                                      $stu_name=(!empty($stu_name))?$stu_name:"Student_".$i;
                                      // Result score of Student in Quiz
                                   
                    
                                      
                                       ?>
                                        <tr id="<?php echo $row['student_id'];?>">
                                            <td align="center"><?php echo $i; ?></td>
                                            <td><strong class="text-danger">
                                                    <?=$stu_name?> </strong></td>
                                           
                                                    
                                             <?php $k=0;while($k<$total_quiz){ 
                                                 $liveses_id=$ses_quiz[$k]['sesid'];
                                                 $Quiz=$ses_quiz[$k]['quiz_id'];// quiz_ques
                                             $tot_ques=$ses_quiz[$k]['quiz_ques'];

                                   // Validate Student apeared in sesison or not .::For Each sssion    
                           $q="SELECT * FROM `int_slots_x_student_teacher` WHERE `slot_id` ='$liveses_id' AND `student_id` ='$std_id' ";        
                             $is_quiz_assign= mysql_num_rows(mysql_query($q)); // 0 not assigned toStu
                             $not_apear="-";//Not Apear
                         $is_apeared=($is_quiz_assign==1)?"Yes":$not_apear;
                            // tutses_id  quiz_id //SesQuizStu>0 atttempted
                           //echo '<pre>'.$q; die;
                                     //////////////////////////////////////// 
                         $student_score="-";
                        if($is_quiz_assign==1){
                         // $tot_ques ::quesInQuiz
                              $qq=" SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, ((SUM( corrected ) /$tot_ques) *100) AS percentage, student_id FROM students_x_quiz ";                
                         $qq.=" WHERE tutses_id='$liveses_id' AND student_id='$std_id' "; 
                        // $qq.=" AND quiz_id='$Quiz' AND teacher_id='$teacher_id' ";
                          $qq.=" AND quiz_id='$Quiz' ";
                        // print_r($qq);die; //Student score
                          $score=mysql_fetch_assoc(mysql_query($qq));  
                      $student_score=(intval($score['percentage'])>0)? round($score['percentage']).'%':0;
                           $student_score=round($score['percentage']).'%';
                        
                       }           
                                                 
                                                 ?>       
                                                    
                                            <td align="center">
                          <?=($is_quiz_assign==1)?$ses_quiz[$k]['name']:$not_apear;?></td>
                                            <td align="center">
                                              <?php  if($is_quiz_assign==1){ ?>  
                                                <a style="cursor:pointer;"
                  onclick="window.open('session_feedaback.php?ses=<?=$liveses_id?>','Session Details', 'width=800,height=600;')">View Session</a>
                                              <?php }else echo $not_apear;?>           
                                            </td>
                                         
                                            <td align="center">
                                       <?=$student_score?>
                                            </td>
                                                <?php $k++; }?>
                                            
                                            
                                        </tr>
                                                <?php
                                                $i++;
                                    }
                                       
                                        ?>
                            </table></div>
                            <?php } ?>
                        <div class="clearnone">&nbsp;</div>
                    </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<script type="text/javascript">
               $(document).ready(function(){
                   
                    $(".delete-classes").click(function(){
                        var class_id = $(this).data('cid'); 
                        var flag = confirm('Are you sure you want to delete the selected class.');
                          if(flag) {
                              $.ajax({
                              type:"post",
                              url:"delete_student.php",
                              data:"classes_id="+class_id+"&action=deleteClasses",
                              success:function(data){
                                  data = $.trim(data);
				if(data=='true'){
                                $('#'+class_id).remove();
                                $("#response-msg").html('<strong>Thank you!</strong> Class has been successfully deleted.').removeClass('alert alert-danger').addClass('alert alert-success').show(500);
                            }
                              }
                              });
                                }else{

                                }
 
                    });
               });
       </script>
       
       
       <script>
//	(function($){
//		$(".popup-form").submit(function(e){
//			e.preventDefault();
//                        var data = $('.form-value').val();
//                        alert(data);
//            var f = 1 ;
//			$(".popup-form input.required").each(function(){
//				var va = $(this).val();
//				if( va == '' || typeof(va) == 'undefined' || va == null ){
//					f = 0;
//					if( $(this).next('label.error').length == 0 ){
//						var lbl = '<label class="error alert-danger">This field is required</label>';
//						$(this).after(lbl);
//					}
//				}
//			});
////                    if( f == 1 ){   
////			$.ajax({
////                              type:"post",
////                              url:"ajax.php",
////                              data:"classes_id="+class_id+"&action=editClasses",
////                              success:function(data){
////                                  data = $.trim(data);
////				if(data=='true'){
////                                $('#'+class_id).remove();
////                                $("#response-msg").html('<strong>Thank you!</strong> Class has been successfully deleted.').removeClass('alert alert-danger').addClass('alert alert-success').show(500);
////                            }
////                              }
////                              });
////		}
//		
//             
//		});
//		
//	})(jQuery);
    </script>

<?php include("footer.php"); ?>

