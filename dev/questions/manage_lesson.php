<?php

ini_set('max_execution_time', 300);

 include("header.php"); 

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




$error='===';
// $_SESSION['msg_info']='record messag.';
if(isset($_POST['submit'])){

	$is_uploaded=0;
 $msg=array();
  $name=$_POST['name'];
 
  $url=addslashes($_POST['url']);
  $objective_id = $_POST['objective_id'];
  $upload_dir = 'uploads/lesson/';
  $image_name=$_FILES['file_name']['name'];
  $temp=$_FILES['file_name']['tmp_name'];
 // $size=$_FILES['file_name']['size'];

  $image_name=rand(10000,0000).$image_name;
  //  if ($_FILES["fileToUpload"]["size"] > 500000) { :: 500Kb
  /////////////
   //  print_r($_FILES['file_name']); die; 
  // print_r($_FILES);  die; 
   //echo  'test==';
    //echo   $_FILES['file_name']['error'];   die; 

         $msg[]= 'Message:';

     if($_FILES['file_name']['error']==0){
     	 $up=move_uploaded_file($temp,$upload_dir.$image_name);

     	  if($up){
     	  	$is_uploaded=1;
     	  	 $msg[]= 'File Upload:OK';

     	  }else{ 
     	  	  	 $msg[]= 'File not uploaded';
     	  }
     	}else{
     		$msg[]= 'Error in uploaded file';
     	}
   
    // print_r($up); die ;

  
  
   
  
   if(!empty($msg)){
   	$_SESSION['msg_info']=implode('<br/>',$msg);
   }

  // echo  $_SESSION['msg_info']  ; die; 
  //////////SAVE inFO. ///////////////////
  //die;
  $sql="INSERT INTO master_lessons (name,objective_id,file_name,url,date_created )VALUES('$name','$objective_id','$image_name','$url','DATETIME: Auto CURDATE()')";
  //echo $sql;
    if($is_uploaded==1){
    	$_SESSION['msg_success']='Record added successfully!';# sccess msg
 $insert=mysql_query($sql);
 header("Location:lessons.php");exit;
     }
/////////////////////
  // header("Location:lessons.php");exit;
  
}


/***
@Update
*/

$edit_id=$_GET['edit_id'];


if(isset($_POST['update'])){


	$name=$_POST['name'];
	$url=addslashes($_POST['url']);
	$objective_id = $_POST['objective_id'];

	if($_FILES['file_name']['name']!=''){ // new image changed
		$upload_dir = 'uploads/lesson/';
        $image=$_FILES['file_name']['name'];
        $temp=$_FILES['file_name']['tmp_name'];
      //  $size=$_FILES['file_name']['size'];
        $old_file=$_POST['old_image_name'];
        unlink($upload_dir.$old_file); 

        //  delete old image
        $image_name=rand(10000,0000).$image;
        move_uploaded_file($temp,$upload_dir.$image_name);
        /////Save///////


        /////////////////
	}
	else{
		$image_name=$_POST['old_image_name'];
	}


	///////////////////////////
	$update_sql = mysql_query("update master_lessons set name='$name', url='$url', objective_id='$objective_id', file_name='$image_name' where id='$edit_id' ");
	if($update_sql){ 
	  	$_SESSION['msg_success']='Record saved successfully!';
		header("Location:lessons.php"); exit;
	}
}
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>



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
                          Manage Lesson</h3>
                    </div>		<!-- /.ct_heading -->

					
                    <div class="ct_display clear">
                       <!--  <form name="form_passage" id="form_passage" method="post"  enctype="multipart/form-data"> -->
                        <form name="form_passage" id="form_passage" method="post"  enctype="multipart/form-data">


                            

                           <?php
                      // $qq=" SELECT DISTINCT(objective_id) FROM `term_relationships` WHERE 1  ";
                       $objectives_sql="SELECT t.name, t.short_code, t.id, tr.objective_id
FROM `term_relationships` tr
LEFT JOIN terms t ON tr.objective_id = t.id
WHERE t.name IS NOT NULL
AND t.short_code IS NOT NULL
GROUP BY tr.objective_id
ORDER BY t.name ASC ";



                         $results_q= mysql_query($objectives_sql);   
                           $tot_obj=mysql_num_rows($results_q);
                          // echo $tot_obj;
                           ?>
                            <div class="col-md-12">
                            <?php   include "msg_inc_1.php";  ?>
                                   
                                    <!-- <p>show===============</p> -->
                          

                            <div class="">
                                <p>
                                    <label for="lesson_name">Choose Objective:<?php //=$tot_obj?></label><br />
                                    <select name="objective_id" id="district"  required value="">
                                    <option value='<?php echo $res_update['objective_id'];?>'>Select Objective</option>
                                 <?php while ($line= mysql_fetch_assoc($results_q)) {
                                 
             $obj_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE id=".$line['objective_id']));   
             $line['short_code']=trim($line['short_code']);
              $code=(!empty($line['short_code']))?'['.$line['short_code'].']':'';
                                     ?>
                                   
               <option <?php if ( $object_id == $line['objective_id']) { echo "selected" ; } ?> value="<?php print $line['objective_id']; ?>"><?=$line['name'].$code?></option>

                            <?php } ?>
                                    </select>

                                </p>
                            </div> </div>



                            <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Lesson Name</label>
                                    <input type="text"  placeholder="Lesson Name" style=" width: 100%" name="name" class="required textbox" 
             value="<?php echo $res_update['name'];?>" required/>
                                </p></div>






                            <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Upload File</label>
                                    <input type="file"  style=" width: 100%" name="file_name" class="required textbox" />
			 <?php if($res_update['file_name']!=''){
			 	$file_url='https://intervene.io/questions/uploads/lesson/'.$res_update['file_name'];
			 	?>
					  
					  <!-- <img src="uploads/lesson/<?php echo $res_update['file_name']; ?>" width="50" height="50" > -->
					  <a href="<?=$file_url?>"  target="_blank" > File: <?php echo $res_update['file_name'];  ?></a>
					  
					  <?php }?>
					  
                      <input  type="hidden" name="old_image_name" value="<?php echo $res_update['file_name']; ?>" />
                                </p></div>     
                                <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Activity URL</label>
                                    <input type="url"  style=" width: 100%" name="url" class="required textbox" 
             value="<?php echo $res_update['url'];?>" />
                                </p></div>      
                               
                            
                            
                            
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">
           <!-- <input type="submit" name="submit" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Submit" /> -->
				  <?php if($edit_id==''){?>
                      <button type="submit" name="submit" style=" margin-top: 10px;" class="form_button submit_button">Submit</button>
                      <?php }else{?>
                      <button type="submit" name="update" style=" margin-top: 10px;" class="form_button submit_button">Update</button>
                      <?php }?>

                            </p>

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

<?php include("footer.php"); ?>