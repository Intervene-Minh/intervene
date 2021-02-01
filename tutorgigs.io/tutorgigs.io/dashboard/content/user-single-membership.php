<?php
if(isset($_GET['package']) && is_numeric($_GET['package'])){
	$package_id = $_GET['package'];
}else{
	header('Location: membership.php');
	exit;
}
$results = mysql_query("SELECT * FROM `packages` WHERE `id` = $package_id LIMIT 1;");
$item = array();
$title = 'No Package Found!';
if(mysql_num_rows($results) > 0){
	$item = mysql_fetch_assoc($results);
	$title =  $item['name'];
	
}
?>


<div id="list-document" class="content_wrap">
	<div class="ct_heading clear">
		<h3 class="text-uppercase"><?php echo $title;?> Package</h3>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display clear">

		<?php
			if( !empty($item) > 0 ) {
				
				$limited = $item['limited'];
				$price = '$'.$item['price'];
				$description = $item['description'];
				
				if($item['id']==3){
					$limited = 'Unlimited';
				}
				
				echo '<div class="item-listing  clearfix">';
				echo '	<div class="row">
						<div class="col-md-6"><label>Number of questions to print: '.$limited.'</label></div>
						<div class="col-md-6"><label>Price: '.$price.'</label></div>
					</div>
					<div class="row">
						<div class="col-md-12">'.$description.'</div>
					</div>';
				echo '<p></p>';
				echo '<p></p>';
				echo $item['id']!=1?'<div class="row"><div class="pull-left col-md-6"><img src="images/banner_paypal.png"></div></div><div class="col-md-6"><a href="pay-membership.php?action=checkout&package='.$item['id'].'"  class="btn btn-lg btn-primary"">Order Now!</a></div>':'';
				echo '</div>';
				
				echo '<div class="clear"></div>';
			} else {
				echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
			}
			?>
	
		
	</div>		<!-- /.ct_display -->
</div>