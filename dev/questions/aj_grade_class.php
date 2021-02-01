<?php
/////////  assign-students :: to

include('inc/connection.php'); 
session_start();
	ob_start();
$action = $_POST['action'];

//  Only Login Teacher Classs

// gradid
if(isset($_POST['gradid'])){
    $_POST['gradid']= intval($_POST['gradid']);
  //  echo $_POST['gradid']."--Grade id ";
}

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
$error = '';
 $today = date("Y-m-d H:i:s");   
 // lesson_submit
//var_dump($_POST); die;
//$error="Pelse enter value.. ";
// Add Students 




//XXXXXXXXXXXXXXX















///////////////////
$teacher_grade_res = mysql_query("
	SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
	FROM `techer_permissions`
	WHERE teacher_id = {$user_id} 
");
$t_grades = mysql_fetch_assoc($teacher_grade_res);
$teacher_grade = $t_grades['shared_terms'];
if ($_GET['class_id'] > 0) {
    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
    
    if ($edit_class['id'] != $_GET['class_id']) {
        $error = 'This is not valid class.';
    }
}

?>

    
                                        <option value="">Choose Class</option>
                                        <?php
                                     $res = mysql_query('SELECT class.*, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');   
                                        
                                        if (mysql_num_rows($res) > 0) {
                                            while ($result = mysql_fetch_assoc($res)) {
                                              if($result['grade_level_id']!=$_POST['gradid'])continue;  
                                                
                                                
                                 $selected = (isset($_GET['cl'])&&$result['id'] == $_GET['cl']) ? ' selected="selected"' : '';
                                                echo '<option value="' . $result['id'] . '"' . $selected . '>'.$result['grade_name'].' : ' . $result['class_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                  


