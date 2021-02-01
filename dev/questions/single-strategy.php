<?php
$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");

if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}
$getId = 0;
/* Process Submittion */
if( isset($_POST['lesson_submit']) ) {
	$lesson_id		= (isset($_POST['lesson_id']) && is_numeric($_POST['lesson_id']) && $_POST['lesson_id'] > 0) ? $_POST['lesson_id'] : 0;
	$lesson_name	= mysql_real_escape_string($_POST['lesson_name']);
	$lesson_desc	= mysql_real_escape_string($_POST['lesson_desc']);
	$distrator_id	= $_POST['distrator_id'];
	$path_file		= '';

	if(!empty($_FILES['lesson_file']['name'])) {
		include("inc/upload-lesson.php");
		$path = 'uploads/strategies/';
		$check = upload_lesson($_FILES['lesson_file'],$path);
		if($check!=''){
			$path_file = $check;
		}
	} else {}

	// Save into database
	if( $lesson_id == 0 ) {		# Insert
		$alert = 'add new';
		$query = "INSERT INTO `lessons` (`name`,`distrator_id`, `path_file`,`description`)
		VALUES ('{$lesson_name}', '{$distrator_id}' , '{$path_file}' , '{$lesson_desc}')";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
		// Get inserted id
		$getId = mysql_insert_id();
	} else {						# Update
		$alert = 'edit';
		// Update passage
		$query = ($path_file == '')
			? "UPDATE `lessons` SET
				`name` = '{$lesson_name}',
				`distrator_id` = '{$distrator_id}',
				`description` = '{$lesson_desc}'
			WHERE `id` = {$lesson_id} LIMIT 1"
			: "UPDATE `lessons` SET
				`name` = '{$lesson_name}',
				`distrator_id` = '{$distrator_id}',
				`path_file` = '{$path_file}',
				`description` = '{$lesson_desc}'
			WHERE `id` = {$lesson_id} LIMIT 1";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
		$getId = $lesson_id;
	}
	$error	= $return ? 'Successfully!' : 'Can not ' . $alert . ' lesson. Please try again later!';
}

// Init @array $default
$default = array('id' => 0, 'name' => '', 'distrator_id' => '','path_file'=>'','description'=>'');

// Retrive question data
$lesson_id = (isset($_GET['strategy_id']) && is_numeric($_GET['strategy_id']) && $_GET['strategy_id'] > 0) ? $_GET['strategy_id'] : 0;
if($getId>0)$lesson_id = $getId;
$result = mysql_fetch_assoc( mysql_query("SELECT * FROM `lessons` WHERE `id` = " . $lesson_id) );		# Return @boolean false if not found
// print_r($result);
if( $result ) {
	$default['id'] = $result['id'];
	$default['name'] = $result['name'];
	$default['distrator_id'] = $result['distrator_id'];
	$default['path_file'] = $result['path_file'];
	$default['description'] = $result['description'];
}
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" />

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i><?php echo $result?'Edit':'Add';?> Distractor Definition</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
							<h4><?php echo $result?'Edit':'Add new';?> distractor definition here:</h4>
							<div class="add_question_wrap clear fullwidth">
								<p>
									<label for="lesson_name">Distractor Definition Name:</label>
									<input type="text" name="lesson_name" id="lesson_name" class="required textbox" value="<?php echo $default['name']; ?>" />
								</p>
								<p>
									<label for="distrator_id">Distractor:</label>
									<?php
									$distrators = mysql_query("SELECT * FROM `distrators` WHERE id NOT IN  (SELECT distrator_id FROM lessons)");
									if( mysql_num_rows($distrators) > 0 ) {
									?>
									<select name="distrator_id" id="distrator_id" class="required textbox">
										<option value="0"></option>
										<?php
										while( $distrator = mysql_fetch_assoc($distrators) ) {
											echo '<option value="' . $distrator['id'] . '" '.($default['distrator_id']==$distrator['id']?'selected':'').'>' . $distrator['name'] . '</option>';
										}
										?>
									</select>
									<?php } ?>
								</p>
								<p>
									<label for="lesson_file">Attach file:
										<?php if($default['path_file']!=''){
											echo ' <i class="fa fa-file-text-o" aria-hidden="true"></i>';
											echo ' <a href="'.$default['path_file'].'" target="_blank">Open</a>';
											echo ' <a href="'.$default['path_file'].'" download>Download</a>';
										}?>
									</label>
									<input type="file" name="lesson_file" id="lesson_file" class="required textbox" accept="application/pdf"/>
									<span>*Only accept file .pdf</span>
								</p>
								
								<p>
									<label for="lesson_desc">Description:</label>
									<textarea name="lesson_desc" id="lesson_desc" class="textbox" rows="5"><?php echo $default['description'];?></textarea>
								</p>
							</div>
							<p>
								<input type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $default['id']; ?>" />
								<input type="submit" name="lesson_submit" id="lesson_submit" class="form_button submit_button" value="Submit" />
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
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
            
    $(document).ready(function () {
        $('#distrator_id').chosen();
        $('#distrator_id').chosen();
    });
</script>

<?php include("footer.php"); ?>