<?php
include("header.php");
$ClassIdsAr=[];
 //echo 'Test';
 //print_r($_SESSION);
##############################
$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}




function _gradeName($id){

      $str="SELECT `id`,`name` FROM `terms` WHERE id=$id";
      $qr=mysql_query($str);
      $res = mysql_fetch_assoc($qr);
      return $res['name']; 
                           
}


if($_POST['action'] == 'update_class_name') {
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}




 $str =mysql_query("SELECT class_id FROM `class_x_teachers` WHERE `teacher_id` = '".$user_id."'");
 while($res = mysql_fetch_assoc($str)){

  $ClassIdsAr[$res['class_id']]=$res['class_id'];
}



$clsIDS=implode(',',$ClassIdsAr);
$str="SELECT  class.id as class_id, class.grade_level_id as grade_name, count(stu.id) as total_student, class.class_name,class.created
FROM `students_x_class`  as  stu  LEFT JOIN classes as class  ON class.id= stu.class_id
WHERE class.id  IN (".$clsIDS.")
GROUP BY class.id ORDER BY class.created DESC";
$classes_res = mysql_query($str);



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
                        <h3>Manage Classes(<?=mysql_num_rows($classes_res)?>)</h3>
                        <ul>
                            <li><a href="create_class.php"><i class="fa fa-plus-circle"></i> Add Class</a></li>
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
                                    <th>Class Name</th>
                                    <th>Grade Name</th>
                                    <th width="15%"># Of Students</th>
                                    <th width="30%">Action</th>
                                </tr>
                                <?php 
                               if (mysql_num_rows($classes_res) > 0) {
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($classes_res)) {
                                      $classid=$row['class_id'];
                                       ?>
                                        <tr id="<?php echo $row['class_id'];?>">
                                            <td align="center"><?php echo $i; ?></td>
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
                                            <td align="center"><?php echo _gradeName($row['grade_name']); ?></td>
                                            <td align="center"><?php echo $row['total_student']; ?></td>
                                           <?php 
                                           $edit_url='edit_classes.php?cid='.$row['class_id'];
                                           ?>
                                            <td align="center">
                                              <a title="Students List" href="<?=$edit_url?>">
                                                 <i class="fa fa-eye" aria-hidden="true"><div></div></i></a>&nbsp; &nbsp;
                                                    <a title="Edit Student" href="edit_classes.php?cid=<?php echo $row['class_id'] ?>">
                                                    <span class="glyphicon glyphicon-pencil"></span></a> 
                                                    &nbsp; &nbsp;
                                                    <a  title="Delete class" class="delete-classes" data-cid="<?php echo $row['class_id']; ?>"  href="javascript:;">
                                                    <span class="glyphicon glyphicon-trash"></span></a>
                                                     &nbsp; &nbsp;
                                                     <span style="cursor:pointer"  title="Click to Export Students of <?=$row['class_name']?>"
                                                      onclick="window.location.href='export_students.php?id=<?=$classid?>';"type="submit" name="submit_claim" class="" value="855"><i class="fa fa-file-excel-o"></i></span> 
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
<?php include("footer.php"); ?>