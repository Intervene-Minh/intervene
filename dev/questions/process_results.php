<?php 
/***
@ Process and update  Roster
@ process_results.php
@School: 1 assessment - Students results  
***/
//echo $msg='assessment - Students results  ';
 
include("header.php"); 
 $error = '';
 $next_url="result_uploaded.php";# List url or reults
$roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_provider_1_tab WHERE id=".$_GET['id']));
     $school_id=$roster['school_id']; // file row 28


     //print_r($roster); die;




////////////////
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

$created = date('Y-m-d H:i:s');
//echo "process_roster";
// update_roster
//if(isset($_POST['update_roster'])){ process_roster


 if(isset($_POST['update'])){

  //print_r($_POST); die;

   $curr_id=$_GET['id'];
$roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_provider_1_tab WHERE id=".$curr_id));
     $school_id=$roster['school_id']; // file row 28

  /////////////////
     $ctr = 0; // uploads/results

        //print_r($_FILES); die;
        $file_name = $_FILES['csv_upload']['name'];
         $ext = pathinfo($file_name, PATHINFO_EXTENSION);

         //var_dump($ext); die; 
        $cwd = getcwd();
        $uploads_dir = $cwd . '/uploads/results';  // rosters student_csv
        $tmp_name = $_FILES["csv_upload"]["tmp_name"];
        $name = $school_id . '_' . $school_id . '_' . basename($_FILES["csv_upload"]["name"]);

          
      //  if(isset($_FILES["csv_upload"]["name"])&&!empty($_FILES["csv_upload"]["name"])&&$ext=='csv'){
       if(!empty($_FILES["csv_upload"]["name"])&&$ext=='csv'){
          unlink($uploads_dir.'/'.$roster['filename']); 

          $name=$roster['filename'];  // Old db file name 
        //die;
           if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
             $error='File Updated'; 
           }else{ $error='Not Updated';  }


        }else{
          if(empty($_FILES['csv_upload']['name']))
         $error = 'Select a CSV file !';
           elseif($ext!='csv')
         $error = 'CSV file required';

        }

/////////////////// $_SESSION['schools_id'];
   //$_SESSION['warn_msg']=$error;

   // header("Location:".$_SERVER['PHP_SELF']);exit; 

 }//update


  // $_SESSION['warn_msg']='msggggggg';
   //print_r($_SESSION);



//if(isset($_POST['update_roster']) ||isset($_POST['process_roster'])){

  $w_opns_arr=array("A","B","C","D","F","G","H","J");//Wrong option format
   $format_1=array("A","B","C","D");
  $format_2=array("F","G","H","J");

  

// $student_ans=45; 
// $student_ans="A"; 
//$student_ans="+";

   // if($student_ans=="+"||!in_array($student_ans, $w_opns_arr)){
   //        $line['corrected']=1;
   //      }else{
   //        $line['corrected']=0;
   //      }

   //      echo '==' .$line['corrected'] ; die; 



 if(isset($_POST['process_roster'])){
// Delete old result 
  $get_ref_id=$_GET['id'];
  $Delete1=mysql_query(" DELETE FROM `teacher_x_assesments_x_students` WHERE ref_id='$get_ref_id' ");

  $Delete2=mysql_query(" DELETE FROM `students_x_assesments` WHERE ref_id='$get_ref_id' ");



	//print_r($_POST); die;
   $curr_id=$_GET['id'];
$roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_provider_1_tab WHERE id=".$curr_id));
     $school_id=$roster['school_id']; // file row 28

  /////////////////
     $ctr = 0;
              // if Process Roster
           


  // $roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_provider_1_tab WHERE id=".$curr_id));


             $cwd = getcwd();
            $uploads_dir = $cwd . '/uploads/results';  // rosters student_csv
            $name=$roster['filename'];
           // echo $uploads_dir . '/' . $name; die;
        
             $row = 1;
            // echo  $uploads_dir . '/' . $name; die; 

             $arr_student=array(); $ans_arr=array(); //  $student_arr=ar

             $student_arr=array(); //$question_arr=array();

        if (($handle = fopen($uploads_dir . '/' . $name, "r")) !== FALSE) {
            $d = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
             //echo '<pre>';  print_r($data); die; 
               if ($d==0) {
                 unset($data[0]);
                unset($data[1]);
                 unset($data[2]);
                 $question_arr=$data;
               }

               //answer keys
                if ($d==1) { 
                  $ans_arr[]=$data;
                 // echo count($data);

                 // echo '<pre>';  print_r($ans_arr); die;

                } 
                //answer keys


                
                if ($d >=2) {  // Student Array
                  $user_sql=mysql_query("SELECT * FROM users WHERE email= '$teacher_em' ");
                 // echo 'Students';
                  $student_arr[$data[0]]=$data;
                  // echo '<pre>';  print_r($data); die; 
              //   print_r($data);  die;
                  //  if(mysql_num_rows($user_sql)==1){ 

                     
  
                 


                }
                $d = $d + 1;






            }
            fclose($handle);

        }// f (($handle = fopen($uploads_dir . '/' . $name, "r")) !== FALSE) {
