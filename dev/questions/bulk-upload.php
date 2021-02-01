<?php
/**

@Import Bulk : Questions csv.

*/


require_once ('translate/vendor/autoload.php');
use \Statickidz\GoogleTranslate;

$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$current_date = date('Y-m-d H:i:s');

include("header.php");
if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}

// Retrive question data
$quesId = (isset($_GET['question']) && is_numeric($_GET['question']) && $_GET['question'] > 0) ? $_GET['question'] : 0;

//if not admin but want to edit return index
require_once('inc/check-role.php');
$role = checkRole();
if($quesId>0 && $role!=0){
	header('Location: index.php');
	exit;
}


// echo 'Bullk upload';  // die;




// upload////////////////////////
if( isset($_POST['upload']) ) { 
    $msg=array();
   $filename=$_FILES["file"]["tmp_name"];	 // die;	
 
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
                        $failed=0;$success=0;
                        $i=0;
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         { 
                    if($i>=1){
                      
                 $answer_data=array();  $arr=array();
                 //  check for distractor
               
                        //1-options//////////////// 
                 $arr['corect']=($getData[13]==1)?1:0;  // answer at [13th]1-4
                 $arr['answer']=$getData[5]; // 5 first opt
               
                 if(!empty($getData[9])){
               //  $filename=  $arr['image']=$getData[9]; // 10,13
                  $filename=$getData[9];
                   $arr['image']="uploads/pic/".$filename; 
                 }
                
                 $arr['explain']=(!empty($getData[14]))?$getData[14]:0; // distractor1 id :14 start
              
                 if(!empty($getData[9])){
                $filename_1="uploads/pic/".$filename; // CSV image
           list($width, $height) = getimagesize($filename_1);
                
                   $arr['width']=$width;
                    $arr['height']=$height;
                }
                    
                 $answer_data[]=$arr;
                //1-options//////////////// 
                 $arr = array();
                 
                   //////////////2-options//////////////// 
                 $arr['corect']=($getData[13]==2)?1:0; 
                 $arr['answer']=$getData[6]; 
               
                 if(!empty($getData[10])){
                 //$filename=  $arr['image']=$getData[10];
                  $filename=$getData[10];
                   $arr['image']="uploads/pic/".$filename;
                }
                
                 $arr['explain']=(!empty($getData[15]))?$getData[15]:0;// distractor2
              
                 if(!empty($getData[10])){
                $filename_1="uploads/pic/".$filename; 
           list($width, $height) = getimagesize($filename_1);
                
                   $arr['width']=$width;
                    $arr['height']=$height;
                }
                    
                 $answer_data[]=$arr;
                //1-options//////////////// 
                 $arr = array();
                 
                 //3-options//////////////// 
                 $arr['corect']=($getData[13]==3)?1:0; 
                 $arr['answer']=$getData[7]; 
               
                 if(!empty($getData[11])){
                // $filename=  $arr['image']=$getData[11]; 
                  $filename=$getData[11];
                   $arr['image']="uploads/pic/".$filename;
                }
                
                 $arr['explain']=(!empty($getData[16]))?$getData[16]:0; // distractor3
              
                 if(!empty($getData[11])){
                $filename_1="uploads/pic/".$filename; // CSV image
           list($width, $height) = getimagesize($filename_1);
                
                   $arr['width']=$width;
                    $arr['height']=$height;
                }
                    
                 $answer_data[]=$arr;
				 $arr = array();
                //1-options//////////////// 
                 
                 //4-options//////////////// 
               $arr['corect']=($getData[13]==4)?1:0; 
                 $arr['answer']=$getData[8]; 
				
                 if(!empty($getData[12])){
               //  $filename=  $arr['image']=$getData[12]; 
                  $filename=$getData[12];
                   $arr['image']="uploads/pic/".$filename;
                }
                
                 $arr['explain']=(!empty($getData[17]))?$getData[17]:0; // distractor4
              
                 if(!empty($getData[12])){
                $filename_1="uploads/pic/".$filename; // CSV image
           list($width, $height) = getimagesize($filename_1);
                
                   $arr['width']=$width;
                    $arr['height']=$height;
                }
                    
                 $answer_data[]=$arr;
				 $arr = array();
                //1-options//////////////// 
                 
                 //////////////2nd/////////////
                   $answer_data = addslashes(serialize($answer_data)); 
                   $getData[0]=(!empty($getData[0]))?$getData[0]:0; // 
                    $getData[18]=(!empty($getData[18]))?$getData[18]:0; // public
                    $getData[3]=(!empty($getData[3]))?$getData[3]:0; // category,subject
                   $getData[1]=addslashes($getData[1]); // question
                   $getData[2]=addslashes($getData[2]);// question detail
                   
                   ////// $getData[4]:: objective id of a question
                  $object_arr= explode(":",$getData[4]);
                 // print_r($object_arr); die;
                   /////////////// $current_date
              $sql = "INSERT INTO questions(name,question,author,category,type,answers,passage,public,date_created,bulk_migration)
		VALUES ('{$getData[1]}', '{$getData[2]}', '1','{$getData[3]}','1','$answer_data','{$getData[0]}',
    '{$getData[18]}','$current_date','1' )";
                
             // echo $sql; die;
                $result= mysql_query($sql) or die('error'.mysql_error());		# Return @bool true | false
		// Get inserted id
		$getId = mysql_insert_id();
                
               
                
                // distractor id
                
				if(!isset($result))
                                {  $failed++;
                                 $msg[]='Faild-'.$failed;
                                }else {  $success++; // uploaded
                                
                               // $msg[]='uploaded with  Question id='.$getId;
                                 // + objectives
                if(is_array($object_arr)&&count($object_arr)>0){
                    
                foreach( $object_arr as $object_item ) {
                  // term_relationships , bulk_migrated , question_id , objective_id

           if(intval($object_item)>0){
            $input_term=  mysql_query("INSERT INTO `term_relationships` (question_id,objective_id,bulk_migrated) 
        VALUES ('{$getId}', '{$object_item}','1' )");

                                     }
		

		              }
                
                }
                                } // success
					
                 } // uploade	
				
	       $i++;  }
			
	         fclose($file);	
		 }else $msg[]='no file found!!';
     
  ///////// 
        $msg[]=$success.'-entry uplaoded !!';          
                 
}

