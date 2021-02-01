<?php
// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `d`.`name` AS distrator_name, `l` . *
	FROM `distrators` `d`
	INNER JOIN `lessons` `l` ON `d`.`id` = `l`.`distrator_id`");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$childs = mysql_query("
	SELECT `d`.`name` AS distrator_name, `l` . *
	FROM `distrators` `d`
	INNER JOIN `lessons` `l` ON `d`.`id` = `l`.`distrator_id`
	ORDER BY `d`.`name` ASC
	" . $limit
);
?>
<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
		<h3>Distractor Definitions in Document</h3>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">
		<?php
		if( mysql_num_rows($childs) > 0 ) {
			echo '<ul style="list-style: none; padding: 0;">';
			$i = 1;
			while( $item = mysql_fetch_assoc($childs) ) {
		?>
				<li>
					<div class="item-listing clear">
						<a href="javascript: void(0);" class="toggle-detail" style="margin-left: 0;">
							<span style="display: block; float: left; width: 25px;"><?php echo $i; ?>.</span>
							<?php echo $item['distrator_name']; ?>
						</a>
						<div class="item-detail clear fullwith">
							<?php
							if($item['id'] == '')
								echo 'No strategy found!';
							if($item['name'] != '')
								echo '<p>'.$item['name'].'</p>';
							if($item['path_file'] != '') {
								echo '<p><strong>Attachment:</strong> <i class="fa fa-file-text-o" aria-hidden="true"></i>';
								echo ' <a href="'.$item['path_file'].'" target="_blank">Open</a>';
								echo ' <a href="'.$item['path_file'].'" download>Download</a></p>';
							}
							if($item['description'] != '')
								echo $item['description'];
							?>
						</div>
					</div>
				</li>
		<?php
				$i++;
			}
			echo '</ul>';	
		} else {
			echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
		}
		?>
	</div>		<!-- /.ct_display -->
</div>

<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>