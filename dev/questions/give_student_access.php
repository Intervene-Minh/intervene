<?php

 
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
//echo 'hiifd f'; die; 
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}

if($_POST) {
    $class_id = $_POST['class'];


$stuArr=[];
$q = mysql_query("SELECT student_id FROM `students_x_class`  WHERE `class_id` = $class_id");
while($r = mysql_fetch_assoc($q)){

$stuArr[$r['student_id']]=$r['student_id'];
}

$stUIdd=implode(',', $stuArr); 
$str="SELECT first_name, last_name, username, password FROM students WHERE id IN ($stUIdd)";

 $res = mysql_query($str);
 if(mysql_num_rows($res) >0) {
         set_time_limit(600);
    ini_set("memory_limit","256M");
    //
    $timeo_start = microtime(true);
    //==============================================================
    include("mpdf/mpdf.php");
    $class_result = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $class_id . '\' '));

$html = '<head>

</head>
<body><table width="100%" border=1><tr><td align="center" colspan="3"><h1>Class: '.$class_result['class_name'].' <br/> Student List</h1></td></tr>
';
    $ctr = 0;
        while($student = mysql_fetch_assoc($res)) {
            
            if($ctr%3==0) {
                $html .= '<tr>';
            }
            $html .= '<td>Go to: intervene.io <br/> Click: Login at top right <br />'
                    . 'Name : '.$student['first_name'].' '.$student['last_name'];
//            $html .= '<br/>Birthday: '.$student['dob'];
            $html .= '<br/>Username: '.$student['username'];
            $html .= '<br/>Password: '.base64_decode($student['password']).'</td>';
            $ctr = $ctr + 1;
            if($ctr%3==0) {
                $html .= '</tr>';
            }
            //$html .= '</tr>';
        }
        $html .= '</table></body>';
        $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($html);
    ob_clean(); 
    $mpdf->Output('student_list.pdf','D'); 
    exit;
    }else{
        $error = 'There are no students!';
    }
}


$res = mysql_query('SELECT * FROM classes WHERE teacher_id = \'' . $user_id . '\' ');
function _gradeName($id){

      $str="SELECT `id`,`name` FROM `terms` WHERE id=$id";
      $qr=mysql_query($str);
      $res = mysql_fetch_assoc($qr);
      return $res['name']; 
                           
}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Student Access</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Class</label>
                                    <select name="class" class="required textbox" >
                                        <option value="">Choose Class</option>
                                        <?php
                                        if (mysql_num_rows($res) > 0) {
                                            while ($result = mysql_fetch_assoc($res)) {
                                                $selected = ($result['id'] == $_GET['cid']) ? ' selected="selected"' : '';
                                                echo '<option value="' . $result['id'] . '"' . $selected . '> '._gradeName($result['grade_level_id']) .': ' . $result['class_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <?php if(mysql_num_rows($asses_res)>0) { ?>
                                    <label for="lesson_name">Choose Assesments</label>
                                    <?php while ($assesments = mysql_fetch_assoc($asses_res)) { ?>
                                    <br />
                                    <input type="radio" name="assesment" value="<?php print $assesments['id'] ?>" /> <?php print $assesments['assesment_name'] ?>
                                    <?php } } ?>
                                </p>
                            </div>
                            <p>
                                
                                <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" style="width: 150px;"  value="Print Student Login" />
                                
                            </p>
                            
                            <p>Your student information will open in a new tab as a
PDF document, where you can save or print.
                            </p>
                            <p>
We've put the information in boxes so you can print
and cut to give each student easy access to their
login information when they need it.</p>
<p>
If you're having trouble opening the file, please
contact us at <a href="mailto: learn@p2g.org">learn@p2g.org</a></p>
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
            }
            else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            }
            else {
                $('#csv-upload').hide();
            }
        });
    });

</script>

<?php include("footer.php"); ?>
