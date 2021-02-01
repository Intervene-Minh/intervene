<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
//$created = date('Y-m-d H:i:s');

//$user_id = $_SESSION['login_id'];
if (isset($_POST['edit_student']) ) {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $passowrd = base64_encode($_POST['password']);
//    unset($_POST['edit_student']);
//    unset($_POST['password']);
    //print_r($_POST);
    mysql_query("UPDATE demo_students SET first_name = \"" . $first_name . "\", middle_name = \"" . $middle_name . "\", last_name = \"" . $last_name . "\", username = \"" . $username . "\", password = \"" . $passowrd . "\" WHERE `id` = {$_GET['sid']}  ");
    $error = 'Details has been updated successfully.';
}


$query = mysql_query("SELECT first_name,middle_name,last_name,username,password FROM demo_students WHERE id=" . $_GET['sid']);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("demo_sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">	
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Edit Student</h3>
                    </div>
                    <div class="ct_display clear">
                        <form name="form_class" id="edit_student" method="post" action="">
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="first_name">First Name</label>
                                    <input type="text" required="required" class="required" name="first_name" value="<?php echo $row['first_name'];?>">
                                </p>
                                <p>
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" value="<?php echo $row['middle_name'];?>">
                                </p>
                                <p>
                                    <label for="last_name">Last Name</label>
                                    <input type="text"  name="last_name" value="<?php echo $row['last_name'];?>">
                                </p>
                                <p>
                                    <label for="lesson_name">Username</label>
                                    <input type="text" required="required" class="required" name="username" value="<?php echo $row['username'];?>">
                                </p>
                                <p>
                                    <label for="lesson_name">Password</label>
                                    <input type="text" required="required" class="required" name="password" value="<?php echo base64_decode($row['password']);?>">
                                </p>
                               
                                </div>

                                </p
                            </div>
                            <p>
                                <input type="submit" name="edit_student"  class="form_button submit_button" value="Submit" />
                                <input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
                            </p>
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
    
</script>

<?php include("footer.php"); ?>
