<?php
// Setup title and where clause of query
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

$select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1") );		# Return @boolean false if not found
if( $select ) {
	$title = $select['name'];
	$clause = ( $select['taxonomy'] == 'objective' )
			? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id`
				WHERE r.`objective_id` = {$termId}
				AND q.`category` IN (
					SELECT `termId` FROM `shared` WHERE `userId` = {$_SESSION['login_id']}
				)"
			: " WHERE {$select['taxonomy']} = {$termId}";
} else {
	$title = 'List Questions';
	$clause = '';
}

// Validate term id from shared term list
if( $select['taxonomy'] == 'category' ) {
	$validate = mysql_query("SELECT * FROM `shared` WHERE `userId` = {$_SESSION['login_id']} AND `termId` = $termId");
	if( mysql_num_rows($validate) == 0 ) {
		//header("Location: folder.php");
		//exit();
	}
}

$passage_id = 0;
if(isset($_GET['passage']) && $_GET['passage'] != "") $passage_id = $_GET['passage'];

if(isset($_SESSION['list'])){
	#if isset $_SESSION['list'] no query it.
	if($clause == "") {
		$notin = " WHERE q.`id` NOT IN ( '" . implode($_SESSION['list'], "', '") . "' ) AND q.`passage` = $passage_id"; 
	} else {
		$notin = " AND q.`id` NOT IN ( '" . implode($_SESSION['list'], "', '") . "' ) AND q.`passage` = $passage_id";
	}
} else {
	$_SESSION['list'] = array();
	if($clause == "") {
		$notin = " WHERE q.`passage` = $passage_id";
	} else {
		$notin = " AND q.`passage` = $passage_id";
	}
}

// Public Questions
$public_question = ($_SESSION['login_role'] == 1) ? " AND `q`.`public` = 1" : "";

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `questions` q" . $clause . $notin . $public_question);		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$childs = mysql_query("SELECT * FROM `questions` q" . $clause . $notin . $public_question . ' ORDER BY `q`.`date_created` DESC' . $limit);

if($passage_id != 0) {
	$result_passage = mysql_query("SELECT * FROM `passages` p WHERE `id` = $passage_id ORDER BY `q`.`date_created` DESC");
	$this_passage = mysql_fetch_assoc($result_passage);
}
?>

<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
		<h3>Add Questions in Document</h3>
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
					 $answers = unserialize($item['answers']);
                                        // echo '<pre>'; print_r($answers); die;
					//end
                                         
                                         $i= 0;
                                         $option = array('A','B','C','D');
					foreach( $answers as $key => $answer ) {
						$converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
						$clear = strip_tags($converted);
						$result = trim($clear, chr(0xC2) . chr(0xA0));
						$result = trim($result);
						//lv-edit-2
						$answer['answer'] = str_replace('\"','"',$answer['answer']);
                                               // echo '<pre>'; print_r($answer);
						//end
						$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
						$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
						$echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
                                                $echo .= $option[$i].'.';
						$echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
						$echo .= ( $result == "" ) ? "" : $answer['answer'];
						$echo .= ($answer['image'] != '') ? '<p><img src="' . $answer['image'] . '"' . $width . $height . ' /></p>' : '';
						/* 
						if( $answer['explain'] != '' ) {
							$explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
							$echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
						}
						 */
						$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
                                                $i++;
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
	<script>
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
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>