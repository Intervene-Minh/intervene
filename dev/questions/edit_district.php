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
if($_POST['district_id'] > 0) {

    mysql_query('UPDATE loc_district SET '
            . 'district_name = \''.$_POST['district'].'\' '
            . ' WHERE '
            . 'id = \''.$_POST['district_id'].'\' ');
    $error =  'Details has been saved successfully.';
}else{
  mysql_query('INSERT INTO loc_district SET '
            . 'district_name = \''.$_POST['district'].'\' ,  '
            . 'state_id = 1 ');  
  $error =  'Details has been saved successfully.';
}
}

$query =  mysql_query("SELECT * FROM `loc_district` WHERE  id =  ".$_GET['id']);
$district = mysql_fetch_assoc($query);

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
						<h3><?php echo $_GET['id']?'Edit':'Add new';?> District</h3>
					</div>		<!-- /.ct_heading -->
                                        <div class="ct_display clear">
                                            <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
							<h4><?php echo $_GET['id']?'Edit':'Add new';?> District:</h4>
							<div class="add_question_wrap clear fullwidth">
								<p>
									<label for="lesson_name">District:</label>
                                                                        <input type="text" name="district" value="<?php print $district['district_name'] ?>" />
								</p>
                                                                <p>
                                                                    <?php if($district['id'] > 0) { ?>
                                                                    <input type="hidden" name="district_id" id="district_id" value="<?php echo $district['id']; ?>" />
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
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
    <?php include("footer.php"); ?>
<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>