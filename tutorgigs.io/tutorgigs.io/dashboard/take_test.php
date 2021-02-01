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
                        <h3>Exam Test</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        
                        
                        <?php 


 ///////////////////
$curr_test_id= $_SESSION['assessment']['assesment_id']; 
$created = date('Y-m-d H:i:s');
$teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id

 //print_r($_SESSION);
$con_1= @mysql_connect('localhost', 'intervenedevUser', 'Te$btu$#4f56#');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections


 if(!isset($_GET['test_id'])){
    exit('Page not found.!');
   }
   
   $test_id = $_GET['test_id'];
 
////////selected test or in_process test id ///

 $sql_select_quest="SELECT * FROM `questions` WHERE `TestID` =".$test_id;
 $results_data=mysql_query($sql_select_quest); // type=math
                    
                 $_SESSION['assessment'] = array();
                    $_SESSION['assessment']['ses_test_id'] =$test_id; 
                    
                    $_SESSION['assessment']['assesment_id'] =$test_id;   // $assesment['assessment_id'];
                    $_SESSION['assessment']['qn_list'] = array();


     while ($row = mysql_fetch_assoc($results_data)) {
  //echo $row['Question']; echo '<br/>';
   $_SESSION['assessment']['qn_list'][] = $row['ID'];

  	}


      $cur_quiz_id=$_SESSION['assessment']['ses_test_id']; // Test ID 
   // get question attempted 
    $attempted="SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$teacher_id." AND `test_id` =".$test_id;
     $total_attempted=mysql_num_rows(mysql_query($attempted));

      $test_sql = "SELECT * FROM `tests` WHERE `ID` =".$test_id;
      $test_result_data = mysql_fetch_object(mysql_query($test_sql));
    
                $test_id=70;

                    /// if Already attempted. ///////////////
                     // TEST Exist of USER 

                    $resume_result = mysql_fetch_assoc(mysql_query('SELECT MAX(num) as last_qn_num FROM students_x_assesments WHERE '
                                    . 'assessment_id = \'' . $test_id . '\' AND '
                                    . 'student_id = \'' . $_SESSION['student_id'] . '\' AND '
                                    . 'teacher_id = \'' . $_SESSION['teacher_id'] . '\' AND '
                                    . 'school_id = \'' . $_SESSION['schools_id'] . '\' '));



                   // $number=($total_attempted>0)?$total_attempted-1:0;
                     $number=($total_attempted>0)?$total_attempted:0;


          
                    
					
                    //if ($resume_result['last_qn_num'] > 0) {  // Resume
                     if($total_attempted>0){
                        ?>
                        <div class="align-center col-md-12">
                
                <div style=" width:auto;" title="">
						 
                        <a href="start_exam_test.php?pos=<?=$number ?>" class="btn btn-green"><button class="form_button submit_button" style="height: 200px;width: 200px;font-size: 30px;">Resume</button></a>
                        </div></div>
						
                    <?php } else {   // START NEW   ?>
                      <div class="align-center col-md-12">
                          <div class="jumbotron" style="background-color: #fff">
  <h2><?php echo $test_result_data->Name;?> Exam Test</h1>
  <span>You are going to start <?php echo $test_result_data->Name;?> Exam Test. To start click on the below button</p>
  <p><a class="btn btn-primary btn-lg" href="start_exam_test.php" role="button">START TEST</a></p>
</div>		 
                        
                                                                                    </div>

                                                                                   
                    <?php
																					
                    }
            
 ?>
         
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