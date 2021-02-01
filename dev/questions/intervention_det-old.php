<?php
////
/////////  assign-students :: to
// edit-session :
/**
 * a. Students
b. Objective
c. Grade :XX
d. Notes
e. Exit quiz

 * 
 *  */

$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
 $today = date("Y-m-d H:i:s"); 
include("header.php");
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
$error = '';
  
 // lesson_submit
//var_dump($_POST); die;
//$error="Pelse enter value.. ";
// Add Students 
 if(isset($_GET['ses_id'])){
$master= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['ses_id'])); 
//print_r($master); die; 

@extract($master);
$quiz_det= mysql_fetch_assoc(mysql_query(" SELECT grade_id FROM int_quiz WHERE id=".$quiz_id));  
 $sel_grade_id=$quiz_det['grade_id'];
 }else  $error = 'Page not found';
 ////////////////////////
 
 @extract($_POST);


 


//XXXXXXXXXXXXXXX









function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnpqrstuvwxyz";
    $codeAlphabet .= "123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }

    return $token;
}





///////////////////
$teacher_grade_res = mysql_query("
  SELECT  GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
  FROM `techer_permissions`
  WHERE teacher_id = {$user_id} 
");
$t_grades = mysql_fetch_assoc($teacher_grade_res);
$teacher_grade = $t_grades['shared_terms'];
if ($_GET['class_id'] > 0) {
    $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
    
    if ($edit_class['id'] != $_GET['class_id']) {
        $error = 'This is not valid class.';
    }
}

?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>    <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Intervention Session Details</h3>
                    </div>    <!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
                          <!--   <h4>Intervention Session Details here:</h4> -->
                            <?php  
                              $curr_time= date("Y-m-d H:i:s"); 
                             $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  
                           
                           //  echo 'curr_Time-'.$curr_time;
                             
                             $sdate=date_format(date_create($ses_start_time), 'F d,Y');
      $at_time=date_format(date_create($ses_start_time), 'h:i a');
                            ?>
                   <h4 style="color: #1b64a9; font-size:16px">Session Date/Time:&nbsp;<?=$sdate?> at-<?=$at_time?>
                       <?php //=($in_sec>-3600)?NULL:",&nbsp;Can't Re-assign students, session expired";?></h4>
                            
                            
                            <div class="add_question_wrap clear fullwidth">
                               <script>
$(document).ready(function(){
    $("#gradeid").change(function(){
         var gjd = $("#gradeid").val();
     // alert('hghgh'+gjd); // alert(this.val()); 
        $.post("aj_grade_class.php",
        {
          gradid:gjd,
          city: "Duckburg"
        },
        function(data,status){
             $("#tclassid").html(data);
            //alert("Data: " + data + "\nStatus: " + status);
        });
    });
});
</script>
                                <?php 
                            // $master    
                            // $sel_grade_id    
                                  // find student list 
                           $res11= mysql_query(" SELECT student_id FROM int_slots_x_student_teacher WHERE slot_id=".$_GET['ses_id']);   
                           $st_arr=array();
                            while ($row= mysql_fetch_assoc($res11)) {
                              $st_arr[]=$row['student_id'];
                            }
                                
                                $sel_class_id=$class_id;// master session
                                 if(isset($_GET['cl'])){
                                    $clss_det= mysql_fetch_assoc(mysql_query(" SELECT grade_level_id FROM classes WHERE id=".$_GET['cl'])); 
                                    $sel_grade_id=$clss_det['grade_level_id'];
                                    $sel_class_id=$_GET['cl'];
                                     $st_arr=array();// re- assign
                              //  Grade id .    
                                 }else{
                                 
                                 }
                               
                           //   chane time and    
                        
                        
                        ?>
                                
                                 


                                   <!--  <p>
                                     Objectives :fdfd
                                    </p> -->
                                
                                
                                <?php 

                                // $master  $mater['lesson_id'] grade_id 
                                $master_lessons=mysql_fetch_assoc(mysql_query(" SELECT * FROM master_lessons WHERE id=".$master['lesson_id']));
                                $quiz_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id=".$master['quiz_id']));
                                $terms=mysql_fetch_assoc(mysql_query(" SELECT id,name FROM terms WHERE id=".$master['grade_id']));
                                // 
                                    // echo $terms['name'];

                                ?>
                                
                                
                                    
                                    
                            
                            
                            
                             

                                <div id="textarea" style="display: block">

                                     <span> <strong class="text-primary">
                                 Grade:</strong> </span> <?=$terms['name']?> <br/>


                                   <span> <strong class="text-primary">
                                 Quiz:</strong> </span> <?=$quiz_det['objective_name']?> <br/>

                                   <span> <strong class="text-primary">
                                 Lesson:</strong> </span> <?=$master_lessons['name']?>  <br/>
                                    
                                   <!-- <p class="text-success">  -->
                                     <span> <strong class="text-primary">
                                  Student List :</strong> </span> 


 



                                   <?php 
                                   $ses_id=$_GET['ses_id'];
                                   $sql_student=" SELECT sx.slot_id,sx.launchurl,sx.student_id,s.id as sid,s.first_name,s.middle_name,s.last_name FROM int_slots_x_student_teacher sx
left join students s
ON sx.student_id=s.id
WHERE sx.slot_id= '$ses_id' ";  


                           $res=mysql_query($sql_student);
                             while ($row= mysql_fetch_assoc($res)) {
                               $name=$row['first_name'];
                                $launchurl=$row['launchurl'];

                              
                                if($master['board_type']=='braincert'){
                                   echo $name.',&nbsp;';
                               echo '<p  class="text-danger"><a style="display: inline-block;word-break: break-all" href="'.$launchurl.'"> '.$launchurl.'</a></p> <br/>';
                               }else{
                                echo $name.',&nbsp; <br/>';
                               }
                                
                              }

                              // $mster



                                   ?>
                                    
                              

                                </div>
                               

                               
                            </div>
                            
                            
                            <p> 
                            <a title="Back, Home" 
                            href="intervention_list.php" class="btn btn-success btn-sm">Back,</a>

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

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', school_id: '<?php //print implode(',', $assessment_school); ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
    //////////
    $(document).ready(function () {

        $('#districtqzz').chosen();

        $('#districtqzz').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', 
                    school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#districtqzz').change();
    });
    
    
    
</script>


<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>

    $(function () {
        $('input[name="sudent_details"]').on('click', function () {
            if ($(this).val() == 'manual') {
                $('#textarea').show();
            } else {
                $('#textarea').hide();
            }
            if ($(this).val() == 'csv') {
                $('#csv-upload').show();
            } else {
                $('#csv-upload').hide();
            }
        });
    });

</script>

<?php include("footer.php"); ?>
