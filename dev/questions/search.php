<?php
/***
@ Demo User // demo_user_id 
@ Teacher User

**/

//////////
include("header.php");

//print_r($_SESSION);




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

			</div>	
				<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
				 if(isset($_SESSION['demo_user_id'])&&$_SESSION['demo_user_id']>0){
				 	// echo 'Demo user Search page';  // demo_search.php
				 	include("content/demo_search.php");
				 }else{
				 	include("content/search.php");

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