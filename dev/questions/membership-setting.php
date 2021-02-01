<?php
include("header.php");

$error='';
//only admin can access this page
$role = checkRole();
if($role!=0){
	header('Location: index.php');
	exit;
}

$results = mysql_query("SELECT * FROM `packages`", $link);
?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="folder_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3>Packages</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display no_padding clear">
						<?php
						if( mysql_num_rows($results) > 0 ) {
							$i=1;
							?>
						<form id="premium-setting-form" action="" method="POST">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">Name</th>
											<th class="text-center">Limited</th>
											<th class="text-center">Price</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
							<?php	
								while( $item = mysql_fetch_assoc($results) ) {
									echo '
									<tr>
										<td class="text-center">'.$i.'</td>
										<td  class="text-left">'.$item['name'].'</td>
										<td  class="text-right">'.$item['limited'].'</td>
										<td class="text-right">$'.$item['price'].'</td>
										<td class="text-center"><a href="single-membership.php?package='.$item['id'].'"><i class="fa fa-pencil-square-o"></i></a></td>
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
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script>
	jQuery(document).ready(function($){
		var check_ajax = false;
		
	});
</script>
<?php include("footer.php"); ?>