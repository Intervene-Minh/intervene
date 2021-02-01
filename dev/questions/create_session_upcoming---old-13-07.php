<?php
/*
@ Add tutoing details
**/
/////////////////////////////////

$test_message=[];

$step_2_tutoring_url='create_session_step2.php';
/////////////////////////////////////
$tab_sessions='int_schools_x_sessions_log'; # intervenetion and homework_help
$tab_ses_stuents='int_slots_x_student_teacher'; # student list in  intervention
$tab_ses_quiz_answer='students_x_quiz'; # sesion student quiz answer

$client_id='Intervene123456';
 







$district_wise_school_list="SELECT s.district_id, count( s.SchoolId ) AS totsc, d.id, d.district_name
FROM `schools` s
LEFT JOIN loc_district d ON s.district_id = d.id
WHERE s.district_id >0
GROUP BY s.district_id
ORDER BY d.district_name";


$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$today = date("Y-m-d H:i:s"); // 
$msg=array();
$msg_error=array();


include("header.php");



include('libraries/newrow.functions.php');



///////////////////////////////////////////
 
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

  //print_r($_SESSION);
/////////////////////////////////////////////
$current_date = date("Y-m-d H:i:s"); // 


$sql_tutor=" SELECT id,f_name,lname,email FROM gig_teachers WHERE notify_all='yes' OR notify_jobs='yes' ";
    $resul_tutor= mysql_query($sql_tutor);
    $arr_notify_tutors=array();// tutor list
    while ($line=mysql_fetch_assoc($resul_tutor)) { 
      $arr_notify_tutors[]=$line;
    }
 

    function send_job_email($email,$to,$message,$f_name){
 // $to = "isha@srinfosystem.com";
$subject = "New Job found- Tutorgigs.io";
  // learn@intervene.io
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@tutorgigs.io>' . "\r\n";
 //$headers .= 'Cc: rohit@srinfosystem.com' . "\r\n";
 if(mail($to,$subject,$message,$headers)) return true;
 else return false;
 

}
///
#Set Test case for function db
// $isResponseLogOn=1;