/* Process Submittion */


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
						<h3><i class="fa fa-plus-circle"></i>Bulk-upload Questions</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_question" id="form_question" method="post" action="" enctype="multipart/form-data">
<!--							<h4>Add new questions here:</h4>-->
                                                        <?php  
                                                       // $msg[]='fille error';  
                                                       // $msg[]='2 fille error';  $msg[]='32 fille error';
                                                        ?>
                                                        <h4><?php if(is_array(($msg))&&count($msg)>0) echo implode('<br/>', $msg);
                                                                else echo 'Please upload CSV file';  ?></h4>
                                                        
							<div class="add_question_wrap clear fullwidth">
								
                                                                <!--              <p>
									<input type="checkbox" name="question_public"
                                                                               id="question_public" style="vertical-align: sub;" <?php echo $default['public']==1 ? 'checked' : '';?>>
									<label for="question_public">
                                                                            Public Question <em>(Show for free user)</em></label>
								</p>-->
                                                                
                                                                
                                                                
								
								<p>
									<div class="form-group">
                                <label for="school">CSV File</label>
                                <input name="file" title="file_ques" type="file">	 
                                 <!--     <input name="upload" value="Upload" type="submit"> <br>-->

                                <small class="error text-danger" style="display: none;">This field is required!</small>
                            </div>
                                                                
                                                                
                                                                </p>
								
								
								
								
								
								
								
							</div>
							<p>
								<input type="hidden" name="question_id" id="question_id" value="<?php echo $default['id']; ?>" />
								<input type="submit" name="upload" id="question_submit" class="form_button submit_button" value="Submit" />
								<input type="reset" name="question_sreset" id="question_sreset" class="form_button reset_button" value="Reset" />
                                          <a style="padding: 9px 0px; color:#fff;" href="https://intervene.io/questions/uploads/sample-questions.csv" 
                                                                   class="form_button reset_button" >Download Sample CSV</a>                                                    
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
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>

<?php include("footer.php"); ?>
<script type="text/javascript">
	$(document).ready(function(){
	    $("#trans").on("change",function(){
		   if($(this).is(":checked"))
		      $(this).val("1");
		    else
		      $(this).val("0");
		});
	});
</script>