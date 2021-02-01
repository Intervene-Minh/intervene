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

$passage_id = 0;
if(isset($_GET['passage']) && $_GET['passage']!="")$passage_id = $_GET['passage'];

if($clause==""){
	$passage_in = " WHERE q.`passage`=$passage_id"; 
}else{
	$passage_in = " AND q.`passage`=$passage_id";
}

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `questions` q" . $clause . $passage_in);		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'date_created';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

$childs = mysql_query("SELECT * FROM `questions` q" . $clause . $passage_in . " ORDER BY `" . $order_by . "` " . $order . " " . $limit);

if($passage_id!=0){
	$result_passage = mysql_query("SELECT * FROM `passages` p WHERE `id` = $passage_id ORDER BY `date_created` DESC");
	$this_passage = mysql_fetch_assoc($result_passage);
	$title = $this_passage['title'];
}
?>

<div id="folder_wrap" class="content_wrap">
	<div class="ct_heading clear">
		<h3><?php echo $title; ?></h3>
		<ul>
			<li><a href="single-question.php?action=new"><i class="fa fa-plus-circle"></i></a></li>
			<li><a href="#"><i class="fa fa-arrow-right"></i></a></li>
			<li><a href="javascript: void(0);" id="edit-question"><i class="fa fa-pencil-square-o"></i></a></li>
			<?php if( isGlobalAdmin() ) : ?>
				<li><a href="javascript: void(0);" class="remove_items" data-type="question"><i class="fa fa-trash"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display no_padding clear">
		<?php
			if($passage_id!=0){
				echo '<div class="ct_display">';
				// echo '<h2>'.$this_passage['title'].'</h2>';
				echo $this_passage['content'];
				echo '</div>';
			}
		?>
		
		<div class="item-listing no_padding clear">
			<div class="col-md-1">&nbsp;</div>
			<div class="col-md-4 align-center">
				<h4>
					Question Name
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=name&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="col-md-4 align-center">
				<h4>
					Spanish
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=question_spanish&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="col-md-3 align-center">
				<h4>
					Publication
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=public&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="clearnone fullwith">&nbsp;</div>
		</div>
		
		<?php
		if( mysql_num_rows($childs) > 0 ) {
			$i = 1;
			while( $item = mysql_fetch_assoc($childs) ) {
				$line = ($i % 2 == 0) ? ' second' : '';
				if( $item['type'] == 1 ) {
					$echo = '<ul class="list-answers">';
					//lv-edit 04/05/2016
					$lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers']);
					$answers = unserialize($lv_answers);
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
							$echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
						}
						$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
					}
					$echo .= '</ul>';
				} else {
					$echo = $item['answers'];
				}
		?>
				<div class="item-listing clear<?php echo $line; ?>">
					<div class="col-md-1">
						<input type="checkbox" name="questions[]" class="edit_items" value="<?php echo $item['id']; ?>" title="Edit Question (<?php echo $item['name']; ?>)" />
					</div>
					<div class="col-md-9">
						<a href="javascript: void(0);" class="toggle-detail"><?php echo $item['name']; ?></a>
						<div class="item-detail clear fullwith">
							<?php echo $item['question']; ?>
							<p><strong><u>Answer:</u></strong></p>
							<?php echo $echo; ?>
							<div class="clearnone">&nbsp;</div>
						</div>
					</div>
					<div class="col-md-2 align-center">
						<i class="fa fa-<?php echo $item['public'] ? 'check' : 'minus'; ?>" aria-hidden="true"></i>
						
					</div>
					<div class="clearnone fullwith">&nbsp;</div>
				</div>
		<?php
				$i++;
			}
		} else {
			echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
		}
		?>
		<div class="clearnone">&nbsp;</div>
	</div>		<!-- /.ct_display -->
</div>		<!-- /#folder_wrap -->

<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>