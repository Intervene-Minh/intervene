<?php
/**
@ Create teacher by admin 
**/

$page_name='Create teacher';


echo $page_name; 

require_once ('translate/vendor/autoload.php');
 use \Statickidz\GoogleTranslate;
$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
include("header.php");




///////////////////////////

if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}
/////////////////////////////


/* Process Submittion */
if( isset($_POST['passage_submit']) ) {


 echo  '<pre>';
 print_r($_POST); die; 
 
/////////////////////////////
	$passage_id		= (isset($_POST['passage_id']) && is_numeric($_POST['passage_id']) && $_POST['passage_id'] > 0) ? $_POST['passage_id'] : 0;
	$passage_title				= $_POST['passage_title'];
	$passage_title_spanish		= $_POST['passage_title_spanish'];


	$passage_content			= addslashes($_POST['passage_content']);

	//$passage_content_spanish	= mysql_real_escape_string($_POST['passage_content_spanish']);

	$passage_content_spanish	=addslashes($_POST['passage_content_spanish']);
	##############Convert Actual Question: to spanish##########################
	#passage_content_spanish if Tick auto translate.
	$question_question_spanish=$passage_content_spanish; // Orginal
	
	if(isset($_POST['translate'])){
		unset($question_question_spanish);
		$source = 'en';
		$target = 'es';
		$array = preg_split('/(<img[^>]+\>)/i', $_POST['passage_content'], -1, PREG_SPLIT_DELIM_CAPTURE);

		$trans = new GoogleTranslate();
		$question_question_spanish = '';
		foreach ($array  as $value){

			if (strpos($value, '<img') !== false) {
			    $question_question_spanish .= $value;
			}else{
				$question_question_spanish .= $trans->translate($source, $target, $value);
			}
		}
		 

	 
		////////////////
	 $question_question_spanish = $trans->translate($source, $target, $question_question_spanish);
	  //echo ($question_question_spanish);  echo 'Spanish===<br/>'; //die ;



      $question_question_spanish=_remove_basic_tags($question_question_spanish); // h1,h2 etc

	   } //# translate



	###########################################
	// Save into database
	   $send_msg='';
	if( $passage_id == 0 ) {		# Insert
		$alert = 'add new';
		$query = "INSERT INTO `passages` (`title`,`title_spanish`, `content`,`content_spanish`)
		VALUES ('{$passage_title}', '{$passage_title_spanish}' , '{$passage_content}' , '{$question_question_spanish}')";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
		// Get inserted id
		$getId = mysql_insert_id();

		$send_msg='Passage added successfully! ';
	} else {						# Update
		$alert = 'edit';
		$send_msg='Passage saved successfully! ';
		// Update passage
		$query = "UPDATE `passages` SET
			`title` = '{$passage_title}',
			`title_spanish` = '{$passage_title_spanish}',
			`content` = '{$passage_content}',
			`content_spanish` = '{$question_question_spanish}'
		WHERE `id` = {$passage_id} LIMIT 1";
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
	}
     ///////Display////////    

	$error	= $return ? $send_msg : 'Can not ' . $alert . ' passage. Please try again later!';


}






// Init @array $default/////////////////////////////
$default = array('id' => 0, 'title' => '', 'content' => '');

// Retrive question data
$passageId = (isset($_GET['passage']) && is_numeric($_GET['passage']) && $_GET['passage'] > 0) ? $_GET['passage'] : 0;
$result = mysql_fetch_assoc( mysql_query("SELECT * FROM `passages` WHERE `id` = " . $passageId) );		# Return @boolean false if not found
// print_r($result);
if( $result ) {
	$default['id'] = $result['id'];
	$default['title'] = $result['title'];
	$default['title_spanish'] = $result['title_spanish'];
	$default['content'] = $result['content'];
	$default['content_spanish'] = $result['content_spanish'];
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
						<h3><i class="fa fa-plus-circle"></i><?php echo $result?'Edit':'Add';?> Passage</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
							<h4><?php echo $result?'Edit':'Add new';?> passage here:</h4>
							<div class="add_question_wrap clear fullwidth">
								<p>
									<label for="passage_title">Title of Passage:</label>
									<input type="text" name="passage_title" id="passage_title" class="required textbox" value="<?php echo $default['title']; ?>" />
								</p>
								<p>
									<label for="passage_title_spanish">Title of Passage in Spanish:</label>
									<input type="text" name="passage_title_spanish" id="passage_title_spanish" class="required textbox" value="<?php echo $default['title_spanish']; ?>" />
								</p>
								<p>
									<label for="passage_content">Actual Question:</label>
									<textarea name="passage_content" id="passage_content" class="textbox" rows="20"><?php echo $default['content']; ?></textarea>
									<!-- Auto convert save time -->
									<br/>
									<input id="trans" type="checkbox" name="translate" value="0" /> Auto translate <span class="text-danger">"Actual Question"</span> from English to Spanish
								

								</p>




								<p>
									<label for="passage_content_spanish">Actual Question in Spanish:</label>
									<textarea name="passage_content_spanish" 
									id="passage_content_spanish" class="textbox" rows="20"><?php echo  stripslashes($default['content_spanish'])  ?></textarea>
								</p>
							</div>
							<p>
								<input type="hidden" name="passage_id" id="passage_id" value="<?php echo $default['id']; ?>" />
								<input type="submit" name="passage_submit" id="passage_submit" class="form_button submit_button" value="Submit" />
								<input type="reset" name="passage_sreset" id="passage_sreset" class="form_button reset_button" value="Reset" />
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
</script>

<?php include("footer.php"); ?>