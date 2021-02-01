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
					require_once('inc/check-role.php');
					$role = checkRole();
					if($role == 0) {
						include("content/admin-distrator.php");
					} elseif($role > 0) {
						include("content/user-distrator.php");
					}
				?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>