<?php 
/**
 Step3:: Add Questions in a Quiz 
 2.Search question by objectives
 //echo 'Step 3::Add Questions in a Quiz ';
 //Grade ID: Folders http://intervene.srinfosystem.com/questions/quiz_step2.php?taxonomy=1\
 //Choose Questions:: http://intervene.srinfosystem.com/questions/quiz_step2_new.php?taxonomy=2634 , 
 //-- while selecting folder.
*/


$step_3_url="quiz_step2_new.php";

$step_4_url="quiz_step3.php";// Arrange Questions

///
include("header.php"); 

//print_r($_SESSION);
//print_r($_post);
//print_r($_get);



//// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT * FROM `questions` WHERE category = " . $_GET['taxonomy']." ORDER BY date_created DESC");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

if (1==2222222){
    echo "<h3> PHP List All Session Variables</h3>";
        foreach ($_SESSION as $key=>$val)
        echo " - ".$key." - ".$val."<br/>";
        
    echo "<h3> PHP List All Post Variables</h3>";
        foreach ($_POST as $key=>$val)
        echo " - ".$key." - ".$val."<br/>";    
    
    echo "<h3> PHP List All Get Variables</h3>";
        foreach ($_GET as $key=>$val)
        echo " - ".$key." - ".$val."<br/>";

    echo "<h3> PHP List All Variables</h3>";
        $arr = get_defined_vars();
        foreach ($arr as $key=>$val)
        echo " - ".$key." - ".$val."<br/>";
    
}

// Search +addd

