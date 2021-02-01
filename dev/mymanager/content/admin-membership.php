<?php
$error='';
//only admin can access this page
$role = checkRole();
if($role!=0){
	header('Location: index.php');
	exit;
}

$sql = "
	SELECT `o`.*, `u`.`first_name`, `u`.`last_name`,`p`.`name` as package_name
	FROM `orders` o
	INNER JOIN `users` u ON `o`.`user_id` = `u`.`id`
	INNER JOIN `packages` p ON `o`.`package_id` = `p`.`id`
	ORDER BY  `o`.`id` DESC 
	;";
$results = mysql_query($sql, $link);
?>

				<div id="folder_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3>Order</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display no_padding clear">
						<?php
						if( mysql_num_rows($results) > 0 ) {
						?>
						<form id="premium-setting-form" action="" method="POST">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">Transaction ID</th>
											<th class="text-center">User</th>
											<th class="text-center">Amount</th>
											<th class="text-center">Package</th>
											<th class="text-center">Date Created</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$i = 1;
									while( $item = mysql_fetch_assoc($results) ) {
										$data = unserialize($item['paypal_info']);
										// echo "<pre>";
										// print_r($data["AMT"]);
										// echo "</pre>";
										echo '<tr>
											<td class="text-center">'.$i.'</td>
											<td class="text-center"><a href="single-order.php?order='.$item['id'].'">'.$item['transection_code'].'</a></td>
											<td  class="text-left"><a href="profile.php?id='.$item['user_id'].'">'.$item['first_name'].' '.$item['last_name'].'</a></td>
											<td  class="text-right">$ '.urldecode($data["AMT"]).'</td>
											<td class="text-right"><a href="single-membership.php?package='.$item['package_id'].'">'.$item['package_name'].'</a></td>
											<td  class="text-right">'.date('F d, Y', strtotime($item['date_created'])).'</td>
										</tr>';
										$i++;
									}
									?>
									</tbody>
								</table>
							</div>
							<div class="clearnone">&nbsp;</div>
						</form>
						<?php
						} else {
							echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
						}
						?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>
<script>
	jQuery(document).ready(function($){
		var check_ajax = false;
		$('.fa.fa-pencil-square-o').on('click',function(){
			alert("Need a page to show order details!");
			return false;
		});
	});
</script>