///////////////////////////
function Duplicate_tutoring($selected_date,$tuoringId)
{  $message=[]; global $isResponseLogOn;
   global $current_date;

$TutoringId=$tuoringId; // 3581 , 3580
# Duplicate function
$Tutoring_sql=" SELECT * FROM int_schools_x_sessions_log WHERE id=".$TutoringId;
$Tutoring1=mysql_query($Tutoring_sql);

$Tutoring= mysql_fetch_assoc($Tutoring1);
@extract($Tutoring);

$selected_date=$selected_date;  // date('Y-m-d');

 // echo  $selected_date; die;
$hh =$timmes['hours']=date('H', strtotime($ses_start_time));
$ii=$timmes['min']=date('i', strtotime($ses_start_time));

$timmes['ses_start_time']=$ses_start_time;





  $session_date_ymd=$selected_date;

 $ses_start_time_1= $start_time= date('Y-m-d H:i', strtotime('+' . $hh . 'hour +' . $ii . ' minutes', strtotime($session_date_ymd))); 

    $ses_end_time1= date('Y-m-d H:i', strtotime('+50 minutes', strtotime($start_time)));
    $activity_time= date('Y-m-d H:i', strtotime('-5 minutes', strtotime($start_time)));

$data['start_time']=$start_time;
$data['ses_end_time1']=$ses_end_time1;
$data['activity_time']=$activity_time;
// ses_start_time

$data['ses_start_time_1']=$ses_start_time_1;
// start_date
$data['start_date_1']=$session_date_ymd;


//



  $client_id='Intervene123456'; //

  $sql_session= " INSERT INTO int_schools_x_sessions_log SET Tutoring_client_id='$client_id',curr_active_board='$curr_active_board',board_type='$curr_active_board',type='intervention',activity_start_time='$activity_time',
  ses_start_time='$ses_start_time_1',"
                            . "ses_end_time='$ses_end_time1',start_date='".$data['start_date_1']."', "
                   . "school_id='$school_id',lesson_id='$lesson_id',
                   quiz_id='$quiz_id',grade_id='".$grade_id."', "
                            . "created_date='$current_date',teacher_id='1999',district_id='$district_id' ";

                              $Add=mysql_query($sql_session);
                             $TutoringNewId= mysql_insert_id();
                             if($TutoringNewId>0){
                             $message[]='New Duplicate session created-ID:'.$TutoringNewId;
                             }



// NewId
$slot_id1=$TutoringNewId; // new session id 

$Sql_tutoring_student=" SELECT *
FROM int_slots_x_student_teacher
WHERE slot_id=".$TutoringId;

                  $Tutoring_students=mysql_query($Sql_tutoring_student);
                  $i=0;
                  $test_students_str='';

            while ($line=mysql_fetch_assoc($Tutoring_students))
            {  
                

                @extract($line);
                $test_students_str.=':sid:'.$student_id;

                $msg_query1=mysql_query(" INSERT INTO int_slots_x_student_teacher 
                (slot_id, type,student_id,int_teacher_id, int_school_id,created_date,quiz_id,tut_teacher_id,quiz_status) 
                VALUES('$slot_id1','$type',
                '$student_id','$int_teacher_id', '$int_school_id','$created_date',
                '$quiz_id','$tut_teacher_id','$quiz_status' )");
                $i++;
            }

   //  student added to new sesson 
            $message[]='List of student- '.$test_students_str;
            $message[]=$i.'-- student added to session ID::'. $TutoringNewId;  

              if($isResponseLogOn==1)
              {
                    echo $isResponseLogOn ,':isResponseLogOn::--<pre>';
                    print_r($message);
                    die; 

              }
 

  //   return      
  return $TutoringNewId; // new tutoring
  
  
}



















/*
[select_date]
*/
///////Create Intervention Session //////
if (isset($_POST['create_submit']))
{  

    // echo '<pre>';
    // print_r($_POST); die; 

$selected_date=$_POST['select_date'][0];
$session_date_ymd=date('Y-m-d H:i:s', strtotime($selected_date));  // 2012-04-20
$selected_date=date('Y-m-d', strtotime($_POST['select_date'][0])); 
  if(!isset($_GET['sid'])){
    die('?id=3580,  select tuoring id ');
  }


        $tuoringId=$_GET['sid'];
        $TutoringNewId=Duplicate_tutoring($selected_date,$tuoringId);
        // get new tutoringID
        $first_ses_id=$TutoringNewId;
        $error=(count($_POST['lesson'])).'-Sessions Duplicate Done !';
        // newrow.relaunch.process.php
        $redrectTo='create_session_step2';
        header("Location:newrow.relaunch.process.php?id=".$TutoringNewId); exit;


}



//Display only //////
 if(isset($_GET['sid'])){
$Tutoring= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['sid'])); 



@extract($Tutoring);
$quiz_det= mysql_fetch_assoc(mysql_query(" SELECT grade_id FROM int_quiz WHERE id=".$quiz_id));  
 $sel_grade_id=$quiz_det['grade_id'];
 }else  $error = 'Page not found';
 ////////////////////////

?>




<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>


<script type="text/javascript">
// 
   $(document).ready(function () {


 //#calendar


  // date
  // $('.datepicker').datepicker({
  //                           format: 'mm/dd/yyyy',
  //                                   startDate: '-3d'
  //                                   });

    /////////////////////////
        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            //$('#grade_div').html('Please wait...');
            $.ajax({
                type: "POST",
                url: "ajax-sessions.php",
                data: {district: district, action: 'get_multiple_schools', school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                     $.getScript("intervention.js");
                    //Check for school/////


                },
                async: true
            });
        });
        $('#district').change();
        ////////////grade_id////////////////
        // $('#grade_id').chosen();
      




    }); //end 
    ////////////////////////







////////Form submit/////
$(document).ready(function(){
  $("#form_passage_stop").submit(function(){
    //event.preventDefault();
    var tot_students= $("#students_id :selected").length;
    if(tot_students<1||tot_students>8){

         alert(tot_students+'-Student, choose (1-8)students!');  return false;
    }else{  // submit Please wailt
     

     // alert('Please wailt!, Submiting');
      $('#lesson_submit_id').val('Wait.....');
      $('#lesson_submit_id').html('Please wait..!');
      $("input[type='submit']", this)
      .val("Please Wait...")
      .attr('disabled', 'disabled');
      
     return true;
    
    }
    //return true;

       // alert(count+'= Students');

   // alert("Submitted"); 
   
  });
});

/////Student Select////


</script>
 <style type="text/css">
   #add{
    display:none !important;
   }
 </style>

