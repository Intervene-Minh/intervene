<?php
//School Step : datadash folder
//echo 'school_assessment_step1'; die;
/***
 * @list of grades of a school
 * //print_r($school_det); die;
 * // school_assessment_step2.php?taxonomy=22
 @ Arrange and delete
 * **/
//ini_set('display_errors', 1);
include("header.php");
require_once('inc/school_validate.php'); // IMP

  $step_2_url="school_assessment_step2.php"; 
$step_1_url="school_assessment_step1.php";


  



  // Re-direct////////////////////
if(isset($_SESSION['ses_taxonomy'])){
       // w/o passage Test
    
    if($_SESSION['is_passage_grade']!=1){
         header("Location:".$step_2_url."?taxonomy=".$_SESSION['ses_taxonomy']);exit; // Go, Step2: Question w/o Passage 
    } //else echo 'Same pages, ';
      
   
}

// Get current term id - return 0 if no term is selected
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

// Init condition for shared folders
$condition = "";
$ses_school_id=$_SESSION['schools_id'];






////For Schools Only////
if( $_SESSION['schools_id'] > 0 ) {
	// Retrieve shared folders
       $sql=" SELECT *
FROM `school_permissions`
WHERE `school_id` =".$ses_school_id."
AND `permission` = 'data_dash' ";
	//$shared_folders = mysql_query("SELECT DISTINCT(`termId`) FROM `shared` WHERE `userId` = {$_SESSION['schools_id']}");
    $shared_folders = mysql_query($sql);
        
	if( mysql_num_rows($shared_folders) > 0 ) {
		$shared = array();
		while( $row = mysql_fetch_array($shared_folders) )
			$shared[] = $row['grade_level_id'];
	  
                
		// Get list parents of shared folders
		$parents = array(); // mysql_query
		$shared_parents = mysql_query("SELECT DISTINCT(`parent`) FROM `terms` WHERE `id` IN (" . implode(',', $shared) . ")");
                
		while( $row = mysql_fetch_array($shared_parents) )
			$parents[] = $row['parent']; // School Parent folders : For assign Grade
	}
        //print_r($parents); die;
        
      
        
  // $shared[] = $parents[]= 2617; // ELA
		// $shared[] = $parents[]= 2618; // ELA
	$condition = ($termId == 0) ? implode(',', $parents) : implode(',', $shared);
	$condition = "AND `id` IN ($condition)";
}
   //print_r($shared); die;
// Query term title
$select = mysql_fetch_assoc( mysql_query("SELECT `name` FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `id` = {$termId}") );	# Return @boolean false if not found
$title	= $select ? $select['name'] : 'Folder Questions';
 // mysql_query
// Count children of current taxonomy. Redirect to questions.php==questions_list.php if no item found
$childs =mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 {$condition} AND `parent` = {$termId} ORDER BY `name` ASC");
// echo $childs ; die;
 //echo '<pre>',$childs ; die;