////process results ///
          
    $assessmentId=$roster['assessment_id']; 

  
      //echo '<pre>';  print_r($student_arr);  die; 
         $question_distractors=array();
      foreach ($question_arr as $key => $qnum) {
        # code...
        $assessment_q=mysql_fetch_assoc(mysql_query(" SELECT * FROM `assessments_x_questions` WHERE `assesment_id` = '$assessmentId' AND `num` = '$qnum' "));
        // print_r($assessment_q); die;
        $qdata=mysql_fetch_assoc(mysql_query("SELECT * FROM `questions` WHERE id=".$assessment_q['qn_id']));

         $gievn_questions_ans = unserialize($qdata['answers']);
       // print_r($qdata); die; 
         $find_correct=null;
         foreach ($gievn_questions_ans as $key2=> $row) {
           # code...
          if($row['corect']==1){
          $find_correct=$key2;
          }
         }

         ////check answer selected 
        $question_distractors[$key]=array( 'num'=>$qnum, 'correct_opn'=>$find_correct,
            'qid'=>$assessment_q['qn_id'], 'distractor'=>$gievn_questions_ans);

      } 


     // echo   $question_distractors[5]['distractor'][2]['explain'];  // 2 is option selected by user // 5=question key

       // echo '<pre>';  print_r($question_distractors);


// $format_1=array("A"=>0,"B"=>1,"C"=>2,"D"=>3);
// $flip_array = array_flip($format_1);
// print_r($flip_array); 


 


      //   echo '<pre>';  print_r($student_arr);
      $question_arr = array_filter($question_arr);

        //  echo '<pre>';  print_r($question_arr); // question_arr
 
         
        //die; 

          
           $school_id=$roster['school_id'];
           $ref_id=$roster['id'];
           $today=$datetm = date('Y-m-d H:i:s');
           $ref_type='school_provider_1_tab';//table name

           $format_1=array("A"=>0,"B"=>1,"C"=>2,"D"=>3);


         foreach ($student_arr as $sid => $sarr) {
          
    // $student=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE id=".$sid));
     // UID///
          //  $student=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE uid=".$sid));
          // schoolwise , uid student.
      $student=mysql_fetch_assoc(mysql_query("SELECT * FROM students WHERE school_id='$school_id' AND uid=".$sid));
      $stuId=$student['id'];

           // assigned_date  completion_date
       $sql=" INSERT INTO  teacher_x_assesments_x_students SET teacher_id='".$student['teacher_id']."',
          assessment_id='$assessmentId',
           student_id='".$stuId."',
            status='Completed',
             school_id='$school_id',
             assigned_date='$today',
             completion_date='$today',
             ref_id='$ref_id',
               ref_type='$ref_type' ";  
              // echo $sql; die; 
               // if student Record found
               if($stuId>0){
                $add=mysql_query($sql);// Completed status table 

               } // not entered record
                

     //  Questions in assessment 
     
     foreach ($question_arr as $key => $number){
      $line=array(); //data to insert
       # code...
      $qdata=mysql_fetch_assoc(mysql_query("SELECT * FROM assessments_x_questions WHERE assesment_id='$assessmentId' AND num= '$number' "));
       $line['qn_id']=$qdata['qn_id'];

           // answer except : abcd, fgh is correct.
        $student_ans=$sarr[$key];// +,ABCD by student 
        // correct process  $line['correct']
          $student_ans=trim($student_ans);

        //if($student_ans=="+"||!in_array($student_ans, $w_opns_arr)){
          $line['corrected']=0;

          if(!empty($student_ans)&&$student_ans=="+"){
          $line['corrected']=1;
        }elseif(!empty($student_ans)&&in_array($student_ans, $w_opns_arr)){
            // wrond attempeted
           $line['corrected']=0;

        }elseif(!empty($student_ans)&&!in_array($student_ans, $w_opns_arr)){
          $line['corrected']=1;// attempeted other value 
        }

        //  Calcualte distractor 
        $dist=0; 
        // $format_1=array("A"=>0,"B"=>1,"C"=>2,"D"=>3);

        if($student_ans=="+"){ // actual answer of question 
            $line['corrected']=1;
        //$question_distractors[5]['distractor'][2]['explain'];
          //pic from correct_opn
            // A","B","C","D","F","G","H","J"
          $actual=$question_distractors[$key]['correct_opn'];//
          $dist=$question_distractors[$key]['distractor'][$actual]['explain'];
        }elseif($student_ans=="A"||$student_ans=="B"||$student_ans=="C"||$student_ans=="D"||$student_ans=="F"||
          $student_ans=="G"||$student_ans=="H"||$student_ans=="J"){
            $ans_selected=$format_1[$student_ans];
           $dist=$question_distractors[$key]['distractor'][$ans_selected]['explain'];
        }

      //  print_r($line);  die; 

        // w_opns_arr
         $line['number']=$number;
         $line['grade_id']=$student['grade_level_id'];
         $line['class_id']=$student['class_id'];
         $line['teacher_id']=$student['teacher_id'];

         // grade_id class_id  teacher_id

         //////SQL/////// ref_id ref_type  distractor
         $sql=" INSERT INTO  students_x_assesments SET assessment_id='$assessmentId',
          student_id='$stuId',
           qn_id='".$line['qn_id']."',
            num='".$line['number']."',
             corrected='".$line['corrected']."',
             school_id='$school_id',
             teacher_id='".$line['teacher_id']."',
             grade_id='".$line['grade_id']."',
             class_id='".$line['class_id']."',
             distractor='$dist',
             ref_id='$ref_id',
             ref_type='$ref_type',
               created='$today' ";  
              // echo $sql; die; 
               if($stuId>0){
                 $add_result=mysql_query($sql);
               }
        

         /////////////////

      //print_r($line);  die; 



         }
         


           # code...
         }
      // Completed///end add record 

    
         //  $roster['assessment_id'];
         if($roster['status']!='Completed'){
          $error.='Completed,'; 
          $up_status=mysql_query("UPDATE school_provider_1_tab SET 
       updated='$created',status='Completed' WHERE id=".$_GET['id']);

         }
     

            $error.='Processed!'; 


          
       
     

}