<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>    <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>
                          Create Session</h3>
                    </div>    <!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Create Session:">Create Session:</h4>

                             <?php 
                             // https://newrow.com
                             //  groupworld##  'newrow'=>'Newrow Virtual Board',  
                             $Arrvirtual_board=array('newrow'=>'Newrow Virtual Board',  
                              //'groupworld'=>'Groupworld Virtual Board',
                            );

                             ?>

<div style=" display: none;" class="add_question_wrap clear fullwidth">
<p>
<label for="lesson_name">Virtual board :</label>
<select name="virtual_board" id="virtual_board" class="textbox">
<?php  foreach ($Arrvirtual_board as $key => $value) {

?>
<option value="<?=$key?>"> <?=$value?> </option>
<?php  } ?>
</select>
</p>

<!-- Test sesion  -->
<p>
<input type="checkbox" name="is_testing" id="is_testing" style="vertical-align: sub;">
<label for="question_public">Is Test Intervention <em>(if checked, automatically assigned to Test tutor)</em></label>
</p>

</div>


                                                           

                          
                        
                            


                              <!-- Select Date time -->

<div class="add_question_wrap clear fullwidth">

<p class="text-info" style="">Select upcoming date: </p>
<div class="row" id="add_more" >
<div class="col-md-6">
<label for="date">Date</label>


<input 
name="select_date[]" class="datepicker textbox cdate" 
data-date-format="mm/dd/yyyy">


</div>


<!-- Times -->



<!-- Lesson -->

<div style="display: none; " class="col-md-10" id="lesson_div">
<p>
<label for="lesson_name">Choose Lesson:</label>
<select name="lesson[]" id="lesson" class="textbox">
<option value="">Select Lesson</option>
<?php $lesson_query =mysql_query("select * from master_lessons");
while ($lesson = mysql_fetch_assoc($lesson_query)) { ?>
<option value="<?php echo $lesson['id']; ?>"><?php echo $lesson['name']; ?></option>

<?php } ?>
</select>
</p>
</div>
<!-- Lesson -->
</div>



</div>

  <!-- Tutoring detais -->
     <?php  include "tutoring.detail.section.php"; ?>









                            <p>

      

           <button type="submit" name="create_submit"
            id="lesson_submit_id" class="form_button" value="" >Duplicate, Tutoring-ID:-<?= $_GET['sid'] ?> </button>
                                

                            </p>

                        </form>





                        <div class="clearnone">&nbsp;</div>
                    </div>    <!-- /.ct_display -->
                </div>
            </div>    <!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>    <!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<script>  




///////////////////
 $(document).ready(function(){  

// mouseover
//$(document).on('click', '.datepicker', function(){
  $(document).on('mouseover', '.datepicker', function(){
    //this.id='';
      //console.log('Allow date'+this.id);

       $('.datepicker').datepicker({
                            format: 'mm/dd/yyyy',
                                    startDate: '-3d'
                                    });
});

  ///////////////////////////////
      var i=1;  
      $('#add').click(function(){ 
       // alert('');

       


        ////////////////

           i++;  
           var text_ino=$('#add_more').html();
            var get_lesson=$('#lesson_div').html();// time_section
            var time_section1=$('#time_section').html();
           var html = '';
           //  info_text For Repeat Text
           var info_text='<div class="col-md-6"> <label for="date">Date</label><input name="select_date[]" class="datepicker textbox cdate" data-date-format="mm/dd/yyyy"></div>';
           info_text+='<div class="col-md-6">'+time_section1+'</div>';

           var lession_sec='<div class="col-md-10">'+get_lesson+'</div>';
             lession_sec+='<div  class="col-md-2"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove" style="margin-top: 32px;float:right;">X</button></div>';
             info_text+=lession_sec;


         //var datatext='<div id="row'+i+'" >'+text_ino+'</div>';

           var datatext='<div title="parent" id="row'+i+'"><div class=""  >'+info_text+'</div></div>';

          $('#repeat_id').append(datatext);
          /////////////////////////////
           // $('.datepicker').datepicker({
           //                  format: 'mm/dd/yyyy',
           //                          startDate: '-3d'
           //                          });


          ///////////////////


      });  

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+i+'').remove();  
           i--;
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_session').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_session')[0].reset();  
                }  
           });  
      });  
 });  
 </script>


<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>
