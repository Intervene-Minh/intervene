<?php
/***
@ Option Image Edit option 
@ Question Option change
@error.
@ // include("header_updated.php");
@ Convert English Text to Spanish Format. 
@

**/

echo '==========language_convert';

#########################
require_once ('translate/vendor/autoload.php');
 use \Statickidz\GoogleTranslate;


///////////////////////////////////////

$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
include("header.php");

if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}





// Retrive question data
$quesId = (isset($_GET['question']) && is_numeric($_GET['question']) && $_GET['question'] > 0) ? $_GET['question'] : 0;

//if not admin but want to edit return index
require_once('inc/check-role.php');
$role = checkRole();
if($quesId>0 && $role!=0){
	header('Location: index.php');
	exit;
}


/* Process Submittion */
if(isset($_POST['question_submit']) ) {
    
    // echo '<pre>';
    //     print_r($_POST); die;
/////////////////////////////////////////

	
	$question_id		= (isset($_POST['question_id']) && is_numeric($_POST['question_id']) && $_POST['question_id'] > 0) ? $_POST['question_id'] : 0;
	$question_public		= 0;
	if(isset($_POST['question_public']) && $_POST['question_public']=='on'){
		$question_public		= 1;
	}
	$question_name		= $_POST['question_name'];
	$question_name_spanish		= $_POST['question_name_spanish'];
	$question_question	= mysql_real_escape_string($_POST['question_question']);
	$question_question_spanish	= mysql_real_escape_string($_POST['question_question_spanish']);
	$question_category	= $_POST['question_category'];
	$question_object	= array_unique($_POST['suggest']);
	$question_type		= $_POST['question_type'];


	 //if($_POST['translate'] == 1){
     if(isset($_POST['translate'])){
            
		$source = 'en';
		$target = 'es';
		$array = preg_split('/(<img[^>]+\>)/i', $_POST['question_question'], -1, PREG_SPLIT_DELIM_CAPTURE);

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
		

		//////////////////



		
		// $question_question_spanish = $trans->translate($source, $target, $question_question_spanish);

		$question_question_spanish = str_replace('& nbsp;', '&nbsp;', $question_question_spanish);
		$question_question_spanish = str_replace('/ images / ', '/images/', $question_question_spanish);
		$question_question_spanish = str_replace('Ol', 'ol', $question_question_spanish);

		$question_question_spanish = str_replace('</ Ol>', '</ol>', $question_question_spanish);
		$question_question_spanish = str_replace('<Li>', '<li>', $question_question_spanish);

		$question_question_spanish = str_replace('<H1>', '<h1>', $question_question_spanish);
		$question_question_spanish = str_replace('<H2>', '<h2>', $question_question_spanish);
		$question_question_spanish = str_replace('<H3>', '<h3>', $question_question_spanish);
		$question_question_spanish = str_replace('<H4>', '<h4>', $question_question_spanish);
		$question_question_spanish = str_replace('<H5>', '<h5>', $question_question_spanish);
		$question_question_spanish = str_replace('<H6>', '<h6>', $question_question_spanish);

		$question_question_spanish = str_replace('</ h1>', '</h1>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h2>', '</h2>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h3>', '</h3>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h4>', '</h4>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h5>', '</h5>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h6>', '</h6>', $question_question_spanish);

		$question_question_spanish = str_replace('<P>', '<p>', $question_question_spanish);
		$question_question_spanish = str_replace('</ p>', '</p>', $question_question_spanish);

		$question_question_spanish = str_replace('<Table>', '<table>', $question_question_spanish);
		$question_question_spanish = str_replace('</ P>', '</p>', $question_question_spanish);

		$question_question_spanish = str_replace('<Tbody>', '<tbody>', $question_question_spanish);

		$question_question_spanish = str_replace('<Tr>', '<tr>', $question_question_spanish);

		$question_question_spanish = str_replace('<Td>', '<td>', $question_question_spanish);

		$question_question_spanish = str_replace('</ td>', '</td>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Tr>', '</tr>', $question_question_spanish);
		

		$question_question_spanish = str_replace('</ Tr>', '</tr>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Tbody>', '</tbody>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Table>', '</table>', $question_question_spanish);

		$question_question_spanish = str_replace('<Ul>', '<ul>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Li>', '</li>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Ul>', '</ul>', $question_question_spanish);

		$question_question_spanish = str_replace('<Blockquote>', '<blockquote>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Blockquote>', '</blockquote>', $question_question_spanish);

		$question_question_spanish = str_replace('<P>', '<p>', $question_question_spanish);

		$question_question_spanish = str_replace('</ strong>', '</strong>', $question_question_spanish);

		$question_question_spanish = str_replace('</ em>', '</em>', $question_question_spanish);

		$question_question_spanish = str_replace('</ p>', '</p>', $question_question_spanish);
		
		$question_question_spanish = str_replace('</ Li>', '</li>', $question_question_spanish);

		$question_question_spanish = str_replace('</ span>', '</span>', $question_question_spanish);

		$question_question_spanish = str_replace('> </', '></', $question_question_spanish);

		$question_question_spanish = str_replace('>  </', '></', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / png;', '<img src ="data:image/png;', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / jpg;', '<img src ="data:image/jpg;', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / jpge;', '<img src ="data:image/jpge;', $question_question_spanish);

	}
	///
	  //echo   'Converted:=='. $question_question_spanish; die; 

	///////////////////////////////       
	
	
	$question_passage	= $_POST['question_passage'];
	if($_POST['question_passage']=="")$question_passage=0;
  
     // echo 'T==';  var_export($question_type); die; 
	
	// Retrive answer data///////////////////
	if($question_type == 1||$question_type=='1'){
		
		
          
          
		$correct			= $_POST['correct'];
		$answers			= $_POST['answers'];
		$answers_spanish	= $_POST['answers_spanish'];
		$explains			= $_POST['explains'];
		$hidden_img			= $_POST['hidden_img'];
		$width_img			= $_POST['hidden_wid'];
		$height_img			= $_POST['hidden_hig'];
		$files[]			= $_FILES['images_0'];
		$files[]			= $_FILES['images_1'];
		$files[]			= $_FILES['images_2'];
		$files[]			= $_FILES['images_3'];
		
		$hidden_spanish_img			= $_POST['hidden_spanish_img'];
		$width_spanish_img			= $_POST['hidden_spanish_wid'];
		$height_spanish_img			= $_POST['hidden_spanish_hig'];
		$files_spanish[]			= $_FILES['images_spanish_0'];
		$files_spanish[]			= $_FILES['images_spanish_1'];
		$files_spanish[]			= $_FILES['images_spanish_2'];
		$files_spanish[]			= $_FILES['images_spanish_3'];
	
		// Init @array $answer_data
		$answer_data = array();
		$answer_data_spanish = array();
		
		// Path to folder that store upload files
		$root_folder = "uploads/";
		$year_folder = $root_folder . date("Y");
		$month_folder= $year_folder . '/' . date("m");
		// Create folder if not exist
		!file_exists($year_folder) && mkdir($year_folder , 0777);
		!file_exists($month_folder) && mkdir($month_folder, 0777);
		
		// Loop through list answers
		for( $i=0; $i<4; $i++ ) {

			////////////Options data///////////


			$answer_item = array('corect' => false, 'answer' => '', 'image' => '', 'explain' => '');
			$answer_item['corect']	= ($correct == $i) ? true : false;
			$answer_item['answer']	= $answers[$i];
			$answer_item['explain'] = $explains[$i];
			
			// Setting image dimension
			$answer_item['width'] = $width_img[$i];
			$answer_item['height'] = $height_img[$i];
			
			// Upload image
			$file = $files[$i];
			// Check if file is selected
			if( !isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE ) {		# Nothing file is selected
				$answer_item['image'] = $hidden_img[$i];
			} else {
				$file_name = strtolower($file['name']);
				$file_path = $month_folder . '/' . $file_name;
				// Check if file exist
				if( file_exists($file_path) ) {		# Exist
					$exp = explode('.', $file_name);
					$txt = $exp[0];
					$ext = $exp[1];
					$j = 2;
					// Rename new file from "filename.ext" to "filename ($j).ext"
					while( file_exists($file_path) ) {
						$file_path = $month_folder . '/' . $txt . ' (' . $j . ').' . $ext;
						$j++;
					}
				}
				// Upload
				move_uploaded_file($file['tmp_name'], $file_path);
				// Save url
				$answer_item['image'] = $file_path;
			}
			
			// Push item to $answer_data
			$answer_data[] = $answer_item;
			
			
			//set spanish data
			$answer_spanish_item = array('corect' => false, 'answer' => '', 'image' => '', 'explain' => '');
			$answer_spanish_item['corect']	= ($correct == $i) ? true : false;
			$answer_spanish_item['answer']	= $answers_spanish[$i];
			$answer_spanish_item['explain'] = $explains[$i];
			
			// Setting image dimension
			// width_spanish_img

			  if(isset($width_spanish_img[$i]))
			$answer_spanish_item['width'] =$width_spanish_img[$i];   //$width_img[$i];
		
             if(isset($height_spanish_img[$i]))     
			$answer_spanish_item['height'] =$height_spanish_img[$i];   //$height_img[$i];
			
			// Upload image
			$file_spanish = $files_spanish[$i];
			// Check if file is selected
			if( !isset($file_spanish) || $file_spanish['error'] == UPLOAD_ERR_NO_FILE ) {		# Nothing file is selected
				// $answer_spanish_item['image'] = $hidden_spanish_img[$i];
				if($hidden_spanish_img[$i]!=''){
					$answer_spanish_item['image'] = $hidden_spanish_img[$i];
				}else{
					$answer_spanish_item['image'] = $answer_item['image'];
				}
			} else {
				$file_name = strtolower($file_spanish['name']);
				$file_path = $month_folder . '/' . $file_name;
				// Check if file exist
				if( file_exists($file_path) ) {		# Exist
					$exp = explode('.', $file_name);
					$txt = $exp[0];
					$ext = $exp[1];
					$j = 2;
					// Rename new file from "filename.ext" to "filename ($j).ext"
					while( file_exists($file_path) ) {
						$file_path = $month_folder . '/' . $txt . ' (' . $j . ').' . $ext;
						$j++;
					}
				}
				// Upload
				move_uploaded_file($file_spanish['tmp_name'], $file_path);
				// Save url
				$answer_spanish_item['image'] = $file_path;
			}
			
			// Push item to $answer_data
			$answer_data_spanish[] = $answer_spanish_item;
		}
		
		// Serialize $answer_data
		$answer_data = mysql_real_escape_string(serialize($answer_data));
		$answer_data_spanish = mysql_real_escape_string(serialize($answer_data_spanish));
	} else {
		$answer_data = mysql_real_escape_string($_POST['response_answer']);
		$answer_data_spanish = mysql_real_escape_string($_POST['response_answer_spanish']);
	}
	
        
         $smart_prep_allow=(isset($_POST['smart_prep']))?1:0;
          $data_dash_allow=(isset($_POST['data_dash'])||isset($_POST['smart_prep']))?1:0;
       
	// Save into database
	if($question_id ==0) {		# Insert
		
            $response_msg='Question added Successfully! ';

                $alert = 'add new';
		$query = "INSERT INTO `questions` (`name`, `name_spanish`, `question`, `question_spanish`, `author`, `category`, `type`, `answers` , `answers_spanish`, `passage`, `public`,`smart_prep`,`data_dash`)
		VALUES ('{$question_name}', '{$question_name_spanish}', '{$question_question}', '{$question_question_spanish}', '{$author}', '{$question_category}', '{$question_type}', '{$answer_data}' ,  '{$answer_data_spanish}' , '{$question_passage}', {$question_public},{$smart_prep_allow},{$data_dash_allow})";
		//echo  $query ; die;
// Do query
		$return = mysql_query($query);		# Return @bool true | false
		// Get inserted id
		$getId = mysql_insert_id();
		// Insert objective
		foreach( $question_object as $object_item ) {
			mysql_query("INSERT INTO `term_relationships` (`question_id`, `objective_id`) VALUES ('{$getId}', '{$object_item}')");
		}
	} else {						# Update
		$alert = 'edit';
		 $response_msg='Question saved Successfully! ';
		// Update question
		$query = "UPDATE `questions` SET
			`name` = '{$question_name}',
                            `smart_prep` = '{$smart_prep_allow}',
                                `data_dash` = '{$data_dash_allow}',
			`name_spanish` = '{$question_name_spanish}',
			`question` = '{$question_question}',
			`question_spanish` = '{$question_question_spanish}',
			`author` = '{$author}',
			`category` = '{$question_category}',
			`type` = '{$question_type}',
			`answers` = '{$answer_data}',
			`answers_spanish` = '{$answer_data_spanish}',
			`passage` = '{$question_passage}',
			`date_modified` = '{$datetm}',
			`public` = {$question_public}
		WHERE `id` = {$question_id} LIMIT 1";
               
		// Do query
		$return = mysql_query($query);		# Return @bool true | false
		// Update objective
		mysql_query("DELETE FROM `term_relationships` WHERE `question_id` = {$question_id}");
		foreach( $question_object as $object_item ) {
			mysql_query("INSERT INTO `term_relationships` (`question_id`, `objective_id`) VALUES ('{$question_id}', '{$object_item}')");
		}
	}



	//////////Display////////
	$error	= $return ? $response_msg: 'Can not ' . $alert . ' question. Please try again later!';
        
        
}// add Question







