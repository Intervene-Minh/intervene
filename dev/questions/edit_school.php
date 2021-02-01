<?php
include("header.php");

$login_role = $_SESSION['login_role'];
if ($login_role != 0 || !isGlobalAdmin()) {
    header("location: index.php");
}

$error = '';
$id = $_SESSION['login_id'];

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
<?php
if($_POST) {
if($_POST['school_id'] > 0) {

    mysql_query('UPDATE master_schools SET '
            . 'district_id = \''.$_POST['district'].'\', '
            . 'school_name = \''.$_POST['school_name'].'\'  WHERE '
            . 'id = \''.$_POST['school_id'].'\' ');
    $error =  'Details has been saved successfully.';
}else{
  mysql_query('INSERT INTO master_schools SET '
            . 'district_id = \''.$_POST['district'].'\' ,  '
            . 'school_name = \''.$_POST['school_name'].'\' , '
          . 'state_id = 1  ');  
  $error =  'Details has been saved successfully.';
}
}

$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
$query =  mysql_query("SELECT * FROM `master_schools` WHERE  id =  ".$_GET['sid']);
$school_details = mysql_fetch_assoc($query);

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
						<h3><?php echo $_GET['sid']?'Edit':'Add new';?> Schools</h3>
					</div>		<!-- /.ct_heading -->
                                        <div class="ct_display clear">
                                            <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
							<h4><?php echo $_GET['sid']?'Edit':'Add new';?> Schools:</h4>
							<div class="add_question_wrap clear fullwidth">
								<p>
									<label for="lesson_name">Choose District:</label>
                                                                        <select name="district" id="district">
                                                                            <?php while($district = mysql_fetch_assoc($district_qry)) { ?>
                                                                            <option <?php if($school_details['district_id'] == $district['id']) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>
                                                                              
                                                                            <?php } ?>
                                                                        </select>
								</p>
                                                                <p>
									<label for="lesson_name">School Name:</label>
                                                                        <input type="text" required="" name="school_name" id="school_name" class="required textbox" value="<?php echo $school_details['school_name']; ?>" />
								</p>
                                                                <p>
                                                                    <?php if($school_details['id'] > 0) { ?>
                                                                    <input type="hidden" name="school_id" id="school_id" value="<?php echo $school_details['id']; ?>" />
                                                                    <?php } ?>
								<input type="submit" name="lesson_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
								<input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
							</p>
                                                        </div>
                                            </form>
                                        </div>
                                </div>
            </div>
        </div>
    </div>		<!-- /#header -->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#district').chosen();
	});
	</script>
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
    <?php include("footer.php"); ?>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" />

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>
