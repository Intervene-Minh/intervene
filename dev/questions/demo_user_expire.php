<?php
include("header.php");

if (isset($_POST['submit-renewal'])) {
    $data = $_POST['renewl'];
    if($data == 'purchase_now'){
       // $url = "https://docs.google.com/forms/d/e/1FAIpQLSfhCNWnfrxIDxXw_Q5bgz_Jvwd7suOhj34DqBhh8eQgvKjq4Q/viewform";
          // Demo Purchase Link 
        $url='https://intervene.io/purchaseform.php';


         header("location: $url");exit;

    }elseif($data == 'into_dist'){
        header("location: send_mail.php"); exit;
    }elseif($data == 'schedule_apmnt'){
        $url = "https://calendly.com/intervene/purchase/01-22-2018";
         header("location: $url"); exit;
    }      
}
?>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("demo_sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="folder_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3><a class="open-renewal" href="javascript:;">Trial Expired: Click Here For Full Access</a></h3>
						
					</div>		<!-- /.ct_heading -->
					
					<div class="ct_display clear">
                                            <h1><a class="open-renewal" href="javascript:;">Trial Expired: Click Here For Full Access</a></h1>
                                            
                                            <div class="clear"></div>
                                            <div class="renewal" id="active-form" style="display:none;">
                                               <form name="rewal-form" action="" method="POST">
                                                   <input type="radio" name="renewl" value="purchase_now">Purchase now <br>
                                                   <input type="radio" name="renewl" value="into_dist">Introduce us to your district or administrator<br>
                                                   <input type="radio" name="renewl" value="schedule_apmnt">Schedule appointment to discuss <br>
                                                   <input type="submit" class="btn btn-success" name="submit-renewal">
                                               </form>
                                            </div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script>
    $(document).ready(function () {
        $('a.open-renewal').on('click', function () {
            document.getElementById('active-form').style.display='block';
        });
    }); 
</script>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>