//////  Filter Record .ll
 if(isset($_POST['ser_submit'])){
   //  echo 'hii'; die;
     
     $msgg="Filter recordd";
    $catid=intval($_GET['taxonomy']);
     
        // $_SESSION['ses_quiz_objective'] = terms.id = first one to use to display linked questions
        $quiz_objective = 0;
        if (isset($_POST['qobjectives'])) { 
            $quiz_objective = $_POST['qobjectives'];
        } else { 
            $quiz_objective = $_SESSION['ses_quiz_objective'];
        }
     
    
  
// $q=" Select q.* FROM questions q LEFT JOIN term_relationships tm ON q.id = tm.question_id WHERE q.category=".$catid;   
 // objective question only 
    $q=" Select q.* FROM questions q LEFT JOIN term_relationships tm ON q.id = tm.question_id ";
    $q.=" WHERE tm.objective_id =".$_SESSION['ses_quiz_objective'];

    $q = "SELECT q.* FROM `questions` q join term_relationships tr on tr.question_id = q.id join terms t on t.id = tr.objective_id  ";
    $q.= "WHERE q.category = ".$_GET['taxonomy']. " and t.id = " .$quiz_objective;
  if(isset($_POST['ques_name'])&&!empty($_POST['ques_name']))
 $q.=' AND q.name LIKE "%'.$_POST['ques_name'].'%"  ';

//echo "<br><br> line 85 - $q <br><br>";
 // objective
  // if(count($_POST['objective'])>0){
      
  //    $obj_ids= implode(",", $_POST['objective']);
  //     $q.=" AND tm.objective_id IN ($obj_ids) ";
  // }
   // objective
  /// Reults
  //echo  $q;  die;
  $childs = mysql_query($q);
  $passage_id = 0;
  ////////////
}else{
 
/////// End Filter  mysql_query :: and is correct.
 $childs=mysql_query("SELECT q. *
FROM questions q
LEFT JOIN term_relationships tm ON q.id = tm.question_id
WHERE q.category =".$_GET['taxonomy']."
OR tm.objective_id =".$_SESSION['ses_quiz_objective']);


    $childs = mysql_query("SELECT q.* FROM `questions` q join term_relationships tr on tr.question_id = q.id join terms t on t.id = tr.objective_id  
            WHERE q.category = ".$_GET['taxonomy']." 
            or t.id = " .$_SESSION['ses_quiz_objective']);

$childs99=mysql_query("SELECT * FROM `questions` WHERE category = " . $_GET['taxonomy']." ORDER BY date_created DESC ".$limit);

//echo  '<pre>' , $childs ; die;

$passage_id = 0;

}

///// OLDDDD////
  //  $childs = mysql_query("SELECT * FROM `questions` WHERE category = " . $_GET['taxonomy']." ORDER BY date_created DESC ".$limit);
//$passage_id = 0;
?>


<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
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
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Assessments here">Choose Search Fields:
                                <p class="text-danger">
                                  <?php // =(isset($q))?$q:NULL;?>  
                                    <br/>
                                  
                                    
                                    
                                    
                                     </p>
                            <?php
                           // if(isset($msgg))
                           

              # var_dump($assementArr) ?>
                            
                            
                            </h4>
                            <div class="col-md-12">
                                <p>  
                                    <label for="theobjective">Select Desired Question Objective </label><br>
                                    <select name="qobjectives"  class="textbox" required="" readonly="readonly" >
                                        <option value="0">Select an Objective</option>
                                        <?php
                                        // taxonomy is $catid = questions.category
                                        $catid = intval($_GET['taxonomy']);
                                        $obj_query  = "SELECT distinct t.id, t.obj_short, t.short_code FROM `questions` q join term_relationships tr on tr.question_id = q.id join terms t on t.id = tr.objective_id "; 
                                        $obj_query .= " WHERE q.category = " . $catid . " order by length(obj_short), obj_short ";

                                        $obj_query_result = mysql_query($obj_query);
                                        if (mysql_num_rows($obj_query_result) > 0) {
                                            while ($obj_query_result_rec = mysql_fetch_assoc($obj_query_result)) {
                                                $selected = ($obj_query_result_rec['id'] == $quiz_objective  ) ? ' selected="selected"' : '';
                                                echo '<option value="' . $obj_query_result_rec['id'] . '"' . $selected . '>' . $obj_query_result_rec['obj_short'] . ' ' . $obj_query_result_rec['short_code'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                            </div>
<?php //echo "<br><br> $quiz_objective <br> $obj_query <br><br>"; ?>                            
                            
                            <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Question Name</label>
                                    <input type="text"  placeholder="Question Name" style=" width: 100%" name="ques_name" class="required textbox" 
             value="<?=(isset($_POST['ques_name']))?$_POST['ques_name']:NULL;?>" />
                                </p></div>

                           <?php
                           
                           // ohbjective > that have questions
                           $qq=" SELECT DISTINCT(objective_id) FROM `term_relationships` WHERE 1  ";
                           
                           //$results_q= mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
                         $results_q= mysql_query($qq);   
                           $tot_obj=mysql_num_rows($results_q);
                           ?>


                           
                            
                               
                            
                            
                            
                           
                            
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
                            
<!--                            dffffXXXXXXXX-->
				<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
            <h3 title="Add Questions in a Quiz">Add Questions in a Quiz </h3>
            <a class="btn btn-primary btn-xs"  href="<?=$step_4_url?>"> &nbsp; click for Next Step:  Order the questions </a>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">
	<?php
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
						<p><?php echo (strlen($item['name']) > 110) ? substr($item['name'], 0, 110) . ' ...' : $item['name']; ?></p>
						<?php echo $item['question']; ?>
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
					<a href="../purchaseform.php">Click here to purchase this subject/grade level.</a>
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
				
				var item = $(this).parents('li').first()
				$count++;
				
				/*store id to list*/
				var $id = $(this).val();
				$.ajax({
					type	: 'POST',
					url		: 'inc/ajax-add_to_list.php',
					data	: {
						'add_to_list':$id,
						'is_passage':<?php echo $passage_id;?>
					},
					dataType: 'json',
					success	: function(response) {
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
				
				
			});
			$('.list-notification').on('click',function(){
				$(this).parents('.list-fixed').first().hide(500);
			});
			$('.alert-q-remaining .fa.fa-times').on('click',function(){
				$(this).parents('.alert-q-remaining').first().hide(500);
			});
			
			$('#submit_error').on('click',function(){
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
<?php if( mysql_num_rows($childs) > 550 ) include("pagination.php"); ?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>