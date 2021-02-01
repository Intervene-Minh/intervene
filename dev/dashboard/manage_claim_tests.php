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
 $p2db = p2g(); // tutor_id test_type quiz_test_id status
  $_SESSION['manage_tutor_sess_id'] = $ses_id;
  

   $tutor_profie = mysql_fetch_assoc(mysql_query(" SELECT * FROM tutor_profiles WHERE tutorid=".$_SESSION['ses_teacher_id']));
  
 $row=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND id=".$ses_id));
  $grade_sql = " SELECT * FROM `terms` WHERE id='".$row['grade_id']."'";
                                    $get_grade = mysql_fetch_object(mysql_query($grade_sql));
                                 
                                    
                                    if(strpos($get_grade->name, 'Math') !== false){
                                       $grade_test = 'Math';
                                    } 
                                    else if(strpos($get_grade->name, 'Reading') !== false || strpos($get_grade->name, 'Writing') !== false){
                                        $grade_test = 'English';
                                    }
                                    
             if(!empty($grade_test) )
                                {
                 
                                    $grade_test_data = mysqli_fetch_object( mysqli_query($p2db,"select *from `tests` where Name = '".$grade_test."'"));
                                    $grade_test_sql = " SELECT * FROM `tutor_tests_logs` WHERE  tutor_id='".$_SESSION['ses_teacher_id']."' AND quiz_test_id='".$grade_test_data->ID."' AND status='completed' AND pass_status='pass'";
                                    $grade_test_result = mysqli_query($p2db,$grade_test_sql);
                                    $is_grade_test_result = mysqli_num_rows($grade_test_result);
                                    $grade_test_result_data = mysqli_fetch_object($grade_test_result);
                                    if(empty($is_grade_test_result))
                                      $_SESSION['manage_grade_test_id'] = $grade_test_data->ID;
                                   
                                }
                              
                                if(!empty($row['bilingual_test']) && empty($row['certificate']) )
                                {
                                    $test_id = 73;
                                    $test_type = 'test';
                                    $_SESSION['manage_bilingual_test_id'] = 73;
                                }
                                else if(!empty($row['certificate']) && empty($row['bilingual_test']))
                                { 
                                    if($row['certificate'] == 2)
                                    {
                                         $certificte = $tutor_profie['teacher_certificate']; 
                                       
                                    }
                                    else if($row['certificate'] == 3)
                                    {
                                         $certificte = $tutor_profie['esl_certificate'];
                                        
                                    }
                                    else if($row['certificate'] == 4)
                                    {
                                         $certificte = $tutor_profie['billingual_certificate'];
                                        
                                    }
                                    
                                    if(empty($certificte))
                                    {
                                        $cer_type = $row['certificate'];
                                            $test_type = 'cer';
                                            $_SESSION['manage_certificate_type'] = $cer_type;
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
                        <h3>Get Qualification</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                       
                        <div class="align-center col-md-12">
                
                      <div style=" width:auto;" title="">
						 
                          <div class="jumbotron" style="background-color: #fff">
  <h3>Satisfy following requirement to claim the session</h3>
 <p>&nbsp;</p>
 <?php if(empty($is_grade_test_result)) { ?>
  <p><a class="btn btn-primary btn-lg" href="take_test.php?test_id=<?php echo $grade_test_data->ID;?>" role="button">Take <?php echo $grade_test;?> Test</a> 
 <?php }
      if($test_type == 'cer') { ?>
      <a class="btn btn-warning btn-lg" href="upload_document.php" role="button">Upload Certification</a>
      <?php } else if($test_type == 'test') { ?>
      <a class="btn btn-primary btn-lg" href="take_test.php?test_id=73" role="button">Take Bilingual(Spanish) Test</a> 
      <?php } ?>
  </p>
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