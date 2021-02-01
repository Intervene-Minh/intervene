<?php
// Setup title and where clause of query
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;
$select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1") );		# Return @boolean false if not found
if( $select ) {
	$title = $select['name'];
	$clause = ( $select['taxonomy'] == 'objective' )
			? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}"
			: " WHERE {$select['taxonomy']} = {$termId}";
} else {
	$title = 'List Questions';
	$clause = '';
}

if(isset($_SESSION['list'])){
	#if isset $_SESSION['list'] no query it.
	if($clause==""){
		$in = " WHERE q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )"; 
	}else{
		$in = " AND q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )";
	}
	$orderby = " ORDER BY FIELD(q.`id`,'". implode($_SESSION['list'], "', '")."')";
}else{
	if($clause==""){
		$in = " WHERE q.`id` IN ( '' )"; 
	}else{
		$in = " AND q.`id` IN ( '' )";
	}
	$orderby ="";
}

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `questions` q" . $clause.$in);		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared



// $childs = mysql_query("SELECT * FROM `questions` q" . $clause . $in . $orderby . $limit);
$passage_in_list = mysql_query("SELECT DISTINCT `q`.`passage`,p.*  FROM `questions` q INNER JOIN `passages` p ON `q`.`passage` = `p`.`id` " . $clause . $in . $orderby . $limit);
?>
<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
		<h3>List Document</h3>
		<!--<form id="print-form"method="post" action="inc/ajax-print-passage.php">
			<button id="print" name="print" type="submit">Print</button>
			<input id="print-content" name="print-content" type="hidden"/>
		</form>-->
		<a href="inc/ajax-print-passage.php?lang=spanish" id="print_spanish" class="print_pdf">Print in Spanish</a>
		<a href="inc/ajax-print-passage.php" id="print" class="print_pdf">Print in English</a>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">
		<?php
		if( mysql_num_rows($passage_in_list) > 0 ) {
			echo '<ul id="passage-list" class="save-print-list">';
			while( $item_passage = mysql_fetch_assoc($passage_in_list) ) {
				echo '<li>';
					echo '<h3 class="passage-title">'.$item_passage['title'].'</h3>';
					echo '<div class="passage-content">'.$item_passage['content'].'</div>';
					$childs = mysql_query("SELECT * FROM `questions` q" . $clause . $in .' AND `q`.`passage`= '. $item_passage['id'] . $orderby);
					if( mysql_num_rows($childs) > 0 ) {
						echo '<ul id="ul-list" class="ul-list">';
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
									//lv-edit-2
									$answer['answer'] = str_replace('\"','"',$answer['answer']);
									//end
									$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
									$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
									$echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
									$echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
									$echo .= '<p>' . $answer['answer'] . '</p>';
									$echo .= ($answer['image'] != '') ? '<p><img src="' . $answer['image'] . '"' . $width . $height . ' /></p>' : '';
									if( $answer['explain'] != '' ) {
										$explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
										// $echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
									}
									$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
								}
								$echo .= '</ul>';
							} else {
								$echo = $item['answers'];
							}
				?>
							<li>
								<div class="ques-text">
									<?php echo $item['question']; ?>
									<p><strong><u>Answer:</u></strong></p>
									<?php echo $echo; ?>
								</div>
								<!--
								<button class="remove-in-list" name="remove_in_list" value="<?php echo $item['id'];?>"><i class="fa fa-times"></i>  Remove</button>
								-->
							</li>
					<?php
							$i++;
						}
						echo '</ul>';
					} else {
						echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
					}
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
		}
			?>
	
		
	</div>		<!-- /.ct_display -->
	
	<script>
		$(document).ready(function(){
			// $('.print_pdf').on('click',function(){
				// $('#ul-list').slideUp(500);
			// });
		});
	</script>
</div>
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>