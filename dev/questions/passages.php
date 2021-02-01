<?php
include("header.php");
?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
					/*$role==0=>admin    $role==1=>user*/
					require_once('inc/check-role.php');
					$role = checkRole();
					if($role==0){
						include("content/admin-passage.php");
					}else{
						if($role>0)include("content/user-passage.php");
					}
				?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<div class="list-fixed">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">A problem has been added (<span class="number">0</span> problems total)</div>
	</div>
</div>
<?php include("footer.php"); ?>