<?php
/****
 * @school : assessment list
 * @ //  print_r(array_unique($a));
 * $school_id: School in Session

 @ Only Mark active by school show in list.
 @ Searh only actual assessments=
 [No demo assessment for school user]
 * ******/

//////




$error= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
require_once('inc/school_validate.php'); // IMP
//echo $school_id; die;
$edit_url="edit_school_assessment.php";
$add_more_ques_url="school_assessment_step3.php";
$duplicate_url="school_assessment_duplicate.php";



////////Delete a test///////////
#multiple not allowed , some may assigned or not 
if(isset($_GET['del'])&&is_school_assessment(intval($_GET['del']),$school_id)==0){
   // exit('Not allowed');
     $_SESSION['ses_msg_1']='Sorry, You can not Deleted!!';// modified
      header('Location:school_assessment_list.php'); exit;
     
 }elseif(isset($_GET['del'])&&is_school_assessment(intval($_GET['del']),$school_id)==1){
      // assessments assessments_access assessments_x_questions, teacher_x_assesments_x_students//  students_x_assesments
     
       $del_id=$_GET['del'];
       
    
   $sql=mysql_query("SELECT * FROM `teacher_x_assesments_x_students` WHERE `assessment_id` =".$del_id);//21 =4
   $toal_assignement=mysql_num_rows($sql);
   // Check for Test assigned or Not
   if($toal_assignement>0){
       $_SESSION['ses_msg_1']= $toal_assignement.'-Record found, can not delete,\n Teacher already assigned this test!';
   }elseif($toal_assignement==0){
       
        $access_delete=mysql_query("DELETE FROM `assessments_access` WHERE `assessment_id` =".$del_id);
    $assessment_q_del=mysql_query("DELETE FROM `assessments_x_questions` WHERE `assesment_id` =".$del_id);
    $test=mysql_query("DELETE FROM `assessments` WHERE `id` =".$del_id);
        $_SESSION['ses_msg_1']='Test Deleted!';//  $del_id
        
   }
   
 header('Location:school_assessment_list.php'); exit; 
  

 }


/////////////////////////////

//if(isset($_GET['assesment_id'])){
//$qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
//$assesment_result = mysql_fetch_assoc($qry); 
//$qry2 = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
//$assesment_det= mysql_fetch_assoc($qry2); 
//print_r($assesment_det); die;
//
// }


 
 
 
 
 $assementArr=array(); // for search
 $is_serach=0;// no seach
 
 
 
 
 
/////////Search//////////
  $district_id=$school_det['district_id'];
 $master_school=$school_det['master_school_id'];
if (isset($_POST['assesment_submit'])) {
    //print_r($_POST); die;
    
    $is_serach=1;
      $sql_query=' SELECT a.* FROM assessments a WHERE 1 ';
    
   
   
    if(isset($_POST['assesment_name'])&&!empty($_POST['assesment_name'])){
      // $change=' WHERE AND a.assesment_name LIKE "%'.$_POST['assesment_name'].'%" AND ';
        $assess_name=$_POST['assesment_name'];
    $created=(isset($_POST['princ_created']))?$school_id:"0,".$school_id;
      
     $sql_query="SELECT a.*, access.* FROM assessments a LEFT JOIN assessments_access access ON a.id = access.assessment_id WHERE a.status_active='yes' AND a.created_by IN(".$created.") AND "
             . "a.assesment_name LIKE '%".$assess_name."%' AND 
       ( (a.access_level = 'ALL') OR (a.access_level = 'school' AND access.access_level = 'school' AND access.entity_id =".$master_school." ) 
         
        OR (a.access_level = 'district' AND access.access_level = 'district' AND access.entity_id =".$district_id." ) 
        ) GROUP BY a.id ORDER BY a.created_by DESC "; 

        /// seeach 2 ///

         $sql_query="SELECT a.*, access.* FROM assessments a LEFT JOIN assessments_access access ON a.id = access.assessment_id WHERE a.is_demo='no' AND a.status_active='yes' AND a.created_by IN(".$created.") AND "
             . "a.assesment_name LIKE '%".$assess_name."%' AND 
       ( (a.access_level = 'school' AND access.access_level = 'school' AND access.entity_id =".$master_school." ) 
         
        OR (a.access_level = 'district' AND access.access_level = 'district' AND access.entity_id =".$district_id." ) 
        ) GROUP BY a.id ORDER BY a.created_by DESC "; 

       // echo $sql_query; die; 



    
       
   
    }elseif(isset($_POST['princ_created'])){
         $sql_query=' SELECT a.* FROM assessments a WHERE 1 ';
         $sql_query.=' AND a.created_by='.$school_id; 
          $sql_query.=' ORDER BY created DESC '; 
    }
     
   
     
 } 

 

