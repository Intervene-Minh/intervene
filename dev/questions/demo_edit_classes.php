<?php
include("header.php");

$login_role = $_SESSION['login_role'];

$error = '';
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$school_id = $_SESSION['school_id'];
$id = $_SESSION['demo_user_id'];
$teacher_grade_res = mysql_query("
	SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$user_id} 
");
        
        
$t_grades = mysql_fetch_assoc($teacher_grade_res);

$teacher_grade = $t_grades['shared_terms'];

$teacher_class = mysql_query('SELECT class_name, id FROM demo_classes WHERE teacher_id = \''.$user_id.'\' AND school_id = \''.$school_id.'\' ');

$cond = '';
if(isset($_GET['first_name'])) {
    $cond .= ' AND stu.first_name LIKE "%'.$_GET['first_name'].'%" ';
}
if(isset($_GET['middle_name'])) {
    $cond .= ' AND stu.middle_name LIKE "%'.$_GET['middle_name'].'%" ';
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

$student_res = mysql_query('SELECT stu.*, t.name, class.class_name FROM demo_students stu LEFT JOIN terms t ON stu.grade_level_id = t.id AND stu.grade_level_id != 0 '
        . 'LEFT JOIN demo_classes class ON class.id = stu.class_id  '
        . ' WHERE '
        . 'stu.teacher_id = \'' . $user_id . '\' AND '
        . 'stu.school_id = \'' . $school_id . '\' '.$cond.' GROUP BY stu.id  ORDER BY stu.created DESC ');
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
<?php include("demo_sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div class="table-responsive">
                    <form id="search-users" method="GET" action="">
                        <table class="table">
                            <tr>
                                <td><label>Search:</label></td>
                                
                                <td><input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo (isset($_GET['first_name'])) ? $_GET['first_name'] : ''; ?>" /></td>
                                <td><input type="text" name="middle_name" class="form-control" placeholder="Middle Name" value="<?php echo (isset($_GET['middle_name'])) ? $_GET['middle_name'] : ''; ?>" /></td>
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
                            <li><a href="demo_create_class.php?class_id=<?php print $_GET['cid'] ?>"><i class="fa fa-plus-circle"></i> Add Students in this Class</a></li>
<!--                            <li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
                            <li>
                                <button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
                            </li>-->
                        </ul>
                    </div>		<!-- /.ct_heading -->
                    <div class="clear">
                        <?php
                        if ($error != '') {
                            echo '<p class="error">' . $error . '</p>';
                        } else {
                            ?>
                            <table class="table-manager-user col-md-12">
<!--                                <colgroup>
                                    <col width="30">
                                    <col width="220">
                                    <col width="80">
                                    <col width="80">
                                    <col width="120">
                                    <col width="100">
                                    <col width="100">
                                </colgroup>-->
                                <tr>
<!--                                    <th>#</th>-->
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
<!--                                    <th>DOB</th>-->
                                    <th>Grade</th>
                                    <th>Class</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                                if (mysql_num_rows($student_res) > 0) {
                                    while ($row = mysql_fetch_assoc($student_res)) {
                                        if(trim($row['first_name']) || $row['middle_name'] || $row['last_name']) {
                                        ?>
                                        <div id="response-msg" class="alert alert-success" style="display:none;"></div>
                                        <tr id="<?php echo $row['id'];?>">
<!--                                            <td>
                                                <input type="checkbox" class="checkbox" value="<?php echo $row['id']; ?>"/>
                                            </td>-->
                                            <td><?php echo $row['first_name']; ?></td>
                                            <td><?php echo $row['middle_name']; ?></td>
                                            <td><?php echo $row['last_name']; ?></td>
<!--                                            <td><?php echo $row['dob']; ?></td>-->
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['class_name']; ?></td>
                                            <td><?php echo $row['created']; ?></td>
                                            <td align="center"><a href="demo_edit_student.php?sid=<?php echo $row['id'];?>"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a  class="delete-stud" data-sid="<?php echo $row['id']; ?>"  href="javascript:;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                                <?php
                                    }}
                                        } else {
                                            echo '<div class="clear"><p>There is no item found!</p></div>';
                                        }
                                        ?>
                            </table>
                            <?php } ?>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                    <input type="hidden" id="arr-user" name="arr-user" value=""/>
                </form>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
               $(document).ready(function(){
                   
                    $(".delete-stud").click(function(){
                        var stud_id = $(this).data('sid'); 
                        var flag = confirm('Are you sure you want to delete this student ?');
                          if(flag) {
                              $.ajax({
                              type:"post",
                              url:"delete_student.php",
                              data:"student_id="+stud_id+"&action=deletedemoStudent",
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