/////update_roster/////
//update query
$edit_id=$_GET['edit_id'];
$update_query="select * from master_lessons where id='$edit_id'";
$update_query_res=mysql_query($update_query);

$res_update = mysql_fetch_assoc($update_query_res); 
$object_id = $res_update['objective_id'];

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT * FROM `questions` WHERE category = " . $_GET['taxonomy']." ORDER BY date_created DESC");		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>



<?php
if(isset($_POST['submit']))
{
  $name=$_POST['name'];
 
  $url=addslashes($_POST['url']);
  $objective_id = $_POST['objective_id'];
  $upload_dir = 'uploads/lesson/';
  $image=$_FILES['file_name']['name'];
  $temp=$_FILES['file_name']['tmp_name'];
 // $size=$_FILES['file_name']['size'];

  $image_name=rand(10000,0000).$image;
  move_uploaded_file($temp,$upload_dir.$image_name);
  $sql="INSERT INTO master_lessons (name,objective_id,file_name,url,date_created )VALUES('$name','$objective_id','$image_name','$url','DATETIME: Auto CURDATE()')";
  //echo $sql;
  
  $insert=mysql_query($sql);
  
  if($insert)
  {
     header("location:lessons.php");
  }
  
}

?>
<?php
$edit_id=$_GET['edit_id'];// 
// if(isset($_POST['update']))
// {
// 	$name=$_POST['name'];
// 	$url=addslashes($_POST['url']);
// 	$objective_id = $_POST['objective_id'];
// 	if($_FILES['file_name']['name']!=''){
// 		$upload_dir = 'uploads/lesson/';
//         $image=$_FILES['file_name']['name'];
//         $temp=$_FILES['file_name']['tmp_name'];
//       //  $size=$_FILES['file_name']['size'];
//         $image_name=rand(10000,0000).$image;
//         move_uploaded_file($temp,$upload_dir.$image_name);
// 	}
// 	else{
// 		$image_name=$_POST['old_image_name'];
// 	}
// 	$update_sql = mysql_query("update master_lessons set name='$name', url='$url', objective_id='$objective_id', file_name='$image_name' where id='$edit_id' ");
// 	if($update_sql)
// 	{
// 		header("location:lessons.php");
// 	}
// }
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
                        <h3>
                          Manage Result</h3>
                    </div>		<!-- /.ct_heading -->

                    <p class="text-danger">
                    <?php  if(isset($_SESSION['warn_msg']))echo $_SESSION['warn_msg'];?></p>
 
					
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                           

                           <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Upload file</label>
                                 <input type="file" style=" width: 100%" name="csv_upload" class="required textbox">   
                                </p>
                                <p>Click to <a href="uploads/results/<?=$roster['filename']?>"
                          class="btn btn-primary btn-sm">Download File</a> </p>

                                </div>
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">

                            <button type="submit" name="update" style=" margin-top: 10px;" class="form_button submit_button">Update</button>

                               <button type="submit" name="process_roster" style=" margin-top: 10px;" class="form_button submit_button">Process</button>

                            </p>
                            <p > <a href="<?=$next_url?>" style="text-align: left;" class="form_button submit_button">Back Home</a> </p>






                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div> 
                            
                            <!-------Fileter ---->
        
	<!-- Form Add/Edit Distrator -->
	<div id="report_error_dialog" class="form_dialog">
		<div class="clear fullwidth">
			<form name="report_error_form" id="report_error_form" class="form_data" method="post" action="">
				<div class="form_wrap clear fullwith">
					<p>
						<label for="error_subject">Subject:</label>
						<input type="text" name="error_subject" id="error_subject" class="field_data textfield" value="" />
					</p>
					<p>
						<label for="error_comment">Comment:</label>
						<textarea name="error_comment" id="error_comment" class="field_data textfield"></textarea>
					</p>
				</div>
				<div class="button_wrap clear fullwith">
					<input type="hidden" name="hidden_id" class="hidden_id" id="question_id" value="" />
					<input type="submit" name="submit_error" id="submit_error" class="form_button submit_button" value="Send" />
					<input type="reset" name="reset_error" id="reset_error" class="form_button reset_button" value="Cancel" />
				</div>
			</form>
		</div>
	</div>
        <script type="text/javascript">
		$(document).ready(function(){
			var $count =0;
			var $timehidden;
			$('.add-to-list').on('click',function(){ 
				
				var item = $(this).parents('li').first()
				$count++;
				
				/*store id to list*/
				var $id = $(this).val();
				$.ajax({
					type	: 'POST',
					url		: 'inc/ajax-add_to_list.php',
					data	: {
						'add_to_list':$id,
						'is_passage':<?php echo $passage_id;?>
					},
					dataType: 'json',
					success	: function(response) {
						if(response.check){
							item.slideUp(500);
							// var is_unlimited = response.is_unlimited;
							var count = response.count;
							// var remaining = response.remaining;
							
							// if(is_unlimited){
								// remaining = ' Unlimited';
							// }else{
								// if(remaining <0){
									// if(remaining=='-1')$('.alert-q-remaining').show();
									// remaining = 0;
								// }
							// }
							
							$('.list-notification>.text>.number').text(count);
							// $('.list-notification>.text>.remaining').text(remaining);
							$('.list-fixed').show();
							clearTimeout($timehidden);
							$timehidden = setTimeout(function() {
								$('.list-fixed').hide(500);
							}, 10000);
							
						}else{
							alert("Can't add this question");
						}
					}
				});
				
				
			});
			$('.list-notification').on('click',function(){
				$(this).parents('.list-fixed').first().hide(500);
			});
			$('.alert-q-remaining .fa.fa-times').on('click',function(){
				$(this).parents('.alert-q-remaining').first().hide(500);
			});
			
			$('#submit_error').on('click',function(){
				if($('#error_subject').val()==""){
					$('#error_subject').css({'border':'1px solid #e4532c','outline':'none'});
					$('#error_subject').focus();
					return false;
				}else{
					$('#error_subject').css({'border':'1px solid #d6d6d6'});
					
				}
				if($('#error_comment').val()==""){
					$('#error_comment').css({'border':'1px solid #e4532c','outline':'none'});
					$('#error_comment').focus();
					return false;
				}else{
					$('#error_comment').css({'border':'1px solid #d6d6d6'});
					
				}
				$.ajax({
					type	: 'POST',
					url		: 'inc/ajax-send-error.php',
					data	: {
						'error_subject':$('#error_subject').val(),
						'error_comment':$('#error_comment').val(),
						'question_id':$('#question_id').val()
					},
					dataType: 'json',
					success	: function(response) {
						console.log(response);
						if(response.check){
							alert("Success!");
						}else{
							alert("Fail!");
						}
						
						// $('#loading').remove();
						// alert(response.msg);
						// if(response.stt)
							// $(popup).dialog('close');
						// if(response.stt && response.sql == 'update')
							// location.reload();
					}
				});
				$('#reset_error')[0].click();
				return false;
			});
		});
	</script>

    <script>
    function validateUrl(url)
{
    var pattern = '^((ht|f)tp(s?)\:\/\/|~/|/)?([\w]+:\w+@)?([a-zA-Z]{1}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?((/?\w+/)+|/?)(\w+\.[\w]{3,4})?((\?\w+=\w+)?(&\w+=\w+)*)?';

    if(url.match(pattern))
    {
        return true;
    }
    else
    {
        return false;
    }
}
    </script>
</div>
<div class="list-fixed">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">A problem has been added (<span class="number">0</span> problems total)</div>
	</div>
</div>
<div class="alert-q-remaining">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">You have used all of your free questions. <a href="membership.php" class="btn btn-link">Upgrade to Membership</a></div>
	</div>
</div>
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#main -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>

<?php include("footer.php"); ?>