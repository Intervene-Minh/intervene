<?php
if(isset($_GET['search'])){
	$s = $_GET['search'];
}else{
	$s = '';
}
if($s!=''):
	$s = mysql_real_escape_string($s);
if(isset($_SESSION['list'])){
	#if isset $_SESSION['list'] no query it.
	$notin = " AND `id` NOT IN ( '" . implode($_SESSION['list'], "', '") . "' )";
	
}else{
	$_SESSION['list'] = array();
	$notin = "";
}
$user_role = $_SESSION['login_role'];

// Check shared folder condition for search question
$shared = "SELECT `id`
	FROM `terms`
	INNER JOIN `shared` ON `terms`.`id` = `shared`.`termId`
	WHERE `terms`.`taxonomy` = 'category'
	AND `terms`.`active` = 1
	AND `shared`.`userId` = {$_SESSION['login_id']}";

$searchFolder = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `name` LIKE '%{$s}%' AND `active` = 1 AND 0");
$searchObjective = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND (`name` LIKE '%{$s}%' OR `description` LIKE '%{$s}%') AND `active` = 1");
$searchQuestion = mysql_query("SELECT * FROM `questions` WHERE (`name` LIKE '%{$s}%' OR `question` LIKE '%{$s}%') AND `category` IN ( {$shared} ) AND `public` = 1" . $notin);
$searchDistractors = mysql_query("SELECT * FROM `distrators` WHERE `name` LIKE '%{$s}%'");
endif;
?>
<script>
  $(function() {
    $("#result-tabs" ).tabs();
  });
</script>

<div id="search-result" class="content_wrap">
	<div class="ct_heading clear">
		<h3>Result Search For: <strong>'<?php echo $s?>'</strong></h3>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">
		<?php if( mysql_num_rows($searchFolder) == 0 && mysql_num_rows($searchObjective) == 0 && mysql_num_rows($searchQuestion) == 0 && mysql_num_rows($searchDistractors) == 0 ) : ?>
			<h3 class="notfound">There is no item found!<br /><br />
				<a href="../purchaseform.php">Click here to purchase this subject/grade level.</a>
			</h3>
		<?php else: ?>
			<div id="result-tabs">
				<ul>
					<?php if( mysql_num_rows($searchFolder) > 0 ):?><li><a href="#result-tabs-1">Folder</a></li><?php endif;?>
					<?php if( mysql_num_rows($searchObjective) > 0 ):?><li><a href="#result-tabs-2">Objectives</a></li><?php endif;?>
					<?php if( mysql_num_rows($searchQuestion) > 0 ):?><li><a href="#result-tabs-3">Questions</a></li><?php endif;?>
					<?php if( mysql_num_rows($searchDistractors) > 0 ):?><li><a href="#result-tabs-4">Distractors</a></li><?php endif;?>
				</ul>
				<?php if( mysql_num_rows($searchFolder) > 0 ): ?>
					<div id="result-tabs-1" class="search-section">
					<ul class="search-menu">
					<?php while( $item = mysql_fetch_assoc($searchFolder) ):?>
						<li data-parent="<?php echo $item['parent'];?>" data-id="<?php echo $item['id'];?>">
							<a href="folder.php?taxonomy=<?php echo $item['id'];?>"><?php echo $item['name'];?></a>
							<ul class="sub-menu">
							</ul>
						</li>
					<?php endwhile;?>
					</ul>
					</div>
				<?php endif;?>
				
				<?php if( mysql_num_rows($searchObjective) > 0 ):?>
					<div id="result-tabs-2" class="search-section">
						<ul class="search-menu">
						<?php while( $item = mysql_fetch_assoc($searchObjective) ):?>
							<li data-parent="<?php echo $item['parent'];?>" data-id="<?php echo $item['id'];?>">
								<a href="objective.php?taxonomy=<?php echo $item['id'];?>"><?php echo $item['name'];?></a>
							</li>
						<?php endwhile;?>
						</ul>
					</div>
				<?php endif;?>
				
				<?php if( mysql_num_rows($searchQuestion) > 0 ):?>
					<div id="result-tabs-3" class="search-section">
						<ul class="ul-list">
							<?php
							$i = 1;
							while( $item = mysql_fetch_assoc($searchQuestion) ) {
								if( $item['type'] == 1 ) {
									$echo = '<ul class="list-answers">';
									$answers = unserialize($item['answers']);
									foreach( $answers as $key => $answer ) {
										$echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
										$echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
										$echo .= '<p>' . $answer['answer'] . '</p>';
										$echo .= ($answer['image'] != '') ? '<p><img src="' . $answer['image'] . '" height="100" /></p>' : '';
										if( $answer['explain'] != '' && $user_role==0) {
											$explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
											$echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
										}
										$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
									}
									$echo .= '</ul>';
								} else {
									$echo = $item['answers'];
								}
							?>
								<li data-id="<?php echo $item['id'];?>">
									<div class="ques-text">
										<div class="ques-name"><?php echo $item['name'];?></div>
										<div class="ques-full">
											<?php echo $item['question']; ?>
											<p><strong><u>Answer:</u></strong></p>
											<?php echo $echo; ?>
										</div>
									</div>
									<div class="ques-button">
										<button class="report-error popup_report_error" name="report-an-error" value="<?php echo $item['id'];?>" data-target="#report_error_dialog">Report an Error</button>
										<button class="add-to-list" name="add_to_list" value="<?php echo $item['id'];?>">Choose Me<i class="fa fa-heart"></i></button>			
									</div>
								</li>
							<?php
								$i++;
							}
							?>
						</ul>
					</div>
				<?php endif;?>
				
				<?php if( mysql_num_rows($searchDistractors) > 0 ):?>
					<div id="result-tabs-4" class="search-section">
					<ul class="search-menu">
					<?php while( $item = mysql_fetch_assoc($searchDistractors) ):?>
						<li>
							<a href="distrator.php?id=<?php echo $item['id'];?>"><?php echo $item['name'];?></a>
						</li>
					<?php endwhile;?>
					</ul>
					</div>
				<?php endif;?>
			</div>
		<?php endif; ?>
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
				$(this).parents('li').first().slideUp(500);
				$count++;
				$('.list-notification>.text>.number').text($count);
				$('.list-fixed').show();
				/*store id to list*/
				var $id = $(this).val();
				$.post("inc/ajax-add_to_list.php", {"add_to_list": $id});
				
				clearTimeout($timehidden);
				$timehidden = setTimeout(function() {
					$('.list-fixed').hide(500);
				}, 10000);
			});
			$('.list-notification').on('click',function(){
				$(this).parents('.list-fixed').first().hide(500);
			});
			$('.ques-name').on('click',function(){
				$(this).siblings('.ques-full').slideToggle();
			});
			
			$('.search-menu li').each(function(){
				var dp = $(this).attr("data-id");
				var sthis = $(this).find(".sub-menu");
				$('.search-menu li[data-parent='+dp+']').detach().appendTo(sthis);
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
					}
				});
				$('#reset_error')[0].click();
				return false;
			});
		});
	</script>
</div>