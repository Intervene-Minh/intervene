<?php
$error	= '';
/* Process Submittion */
if( isset($_POST['package_submit']) ) {
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	// die();
	$package_id		= (isset($_POST['package_id']) && is_numeric($_POST['package_id']) && $_POST['package_id'] > 0) ? $_POST['package_id'] : 0;
	$package_name				= $_POST['package_name'];
	$package_limited			= $_POST['package_limited'];
	$package_price				= $_POST['package_price'];
	$package_description		= mysql_real_escape_string($_POST['package_description']);
	
	// Save into database
	if( $package_id == 0 ) {		# Insert
		$alert = 'add new';
		$query = "INSERT INTO `packages` (`name`,`limited`,`price`,`description`)
		VALUES ('{$package_name}', '{$package_limited}' , '{$package_price}' , '{$package_description}')";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
		
		//redirect to membership-setting
		if($return){
			header('Location: membership-setting.php');
			exit;
		}
		
		// Get inserted id
		$package_id = mysql_insert_id();
	} else {						# Update
		$alert = 'edit';
		// Update passage
		$query = "UPDATE `packages` SET
			`name` = '{$package_name}',
			`limited` = '{$package_limited}',
			`price` = '{$package_price}',
			`description` = '{$package_description}'
		WHERE `id` = {$package_id} LIMIT 1";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
	}
	$error	= $return ? 'Successfully!' : 'Can not ' . $alert . ' passage. Please try again later!';
}

// Init @array $default
$default = array('id' => 0, 'name' => '', 'limited' => '', 'price' => '', 'description' => '');

// Retrive question data
$packageId = (isset($_GET['package']) && is_numeric($_GET['package']) && $_GET['package'] > 0) ? $_GET['package'] : 0;

if(isset($package_id) && is_numeric($package_id) && $package_id>0)$packageId = $package_id;


$result = mysql_fetch_assoc( mysql_query("SELECT * FROM `packages` WHERE `id` = " . $packageId) );		# Return @boolean false if not found
// print_r($result);
if( $result ) {
	$default['id'] = $result['id'];
	$default['name'] = $result['name'];
	$default['limited'] = $result['limited'];
	$default['price'] = $result['price'];
	$default['description'] = $result['description'];
}
?>

<div id="single_question" class="content_wrap">
	<div class="ct_heading clear">
		<h3><i class="fa fa-plus-circle"></i><?php echo $result?'Edit':'Add';?> Package Premium</h3>
	</div>		<!-- /.ct_heading -->
	<div class="ct_display clear">
		<form id="form_package" method="post" action="" enctype="multipart/form-data">
			<h4><?php echo $result?'Edit':'Add new';?> package here:</h4>
			<div class="add_question_wrap clear fullwidth">
				<p>
					<label for="package_name">Name of package:</label>
					<input type="text" name="package_name" id="package_name" class="required textbox" value="<?php echo $default['name']; ?>" />
				</p>
				<p>
					<label for="package_limited">Limited questions to print:</label>
					<input type="number" name="package_limited" id="package_limited" class="required textbox" value="<?php echo $default['limited']; ?>" />
					<em>"Set value to 0 for unlimited questions"</em>
				</p>
				<div class="form-group has-feedback">
				  <label for="package_price">Price of package:</label>
				  <div class="input-group">
					  <span class="input-group-addon">$</span>
					  <input type="number" step="0.01" name="package_price" id="package_price" class="required form-control" value="<?php echo $default['price']; ?>" />

					</div>
				</div>
				<p>
					<label for="package_description">Description of package:</label>
					<textarea name="package_description" id="package_description" class="textbox" rows="20"><?php echo $default['description']; ?></textarea>
				</p>
			</div>
			<p>
				<input type="hidden" name="package_id" id="package_id" value="<?php echo $default['id']; ?>" />
				<input type="submit" name="package_submit" id="package_submit" class="form_button submit_button" value="Submit" />
				<input type="reset" name="package_sreset" id="package_sreset" class="form_button reset_button" value="Reset" />
			</p>
		</form>
		<div class="clearnone">&nbsp;</div>
	</div>		<!-- /.ct_display -->
</div>

<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
	jQuery(document).ready(function($){
		$('#form_package').on('submit',function(){
			if(!premium_option_valid())return false;
		});
		
		function premium_option_valid(){
			var check = true;
			$('#form_package input.required:visible').each(function(){
				if($(this).val()==''){
					$(this).focus();
					check = false;
					return false;
				}
			});
			return check;
		}
	});
</script>