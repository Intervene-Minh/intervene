<?php
include("header.php");
//echo '========@@';

$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

// Count children of current taxonomy. Redirect to questions.php if no item found
$count = mysql_num_rows( mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `parent` = {$termId}") );	# Total of records
if( $count == 0 ) {
	header('Location: questions.php?taxonomy=' . $termId);
	exit;
}

// Setup title
$select = mysql_fetch_assoc( mysql_query("SELECT `name` FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `id` = {$termId}") );	# Return @boolean false if not found
$title	= $select ? $select['name'] : 'List Objective';

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$childs = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `parent` = " . $termId . $limit);
?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="objective_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3><?php echo $title; ?></h3>
						<ul>
							<li><a href="javascript: void(0);" class="popup_form" data-target="#objective_dialog" title="New Objective"><i class="fa fa-plus-circle"></i></a></li>
							<li><a href="javascript: void(0);" class="popup_edit" data-target="#objective_dialog"><i class="fa fa-pencil-square-o"></i></a></li>

							<?php // if( isGlobalAdmin() ) : ?>
								<!-- <li><a href="javascript: void(0);" class="remove_items" data-type="objective"><i class="fa fa-trash"></i></a></li> -->
							<?php   //endif; ?>

					<li><a href="javascript: void(0);" class="remove_items" data-type="objective"><i class="fa fa-trash"></i></a></li>


						</ul>
					</div>		<!-- /.ct_heading -->
					
					<div class="ct_display no_padding clear">
						<?php
						$i = 1;
						while( $item = mysql_fetch_assoc($childs) ) {
							$line = ($i % 2 == 0) ? ' second' : '';
							$url = 'objective.php?taxonomy=' . $item['id'];
						?>
							<div class="item-listing clear<?php echo $line; ?>">
								<p>
									<input type="checkbox" class="edit_items" value="<?php echo $item['id']; ?>" data-parent="<?php echo $item['parent']; ?>" data-desc="<?php echo $item['description']; ?>" title="Edit Objective (<?php echo $item['name']; ?>)" />
									<a class="item_name" href="<?php echo $url; ?>"><?php echo $item['name']; ?></a>
								</p>
								<p><?php echo $item['description']; ?></p>
							</div>
						<?php
							$i++;
						}
						?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>		<!-- /#objective_wrap -->
				
				<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
<?php ob_flush(); ?>