// Init @array $default//////////////////////
$default = array('id' => 0, 'name' => '', 'question' => '', 'category' => '', 'type' => 1, 'objectives' => '', 'answers' => '', 'answers_spanish' => '' , 'passage' => 0);



$result = mysql_fetch_assoc( mysql_query("SELECT * FROM `questions` WHERE `id` = " . $quesId) );		# Return @boolean false if not found
if( $result ) {
	$default['id'] = $result['id'];
	$default['name'] = $result['name'];
	$default['name_spanish'] = $result['name_spanish'];
	$default['question'] = $result['question'];
	$default['question_spanish'] = $result['question_spanish'];
	$default['category'] = $result['category'];
	$default['type'] = $result['type'];
	$default['answers'] = $result['answers'];
	$default['answers_spanish'] = $result['answers_spanish'];
	$default['passage'] = $result['passage'];
	$default['public'] = $result['public'];
        $default['smart_prep'] = $result['smart_prep'];
        $default['data_dash'] = $result['data_dash'];
	
	$objectives = mysql_query("SELECT t . *  FROM `term_relationships` r INNER JOIN `terms` t ON r.`objective_id` = t.`id` WHERE r.`question_id` = {$result['id']}");
	if( mysql_num_rows($objectives) > 0 ) {
		$obj_array = array();
		while( $objective = mysql_fetch_assoc($objectives) ) {
			$obj_array[] = '<label><input type="checkbox" name="suggest[]" class="suggest" value="' . $objective['id'] . '" checked="checked" /> ' . $objective['name'] . '</label>';
		}
		$default['objectives'] = implode(', ', $obj_array);
	}
}

