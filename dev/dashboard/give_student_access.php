<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
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
    $res = mysql_query('SELECT first_name, middle_name, last_name, username, password, dob FROM students WHERE '
            . 'class_id = \''.$class_id.'\' AND '
            . 'teacher_id = \''.$user_id.'\' AND '
            . 'school_id = \''.$school_id.'\' ');
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
            $html .= '<td>Go to: http://www.intervene.io Click: Login at top right <br />'
                    . 'Name : '.$student['first_name'].' '.$student['middle_name'].' '.$student['last_name'];
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
                                                echo '<option value="' . $result['id'] . '"' . $selected . '> '.$result['grade_level_name'] .': ' . $result['class_name'] . '</option>';
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
