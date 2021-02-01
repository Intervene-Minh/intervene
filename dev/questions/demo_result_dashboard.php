<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

@include("header.php");
$created = date('Y-m-d H:i:s');
ini_set('display_errors', 0);

$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$school_id = $_SESSION['school_id'];
if ($_GET['cid']) {
    $class_res = mysql_fetch_assoc(mysql_query('SELECT * FROM demo_classes WHERE teacher_id = \'' . $user_id . '\' AND id =  ' . $_GET['cid']));
    if ($class_res['grade_level_id'] > 0) {

        // $asses_res = mysql_query('SELECT id, assesment_name FROM assessments WHERE grade_id =\'' . $class_res['grade_level_id'] . '\' ');

        $get_class=$class_res['grade_level_id'];
        $sql_asses_res=" SELECT DISTINCT(ass.id), ass.assesment_name FROM assessments ass WHERE ass.grade_id =".$get_class;
         $sql_asses_res.=" AND ass.created_by=0 ";//principal Created:NotAllowed to demo Teacher
         $sql_asses_res.=" AND ass.is_demo='yes' ";
        /// Result//
        $asses_res=mysql_query($sql_asses_res);


    }
}

$res = mysql_query('SELECT class.*, t.name as grade_name FROM demo_classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');
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
                        <h3><i class="fa fa-plus-circle"></i>Data Dash</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="get" action="demo_data_dash.php" enctype="multipart/form-data">

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Class</label>
                                    <select name="cid" class="required textbox" onchange="open_asses('<?php print $base_url . 'demo_result_dashboard.php?cid=' ?>', $(this).val());">
                                        <option value="">Choose Class</option>
<?php
if (mysql_num_rows($res) > 0) {
    while ($result = mysql_fetch_assoc($res)) {
        $selected = ($result['id'] == $_GET['cid']) ? ' selected="selected"' : '';
        echo '<option value="' . $result['id'] . '"' . $selected . '>' . $result['grade_name'] . ' : ' . $result['class_name'] . '</option>';
//                                               
    }
}

?>
                                    </select>
                                </p>
                                <p>
<?php if (mysql_num_rows($asses_res) > 0) { ?>
                                        <label for="lesson_name">Choose Assesments</label>
                                        <?php while ($assesments = mysql_fetch_assoc($asses_res)) { ?>
                                            <br />
                                            <input type="radio" name="assesment" value="<?php print $assesments['id'] ?>" <?php if ($assesments['id'] == $_GET['assesment']) { ?> checked="" <?php } ?> /> <?php print $assesments['assesment_name'] ?>
                                        <?php
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <p>

                                <input type="submit" name="add_class" id="lesson_submit" class="form_button submit_button" style="width: 100px;" value="Get Data Dash" />

                            </p>
                        </form>
                        <?php     ?>
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
