<?php
/**@front:page
 * **/
include("header.php");
//print_r($_SESSION);


 //echo  $_SESSION['is_passage'].'=========' ; 


?>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
                        
			<div id="sidebar" class="col-md-4">
				<?php 
				 if(isset($_SESSION['demo_user_id'])&&$_SESSION['demo_user_id']>0){
				  include("demo_sidebar.php");  //DemoUser Sidebar

				}else{
					include("sidebar.php"); }
				

				?>


			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
                               
				if(isset($_SESSION['is_passage']) && $_SESSION['is_passage']){
				include("content/save&print-passage.php");
				}else{
					include("content/save&print.php");
				}?>
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
		<div class="text"><a href="active-user.php">Please confirm your emaill address to print!</a></div>
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