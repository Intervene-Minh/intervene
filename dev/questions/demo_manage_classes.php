<?php
include("header.php");
//echo $_SESSION['demo_user_id']; die;
$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$school_id = $_SESSION['school_id'];
$classes_res = mysql_query('SELECT class.id as class_id, count(stu.id) as total_student, class.class_name '
        . 'FROM demo_classes class LEFT JOIN  demo_students stu '
        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY class.id');

//Save: class//
if($_POST['action'] == 'update_class_name') {
    //print_r($_POST); die;
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE demo_classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}

?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
        <?php include("demo_sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                
                <?php  
                       // if ($error != '') { echo '<p class="error">' . $error . '</p>'; }   ?>
                
                    <div class="ct_heading clear">
                        <h3>Manage Classes</h3>
                        <ul>
                            <li><a href="demo_create_class.php"><i class="fa fa-plus-circle"></i> Add Class</a></li>
<!--                            <li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
                            <li>
                                <button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
                            </li>-->
                        </ul>
                    </div>		<!-- /.ct_heading -->
                    <div class="clear">
                        
                           
                        
                          
                            <div id="response-msg" class="alert alert-success" style="display:none;"></div>
                            <table class="table-manager-user col-md-12">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Class Name</th>
                                    <th width="15%"># of students</th>
                                    <th width="15%">Edit Roster</th>
                                    <th width="15%">Delete Class</th>
                                </tr>
                                <?php 
                               if (mysql_num_rows($classes_res) > 0) {
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($classes_res)) {
                                        ?>
                                        <tr id="<?php echo $row['class_id'];?>">
                                            <td align="center"><?php echo $i; ?></td>
                                           <!-- <td>XXX:: <?php echo $row['class_name']; ?></td>-->
                                             <td> 
                                                <a class="text-primary" style=" font-weight: bold;" href="javascript:;" data-class_id="<?php echo $row['class_id']; ?>" data-toggle="modal" data-target="#exampleModalCenter-<?php echo $row['class_id']; ?>"><?php echo $row['class_name']; ?> </a>
                                                                                     <!-- Modal -->
                                              <div    class="modal fade" id="exampleModalCenter-<?php echo $row['class_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                  <div class="modal-content">
                                                    <div class="modal-header" style="padding:15px !important;">
                                                      <h5 class="modal-title text-danger" id="exampleModalLongTitle"><?php echo $row['class_name'];?></h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -18px !Important;">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                      <form  class="popup-form" action="" method="post">
                                                    <div class="modal-body" style="padding:15px !important;">
                                                        <input type="text" class="form-control form-value required" required="required" name="edit_class_name" value="<?php echo $row['class_name'];?>">
                                                    </div>
                                                    <div class="modal-footer" style="padding:15px !important;">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                      <button type="submit" name="save_edit" value="Save" class="btn btn-primary">Save changes</button>
                                                      <input type="hidden" name="hdn_class_id" value="<?php echo $row['class_id']; ?>" />
                                                      <input type="hidden" name="action" value="update_class_name" />
                                                      
                                                    </div>
                                                      </form>
                                                  </div>
                                                </div>
                                              </div>
                                             
                                             
                                                                                     
                                                                                     
                                            </td>
                                            
                                            
                                            <td align="center"><?php echo $row['total_student']; ?></td>
                                            <td align="center"><a href="demo_edit_classes.php?cid=<?php echo $row['class_id'] ?>">Edit</a></td>
                                            <td align="center"><a  class="delete-classes" data-cid="<?php echo $row['class_id']; ?>"  href="javascript:;">Delete</a></td>
                                        </tr>
                                                <?php
                                                $i++;
                                    }
                                        } else {
                                            echo '<div class="clear"><p>There is no item found!</p></div>';
                                        }
                                        ?>
                            </table>
                            
                        <div class="clearnone">&nbsp;</div>
                    </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
 <script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}'); window.location.replace('demo_manage_classes.php');"; } ?>
       </script> 
<script type="text/javascript">
               $(document).ready(function(){
                   
                    $(".delete-classes").click(function(){
                        var class_id = $(this).data('cid'); 
                        var flag = confirm('Are you sure you want to delete the selected class.');
                          if(flag) {
                              $.ajax({
                              type:"post",
                              url:"delete_student.php",
                              data:"classes_id="+class_id+"&action=deletedemoClasses",
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
<?php include("footer.php"); ?>

