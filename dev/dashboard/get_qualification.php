<?php
// @ list-tutor-sessions
/*****
 @ filer by : date selected. 
 * ****/


// List of Teachers 
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");
include("user-class.php");
//////////Validate Site Access//////////
//print_r($_SESSION);
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}
/////////////////////////////////////
  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";

unset($_SESSION['manage_certificate_type']);
unset($_SESSION['manage_tutor_sess_id']);
unset($_SESSION['manage_grade_test_id']);
unset($_SESSION['cer_complete']);
unset($_SESSION['cer_complete']);

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
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
                        <h3>Get Qualification</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                       
                        <div class="align-center col-md-12">
                
                      <div style=" width:auto;" title="">
						 
                          <div class="jumbotron" style="background-color: #fff">
  <h2>Get Qualified More Subject</h2>
 <p>&nbsp;</p>
  <p><a class="btn btn-primary btn-lg" href="tutor_test.php" role="button">Take a Test</a> <a class="btn btn-warning btn-lg" href="upload_document.php?act=tut" role="button">Upload Certification</a></p>
</div> 
                      </div>
                        
                        </div>
			
	           </div>
			
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

	<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
//select all checkboxes
$("#notify_all").change(function(){  //"select all" change 
	var status = this.checked; // "select all" checked status
    //alert(status);
	$('.case').each(function(){ //iterate all listed checkbox items
		this.checked = status; //change ".checkbox" checked status
	});
});

$('.case').change(function(){ //".checkbox" change 
	//uncheck "select all", if one of the listed checkbox item is unchecked
	if(this.checked == false){ //if this item is unchecked
		$("#notify_all")[0].checked = false; //change "select all" checked status to false
	}
	
	//check "select all" if all checkbox items are checked
	if ($('.case:checked').length == $('.case').length ){ 
		$("#notify_all")[0].checked = true; //change "select all" checked status to true
	}
});


</script>
<?php include("footer.php"); ?>