<?php
   include("header.php");
   $error = '';
   $login_role = $_SESSION['login_role'];
   $created = date('Y-m-d H:i:s');
   $user_id = $_SESSION['login_id'];
   $query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
   $rows = mysql_num_rows($query);
   if ($rows == 1) {
       $row = mysql_fetch_assoc($query);
       $school_id = $row['school'];
   }
   $id = $_SESSION['login_id'];
   $teacher_grade_res = mysql_query("
    SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
    FROM `techer_permissions`
    WHERE teacher_id = {$user_id} 
   ");
         
   $t_grades = mysql_fetch_assoc($teacher_grade_res);
   
   $teacher_grade = $t_grades['shared_terms'];
   
   $teacher_class = mysql_query('SELECT class_name, id FROM classes WHERE teacher_id = \''.$user_id.'\' AND school_id = \''.$school_id.'\' ');
   
   $cond = '';
   if(isset($_GET['first_name'])) {
       $cond .= ' AND stu.first_name LIKE "%'.$_GET['first_name'].'%" ';
   }
 
   
   if(isset($_GET['last_name'])) {
       $cond .= ' AND stu.last_name LIKE "%'.$_GET['last_name'].'%" ';
   }
   
   
   if($_GET['grade'] > 0) {
       $cond .= ' AND stu.grade_level_id = '.$_GET['grade'];
   }
   
   if($_GET['cid'] > 0) {
       $cond .= ' AND class.id = '.$_GET['cid'];
   }
     // mysql_query
   

$clsIDS=$_GET['cid'];
$str="SELECT  class.id as class_id, stu.grade_level_id,stu.student_id, class.class_name,class.created
FROM `students_x_class`  as  stu  LEFT JOIN classes as class  ON class.id= stu.class_id
WHERE class.id   = $clsIDS  ORDER BY class.created DESC";

$student_res = mysql_query($str);


function _gradeName($id){

      $str="SELECT `id`,`name` FROM `terms` WHERE id=$id";
      $qr=mysql_query($str);
      $res = mysql_fetch_assoc($qr);
      return $res['name']; 
                           
}


?>
<script>
   $(document).ready(function () {
       $('#delete-user').on('click', function () {
           var count = $('#form-manager .checkbox:checked').length;
           $('#arr-user').val("");
           $('#form-manager .checkbox:checked').each(function () {
               var val = $('#arr-user').val();
               var id = $(this).val();
               $('#arr-user').val(val + ',' + id);
           });
           var str = $('#arr-user').val();
           $('#arr-user').val(str.replace(/^\,/, ""));
           return confirm('Are you want to delete ' + count + ' user?');
       });
   });
</script>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div class="table-responsive">
               <form id="search-users" method="GET" action="">
                  <table class="table">
                     <tr>
                        <td><label>Search:</label></td>
                        <td><input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo (isset($_GET['first_name'])) ? $_GET['first_name'] : ''; ?>" /></td>


                        <td><input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo (isset($_GET['last_name'])) ? $_GET['last_name'] : ''; ?>" /></td>
                        <td>
                           <select name="grade" class="form-control">
                              <option value="">Choose Grade</option>
                              <?php
                                 $folders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$teacher_grade}) AND `active` = 1");
                                 if (mysql_num_rows($folders) > 0) {
                                     while ($row = mysql_fetch_assoc($folders)) {
                                 
                                 $select = (isset($_GET['grade']) && $_GET['grade'] == $row['id']) ? 'selected' : '';
                                 echo "<option value='{$row['id']}' {$select}>{$row['name']}</option>";
                                 }
                                 }
                                 ?>
                           </select>
                        </td>
                        <td style="display:none;">
                           <select name="cid" class="form-control">
                              <option value="">Choose Class</option>
                              <?php while($cls = mysql_fetch_assoc($teacher_class)) { 
                                 $select = (trim($_GET['cid']) == $cls['id']) ? 'selected="selected"' : '';
                                 echo "<option value='{$cls['id']}' {$select}>{$cls['class_name']}</option>";
                                 } ?>
                           </select>
                        </td>
                        <td><input type="submit" name="action" class="btn" value="Search" /></td>
                     </tr>
                  </table>
               </form>
            </div>
            <form id="form-manager" class="content_wrap" action="" method="post">
               <div class="ct_heading clear">
                  <h3>Manage Students</h3>
                  <ul>
                     <li><a href="create_class.php?class_id=<?php print $_GET['cid'] ?>"><i class="fa fa-plus-circle"></i> Add Students in this Class</a></li>
                     
                  </ul>
               </div>
               <!-- /.ct_heading -->
               <div class="clear">
                  <?php
                     if ($error != '') {
                         echo '<p class="error">' . $error . '</p>';
                     } else {
                         ?>
                  <table class="table-manager-user col-md-12">
                    <tr>
                        
                        <th>First Name</th>
                        <!--<th>Middle Name</th>-->
                        <th>Last Name</th>
                        <th>Grade</th>
                        <th>Class</th>
                        <th>Date Created</th>
                        <th>Action</th>
                     </tr>
                     <?php
                        if (mysql_num_rows($student_res) > 0) {

                            while ($row = mysql_fetch_assoc($student_res)) {
                              $stuID =  $row['student_id'];
                              $str ="SELECT * FROM `students` WHERE  id=$stuID";
                              $qr = mysql_query($str);
                              $Strow     = mysql_fetch_assoc($qr);
                              

                              $str="SELECT TelUserID  FROM `Tel_UserDetails` WHERE IntervenID=".$stuID;
                              $telpas_res = mysql_query($str);
                             $Telrow     = mysql_fetch_assoc($telpas_res);
                             $TelUserID = $Telrow['TelUserID'];
                              if( trim($Strow['first_name']) || trim($Strow['last_name']) ) {


                              ?>
                                <div id="response-msg" class="alert alert-success" style="display:none;"></div>
                             <tr id="<?php echo $stuID;?>">
                                <td align="center"><?php echo $Strow['first_name']; ?></td>
                                <!--<td><?php //echo $Strow['middle_name']; ?></td>-->
                                <td><?php echo $Strow['last_name']; ?></td>
                                <td><?php echo _gradeName($row['grade_level_id']); ?></td>
                                <td><?php echo $row['class_name']; ?></td>
                                <td><?php echo $row['created']; ?></td>
                                <td align="center">
                                  <a href="edit_student.php?sid=<?php echo $stuID;?>"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                   <a  class="delete-stud" data-sid="<?php echo $stuID; ?>" telUID="<?php echo $TelUserID;?>" 
                                    classID="<?php echo $row['class_id']?>"
                                      href="javascript:;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </td>
                             </tr>


                          <?php } } } else {
                                echo '<div class="clear"><p>There is no item found!</p></div>';
                            }
                            ?>
                  </table>
                  <?php } ?>
                  <div class="clearnone">&nbsp;</div>
               </div>
               <!-- /.ct_display -->
               <input type="hidden" id="arr-user" name="arr-user" value=""/>
            </form>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- /#header -->
<script type="text/javascript">
   $(document).ready(function(){
    $(".delete-stud").click(function(){
            var stud_id = $(this).data('sid');
            var telpas_id = $(this).attr('telUID');
            var classID = $(this).attr('classID');
            var flag = confirm('Are you sure you want to delete this student ?');
              if(flag) {
                  $.ajax({
                  type:"post",
                  url:"delete_student.php",
                  data:"student_id="+stud_id+"&action=deleteStudent&telUID="+telpas_id+"&classID="+classID,
                  success:function(data){
                      data = $.trim(data);
                      if(data=='true'){
                        $('#'+stud_id).remove();
                    $("#response-msg").html('Student(s) have been successfully deleted.').removeClass('alert alert-danger').addClass('alert alert-success').show(500);
                }
                  }
                  });
                    }else{
   
                    }
   
        });
   });
</script>
<?php include("footer.php"); ?>