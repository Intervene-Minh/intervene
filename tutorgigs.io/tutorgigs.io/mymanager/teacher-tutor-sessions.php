<?php
 // teacher-tutor-sessions :: of a Teacher 

include("header.php");
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
                        <h3>My Tutor Sessions</h3>
                        <ul>
                            <li><i class="fa fa-user"></i></li>
<!--                            <li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
                            <li>
                                <button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
                            </li>-->
                        </ul>
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
                                    <th>Date</th>
                                    <th >students</th>
                                    <th width="15%">Special notes</th>
                                    
                                   
                                    <th width="15%">Quiz</th>
                                    <th >Action</th>
                                </tr>
                                <?php 
                               // intTecher Slots  
                                // $user_id = $_SESSION['login_id'];
                                
                                
                                $q="SELECT * FROM int_schools_x_slots_master WHERE teacher_id='$user_id' ";
                                $results=mysql_query($q);
                               // echo  $user_id ; 
                                
                                
                                
                               if (mysql_num_rows($results) > 0) {
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($results)) {
                                      
                                     // Note if any 
                                if($row['quiz_id']>0)       
                           $quiz=mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id='".$row['quiz_id']."' ") ) ; 
                           
                           
                           $quiz_name=($row['quiz_id']>0)?$quiz['objective_name']:"XX";
                           
                     $tot=mysql_num_rows(mysql_query("SELECT * FROM `int_slots_x_student_teacher` WHERE slot_id='".$row['id']."' ")) ;             
                                 $tot_students=($tot>0)?$tot:"XX";  // if asigned Student by teacher>> #totStud>0
                                 
                                 $msg=NULL;
                                     if($tot_students=="XX")
                                     $msg="Student not Assigned";
                                     
                                  $row['special_notes']=(!empty($row['special_notes']))?$row['special_notes']:"XX";
                                  $st_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-sm":"btn btn-primary btn-sm";
                                 
                                    ////////////////   
                                       ?>
                                        
                                        <tr id="37">
                                            <td align="center"><?=$i?>. </td>
                                            
                                             <td align="center"> 
                                               <?=$row['slot_date_time']?> </td>
                                            
                                             <td align="center" title="<?=$msg?>"> 
                                                <?=$tot_students?>    </td>
                                            <td> <?=$row['special_notes']?></td>
                                            <td align="center">
                                              <?=$quiz_name?>  
                                            
                                            </td>
                                            <td align="center">
                                               
                                   <span class="<?=$st_class?>"><?=$row['tut_status']?></span>     
                                      <br/>
                                      <?php if(intval($tot_students)==0){?>
                               <a  href="assign-students.php?ses=<?=$row['id']?>"   > Asign Students </a>
                                      <?php }?>
                               
                               
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

