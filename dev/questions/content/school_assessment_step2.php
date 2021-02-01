<?php 
/**
 * @Step 2; school_assessment_step2 
 * @School : assessment
 * $_SESSION['ses_taxonomy']=$_GET['taxonomy']; //  selected subject and grade level
====================
@ 4-oct : https://intervene.atlassian.net/browse/IIDS-152
@ arrage :: Assessments - Edit Question Not Deleting
 * **/




include("header.php"); 
require_once('inc/school_validate.php'); // IMP

////////////////////
//print_r($_SESSION);



//////////Question Session list prepare/////////////

  if(!isset($_SESSION['ses_school_list'])){  
     $_SESSION['ses_school_list'] = array();// echo 'List array not ';
  }    

///////////////////////////
$step_1_url="school_assessment_step1.php";
//// PassageQuestions in session :re-direct:Skip Step2/////////
if($_SESSION['ses_taxonomy']>0&&$_SESSION['is_passage']==1){
    header("Location:".$step_1_url."?taxonomy=".$_SESSION['ses_taxonomy']);exit;
}


if(isset($_SESSION['schools_id'])){
$school_id=$_SESSION['schools_id']; //
}else exit('Page not found');



if(isset($_GET['taxonomy'])&&$_GET['taxonomy']>0)
$_SESSION['ses_taxonomy']=$_GET['taxonomy']; //  selected subject and grade level
//
// School Data Dash Grade list 
 $sql="SELECT *
FROM `school_permissions`
WHERE `school_id` =".$school_id."
AND `permission` = 'data_dash'
LIMIT 0 , 30 "; $result=mysql_query($sql);
$school_grade_arr=array();
while( $row = mysql_fetch_array($result) ){
   $school_grade_arr[]= $row['grade_level_id'];
}
 //$_SESSION['ses_datadash_grade']=$school_grade_arr;  // School grade list
 
 $shared_parents = mysql_query("SELECT DISTINCT(`parent`) FROM `terms` WHERE `id` IN (" . implode(',', $school_grade_arr) . ")");
                $parents=array();
		while( $row = mysql_fetch_array($shared_parents) )
			$parents[] = $row['parent']; // School Parent folders : For assign Grade
 
                //print_r($parents); die;
// print_r($school_grade_arr); die; // assessment
 $assessment_folder_id=0;// Parent Category choose for assessment
 // Folder selected by School



// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query(" SELECT * FROM questions WHERE public =1 AND ( smart_prep =1 OR data_dash =1 ) AND category = " . $_GET['taxonomy']." ORDER BY date_created DESC  ");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$question_found=$count;

 ///Filter Record///////////////////////  
 if(isset($_POST['ser_submit'])){
     //print_r($_POST); die;
    $msgg="Filter recordd";
    $catid=intval($_GET['taxonomy']);
  //$q='Normal wuerey ';
   $q=" SELECT * FROM questions WHERE public =1 AND ( smart_prep =1 OR data_dash =1 ) AND category = " . $_GET['taxonomy']." ORDER BY date_created DESC ";

 ////// objective//////////////
  if(count($_POST['objective'])>0){
      $q=" Select q.* FROM questions q LEFT JOIN term_relationships tm ON q.id = tm.question_id WHERE q.public =1 AND (q.smart_prep =1 OR q.data_dash =1 ) AND q.category=".$catid;   
 //$q.=" q.public =1 AND (q.smart_prep =1 OR q.data_dash =1 ) "; // Public==1 & samrtPrep=1 OR Datadah==1
 
      
     $obj_ids= implode(",", $_POST['objective']);
      $q.=" AND tm.objective_id IN ($obj_ids) ";
  }
  //echo  $q;  die;
   // $limit
  /// Reults
  $childs = mysql_query($q.$limit);
  $passage_id = 0;
  // Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query($q);		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared
$question_found=$count;
// Pagination
  
  ////////////
}else{ // all questions // mysql_query
     $ques_query=" SELECT * FROM questions WHERE public =1 AND ( smart_prep =1 OR data_dash =1 ) AND category = " . $_GET['taxonomy']." ORDER BY date_created DESC ".$limit;
$childs =mysql_query($ques_query);

$passage_id = 0;

}

