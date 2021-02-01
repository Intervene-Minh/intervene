<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$error = '';
$school_id = $_SESSION['school_id'];
function checkmydate($date) {
    $tempDate = explode('-', $date);
    // checkdate(month, day, year)
    return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
}

if ($_POST ) {
    if (!$_POST['class_name']) {
        $error = 'Please enter class name.' . '\r\n';
    }
    if ($_POST['grade'] <= 0 && !$_POST['class_id']) {
        $error .= 'Please choose grade level.';
    }
}
if ((isset($_POST['add_class']) && $_POST['class_name'] && $_POST['grade'] > 0) || $_POST['class_id'] > 0) {
    //print_r($_POST);die;

    $arr_student = array();
    if ($_POST['sudent_details'] == 'manual') {
        $all_stud = explode(PHP_EOL, $_POST['std_dtl']);
        $ctr = 0;
        foreach ($all_stud as $students) {
            //echo $student;
            $student = explode(" ", trim($students));
            $first_name = $student[0];
            if ($student[2]) {
                $middle_name = $student[1];
                $last_name = $student[2];
            } else {
                $middle_name = '';
                $last_name = $student[1];
            }
            if (trim($first_name) || trim($middle_name) || trim($last_name)) {
                $arr_student[$ctr]['first_name'] = trim($first_name);
                $arr_student[$ctr]['middle_name'] = trim($middle_name);
                $arr_student[$ctr]['last_name'] = trim($last_name);
                // $arr_student[$ctr]['dob'] = $DOB;
                $ctr = $ctr + 1;
            }
        }
    } else {
        $ctr = 0;
        $file_name = $_FILES['csv_upload']['name'];
        $cwd = getcwd();
        $uploads_dir = $cwd . '/uploads/student_csv';
        $tmp_name = $_FILES["csv_upload"]["tmp_name"];
        $name = $school_id . '_' . $user_id . '_' . basename($_FILES["csv_upload"]["name"]);
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
        $row = 1;

        if (($handle = fopen($uploads_dir . '/' . $name, "r")) !== FALSE) {
            $d = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // print_r($data); 
                if ($d >= 1) {
                    $DOB = trim($data[3]);
                    $first_name = trim($data[0]);
                    $middle_name = trim($data[1]);
                    $last_name = trim($data[2]);
                    if ($first_name || $middle_name || $last_name) {
                        $arr_student[$ctr]['first_name'] = $first_name;
                        $arr_student[$ctr]['middle_name'] = $middle_name;
                        $arr_student[$ctr]['last_name'] = $last_name;
                        //$arr_student[$ctr]['dob'] = $DOB;
                        $ctr = $ctr + 1;
                    }
                }$d = $d + 1;
            }
            fclose($handle);
        }
    }
    if (count($arr_student) > 0) {
       // print_r($_POST); die;
        $class_name = $_POST['class_name'];
        $grade = $_POST['grade'];
        if ($grade != '') {
            $query = mysql_query("SELECT name FROM terms WHERE id=" . $grade);
            $rows = mysql_num_rows($query);
            if ($rows == 1) {
                $row = mysql_fetch_assoc($query);
                $grade_name = $row['name'];
                //echo $grade_name ;die;
            }
        }
        if ($class_name != '') {
            if ($_POST['class_id'] > 0) {
                
                mysql_query('UPDATE demo_classes SET '
                        . 'class_name  = \'' . addslashes($class_name) . '\' WHERE '
                        . 'id = \'' . $_POST['class_id'] . '\' ');
                $inserted_class_id = $_POST['class_id'];
            } else {
                $class_id = mysql_query('INSERT INTO demo_classes SET '
                        . 'class_name  = \'' . addslashes($class_name) . '\' , '
                        . 'grade_level_id = \'' . $grade . '\' , '
                        . 'grade_level_name  = \'' . $grade_name . '\' , '
                        . 'teacher_id  = \'' . $user_id . '\' , '
                        . 'school_id  = \'' . $school_id . '\' , '
                        . 'created = \'' . $created . '\' ');
                $inserted_class_id = mysql_insert_id();
            }
            for ($i = 0; $i < count($arr_student); $i++) {
                mysql_query('INSERT INTO demo_students SET '
                        . 'class_id = \'' . $inserted_class_id . '\' , '
                        . 'first_name = \'' . $arr_student[$i]['first_name'] . '\', '
                        . 'middle_name = \'' . $arr_student[$i]['middle_name'] . '\', '
                        . 'last_name = \'' . $arr_student[$i]['last_name'] . '\' , '
//                        . 'dob = \'' . $arr_student[$i]['dob'] . '\', '
                        . 'username = \'' . strtoupper(getToken(5)) . '\', '
                        . 'teacher_id  = \'' . $user_id . '\' , '
                        . 'school_id  = \'' . $school_id . '\' , '
                        . 'grade_level_id = \'' . $grade . '\' , '
                        . 'password = \'' . base64_encode(rand(10, 99)) . '\' , '
                        . 'status = 1 , '
                        . 'created = \'' . $created . '\'  ');
            }
            if ($_POST['class_id'] > 0) {
                $error = 'Student(s) have been added successfully.';
            }else{
                $error = 'Class has been created.';
            }
        } else {
            $error = 'Please enter the class name.';
        }
    }

