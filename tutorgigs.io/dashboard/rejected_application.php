<?php
/****
mysql_query(query,connection)
#  include("student_header.php"); 
// p2g  test.
@  SELECT * FROM `questions` WHERE `TestID` = 5

**/
   include("header.php");

 $passing_percent=70; // 

$con_1= @mysql_connect('localhost', 'ptwogorg_prod', 'aE&ZidJX)8bl');
$pdb=mysql_select_db('ptwogorg_main', $con_1);  //NEW Connections



    $teacher_id=$_SESSION['ses_teacher_id']; // Login tutor id



/**
 $con = mysqli_connect("localhost","mhl397","Developer2!","lonestaar");
    $sql="SELECT *
FROM `gig_teachers`
WHERE `id` = '75'  ";
$result=mysqli_query($con,$sql);
$next_state_url='rejected_application.php';
// Associative array
$row=mysqli_fetch_assoc($result);
$Update=mysqli_query($con," UPDATE `gig_teachers` SET all_state_url='$next_state_url' WHERE id=".$_SESSION['ses_teacher_id']);
//print_r($row);

**/







?>

<link type="text/css" href="css/home-page.css" rel="stylesheet" />

<div id="main" class="clear fullwidth">
    <div class="container">

         





        <div class="row">
        <div id="content" class="col-md-12">
                
                <div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
                    <div class="wizard-v3-content">
                        <div class="wizard-form">
                            <div class="wizard-header">
                                <h3 class="heading">Complete your Registration</h3>
                                <p>Fill entire form field out to go to the next step</p>
                            </div>
                            <form class="form-register" action="" method="post">
                                <div id="xxform-total">
                                         
                           <div class="steps clearfix"><ul role="tablist"><li role="tab" aria-disabled="false" class="first done" aria-selected="false">
                           <a id="form-total-t-0" href="application.php" aria-controls="form-total-p-0"><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-account"></i></span>
                                        <span class="step-text">Application</span>
                                    </div></a></li><li role="tab" aria-disabled="false" class="current" aria-selected="true"><a id="form-total-t-1" href="#form-total-h-1" aria-controls="form-total-p-1"><span class="current-info audible"> </span><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-lock"></i></span>
                                        <span class="step-text">Quiz</span>
                                    </div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-2" href="#form-total-h-2" aria-controls="form-total-p-2"><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-card"></i></span>
                                        <span class="step-text">Interview</span>
                                    </div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-3" href="#form-total-h-3" aria-controls="form-total-p-3"><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                                        <span class="step-text">Payment Info </span>
                                    </div></a></li><li role="tab" aria-disabled="false"><a id="form-total-t-4" href="#form-total-h-4" aria-controls="form-total-p-4"><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                                        <span class="step-text">Legal Stuff </span>
                                    </div></a></li><li role="tab" aria-disabled="false" class="last"><a id="form-total-t-5" href="#form-total-h-5" aria-controls="form-total-p-5"><div class="title">
                                        <span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
                                        <span class="step-text">Training </span>
                                    </div></a></li></ul></div>




                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        <!-- fdfgggggggggg -->
            <div  class="col-md-3"></div>
            <!-- /#sidebar -->
            <div id="content" class="col-md-12">
                <div id="single_question" class="content_wrap">

                    <div class="ct_display clear">                   
                        <form method="post">
                        
                        
                        <h3 class="text-center text-primary"> 
                        Your application has been rejected. <br/>
                         
                       
                         
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>