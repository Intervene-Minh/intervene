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


// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}

$error='';
$id = $_SESSION['ses_teacher_id'];

$res= mysql_query("SELECT * FROM gig_teachers WHERE id='$id'");
if(isset($_POST['submit_data'])){

if(mysql_num_rows($res)==1){
$notify_all=$_POST['notify_all'];
$notify_msg =$_POST['notify_msg'];
$notify_jobs=$_POST['notify_jobs'];

    $query= mysql_query("UPDATE gig_teachers SET notify_all='$notify_all',notify_jobs='$notify_jobs',notify_msg='$notify_msg' where id='$id'");
    //echo $query;exit;
  }
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
                        <h3>Profile</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			 <?php $check_query = mysql_query("SELECT * FROM gig_teachers where id='$id'");
					  $check_res = mysql_fetch_assoc($check_query);
				?>



                         <div class="add_question_wrap clear fullwidth">
								<p>
									<input type="checkbox" value="yes"<?php if($check_res['notify_all'] == 'yes'){ echo 'checked';}?> id="notify_all" name="notify_all" style="vertical-align: sub;">
									<label for="question_public" style="font-weight:bold;">Select All</label>
								</p>
       
								<p>
								<input type="checkbox"  value="yes"<?php if($check_res['notify_jobs']== 'yes'){ echo 'checked';}?> class="case" name="notify_jobs" style="vertical-align: sub;">
								<label for="smart_prep" style="font-weight:bold;">Job</label>
                                                                        
                                 <input type="checkbox" value="yes"<?php if($check_res['notify_msg']== 'yes'){ echo 'checked';}?> class="case" name="notify_msg" style="vertical-align: sub;">
								 <label for="data_dash" style="font-weight:bold;">Message</label>
								</p>
								<div class="clear">&nbsp;</div>
                                 <button type="submit" id="profile-submit"  style=" background-color:orange;
                                            color:#fff; border:1px solid orange" class="btn btn-default"
                                  name="submit_data">Save</button>

                                 <!-- <button type="submit" name="submit_data"  value="1187">ClaiJob</button> -->
                                </div> 
							</div>
			 
                        </form>
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