<?php 
/***
@ Process and update  Roster
***/
include("header.php"); 

$roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_master_rosters WHERE id=".$_GET['id']));
     $school_id=$roster['school_id']; // file row 28
     //print_r($roster); die;

//$error = 'Hello';


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

if(isset($_POST['update_roster']) ||isset($_POST['process_roster'])){
	//print_r($_POST); die;
   $roster_id=$_GET['id'];
$roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_master_rosters WHERE id=".$roster_id));
     $school_id=$roster['school_id']; // file row 28

  /////////////////
     $ctr = 0;

      if(isset($_POST['update_roster'])){
        $file_name = $_FILES['csv_upload']['name'];
        $cwd = getcwd();
        $uploads_dir = $cwd . '/uploads/rosters';  // rosters student_csv
        $tmp_name = $_FILES["csv_upload"]["tmp_name"];
        $name = $school_id . '_' . $school_id . '_' . basename($_FILES["csv_upload"]["name"]);
          
        if(isset($_FILES["csv_upload"]["name"])&&!empty($_FILES["csv_upload"]["name"])){
          
          unlink($uploads_dir.'/'.$roster['filename']); 
          $name=$roster['filename'];
        //die;
           if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
             $error='#Roster Updated'; 
           }


        }
         }//update
     //   move_uploaded_file($tmp_name, "$uploads_dir/$name");
          // if Process Roster insert poster
           if(isset($_POST['process_roster']))
           {
                  //$roster['school_id']==130
                  if($roster['status']=="Completed"){ 
                  //Check Student by UID//
                  $getid=$_GET['id'];// $roster['id']
               
                  }
       // Completed

                  $cwd = getcwd();
                  $uploads_dir = $cwd . '/uploads/rosters';  // rosters student_csv
                  $name=$roster['filename'];
                  // echo $uploads_dir . '/' . $name; die;
                  // echo 'process_roster';  // die;
                  $row = 1;
                  if (($handle = fopen($uploads_dir . '/' . $name, "r")) !== FALSE) 
                      $d = 0;
                  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $arr_student=array();
                    $teacher_em=trim($data[6]);
                    if($d >= 1)
                    {
                        $user_sql=mysql_query("SELECT * FROM users WHERE email= '$teacher_em' ");
                        $first_name = trim($data[3]);
                        $middle_name = trim($data[4]);
                        $last_name = trim($data[5]);
                        if ($first_name || $middle_name || $last_name)
                          {

                          $arr_student['first_name'] = $first_name;
                          $arr_student['middle_name'] = $middle_name;
                          $arr_student['last_name'] = $last_name;
                          $ctr = $ctr + 1;
                          }

                          if(mysql_num_rows($user_sql)==1)
                            { 
                                $teacher=mysql_fetch_assoc($user_sql); 
                                $user_id=$teacher['id'];
                                ////////Add Student Data////////
                                $grade_name=trim($data[1]);
                                $class_name=trim($data[0]);  // mysql_query

                              
                          $class_code = strtolower(str_replace(' ', '-', $class_name)).reset(explode('th',$grade_name)).'th';


                                //  validate Class 
                                 $sql="SELECT * FROM classes WHERE class_code= '".$class_code."' AND teacher_id=".$user_id; // die;
                                  

                                  $res_sql=mysql_query($sql);

                                  if(mysql_num_rows($res_sql)==1)
                                  {
                                    //update
                                    $class_data=mysql_fetch_assoc($res_sql);
                                    //print_r($class_data); die;
                                    $inserted_class_id =$class_data['id'];
                                
                                    }else
                                    {

                                      $class_i =mysql_query('INSERT INTO classes SET '
                                      . 'class_name  = \'' . addslashes($class_name) . '\' , '
                                      . 'grade_level_common  = \'' . $grade_name . '\' , '
                                      . 'teacher_id  = \'' . $user_id . '\' , '
                                      . 'school_id  = \'' . $school_id . '\' , '
                                      . 'class_code  = \'' . $class_code . '\' , '
                                      . 'roster_id  = \'' .$roster_id . '\' , '
                                      . 'created = \'' . $created . '\' ');
                                      $inserted_class_id = mysql_insert_id();
                    //die;
                 }
        
                    // roster_id  uid
                    $UID=trim($data[2]);
                    $str='INSERT INTO students SET '
                    . 'class_id = \'' . $inserted_class_id . '\' , '
                    . 'first_name = \'' . $arr_student['first_name'] . '\', '
                    . 'middle_name = \'' . $arr_student['middle_name'] . '\', '
                    . 'last_name = \'' . $arr_student['last_name'] . '\' , '
                    . 'uid = \'' .$UID. '\', '
                    . 'username = \'' . strtoupper(getToken(5)) . '\', '
                    . 'teacher_id  = \'' . $user_id . '\' , '
                    . 'school_id  = \'' . $school_id . '\' , '
                    //. 'grade_level_id = \'' . $grade . '\' , '
                    . 'grade_level_common  = \'' . $grade_name . '\' , '
                    . 'password = \'' . base64_encode(rand(10, 99)) . '\' , '
                    . 'status = 1 , roster_id='.$roster_id
                    . ', created = \'' . $created . '\'  ';

                    $student=mysql_query($str);

                  } // Teacher exits

                }

                $d = $d + 1;
            }
            /*while loop end*/
            fclose($handle);
            /*end button block*/
        }
            //   print_r($arr_student);die;
            $up_status=mysql_query("UPDATE school_master_rosters SET updated='$created',status='Completed' WHERE id=".$_GET['id']);
            $error=($d-1).'-students uploaded, \n';
            $error.='Roster Processed'; 

           }// process     

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
                          Process Roster</h3>
                    </div>		<!-- /.ct_heading -->

					
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                           

                           <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Upload file</label>
                                 <input type="file" style=" width: 100%" name="csv_upload" class="required textbox">   
                                </p>
                                <p>Click to <a href="uploads/rosters/<?=$roster['filename']?>"
                          class="btn btn-primary btn-sm">Download File</a> </p>

                                </div>
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">

                            <button type="submit" name="update_roster" style=" margin-top: 10px;" class="form_button submit_button">Update</button>

                               <button type="submit" name="process_roster" style=" margin-top: 10px;" class="form_button submit_button">Process Roster</button>

                            </p>
                            <p > <a href="rosters_list.php" style="text-align: left;" class="form_button submit_button">Back Home</a> </p>






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