//    header("Location: manage_classes.php");
//    exit();
//print_r($all_stud); die;
}

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnpqrstuvwxyz";
    $codeAlphabet .= "123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }

    return $token;
}

$teacher_grade_res = mysql_query("
	SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$user_id} 
");
$t_grades = mysql_fetch_assoc($teacher_grade_res);
$teacher_grade = $t_grades['shared_terms'];
if ($_GET['class_id'] > 0) {
    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM demo_classes WHERE id = \'' . $_GET['class_id'] . '\' '));
    
    if ($edit_class['id'] != $_GET['class_id']) {
        $error = 'This is not valid class.';
    }
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
                        <h3><i class="fa fa-plus-circle"></i><?php echo $result ? 'Edit' : 'Add'; ?> Class</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">
                            <h4><?php echo $result ? 'Edit' : 'Add new'; ?> Class here:</h4>
                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Class Name</label>
                                    <input type="text" class="required textbox" name="class_name" value="<?php print $edit_class['class_name']; ?>">
                                </p>
                                <?php if (!$_GET['class_id']) { ?>
                                    <p>
                                        <label for="lesson_name">Choose Grade</label>
                                        <select name="grade" class="required textbox">
                                            <option value=""></option>
                                            <?php
                                            $grade_level_id = 0;
                                            if ($_GET['cat'] > 0) {
                                                $grade_level_id = $_GET['cat'];
                                            }
                                            $folders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$_SESSION['data_dash']}) AND `active` = 1");
                                            if (mysql_num_rows($folders) > 0) {
                                                while ($folder = mysql_fetch_assoc($folders)) {
                                                    $selected = ($folder['id'] == $default['category']) ? ' selected="selected"' : '';
                                                    echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </p>
                                <?php } ?>
                                <p>
                                    <input type="radio" name="sudent_details" value="manual" checked="" /> Add Student Manually<br>
                                    <input type="radio" name="sudent_details" value="csv" /> Upload CSV of Student Roster
                                </p>

                                <div id="textarea" style="display: block">
                                    Ex. : Firstname Middlename Lastname <br/><br/>
                                    <textarea class="form-control" name="std_dtl" placeholder="Robert Sam Smith" rows="20"></textarea> 

                                </div>
                                <div id="csv-upload" style="display: none">
                                    <input type="file" name="csv_upload" />
                                </div>

                                </p
                            </div>
                            <p>
                                <?php if ($edit_class['id'] > 0) { ?>
                                    <input type="hidden" name="class_id" id="class_id" value="<?php echo $edit_class['id']; ?>" />
                                    <input type="hidden" name="grade" value="<?php echo $edit_class['grade_level_id']; ?>" />
                                <?php } ?>
                                <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" value="Submit" />
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

    $(function () {
        $('input[name="sudent_details"]').on('click', function () {
            if ($(this).val() == 'manual') {
                $('#textarea').show();
            } else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            } else {
                $('#csv-upload').hide();
            }
        });
    });

</script>

<?php include("footer.php"); ?>