///////Validate Step 3//
 if(isset($_GET['st'])){
     
      $total_ques=count($_SESSION['ses_school_list']);
     
      $url='school_assessment_step2.php?taxonomy='.$_GET['taxonomy'];
     if($total_ques>=1)
         $url='school_assessment_step3.php';
     
     header("Location:".$url);exit;
   
 }

?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
     // district
        $('#objecitves_id').chosen();

        $('#objecitves_id').change(function () {
            //district = $(this).val();
   
        });
        $('#objecitves_id').change();
    });
</script>
<style>
    #objecitves_id_chosen{width:100% !important;}
    </style>



<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
                            
				<?php  include("sidebar_school.php");  //  , sidebar ?>
			</div>		<!-- /#sidebar -->
			
                        
                        
                        
                        
                        <div id="content" class="col-md-8">
                            
                             <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form  name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Assessments here">Choose Search Fields:
                                <p class="text-danger">
                                  <?php // =(isset($q))?$q:NULL;?>  
                                    <br/>
                                  
                                    
                                    
                                    
                                    <br/> </p>
                            <?php
                           // if(isset($msgg))
                           

              # var_dump($assementArr) ?>
                            
                            
                            </h4>
                            
                            
                            
                            

                           <?php
                           //print_r($_SESSION);
                           // ohbjective > that have questions
                           $sqlx=" SELECT DISTINCT(objective_id) FROM `term_relationships` WHERE 1  ";// 384 result[questions_objectives]
                            $sqlx="SELECT DISTINCT `id` , `name` AS Objective
FROM `terms` t
LEFT JOIN `term_relationships` r ON t.`id` = r.`objective_id`
WHERE t.`taxonomy` = 'objective'
AND t.`active` =1
AND t.parent
IN ( 2630, 2879, 2621, 2622, 2624, 2625 )
LIMIT 0 , 30
";
                    $sql="SELECT tr.objective_id FROM `term_relationships` AS tr LEFT JOIN questions AS q ON tr.question_id = q.id 
                        WHERE tr.question_id >0 AND q.category =".$_GET['taxonomy']." GROUP BY tr.objective_id ";
                            
                            //echo  $sql; die;
                           $results_q=mysql_query($sql);# mysql_query
                        
                          
                        
                        // echo 'result=', 
                                 $tot_obj=mysql_num_rows($results_q);
                         
                           ?>
                            <div class="col-md-12">
                           
                                <p>
                                    <label for="lesson_name">Choose Objectives:</label><br />
                                    <select name="objective[]" id="objecitves_id" multiple="true">
                                 <?php while ($line= mysql_fetch_assoc($results_q)) {
                                  
             $obj_det=mysql_fetch_assoc(mysql_query("SELECT id,name FROM `terms` WHERE id=".$line['objective_id']));     
                                     ?>
                    <option <?php if (in_array($line['objective_id'], $_POST['objective'])) { ?> selected="selected" <?php } ?> value="<?php print $line['objective_id']; ?>"><?php print $obj_det['name']; ?></option>

<?php } ?>
                                    </select>

                                </p>
                            </div> 
                           
                            
                               
                            
                            
                            
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">
           <input type="submit" name="ser_submit" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Search" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
<?php } ?>

                            </p>

                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div> 
                            
                            <!-------Fileter ---->
                            
				<div id="list-document" class="content_wrap">
                                    
	<div class="ct_heading clear">
