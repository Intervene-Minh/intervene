<?php






include("header.php");

include('libraries/newrow.functions.php');

// warning message

$warning_msg=[];

$success_msg=[];











///////////////////////////////////////////

 

if ($_SESSION['login_role'] != 0) { //not admin

    header('Location: folder.php');

    exit;

}



if(isset($_SESSION['ses_admin_token'])&&!empty($_SESSION['ses_admin_token'])){

  $demo='test';

	//echo 'TOKEN>>', $_SESSION['ses_admin_token']; die; 

}

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

                        <h3><i class="fa fa-plus-circle"></i>

                          Edit Session</h3>

                    </div>		<!-- /.ct_heading -->

                    <div class="ct_display clear">

                        <p> <div class="alert alert-success" role="alert" style="text-align: center"><strong>Your session has been edited successfully</strong></div></p>
                        
                        
                            
                            
                        <p>
                            <a href="intervention_list.php" class="btn btn-primary"> Go to session list </a>
                            
                        </p>

                        <div class="clearnone">&nbsp;</div>

                    </div>		<!-- /.ct_display -->

                </div>

            </div>

			

			<div class="clearnone">&nbsp;</div>

		</div>

	</div>

</div>		<!-- /#header -->




<?php include("footer.php"); ?>