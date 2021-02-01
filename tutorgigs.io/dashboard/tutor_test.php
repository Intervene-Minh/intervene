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

 include('inc/sql_connect.php');
 $p2db = p2g();
 $testList = mysqli_query($p2db, "SELECT * FROM `tests` WHERE `Name` != 'Training Test' AND IsActive=1"); //1
?>

<style>
    .feature-list-card .clock_time {
    position: absolute;
    top: -7px;
    left: -19px;
    transform: rotate(-45deg);
    overflow: hidden;
}
.feature-list-card .clock_time div {
    background-color: orange;
    color: #fff;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    width: 100px;
    text-align: center;
}
</style>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Exam Test</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        
			
                      <div class="align-center col-md-12">
                          <div class="jumbotron" style="background-color: #fff;padding-left: 0px;padding-right: 0px;padding-top: 0px">
  <h2>Tutor Exam Test</h1>
  <span> Please click on any below button to have a exam test</p>
  <p>
  <div class="row" style="margin-top: 7%">
          <?php while ( $tRecord = mysqli_fetch_array($testList) ) {
              
              $result = mysqli_query($p2db, "SELECT * FROM questions WHERE TestID=".$tRecord['ID']) ;
              $numQuestions = $result->num_rows;
              
              if($numQuestions > 0) {
                  
                   $sql = " SELECT * FROM `tutor_tests_logs` WHERE tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id = '".$tRecord['ID']."'";
                   $test_result = mysqli_fetch_object(mysqli_query($p2db,$sql));
                   if($test_result->status == 'completed' && $test_result->pass_status == 'pass')
                   {
                        $bg_color = '#337ab7';
                        $status_text = 'Completed';
                        $mark_color = 'green';
                   }
                   else if($test_result->status == 'completed' && $test_result->pass_status == 'fail')
                   {
                        $bg_color = '#337ab7';
                        $status_text = 'Failed';
                        $mark_color = 'red';
                   }
                   else if($test_result->status == 'in_process')
                   {
                       $bg_color = '#337ab7'; 
                       $status_text = 'In Progress';
                       $mark_color = 'orange';
                   }
                   else
                   {
                       $bg_color = '#337ab7'; 
                       $status_text = '';
                   }
          ?>
          
          <div class="col-md-4 "><div class="jumbotron feature-list-card" style="padding: 30px;background-color: <?php echo $bg_color;?>">
               <?php if(!empty($status_text)) { ?>
                  <div class="clock_time" >
                                            <div style="background-color:<?php  echo $mark_color;?> !important">
                                                <?php echo ucwords($status_text);?>
                                            </div>
                                        </div>
               <?php }  ?>
                  <?php if($test_result->status == 'completed' && $test_result->pass_status == 'fail') { ?>
                  <h4 style="color:#fff;font-size: 18px"><?php echo $tRecord['Name'];?> </h4></div> </div>
<?php }  else { ?>     
       <a href="take_test.php?test_id=<?php echo $tRecord['ID'];?>"><h4 style="color:#fff;font-size: 18px"><?php echo $tRecord['Name'];?> </h4></a></div> </div>
       <?php } ?> 
 <?php } ?>    
        <?php } ?>        
                        
 </div>                  
  </p>
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