<!--            <form method="post">-->
		<h3 >Add Questions in Assesment List(<?=mysql_num_rows($childs)?><?php //=$question_found?>)</h3>
                
                <a href="<?="school_assessment_step2.php?taxonomy=".$_GET['taxonomy']."&st=next"?>" class="form_button submit_button" style="float: right; margin-left: 10px;"  >Next</a>
<!--            </form>-->
	</div>		
     <!--	ct_heading clear-->
        
        
        
        
        
        
        
        
        
	<div class="ct_display clear">
	<?php
	    $alpha = array('A', 'B', 'C', 'D','E');
		if( mysql_num_rows($childs) > 0 ) {
			echo '<ul class="ul-list">';
			
			if($passage_id != 0) {
				echo '<h2>'.$this_passage['title'].'</h2>';
				echo $this_passage['content'];
			}
			
			$i = 1;
			while( $item = mysql_fetch_assoc($childs) ) {
				
				if( $item['type'] == 1 ) {
					$echo = '<ul class="list-answers">';
					//lv-edit 04/05/2016
					$lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers']);
					$answers = unserialize($lv_answers);
					// $answers = unserialize($item['answers']);
					//end
					foreach( $answers as $key => $answer ) {
						$converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
						$clear = strip_tags($converted);
						$result = trim($clear, chr(0xC2) . chr(0xA0));
						$result = trim($result);
						//lv-edit-2
						$answer['answer'] = str_replace('\"','"',$answer['answer']);
						//end
						$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
						$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
						$echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
						$echo .= $alpha[$key].'.  &nbsp;&nbsp;&nbsp;';
						$echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
						$echo .= ( $result == "" ) ? "" : $answer['answer'];
						$echo .= ($answer['image'] != '') ? '<p><img src="' . $base_url.$answer['image'] . '"' . $width . $height . ' /></p>' : '';
						/* 
						if( $answer['explain'] != '' ) {
							$explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
							$echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
						}
						 */
						$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
					}
					$echo .= '</ul>';
				} else {
					$echo = $item['answers'];
				}
	?>
				<li>
					<div class="ques-text">
						<p><?php echo (strlen($item['name']) > 110) ? substr($item['name'], 0, 110) . ' ...' : $item['name']; ?>
						<?php 
						if($item['public'] == 1) {
							echo " - <b>Public</b>";
						}else{
							echo "- <b>Not Public</b>";
						}
						
						?>
						</p>
						<?php  echo $item['question'];  ?>
						<p><strong><u>Answer:</u></strong></p>
						<?php echo $echo; ?>
					</div>
					<div class="ques-button">
						<button class="report-error popup_report_error" name="report-an-error" value="<?php echo $item['id'];?>" data-target="#report_error_dialog">Report an Error</button>
						<button class="add-to-list" name="add_to_list" value="<?php echo $item['id'];?>">Choose Me<i class="fa fa-heart"></i></button>			
					</div>
					
				</li>
	<?php
				$i++;
			}
			echo '</ul>';
		} else {
			echo '<div class="item-listing clear">
				<h3 class="notfound">There is no item found!<br /><br />
					<a href="#">Click here to purchase this subject/grade level.</a>
				</h3>
			</div>';
		}
	?>
	</div>		<!-- /.ct_display -->
	<!-- Form Add/Edit Distrator -->
	<div id="report_error_dialog" class="form_dialog">
		<div class="clear fullwidth">
			<form name="report_error_form" id="report_error_form" class="form_data" method="post" action="">
				<div class="form_wrap clear fullwith">
					<p>
						<label for="error_subject">Subject:</label>
						<input type="text" name="error_subject" id="error_subject" class="field_data textfield" value="" />
					</p>
					<p>
						<label for="error_comment">Comment:</label>
						<textarea name="error_comment" id="error_comment" class="field_data textfield"></textarea>
					</p>
				</div>
				<div class="button_wrap clear fullwith">
					<input type="hidden" name="hidden_id" class="hidden_id" id="question_id" value="" />
					<input type="submit" name="submit_error" id="submit_error" class="form_button submit_button" value="Send" />
					<input type="reset" name="reset_error" id="reset_error" class="form_button reset_button" value="Cancel" />
				</div>
			</form>
		</div>
	</div>
        
        
        <script type="text/javascript">
		$(document).ready(function(){
			var $count =0;
			var $timehidden;
			$('.add-to-list').on('click',function(){ 
				//alert('add-to-lis');
				var item = $(this).parents('li').first()
				$count++;
				
				/*store id to list*/
				var $id = $(this).val();
				$.ajax({
					type	: 'POST',
					url		: 'inc.school/ajax-add_to_list.php',
					data	: {
						'add_to_list':$id,
						'is_passage':<?php echo $passage_id;?>
					},
					dataType: 'json',
					success	: function(response) {
                                            // alert(response.check+' HII');
						if(response.check){
                                                   
							item.slideUp(500);
							// var is_unlimited = response.is_unlimited;
							var count = response.count;
							// var remaining = response.remaining;
							
							// if(is_unlimited){
								// remaining = ' Unlimited';
							// }else{
								// if(remaining <0){
									// if(remaining=='-1')$('.alert-q-remaining').show();
									// remaining = 0;
								// }
							// }
							
							$('.list-notification>.text>.number').text(count);
							// $('.list-notification>.text>.remaining').text(remaining);
							$('.list-fixed').show();
							clearTimeout($timehidden);
							$timehidden = setTimeout(function() {
								$('.list-fixed').hide(500);
							}, 10000);
							
						}else{
							alert("Can't add this question");
						}
					}
				});
				
				
			}); //.add-to-list
                        
                      ////////////////////  
                        
			$('.list-notification').on('click',function(){
				$(this).parents('.list-fixed').first().hide(500);
			});
			$('.alert-q-remaining .fa.fa-times').on('click',function(){
				$(this).parents('.alert-q-remaining').first().hide(500);
			});
			
			$('#submit_error').on('click',function(){
                           // alert('gfgg');
				if($('#error_subject').val()==""){
					$('#error_subject').css({'border':'1px solid #e4532c','outline':'none'});
					$('#error_subject').focus();
					return false;
				}else{
					$('#error_subject').css({'border':'1px solid #d6d6d6'});
					
				}
				if($('#error_comment').val()==""){
					$('#error_comment').css({'border':'1px solid #e4532c','outline':'none'});
					$('#error_comment').focus();
					return false;
				}else{
					$('#error_comment').css({'border':'1px solid #d6d6d6'});
					
				}
				$.ajax({
					type	: 'POST',
					url		: 'inc/ajax-send-error.php',
					data	: {
						'error_subject':$('#error_subject').val(),
						'error_comment':$('#error_comment').val(),
						'question_id':$('#question_id').val()
					},
					dataType: 'json',
					success	: function(response) {
						console.log(response);
						if(response.check){
							alert("Success!");
						}else{
							alert("Fail!");
						}
						
						// $('#loading').remove();
						// alert(response.msg);
						// if(response.stt)
							// $(popup).dialog('close');
						// if(response.stt && response.sql == 'update')
							// location.reload();
					}
				});
				$('#reset_error')[0].click();
				return false;
			});
		});
	</script>
</div>
<div class="list-fixed">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">A problem has been added (<span class="number">0</span> problems total)</div>
	</div>
</div>
<div class="alert-q-remaining">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">You have used all of your free questions. <a href="membership.php" class="btn btn-link">Upgrade to Membership</a></div>
	</div>
</div>
<!--                Add button            -->
      <div class=" clear">


                
                <a href="school_assessment_step2.php?taxonomy=22&amp;st=next" class="form_button submit_button" 
                   style="float: right; margin-left: 10px;margin-top: 15px;margin-bottom: 15px;">Next</a>
  
	</div>
                            
                            
                            
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>