<?php
/***
 * Tutor Regis. By Admin.
 * @ manage-tutor
 * ***/
@extract($_REQUEST);
$sub=array(
"Elementary Math","Elementary ELA","Middle School / Junior High School - Math","Middle School / Junior High School - ELA","High School Math","High School ELA","Fluent in Spanish",

"Early Reading Phonics / English Language Learning");
include("header.php");
include('newrow.functions.php');

$error = '';


/// 'profile-submit'
/*
 * firstname
lastname
email
phone
 * 
 * **/

  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";


 if(isset($_POST['submit_data']) ) {
      
     $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$_POST['tutor_id']);
     $num=mysql_num_rows($is_record);
     $profile = mysql_fetch_assoc($is_record);
     
     
       $file_name = $_FILES["file"]["name"];
       $file = time().rand(1001,1111).$file_name;
   
       $target_file = "../training_docs/".$file;
       if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
          $msg= 'uploaded success';
       } else {  
          $msg = 'not uploaded';
          $file = null;
   
      }
         
      
      
     
    $sql=" INSERT INTO training_management SET name = '$name',file = '$file',created_at = '". date('Y-m-d H:i:s')."'";
   
        $ac=mysql_query($sql);
        

        
 header("Location:training_management.php");
    exit;
 }


if(isset($_GET['act']) && $_GET['act'] == 'del' )
{
    $training_sql = " SELECT * from training_management where id='".$_GET['id']."' ";
    $get_training_result = mysql_fetch_assoc(mysql_query($training_sql));
    @unlink("../training_docs/".$get_training_result['file']);
    
    $sql=" DELETE FROM training_management where id='".$_GET['id']."' ";
            
     
    $ac=mysql_query( $sql);
   
    $_SESSION['upload_message'] = "The training item deleted successfully";
    

    header("Location:training_management.php");
    exit;
}

  $is_record=mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$tid);

  $profile = mysql_fetch_assoc($is_record);
  
  $test_sql = " SELECT * from training_management";
  $get_test_result = mysql_query($test_sql);

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
                        <h3>Uplaod Certification Document</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <?php
                        if(isset($_SESSION['upload_message'])) { ?>
                        <div class="alert alert-success" role="alert" style="text-align: center"><?php echo $_SESSION['upload_message'];?></div>
                        <?php unset($_SESSION['upload_document']); } ?>
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			
<input type="hidden" name="tutor_id" value="<?php echo $_GET['tid'];?>">

                         <div class="add_question_wrap clear fullwidth">
                             <p>
									<label for="question_public" style="font-weight:bold;">Name</label>
								</p>
                                                                
                                                                <input style="font-size:13px !important;padding:0px" type="text" name="name" class="form-control">
								<p>
									<label for="question_public" style="font-weight:bold;">Uplaod File</label>
								</p>
                                                                
                                                                <input style="font-size:13px !important;padding:0px" type="file" name="file">
                                                             
                                </div> 
                 
                  
                 
                 
                 <div class="clear">&nbsp;</div>
                 <button type="submit" id="profile-submit" class="btn btn-primary"
                                  name="submit_data">Submit</button>
                 
                               
							</div>
			 
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                    
                    
                    <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Training Item List</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear" style="padding-top: 10px">
                        
             <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
			
             <div class="table-responsive">
                 <table class="table table-srtripe" style="font-size: 14px">
                     <thead>
                         <th>Name</th>
                         <th>URL</th>
                         <th></th>
                     </thead>
                <?php 
            
                while( $row = mysql_fetch_assoc($get_test_result) ) { 
                    ?>
                         
                     <tr>
                         <td><?php echo $row['name'];?></td>
                         <td><a href="../training_docs/<?php echo $row['file'];?>" target="_blank"><?php echo $row['file'];?></a></td>
                         <td>
                             <a  class="btn btn-sm btn-primary"  href="edit_training.php?id=<?php echo $row['id'];?>">Edit</a>
                             <a  class="btn btn-sm btn-danger" href="training_management.php?id=<?php echo $row['id'];?>&act=del" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                        
                         </td>
                     </tr>					
                                                              
                                                             
                                
                 
                <?php } ?>
                     </table>
                </div>  
                 
                 <div class="clear">&nbsp;</div>
                               
							</div>
			 
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->


<div class="modal fade" id="session_claim" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->
 <form method="post">
      <div class="modal-content">

         <div class="modal-header">

          <h4 class="modal-title">Edit Test Score</h4>

          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>

          <div class="panel-body" style="text-align:center" id="modal_body">
               
                    <input type="hidden" name="test_id" id="test_id" value="">
                     <input type="hidden" name="tutor_id" id="tutor_id" value="">
                     <div class="form-group" style="text-align: left">
    <label for="exampleInputEmail1">Passing Score</label>
    <input type="text" class="form-control" id="passing_score" name="passing_score" value="" readonly>
  </div>
  <div class="form-group" style="text-align: left">
    <label for="exampleInputPassword1">Score</label>
    <input type="text" class="form-control" id="score" name="score" value="">
  </div>


 

         
        </div>

        <div class="modal-footer">
 <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

      </div>

      </form>

    </div>

  </div>

	<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
    
 function showscoremodal(test_id, tutor_id, score, passing_mark)
{
    $('#score').val(score);
    $('#test_id').val(test_id);
    $('#tutor_id').val(tutor_id);
    $('#passing_score').val(passing_mark);
    $('#session_claim').modal('show');
}
    
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