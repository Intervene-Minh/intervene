<?php include("header.php"); ?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("demo_sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<?php 
					/*$role==0=>admin    $role==1=>user*/
					require_once('inc/check-role.php');
                                       // echo $_SESSION['demo_login_role']; die;
					$role = checkRole();

					//echo  $role.'=======' ; die; 
					if($role==0){
						include("content/admin-question.php");
					}elseif($role>0){
						// echo 'content/user-question.php'; die; 
						include("content/user-question.php");
                                        }elseif(!empty($_SESSION['demo_user_id'])){
                                        	//echo 'content/demo_user-question.php'; die; 
                                            include("content/demo_user-question.php");
                                        }



				?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php include("footer.php"); ?>