////////Select Folder////////////
if( mysql_num_rows($childs) == 0 ) {
	$select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1") );# Return @boolean false if not found
	
        
        
        if( $select ) {
		$title = $select['name'];
		$clause = ( $select['taxonomy'] == 'objective' )
				? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}"
				: " WHERE {$select['taxonomy']} = {$termId}";
	} else {
		$title = 'List Questions';
		$clause = '';
	}

	$passage_id = 0;
	if(isset($_GET['passage']) && $_GET['passage']!="")$passage_id = $_GET['passage'];
             ///TEST1
        
        if(isset($_SESSION['ses_school_list'])){
            // add some
            $notin_xxx= " WHERE q.`id` NOT IN ( '" . implode($_SESSION['list_x'], "', '") . "' ) AND q.`passage`<>0"; 
        }else{
        $_SESSION['ses_school_list'] = array(); //if not found
        }
        
        
        
        //////////////////////
	if(isset($_SESSION['list_x'])){
          #if isset $_SESSION['ses_school_list'] no query it.
		
		if($clause==""){
			$notin = " WHERE q.`id` NOT IN ( '" . implode($_SESSION['list_x'], "', '") . "' ) AND q.`passage`<>0"; 
		}else{
			$notin = " AND q.`id` NOT IN ( '" . implode($_SESSION['list_x'], "', '") . "' ) AND q.`passage`<>0";
		}
	}else{
		$_SESSION['list_x'] = array(); 
		if($clause==""){
			$notin = "WHERE q.`passage`<>0";
		}else{
			$notin = " AND q.`passage`<>0";
		}
	}
        ///TEST1

	$childs = mysql_query("SELECT DISTINCT `passage` FROM `questions` q" . $clause .$notin. ' ORDER BY `date_created` DESC');
	
	while( $item = mysql_fetch_assoc($childs) ) {
		$array_passage[]=$item['passage'];
	}
        
        //  mysql_query
	$childs_passage =mysql_query("SELECT `id`,`title`,`date_created` FROM `passages` p WHERE `id` IN ( '" . implode($array_passage, "', '"). "' ) ORDER BY `date_created` DESC");


	//echo '<pre>', $childs_passage; die;
	if($childs_passage && mysql_num_rows($childs_passage)==0){
             //echo 'Go to step 2';
		header('Location:school_assessment_step2.php?taxonomy=' . $termId); exit;
		
	}
	
}
?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php  include("sidebar_school.php");  // sidebar_school , sidebar ?>
                            
			</div>
                    <!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="folder_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3><?php echo $title; ?></h3>
						<?php if($role==0 && !$childs_passage):?>
						<ul>
							<li><a href="javascript: void(0);" class="popup_form" data-target="#folder_dialog" title="New Folder"><i class="fa fa-plus-circle"></i></a></li>
							<li><a href="javascript: void(0);" class="popup_edit" data-target="#folder_dialog"><i class="fa fa-pencil"></i></a></li>
							<?php if( isGlobalAdmin() ) : ?>
								<li><a href="javascript: void(0);" class="remove_items" data-type="category"><i class="fa fa-trash"></i></a></li>
							<?php endif; ?>
						</ul>
						<?php endif; ?>
					</div>		<!-- /.ct_heading -->
					
					<div class="ct_display clear">
						<?php
						if(!$childs_passage):
						$i = 1;
						while( $item = mysql_fetch_assoc($childs) ) {
							$url = 'school_assessment_step1.php?taxonomy=' . $item['id'];
						?>
							<div class="item-wrap col-lg-3 col-md-3 col-sm-4 col-xs-6">
								<p><a href="<?php echo $url; ?>"><i class="fa fa-folder-open fa-4x"></i></a></p>
								<p class="item-title">
									<?php if($role==0):?>
									<input type="checkbox" class="edit_items" value="<?php echo $item['id']; ?>" data-parent="<?php echo $item['parent']; ?>" title="Edit Folder (<?php echo $item['name']; ?>)" />
									<?php endif;?>
									<a class="item_name" href="<?php echo $url; ?>"><?php echo $item['name']; ?></a>
								</p>
								<!--
								<p class="item-desc"><?php echo date('Y M d', strtotime($item['date_created'])); ?></p>
								-->
							</div>
						<?php
							echo ($i % 4 == 0 || $i == mysql_num_rows($childs)) ? '<div class="clearnone">&nbsp;</div>' : '';
							$i++;
						}
						else:
						// echo "<p>No item found!</p>";
						// return;
						$i = 1;
						while( $item = mysql_fetch_assoc($childs_passage) ) {
							$url = 'questions_list.php?taxonomy=' .$termId.'&passage='. $item['id'];
							$passage_question = mysql_query("SELECT * FROM questions WHERE passage = {$item['id']} AND public = 1");
							if(mysql_num_rows($passage_question) > 0)
							{
							
						?>
							<div class="item-wrap col-lg-3 col-md-3 col-sm-4 col-xs-6">
								<p><a href="<?php echo $url; ?>"><i class="fa fa-paragraph fa-4x"></i></a></p>
								<p class="item-title">
									<a class="item_name" href="<?php echo $url; ?>"><?php echo $item['title']; ?></a>
								</p>
								<p class="item-desc"><?php echo date('Y M d', strtotime($item['date_created'])); ?></p>
							</div>

						<?php
						}
							// echo ($i % 4 == 0 || $i == mysql_num_rows($childs)) ? '<div class="clearnone">&nbsp;</div>' : '';
							$i++;
						}
						
						endif;
						?>
						<div class="clearfix"></div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
<?php ob_flush(); ?>