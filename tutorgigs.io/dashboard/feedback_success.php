<?php
/**
 * @feedback_form_2
 * @ some question from db 
 * @ some on page .
 * **/
///////////////////////////
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
$created = date('Y-m-d H:i:s');
$page_name="Session Completion Form: Student Behavior";
//////////////////////////
$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
$error = '';

function checkmydate($date) {
    $tempDate = explode('-', $date);
    // checkdate(month, day, year)
    return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
}




 $curr_time= date("Y-m-d H:i:s"); #currTime
  $error=" Your response has been recorded.";
  unset($_SESSION['form_2']); unset($_SESSION['form_3']); 
unset($_SESSION['form_4']); unset($_SESSION['form_5']);  
  unset($_SESSION['form_1']); unset($_SESSION['feedback_ses_id']);
// $_SESSION['feedback_ses_id']


  
// back
 $back_url="sessions-listing.php";
if (isset($_POST['back'])) {
        header("location:$back_url"); exit;
}










// required textbox feedback
?>
<style>
 
 label.feedback{ color: #000;
font-size: 20px;
line-height: 135%;
width: 100%;}
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
                        <h3><i class="fa fa-plus-circle"></i><?=$page_name?></h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post" action="" enctype="multipart/form-data">
                            <h4>We need your feedback to improve the system for Tutors and Students. Please take a moment to share.</h4>
                            <div class="add_question_wrap clear fullwidth">
                             
                                
                                
                             
                                
                                
                               
                            
                                
                                
                                
                                
                                
                              
                                
                                
                                
                                
                                
                                
                                 <p id="stu_engaged_area">
                          <label class="feedback" for="engaged_not_info">
                              Your response has been recorded.</label>
             
                                
                                 
                                 
                                 </p> 
                                
                                
                                
                               
                                
                            </div>
                            <p>
               <input type="submit" name="back" id="lesson_submit" class="form_button submit_button" value="BACK,Listing" />            
                            
                            </p>
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>


  ///   For selecting Area at 
    $(function () {
        $('input[name="stu_engaged"]').on('click', function () {
            if ($(this).val() == 'yes') {
                
                $('#stu_engaged_area').hide();
            } else {
                $('#stu_engaged_area').show();
               
            }
            
            

        });
    });

</script>

<?php include("footer.php"); ?>
