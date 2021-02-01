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
				if(isset($_SESSION['is_passage']) && $_SESSION['is_passage']){
					include("content/arrange-passage.php");
				}else{
					include("content/arrange.php");
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
		<div class="text">A problem has been removed (<span class="number">0</span> problems total)</div>
	</div>
</div>
<?php 
if(isset($_SESSION['list'])):
	require_once('inc/check-status.php');
	$status = checkStatus();
	if($status==0):
?>
<div class="alert-q-remaining" style="display:block">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">Please confirm your emaill address to print!</a></div>
	</div>
</div>
<?php endif;endif;?>
<script>
$('.alert-q-remaining .fa.fa-times').on('click',function(){
	$(this).parents('.alert-q-remaining').first().hide(500);
});
</script>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>