?>


<script>
$=jQuery;
    // change mousedown, change
    $(document).ready(function() {
        $('#smart_prep').change(function() {   
          //  alert('dff');
    if ($(this).is(':checked')) {
       // alert('checked');    
       //this.checked = confirm("Are you sure?"); $(this).trigger("change");  
        $('#data_dash').prop('checked', true); //
    }else{     $('#data_dash').prop('checked', false);}
});
    
});
</script>

<style>
.ui-dialog{
    height: auto;
    width: 600px;
    position: fixed !important;
    top: 32% !important;
    left: 33% !important;
}
</style>

<script type="text/javascript">
	// $(document).ready(function(){
	//     $("#trans").on("change",function(){
	// 	   if($(this).is(":checked"))
	// 	      $(this).val("1");
	// 	    else
	// 	      $(this).val("0");
	// 	});
	// });
</script>
                                                                   

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i>Add Question</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_question" id="form_question" method="post" action="" enctype="multipart/form-data">
							<h4>Add new question here:</h4>
							<div class="add_question_wrap clear fullwidth">
								<p>
									<input type="checkbox" name="question_public" id="question_public" style="vertical-align: sub;" <?php echo $default['public']==1 ? 'checked' : '';?>>
									<label for="question_public">Public Question <em>(Show for free user)</em></label>
								</p>
                                                                
                                                                
                                                                <p>
                                                                    Access Type:&nbsp;<input type="checkbox" name="smart_prep" id="smart_prep" value="1"
                                                                               style="vertical-align: sub;" <?= $default['smart_prep']==1 ? 'checked' : '';?>  >
									<label for="smart_prep">Smart Prep </label>
                                                                        
                                                                        
                                                                        <input type="checkbox" name="data_dash" id="data_dash"
                                                                               style="vertical-align: sub;" <?=  $default['data_dash']==1 ? 'checked' : '';?>  >
									<label for="data_dash">Data Dash </label>
								</p>
								
                                                                
                                                                
                                                                <p>
									<label for="question_passage">Passages of Question:</label>
									<select name="question_passage" id="question_passage" class="textbox">
										<option value=""></option>
										<?php
										$passages = mysql_query("SELECT * FROM `passages` q ORDER BY `date_created` DESC");
										if( mysql_num_rows($passages) > 0 ) {
											while( $passage = mysql_fetch_assoc($passages) ) {
												$selected = ($passage['id'] == $default['passage']) ? ' selected="selected"' : '';
												echo '<option value="' . $passage['id'] . '"' . $selected . '>' . $passage['title'] . '</option>';
											}
										}
										?>
									</select>
								</p>
								<p>
									<label for="question_name">Name of Question:</label>
									<input type="text" name="question_name" id="question_name" class="required textbox" value="<?php echo $default['name']; ?>" />
								</p>
								<p>
									<label for="question_name_spanish">Name of Question in Spanish:</label>
									<input type="text" name="question_name_spanish" id="question_name_spanish" class="required textbox" value="<?php echo $default['name_spanish']; ?>" />
								</p>
								<p>
									<label for="question_question">Actual Question:</label>
									<textarea name="question_question" id="question_question" class="textbox" rows="9"><?php echo $default['question']; ?></textarea>
									<input name="image" type="file" id="upload" class="hidden" onchange="">
								</p>
								<input id="trans" type="checkbox" name="translate" value="0" /> Auto translate actual question from English to Spanish
								<p>
									<label for="question_question">Actual Question in Spanish:</label>
									<textarea name="question_question_spanish" id="question_question_spanish" class="textbox" rows="9"><?php echo $default['question_spanish']; ?></textarea>
								</p>


								<p>
									<label for="question_category">Organize questions</label>
									<select name="question_category" id="question_category" class="required textbox">
										<option value=""></option>
										<?php
										$folders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1");
										if( mysql_num_rows($folders) > 0 ) {
											while( $folder = mysql_fetch_assoc($folders) ) {
												$selected = ($folder['id'] == $default['category']) ? ' selected="selected"' : '';
												echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
												$subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = {$folder['id']} AND `active` = 1");
												if( mysql_num_rows($subfolders) > 0 ) {
													while( $subfolder = mysql_fetch_assoc($subfolders) ) {
														$selected = ($subfolder['id'] == $default['category']) ? ' selected="selected"' : '';
														echo '<option value="' . $subfolder['id'] . '" class="subfolder"' . $selected . '>' . $subfolder['name'] . '</option>';
													}
												}
											}
										}
										?>
									</select>
								</p>
								<p class="search_field">
									<label for="search_object">Objective field</label>
									<input type="text" name="search_object" id="search_object" class="textbox" data-question="<?php echo $default['id']; ?>" placeholder="Search objective field..." value="" />
									<i class="fa fa-search"></i>
									<img class="searching" src="images/loading.gif" alt="..." />
								</p>
								<p id="defaultObj"><?php echo $default['objectives']; ?></p>
								<p id="suggestion"></p>
								<p>
									<label for="question_type">Type of Question</label>
									<select name="question_type" id="question_type">
									<?php
										$types = mysql_query("SELECT * FROM `types`");
										if( $types )
											while( $type = mysql_fetch_assoc($types) ) {
												$selected = ($type['id'] == $default['type']) ? ' selected="selected"' : '';
												echo '<option value="' . $type['id'] . '"' . $selected . '>' . $type['name'] . '</option>';
											}
									?>
									</select>
								</p>
								
								<?php 
								if( $default['type'] == 1 ) {
									$multi_c = '';
									$respond = ' style="display: none;"';
								} else {
									$multi_c = ' style="display: none;"';
									$respond = '';
								}
								?>


								<p class="lv_label_for_multiple_choice" <?php echo $multi_c; ?>> 
								<label>Actual Question Answer (Dropdown answer)</label></p>

								
								<ul id="multiple_choice" class="list_answer"<?php echo $multi_c; ?>>
								<?php
								if( isset($result['answers']) && @unserialize($result['answers']) !== false ) {
									$answerd = unserialize($result['answers']);
                                                       //print_r($answerd); die; // Test
								}
								
								for( $i=0; $i<4; $i++ ) {
									$checkCl = 'fa-square-o';
									$checked = '';
									$dataTxt = '';
									$dataImg = '<i class="fa fa-plus-square fa-3x"></i> (Upload)';
									$hidden_img = '';
									if( isset($result['answers']) && @unserialize($result['answers']) !== false ) {
										//$answerd = unserialize($result['answers']);
										
										$dataTxt = $answerd[$i]['answer'];
										if(isset($answerd[$i]['corect']) && $answerd[$i]['corect']) {
											$checkCl = 'fa-check-square-o';
											$checked = ' checked="checked"';
										}
										if(isset($answerd[$i]['image']) && $answerd[$i]['image'] != '') {
											$hidden_img = $answerd[$i]['image'];
											$dataImg  = '<img src="' . $answerd[$i]['image'] . '" height="40" />';
										}
									}
								?>
									<li>
										<i class="fa fa-check <?php echo $checkCl; ?>"></i>
										<input type="radio" name="correct" class="hidden_field" value="<?php echo $i; ?>" <?php echo $checked; ?> />
										<input type="text" name="answers[]" class="answers" value="<?php echo $dataTxt; ?>" placeholder="Answer here..." />
										<input type="file" name="images_<?php echo $i; ?>" class="uploads hidden_field" />
										<input type="hidden" name="hidden_img[]" value="<?php echo $hidden_img; ?>" />
										<input type="hidden" name="hidden_wid[]" id="width_<?php echo $i; ?>" class="hidden_wid" value="<?php echo $answerd[$i]['width']; ?>" />
										<input type="hidden" name="hidden_hig[]" id="height_<?php echo $i; ?>" class="hidden_hig" value="<?php echo $answerd[$i]['height']; ?>" />
										<a href="javascript: void(0);" class="upload_button" style="<?php //echo $default['passage']!="0"?"display:none;":"";?>"><?php echo $dataImg; ?></a>
										<a href="javascript: void(0);" class="update_image"<?php echo ($hidden_img != "") ? "" : " style='display: none;'"; ?>>
											<span class="glyphicon glyphicon-edit"></span>
										</a>
										<select name="explains[]" class="explains">
											<option value=""></option>
											<?php
											$distrators = mysql_query("SELECT * FROM `distrators` ORDER BY `name` ASC ");
											if( mysql_num_rows($distrators) > 0 ) {
												while( $distrator = mysql_fetch_assoc($distrators) ) {
													$selected = ($answerd[$i]['explain'] == $distrator['id']) ? ' selected="selected"' : '';
													echo '<option value="' . $distrator['id'] . '"' . $selected . '>' . $distrator['name'] . '</option>';
												}
											}
											?>
										</select>
									</li>
								<?php } ?>
									<div class="clearfix"></div>
								</ul>
								
								
								<p  class="lv_label_for_multiple_choice" <?php echo $multi_c; ?>><label>Actual Question Answer in Spanish (Dropdown answer)</label></p>
								<?php 
								if( $default['type'] == 1 ) {
									$multi_c = '';
									$respond = ' style="display: none;"';
								} else {
									$multi_c = ' style="display: none;"';
									$respond = '';
								}
								?>
								<ul id="multiple_choice_spanish" class="list_answer"<?php echo $multi_c; ?>>
								<?php

								$answerd = unserialize($result['answers_spanish']);

								//print_r($answerd2);die;

								unset($answerd);


								/////////////////////
								if( isset($result['answers_spanish']) && @unserialize($result['answers_spanish']) !== false ) {
									$answerd = unserialize($result['answers_spanish']);
									//print_r($answerd);die;
								}


								for( $i=0; $i<4; $i++ ) {
									$checkCl = 'fa-square-o';
									$checked = '';
									$dataTxt = '';
									$dataImg = '<i class="fa fa-plus-square fa-3x"></i> (Upload)';
									$hidden_img = '';
									
								if( isset($result['answers_spanish']) && @unserialize($result['answers_spanish']) !== false ) {

									//if( isset($result['answers_spanish']) ) {


										//$answerd = unserialize($result['answers_spanish']);
										$dataTxt = $answerd[$i]['answer'];
										if(isset($answerd[$i]['corect']) && $answerd[$i]['corect']) {
											$checkCl = 'fa-check-square-o';
											$checked = ' checked="checked"';
										}
										if(isset($answerd[$i]['image']) && $answerd[$i]['image'] != '') {
											$hidden_img = $answerd[$i]['image'];
											$dataImg  = '<img src="' . $answerd[$i]['image'] . '" height="40" />';
										}
									}
								?>
									<li>
										<input type="text" name="answers_spanish[]" class="answers" value="<?php echo $dataTxt; ?>" placeholder="Answer here..." />
										<input type="file" name="images_spanish_<?php echo $i; ?>" class="uploads hidden_field" />
										<input type="hidden" name="hidden_spanish_img[]" value="<?php echo $hidden_img; ?>" />
										<input type="hidden" name="hidden_spanish_wid[]" id="width_spanish_<?php echo $i; ?>" class="hidden_wid" value="<?php echo $answerd[$i]['width']; ?>" />
										<input type="hidden" name="hidden_spanish_hig[]" id="height_spanish_<?php echo $i; ?>" class="hidden_hig" value="<?php echo $answerd[$i]['height']; ?>" />
										<a href="javascript: void(0);" class="upload_button" style="<?php echo $default['passage']!="0"?"display:none;":"";?>"><?php echo $dataImg; ?></a>
										<a href="javascript: void(0);" class="update_image"<?php echo ($hidden_img != "") ? "" : " style='display: none;'"; ?>>
											<span class="glyphicon glyphicon-edit"></span>
										</a>
									</li>
								<?php } ?>
									<div class="clearfix"></div>
								</ul>
								<p id="open_response"<?php echo $respond; ?>>
									<label>Answer:</label>
									<textarea name="response_answer" id="response_answer" class="textbox" rows="9"><?php echo ($default['type'] == 2) ? $default['answers'] : ''; ?></textarea>

									<label>Answer in Spanish:</label>
									<textarea name="response_answer_spanish" id="response_answer_spanish" class="textbox" rows="9"><?php echo ($default['type'] == 2) ? $default['answers_spanish'] : ''; ?></textarea>
								</p>
							</div>
							<p>
								<input type="hidden" name="question_id" id="question_id" value="<?php echo $default['id']; ?>" />
								<input type="submit" name="question_submit" id="question_submit" class="form_button submit_button" value="Submit" />
								<input type="reset" name="question_sreset" id="question_sreset" class="form_button reset_button" value="Reset" />
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

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>