/////xx echo $district_id.'===M'.$master_school;
 
 if($is_serach==1){
    #Search Assessment 
  // echo 'Searh only actual assessments=';
   
  //  echo $sql_query ;

  ////////////////////////
    $childs = mysql_query($sql_query);  
      }else{
             // Intervene or School only Assessmentmysql_query
         
       $sql="SELECT a.*, access.* FROM assessments a LEFT JOIN assessments_access access ON a.id = access.assessment_id WHERE a.is_demo='no' AND a.status_active='yes' AND a.created_by IN(0,".$school_id.") AND  
       ( (a.access_level = 'ALL') OR (a.access_level = 'school' AND access.access_level = 'school' AND access.entity_id =".$master_school." ) 
         
        OR (a.access_level = 'district' AND access.access_level = 'district' AND access.entity_id =".$district_id." ) 
        ) GROUP BY a.id ORDER BY a.created_by DESC ";  

       // echo $sql;  die; 
       
       $childs =mysql_query($sql);  
         
      }
      
      
      
      
    $tot_records=mysql_num_rows($childs) ;    
 //Message////   
    if(!empty($_SESSION['ses_msg_1'])){
        $error=$_SESSION['ses_msg_1']; unset($_SESSION['ses_msg_1']);
    }
///////////////ttt/////////////////////////////    
   // print_r($school_det); $school_id.'===';
    
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
//    $(document).ready(function () {
//
//        $('#district').chosen();
//
//        $('#district').change(function () {
//            district = $(this).val();
//
//            $('#district_school').html('Loading ...');
//            $.ajax({
//                type: "POST",
//                url: "ajax.php",
//                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},
//                success: function (response) {
//                    $('#district_school').html(response);
//                    $('#d_school').chosen();
//                },
//                async: false
//            });
//        });
//        $('#district').change();
//    });
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar_school.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                             
                            <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
      <input type="text"  style=" width: 100%" name="assesment_name" class="required textbox" 
             value="<?=(isset($_POST['assesment_name']))?$_POST['assesment_name']:NULL;?>" />
                                </p></div>
                            
                            <div class="col-md-12">
                                
                                
                                <p>
                                    <input name="princ_created"  <?=(isset($_POST['princ_created']))?'checked':''?> style="vertical-align: sub;" type="checkbox">
                                    <label for="question_public" class="text-primary">Created By-<?=$school_det['SchoolName']?>(<strong>Me</strong>)</label>
								</p>
                                
                                
                                </div>
                            
                            
                            

                            
                            <p style=" margin-top: 10px;text-align: center;">
           <input type="submit" name="assesment_submit" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Search" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
                                              <?php } ?>

                            </p>

                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div> 
                <!--Search Pan--->
                
                
                
                
                
                
                
                
                
                
                
                 <div class="clearnone">&nbsp;</div> <br/>
                 
                <!-- List of Assesments---->
               
                
                
                <form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Assessment List(<?=$tot_records;?>)</h3>
                                             <!-- <ul>
				 <li>
								<button id="delete-user" type="submit" 
                                                                        name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
							</li>
							
                                                       
						</ul>-->
					</div>		<!-- /.ct_heading -->
					<div class="clear">
                                            
                                           
                                            
				<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="220">
									
									<col width="100">
                                                                        <col width="100">
									
									<col width="140">
								</colgroup>
								<tbody><tr>
									
									<th>Assessments</th>
									<th>District</th>
                                                                        <th>Schools</th>
                                                                        <th>Action</th>
                                                                       
								</tr>
																		
                                                         <?php 
                                       if( mysql_num_rows($childs) > 0 ) {
			
			$i = 1;
			while( $row= mysql_fetch_assoc($childs) ) {
                            
                           # record det
          $data= mysql_fetch_assoc(mysql_query(" SELECT * FROM assessments WHERE id='".$row['id']."' "));  
                            //////////////////
                           $distList="All";    $schoolList="All";                         
                            if(!empty($data['access_level'])){ //  formainRec
                                //   	assessment_id access_level entity_id
                                # ORDER BY created ASC
                        $qq= mysql_query(" SELECT * FROM assessments_access WHERE assessment_id='".$row['id']."' "); 
                           // 3 TAB data ... 
                        $arr_dist=array(); $arr_schools=array();
                        $districts=NULL;$schools=NULL; $x=NULL;
                        ///////////////////
                        while($line= mysql_fetch_assoc($qq) ) {
                         // dat of schools or Discrict
                            if($line['access_level']=="district"){
                    $det = mysql_fetch_assoc(mysql_query(" SELECT * FROM loc_district WHERE id='".$line['entity_id']."' "));
                       $x.=(!empty($x))?",<br/>".$det['district_name']:$det['district_name'];
                            }
                              
                            if($line['access_level']=="school"){
                    $det = mysql_fetch_assoc(mysql_query(" SELECT * FROM master_schools WHERE id='".$line['entity_id']."' "));
                         $schools.=(!empty($schools))?",".$det['school_name']:$det['school_name'];    
                    
                    
                            }
                            
                    
                        }// end while
                        
                        ////////////////////////
                        
                        
                            
                            }
                            ?>       
                                                                
                                                                <tr> 
                                                   <td><strong><?=$data['assesment_name']?></strong></td>
                                                           <td><?=(!empty($x))?$x:"All"; ?></td>
                                                         <td><?php //=$schools?>
                                                         <?=(!empty($schools))?$schools:"All"; ?>
                                                         
                                                         
                                                         </td>
                                                                  
                                                         <td class="text-center">
                                                            <?php # echo $school_id ; 
                                                        if($school_id>0&&$school_id==$data['created_by']){
                                                             
                                                            ?>            
                                      <a   title="Edit" href="edit_school_assessment.php?assesment_id=<?=$row['id']?>">
                                          <span class="glyphicon glyphicon-pencil"></span></a>
                                           &nbsp;&nbsp;
                                          
                                          <a  title="+Add More Questions" href="<?=$add_more_ques_url."?assesment_id=".$row['id']?>">
                                              <span class="fa fa-plus-circle"></span></a> &nbsp;&nbsp;
                                         
                                           <a  title="Duplicate: <?=$data['assesment_name']?>" href="<?=$duplicate_url."?assesment_id=".$row['id']?>">
                                          <span class="fa fa-copy"></span></a>&nbsp;&nbsp; 
                                 
                                          <a  title="Delete Test"  href="school_assessment_list.php?del=<?=$row['id']?>"
                                      onclick="return confirm('Are you sure, you want to delete this?');" ><span class="glyphicon glyphicon-trash"></span></a>
                                                                        
                                                        <?php }else echo '<strong class="text-primary">Intervene</strong>'; ?>              
                                                         
                                                       
                                                                    </td>
                                                                </tr>
                                    <?php
                        $i++;}
                                       }
                                                                
                                                                ?>
                                                                
                                                                </tbody></table>
												<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input id="arr-user" name="arr-user" value="" type="hidden">
				</form>
                
                     <!----Assessment List--->
                
                
                
                
                
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php 
 

if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>

<?php 
print_r($_SESSION);

// echo '============';
?>