<?php
/////////  assign-students :: to
// teacher-tutor-sessions.php

$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

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
 $today = date("Y-m-d H:i:s");   
 // lesson_submit
  if(isset($_GET['ses'])){
$mster= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['ses'])); 
  @extract($mster);}
 
//var_dump($_POST); die;
//$error="Pelse enter value.. ";
// Add Students 

if(isset($_POST['add_student'])){
    // Master Slot 
$_POST['quiz_id']=(isset($_POST['quiz_id']))?$_POST['quiz_id']:0;

 $mster= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['ses']));   
    
   $special_notes=NULL;
    $special_notes=$_POST['special_notes'];
    $gradeid=$_POST['grade'];
    $classid=$_POST['class']; // grade_id  class_id
    $q=" UPDATE int_schools_x_sessions_log SET quiz_id='".$_POST['quiz_id']."',class_id='$classid',grade_id='$gradeid', "
            . " special_notes='".$special_notes."',tut_status='STU_ASSIGNED'  WHERE id='".$_GET['ses']."' ";   
   
    $int_teacher_id=$mster['teacher_id'];# int_teacher_id
    $quiz_id=$_POST['quiz_id'];
    $sid="";
    $tot_std=count($_POST['student']);
    // slot_id student_id int_teacher_id int_school_id created_date quiz_id
     // limit students
    if($tot_std>0&&$tot_std<=5){// 1-5
    $a=mysql_query($q); //Master
     for ($k = 0; $k <$tot_std; $k++) {
                   // $today
         $sid.=$_POST['student'][$k];
        $qqq=" INSERT INTO int_slots_x_student_teacher SET slot_id='".$_GET['ses']."',"
                . "int_school_id='".$mster['school_id']."', "
                . " int_teacher_id='$int_teacher_id',"
                . "quiz_id='$quiz_id',student_id='".$_POST['student'][$k]."',created_date='$today' ";
      //  echo $qqq ; die;
      $fd=mysql_query($qqq)or die(mysql_error());
         /////////////
        
                }
      $error=$tot_std."-Students Asigend ";           
    header("Location:teacher-tutor-sessions.php");exit;            
                
    }elseif($tot_std>5){
        //$error="Your selected ".$tot_std."-students.Sorry,Maximum 5 Students can Asigend";  
        $error="A maximum of 5 students can be assigned to a session.  Please remove one or more students from the list.";
    }elseif($tot_std<1){
         $error="Atleast One Student choose";  
     
    }
    
   //// Add Student 
  // $error=$sid."--".$tot_std."-Students Asigend "; 
}


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
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>Assign Students to Session</h3>
                        
                          
                        
                        
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
                           
                            <div class="add_question_wrap clear fullwidth">
                              <?php 
                              # -3600
                              $curr_time= date("Y-m-d H:i:s"); 
                             $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  
                             $sdate=date_format(date_create($ses_start_time), 'F d,Y');
      $at_time=date_format(date_create($ses_start_time), 'h:i a');
       /// echo 'xxCurrent itime :'.date_format(date_create($curr_time), 'h:i a');die;
                            ?>
                   <h4 style="color: #1b64a9; font-size:16px">Session Date/Time:&nbsp;<?=$sdate?> at-<?=$at_time?>
                      <?=($in_sec>0)?NULL:",&nbsp;Can't Assign students, session expired";?></h4>
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
                                $clss_det= mysql_fetch_assoc(mysql_query(" SELECT grade_level_id FROM classes WHERE id=".$_GET['cl']));  
                                
                                ?>
                                
                                  <p>
                                        <label for="lesson_name">Choose Grade</label>
                                        <select name="grade" id="gradeid" class="required textbox">
                                            <option value=""></option>
                                            <?php
                                            $grade_level_id = 0;
                                            if ($_GET['cat'] > 0) {
                                                $grade_level_id = $_GET['cat'];
                                            }
                                            $folders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$teacher_grade}) AND `active` = 1");
                                            if (mysql_num_rows($folders) > 0) {
                                                while ($folder = mysql_fetch_assoc($folders)) {
                                         $selected = ($folder['id'] ==$clss_det['grade_level_id']) ? ' selected="selected"' : '';
                           echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </p>   
                                
                                
                                
                                
                                    
                                      <p>
                                    <label for="lesson_name">Class</label>
                   <select name="class" id="tclassid" class="required textbox"
                           onchange="open_asses('<?php print $base_url.'assign-students.php?ses='.$_GET['ses'].'&cl='?>',
                                       $(this).val());">
                                        <option value="">Choose Class</option>
                                        <?php
                                    //  show only class of same Grade.:: 
                                    
                                        ///////////////////
                                     $res = mysql_query('SELECT class.*, t.name as grade_name FROM classes class LEFT JOIN terms t ON t.id = class.grade_level_id '
        . 'WHERE teacher_id = \'' . $user_id . '\' ');  
                                     $totrr=mysql_num_rows($res);
                                       if(!isset($_GET['cl']))$totrr=0;
                                        
                                        if ($totrr > 0) {
                                            while ($result = mysql_fetch_assoc($res)) {
                                               ////////// $clss_det['grade_level_id']
                                            if($result['grade_level_id']!=$clss_det['grade_level_id'])continue;    
                                           ////////////////////     
                                 $selected = (isset($_GET['cl'])&&$result['id'] == $_GET['cl']) ? ' selected="selected"' : '';
                                                echo '<option value="' . $result['id'] . '"' . $selected . '>'.$result['grade_name'].' : ' . $result['class_name'] . '</option>';
//                                               
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                
                            <!--- NO. of student --->    
                               
                                
                            
                            
                                    
                                    
                                    
                             
                           <?php 
                           //$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
                            if(isset($_GET['cl'])){
                                           $dff=" AND class_id='".$_GET['cl']."' ";
                                $res= mysql_query(" SELECT * FROM students WHERE 1 ".$dff);   
                                       
                                        $totStud=mysql_num_rows($res) ;
                                       }
                           
                           
                        // $res= mysql_query(" SELECT * FROM students WHERE 1 Limit 10");      
                           ?>     
                                
                             <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Choose (1-5)students:</label><br />
                                    
                                    <select class="form-control" name="student[]" id="district" multiple="true">
                                        <?php while ($std= mysql_fetch_assoc($res)) { 
                                         // middle_name  	last_name
                                            $st_name=$std['first_name']." ".$std['middle_name']." ".$std['last_name'];   
                                         // $st_name=$std['middle_name'];   
                                            
                                            ?>
            <option <?php if (in_array($std['id'],$_POST['student'])) { ?> selected="selected" <?php } ?> value="<?php print $std['id']; ?>">
                                                <?=$st_name?></option>

<?php } ?>
                                    </select>
                                    

                                </p>
                            </div>    
                                
                             
                              <p>
                        <label for="lesson_name">Choose Objective/End Quiz</label><br/>  
            
                          
               <select class="form-control" name="quiz_id" id="districtqzz" >           
                   <option value="0">Choose Objective</option>  
                  <?php   //  	grade_level_id
       //  $clss_det= mysql_fetch_assoc(mysql_query(" SELECT grade_level_id FROM classes WHERE id=".$_GET['cl']));  
             
         $res_q= mysql_query(" SELECT * FROM int_quiz WHERE grade_id='".$clss_det['grade_level_id']."' ");   
          // $res_q= mysql_query(" SELECT * FROM int_quiz WHERE 1 ");          
         $j=1;
         while ($row= mysql_fetch_assoc($res_q)) {
             // quiz_id
             if(isset($_POST['quiz_id'])&&$row['id']==$_POST['quiz_id']){
              $checked="checked";
             }else
             $checked=($j==1)?"checked":NULL;
             
             ?>      
             <option value="<?=$row['id']?>"><?=$row['objective_name']?></option>   
               
                      <?php 
          $j++;}
                      ?>   
               
               
         
                                                                          
         </select>      
               
               
               
               
                             </p>
                            
                            
                            
                             

                                <div id="textarea" style="display: block">
                                    
                                    
                                    
                                    Special Notes <br/><br/>
                                    <p class="text-success">Please provide your Intervene tutor with any additional
information to help your student, such as distractors to focus on, concepts, etc...</p> 
                                    
                   <textarea class="form-control"
                             name="special_notes" placeholder="" rows="5"><?=$_POST['special_notes']?></textarea> 

                                </div>
                               

                               
                            </div>
                            
                            
                            
                            
                             <?php if($in_sec>0){ # ?>
                            <p>
                                <?php if ($edit_class['id'] > 0) { ?>
                                    <input type="hidden" name="class_id" id="class_id" value="<?php echo $edit_class['id']; ?>" />
                                    <input type="hidden" name="grade" value="<?php echo $edit_class['grade_level_id']; ?>" />
                                <?php } ?>
                   <input type="submit" name="add_student" id="" class="form_button submit_button" value="Submit" />
                                <input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
                            </p>
                            
                            <?php  }else echo 'Expired, can not assign Student,'
                           . '<a class="btn btn-info btn-sm" href="teacher-tutor-sessions.php"> Back, Home </a>'; ?>
                            
                            
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

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
   /////////////
   
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