<?php 
 //  Question Edit option 
  //include("footer_ques.php"); ?>

   <!-- Footer Section -->

   	<div id="footer" class="clear fullwidth">
		<div class="container">
			<div class="row">
				<p>Copyright &copy; <?php echo date('Y'); ?> Intervene, LLC. All rights reserved.</p>
			</div>
		</div>
	</div>		<!-- /#footer -->

</div>		<!-- /#wrapper -->

<!-- Form Add/Edit Folder -->
<div id="folder_dialog" class="form_dialog">
	<form name="folder_form" id="folder_form" class="form_data" method="post" action="">
		<div class="form_wrap clear fullwith">
			<p>
				<label>Foder Name:</label><input type="text" name="folder_name" id="folder_name" class="field_data textfield" value="" />
			</p>
			<p>
				<label>With:</label>
				<label class="sub-label"><input type="radio" name="folder_level" value="0" class="level_field" />New Foder Level 1</label>
			</p>
			<?php
			$categories = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1");
			if( mysql_num_rows($categories) > 0 ) {
			?>
				<p>
					<label>&nbsp;</label>
					<label class="sub-label"><input type="radio" name="folder_level" value="1" class="level_field" />Subfolders</label>
					<select name="child_of_folder" id="child_of_folder" class="child_of">
						<option value=""></option>
						<?php
						while( $category = mysql_fetch_assoc($categories) ) {
							echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
						}
						?>
					</select>
				</p>
			<?php } ?>
		</div>
		<div class="button_wrap clear fullwith">
			<input type="hidden" name="hidden_id" class="hidden_id" value="" />
			<input type="submit" name="submit_folder" id="submit_folder" class="form_button submit_button" value="Create" />
			<input type="reset" name="reset_folder" id="reset_folder" class="form_button reset_button" value="Cancel" />
		</div>
	<form>
