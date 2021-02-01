<?php

include("header.php");

if($_SESSION['login_role'] !=0) { //not admin
	header('Location: index.php');
	exit;
}
$order_id = 0;
if(isset($_GET['order']) && is_numeric($_GET['order'])){
	$order_id = $_GET['order'];
}else{
	header('Location: index.php');
	exit;
}

$sql = "
	SELECT `o`.*, `u`.`first_name`, `u`.`last_name`
	FROM `orders` o
	INNER JOIN `users` u ON `o`.`user_id` = `u`.`id`
	WHERE `o`.`id` = $order_id
	;";
$results = mysql_query($sql, $link);

?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3>Order</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
					<?php
						if( mysql_num_rows($results) > 0 ):
						while( $item = mysql_fetch_assoc($results) ):
						
						$status = array('0'=>'Pending','1'=>'Paid','2'=>'Cancelled');
						$package = unserialize($item['package_info']);
						$paypal = unserialize($item['paypal_info']);
					?>
						<div class="table-responsive">
						  <table class="table">
							<tbody>
								<tr>
									<th>Status:</th>
									<td><?php echo $status[$item['status']];?></td>
								</tr>
								<tr>
									<th>First Name:</th>
									<td><?php echo $item['first_name'];?></td>
								</tr>
								<tr>
									<th>Last Name:</th>
									<td><?php echo $item['last_name'];?></td>
								</tr>
								<tr>
									<th>Package Name:</th>
									<td><?php echo $package['name'];?></td>
								</tr>
								<tr>
									<th>Amount:</th>
									<td><?php echo '$'.urldecode($paypal["AMT"]);?></td>
								</tr>
								<tr>
									<th>Transection Code:</th>
									<td><?php echo $item['transection_code'];?></td>
								</tr>
								<tr>
									<th>VAT:</th>
									<td><?php echo $item['vat'];?></td>
								</tr>
								<tr>
									<th>Date Created:</th>
									<td><?php echo date('F d, Y', strtotime($item['date_created']));?></td>
								</tr>
							</tbody>
						  </table>
						</div>
					<?php
						endwhile;
						else:
					?>
						<div class="item-listing clear"><p>There is no item found!</p></div>
					<?php
						endif;
					?>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>

<?php include("footer.php"); ?>