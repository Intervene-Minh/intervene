<?php
// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `lessons`");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$childs = mysql_query("
	SELECT `l`.*, `d`.`name` as distrator_name
	FROM `lessons` l
	INNER JOIN `distrators` d ON `d`.`id` = `l`.`distrator_id`
	ORDER BY `date_created` DESC
	" . $limit
);
?>
<div id="folder_wrap" class="content_wrap">
	<div class="ct_heading clear">
		<h3>Distractor Definitions</h3>
		<ul>
			<li><a href="single-strategy.php"><i class="fa fa-plus-circle"></i></a></li>
			<li><a href="javascript: void(0);" id="edit-strategy"><i class="fa fa-pencil-square-o"></i></a></li>
			<?php if( isGlobalAdmin() ) : ?>
				<li><a href="javascript: void(0);" class="remove_items" data-type="lesson"><i class="fa fa-trash"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display no_padding clear">
		<?php
		if( mysql_num_rows($childs) > 0 ) {
			$i = 1;
			while( $item = mysql_fetch_assoc($childs) ) {
				
		?>
				<div class="item-listing clear<?php echo $line; ?>">
					<input type="checkbox" name="lessons[]" class="edit_items" value="<?php echo $item['id']; ?>" title="Edit Lesson (<?php echo $item['name']; ?>)" />
					<a href="javascript: void(0);" class="toggle-detail"><?php echo $item['name']; ?></a>
					<div class="item-detail clear fullwith">
						
						<?php 
							echo '<p><strong>Distrator:</strong> '.$item['distrator_name'].'</p>';
							
							if($item['path_file']!=''){
							echo '<p><strong>Attachment:</strong> <i class="fa fa-file-text-o" aria-hidden="true"></i>';
							echo ' <a href="'.$item['path_file'].'" target="_blank">Open</a>';
							echo ' <a href="'.$item['path_file'].'" download>Download</a></p>';
							}
							
							echo '<p><strong>Description :</strong> '.$item['description'].'</p>';
						?>

						<div class="clearnone">&nbsp;</div>
					</div>
				</div>
		<?php
				
			}
		} else {
			echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
		}
		?>
		<div class="clearnone">&nbsp;</div>
	</div>		<!-- /.ct_display -->
</div>		<!-- /#folder_wrap -->

<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>