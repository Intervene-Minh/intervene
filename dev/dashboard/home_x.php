<?php 
/*
After login :Landing page
**/
include("header.php"); 

//print_r($_SESSION); 
$_SESSION['is_applicant_tutor']=1; // a new applicant become a tutor after Training. 

$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));

//Signup State/////////////
  $get_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($tutor_det);

  
 if($tutor_det['all_state'])
$_SESSION['ses_access_website']=$tutor_det['all_state']; // "yes"==if all 4 step completed by user



////////Acces website-All step completed by Tutor///
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
                        
                        
			<div id="content" class="col-md-8">
				<div class="content_wrap">
					<div class="ct_heading clear">
                                              <h3>Home</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<div class="item-listing clear">
							<h3 class="notfound align-center">
								<a href="#">Welcome
                                                                   <?= $_SESSION['login_user']?>.</a>
							</h3>
                                                   <?php 
                                                   //var_dump($_SESSION);
                                                   ?> 
                                                    
                                                    
						</div>
					</div>
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<?php  print_r($_SESSION);?>

<?php  //include("footer.php"); ?>