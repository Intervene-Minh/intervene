<?php

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `option_premium` ;");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared


$results = mysql_query("SELECT * FROM `packages`". $limit." ;");

$result_user = mysql_query("SELECT u.`q_printed`, u.`q_remaining`, p.`limited` FROM `users` u INNER JOIN `packages` p ON u.`role` = p.`id` WHERE u.`id` = {$_SESSION['login_id']} LIMIT 1;");
if(mysql_num_rows($result_user) > 0){
	$user_info =  mysql_fetch_assoc($result_user);
}
?>


<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
		<h3>List all packages</h3>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">

		<?php
			if( mysql_num_rows($results) > 0 ) {
				echo '<ul class="ul-list">';
				$i = 1;
				
				while( $item = mysql_fetch_assoc($results) ) {
					$current_package = '';
					$remaining = ( $user_info['limited'] == 0 ) ? "Unlimited" : $user_info['q_remaining'];
					if( $item['id'] == $_SESSION['login_role'] )
						$current_package = ' (Current Package - Questions Remaining: ' . $remaining . ')';
		?>
				<li>
					<h4 class="text-uppercase"><a href="single-membership.php?package=<?php echo $item['id']?>"><?php echo $item['name'].$current_package?></a></h4>
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
<?php if( mysql_num_rows($results) > 0 ) include("pagination.php"); ?>