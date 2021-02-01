<?php
/****
 $start_date = date('Y-m-d h:i:s');
 * @students
 * @students :: list 
 @ pending_assessments.php
 @ In Progress :: assessment of students 
 @ Assigned
 @ Completed
  Asssessment Name 
  Status 
  Teacher -
  Result
  # Students :teacher_id school_id grade_level_id class_id created
 * **/
 // echo 'Time:'.$start_date = date('Y-m-d h:i:s');

//include("student_header.php");

  include('inc/connection.php'); 
session_start();
  ob_start();


if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}
 unset($_SESSION['assessment']);
 //print_r($_SESSION);

  $board_url='session-board.php';
  


  $page_name='Session Board';
 $no_record='No record found.!';
 /////////////Session Acttivy /////////////////
//$live_sesid= $_SESSION['live']['live_ses_id']=1355;

$live_sesid= $_SESSION['live']['live_ses_id'];

 $sql_ses_activity_and_url='SELECT ses.id as ses_id,ses.lesson_id,m.*
FROM `int_schools_x_sessions_log` ses
INNER JOIN  master_lessons m
ON ses.lesson_id=m.id
WHERE ses.id='.$live_sesid;
$actvity_det=mysql_fetch_assoc(mysql_query($sql_ses_activity_and_url));
//print_r($actvity_det);
$curr_actvity_url=$actvity_det['url'];



///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='Pending ==pending_assessments.php';
 // print_($_SESSION);
// $sql="SELECT * FROM `teacher_x_assesments_x_students` WHERE `teacher_id` = '".$student_det['teacher_id']."' AND `student_id` =".$_SESSION['student_id'];
// $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
// Left Join assessments a ON  sa.assessment_id=a.id
// WHERE sa.teacher_id = '365' AND sa.student_id= '10748' ";

$student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];// 

$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";


$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
 // $teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE id=".$student_det['teacher_id']));
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}






/////////////Display
//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    is_activity_move();
//var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php"; 
function is_activity_move(){

  console.log('is_activity_move===');// is_activity_move

    // var url_1="https://tutorgigs.io/dashboard/notify_refresh2.php";  
     // var url_1="https://intervene.io/questions/test_ajax.php"; 
   
      var url_1="student_pendings_ajax.php";
      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'student_actvity' }, 
            dataType: 'json',
            success: function (data) { 
            
            var str=' Test info='+data.url_redirect;

             //console.log(str);
             console.log('Sent_from='+data.url_redirect+'status='+data.status);
                //console.log('url_redirect='+data.url_redirect);
             var move_url='';
             
             //console.log('sent_from='+data.sent_from);
             move_url=data.url_redirect;
             if(data.status=='OK'&&data.url_redirect=='student_board.php'){
              
              window.location.href =move_url;//actvity.php |student_board.php
              
             }
             // console.log(data.is_redirect+'=is_redirect');  // url_redirect
             //str+=data.length;
           // Display modal
           $("#items_id").html(str);
          // $("#myModal").modal('show'); 


        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    }); 

       setTimeout(function(){
      is_activity_move();},3000);

}


</script>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <p class="text-center" style="display: none;"  id="items_id">Test info </p>
    <div class="container99" style="text-align: center;">
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:5px; ">
                
               <!--  <div style=" width:auto;" title=""> -->
                 <?php //include("nav_students.php"); ?>
      
                    <!--   </div>   -->  





            </div>
           <!--  NExt col -->
           <?php 
           // https://www.youtube.com/embed/tgbNymZ7vqY
          // $url=';'


          // TITU START //
           
           if(!empty($curr_actvity_url)){
           ?>

           <div class="align-center">

     
        
        <iframe  style="width:100%; height:100%" src="<?php echo $curr_actvity_url;?>">
            </iframe>

        
        
        

                      
            </div>

            <?php }else 


            {?>



<script>window.location.href ='student_board.php'</script>;
<?php } 

//TITU END 
            ?>



           <!-- C0ntent -->



        </div>
    </div>
</div>

<?php ob_flush();
 //print_r($_SESSION);
 ?>