</div>

<!-- Form Add/Edit Objective -->
<div id="objective_dialog" class="form_dialog">
	<form name="objective_form" id="objective_form" class="form_data" method="post" action="">
		<div class="form_wrap clear fullwith">
			<p>
				<label>Objective Name:</label><input type="text" name="objective_name" id="objective_name" class="field_data textfield" value="" />
			</p>
			<p>
				<label>Description:</label><textarea name="objective_desc" id="objective_desc" class="field_data textfield field_desc"></textarea>
			</p>
			<p>
				<label>With:</label>
				<label class="sub-label"><input type="radio" name="objective_level" value="0" class="level_field" />New Objective Level 1</label>
			</p>
			<?php
			$objects = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `parent` = 0 AND `active` = 1");
			if( mysql_num_rows($objects) > 0 ) {
			?>
				<p>
					<label>&nbsp;</label>
					<label class="sub-label"><input type="radio" name="objective_level" value="1" class="level_field" />SubObjective</label>
					<select name="child_of_object" id="child_of_object" class="child_of">
						<option value=""></option>
						<?php
						while( $object = mysql_fetch_assoc($objects) ) {
							echo '<option value="' . $object['id'] . '">' . $object['name'] . '</option>';
						}
						?>
					</select>
				</p>
			<?php } ?>
		</div>
		<div class="button_wrap clear fullwith">
			<input type="hidden" name="hidden_id" class="hidden_id" value="" />
			<input type="submit" name="submit_objective" id="submit_objective" class="form_button submit_button" value="Create" />
			<input type="reset" name="reset_objective" id="reset_objective" class="form_button reset_button" value="Cancel" />
		</div>
	<form>
