<?php
/*
@Show Students Telpas 
Recording to teacher .
@ Telpas audio directory
+ https://ellpractice.com/intervene/moodle/recordings/uploads/recording_1578574684.wav
**/
// echo 
$page_name='Audio recordings:';
include("header.php");

 //echo 'Test';
 //print_r($_SESSION); die;
##############################
$login_teacher_id=$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
//$classes_res = mysql_query('SELECT stu.class_id, COUNT(stu.id) as total_student, class.class_name '
//        . 'FROM students stu LEFT JOIN classes class '
//        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY stu.class_id');


if($_POST['action'] == 'update_class_name') {
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}

$classes_res = mysql_query('SELECT class.id as class_id, class.grade_level_name as grade_name, count(stu.id) as total_student, class.class_name,class.created '
        . 'FROM classes class LEFT JOIN  students stu '
        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY class.id ORDER BY class.created DESC ');


?>
<style>
     .table-manager-user {
    padding: 15px !important;
}
           </style>
           <script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
       </script> 
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                    <div class="ct_heading clear">
                        <h3>List Recordings (<?=mysql_num_rows($classes_res)?>)</h3>
                       
                    </div>		<!-- /.ct_heading -->
                    <div class="clear">
                        <?php
                        if (0) {
                            echo '<p class="error">' . $error . '</p>';
                        } else {
                            ?>
                            <div id="response-msg" class="alert alert-success" style="display:none;"></div>
                            
                            <table class="table-manager-user col-md-12">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Audio File</th>
                                    <th>QuestionID </th>
                                    <th width="15%">#Student </th>
                                   
                                    <th width="30%">Recording</th>
                                </tr>
                                <?php 
          // $classes_res= 
           //$sql=mysql_query("SELECT * FROM telpas_student_course_audios WHERE 1");
            $sql=mysql_query("SELECT ts.*, s.first_name, s.id as sid,s.middle_name, s.last_name
FROM telpas_student_course_audios ts
LEFT JOIN  students s
ON ts.intervene_student_id=s.id
WHERE ts.teacher_id=".$login_teacher_id);

           // $audio_url="http://intervene.io/questions/uploads/recordings/question_audio_4164Click%20on%20the%20teachers%20desk..mp3";
            $audio_url_2="https://ellpractice.com/intervene/moodle/recordings/uploads/";

           $audo_root="http://intervene.io/questions/audio/uploads/recordings/";// questions/uploads/recordings
             

 

                               if (mysql_num_rows($sql) > 0) {
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($sql)) {
                                      $classid=$row['id'];
                                      if($row['course_type']=='quiz'){
                                        $audo_root=$audio_url_2;
                                      }

                                       ?>
                                        <tr id="<?php echo $row['class_id'];?>">
                                            <td align="center"><?php echo $i; ?>

                                            
                                              

                                            </td>

                                            <td> 
                                                <a class="text-primary" style=" font-weight: bold;" href="javascript:;" data-class_id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#exampleModalCenter-<?php echo $row['id']; ?>">
                                                  <?php echo $row['file_audio']; ?>
                                                   </a>
                                                                                     <!-- Modal -->
                                              
                                             
                                             
                                                                                     
                                                                                     
                                            </td>
                                            <td align="center">
                                              QID:<?=$row['question_id']?> <br/>
                                              Resource Type:<?=$row['course_type']?>
                                             </td>
                                            <td align="center">
                                            <?=$row['first_name'].' '.$row['last_name'] ?>
                                                
                                              </td>
                                           <?php 
                                           $edit_url='edit_classes.php?cid='.$row['id'];
                                           // file_audio
                                           ?>
                                            <td align="center">

             <!--  <a title="Students List" href="<?=$edit_url?>">
            <i class="fa fa-eye" aria-hidden="true"><div></div></i></a>  -->
            <audio controls>
  <source src="<?php print($audo_root.$row['file_audio'])?>" type="audio/ogg">
  <source src="<?php print($audo_root.$row['file_audio'])?>" type="audio/mpeg">
Your browser does not support the audio element.
</audio>

                                        
                                                   
                                            
                                            
                                            </td>
                                        </tr>
                                                <?php
                                                $i++;
                                    }
                                        } else {
                                            echo '<div class="clear"><p>There is no item found!</p></div>';
                                        }
                                        ?>
                            </table>
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