</div>

<!-- Form Add/Edit Distrator -->
<div id="distrator_dialog" class="form_dialog">
	<div class="clear fullwidth">
		<form name="distrator_form" id="distrator_form" class="form_data" method="post" action="">
			<div class="form_wrap clear fullwith">
				<label for="distrator_field">Distrator Name:</label>
				<input type="text" name="distrator_field" id="distrator_field" class="field_data textfield" value="" />
			</div>
			<div class="button_wrap clear fullwith">
				<input type="hidden" name="hidden_id" class="hidden_id" value="" />
				<input type="submit" name="submit_distrator" id="submit_distrator" class="form_button submit_button" value="Create" />
				<input type="reset" name="reset_distrator" id="reset_distrator" class="form_button reset_button" value="Cancel" />
			</div>
		</form>
	</div>
</div>

<!-- Form edit answer image dimension -->
<div id="demension_dialog" class="form_dialog">
	<div class="clear fullwidth">
		<form name="dimension_form" id="dimension_form" class="form_data" method="post" action="">
			<div class="form_wrap clear fullwidth">
				<p>
					<label for="image_width">Width:</label>
					<input type="text" name="image_width" id="image_width" class="field_data textfield" value="" /> px
				</p>
				<p>
					<label for="image_height">Height:</label>
					<input type="text" name="image_height" id="image_height" class="field_data textfield" value="" /> px
				</p>
			</div>
			<div class="button_wrap clear fullwith">
				<input type="hidden" id="item_width" value="" />
				<input type="hidden" id="item_height" value="" />
				<input type="submit" name="submit_dimension" id="submit_dimension" class="form_button submit_button" value="Update" />
				<input type="reset" name="reset_dimension" id="reset_dimension" class="form_button reset_button" value="Cancel" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="js/functions-ques.js"></script>
<script type="text/javascript" src="js/form-ques.js"></script>

<?php include("analyticstracking.php"); ?>
<script>
  window.intercomSettings = {
    app_id: "dmq00i7a"
  };
</script>

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/dmq00i7a';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>



</body>
</html>









<!-- Footer Section -->


