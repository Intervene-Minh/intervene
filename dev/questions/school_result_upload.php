<?php
/****
 * @Create school_assessment
 * @Edit Questions: in school_assessment :Existing Assessment
 @Add Data Dash result- School Grade. 
 @css is added in A Assessment
 []-https://intervene.atlassian.net/browse/IIDS-261
 * **/
$error = '';
$author = 1;
$today=$datetm = date('Y-m-d H:i:s');

include("header.php");
require_once('inc/school_validate.php'); // IMP
$_messages_info=[];
$_messages_info[]='==Testing.';

//
//  $_messages_info[0];




////////School:Assessment Management//////////////////////////
 $provider_name='DMAC';
  if(isset($_POST['upload']))
  {
    //
$cwd = getcwd();
$uploads_dir = $cwd . '/uploads/results';  //rosters schoollogo
$filename=$_FILES["csv_upload"]["name"];
$ext = pathinfo($filename, PATHINFO_EXTENSION);
if(!empty($_FILES['csv_upload']['name'])&&$ext=='csv'){
$tmp_name = $_FILES["csv_upload"]["tmp_name"];
$name = $_SESSION['schools_id'] . '_result_' . basename($_FILES["csv_upload"]["name"]);

if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

// school_id provider_name grade_id
// assessment_id filename status  created updated
// provider_name grade_id assessment_id  
# assessment
$sql=" INSERT INTO  school_provider_1_tab SET school_id='$school_id',
provider_name='$provider_name',
grade_id='".$_POST['grade']."',
assessment_id='".$_POST['assessment']."',
filename='$name',
status='In Space Review',
created='$today' ";  
$query=mysql_query($sql);

$error = ' Uploaded,saved';

}

}else{
if(empty($_FILES['csv_upload']['name']))
$error = 'Select a CSV file !';
elseif($ext!='csv')
$error = 'CSV file required';


}



// In Space Review

} // upload_roster


//////////Edit/Add More questions/////////////
// if ($_SESSION['assess_id'] > 0) {
//     $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
//     $assesment_result = mysql_fetch_assoc($qry);
//     $a_id = $_SESSION['assess_id'];// add More assessment_id
//     // At Edit time  : $assessment_temp_name
//     unset($assessment_temp_name);
    
// }





?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>


<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar_school.php"); ?>
            </div>		
            <!-- /#sidebar -->
            
            
              
            <div id="content" class="col-md-8">
                <?php // print_r($assesment_result) ; ?>
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>
                        <i class="fa fa-plus-circle"></i>Upload Assesments Results</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 >Students Result Upload here:</h4>


                            <?php  // echo $assessment_temp_name, '<br/>' ;?>

                           
                            
                            

                  <!--                             Grade list-->
                            
                            <?php  
                            //echo $a_id.'===add more assessment_id';
                             // print_r($_SESSION['ses_datadash_grade']);
                             $sql="SELECT * FROM `school_permissions` WHERE `school_id` =".$school_id." AND `permission` = 'data_dash' "; 
                             $result=mysql_query($sql); $school_grade_arr=array();// School Grades Array
                             $grade_arr2=array();
                             while( $row = mysql_fetch_array($result) ){
                                 $school_grade_arr[]= $row['grade_level_id']; // grade_level_name
                              $grade_arr2[$row['grade_level_id']]=$row['grade_level_name'];   
                             }
 


      


                                // List of folder
                                $shared_parents = mysql_query("SELECT DISTINCT(`parent`) FROM `terms` WHERE `id` IN (" . implode(',',$school_grade_arr) . ")");
                $parents=array();
		while( $row = mysql_fetch_array($shared_parents) )
			$parents[] = $row['parent']; // School Parent folders : For assign Grade
                               //  print_r($grade_arr2); 
                             if(isset($a_id)&&$a_id >0) { 
                             $folders = mysql_fetch_assoc(mysql_query("SELECT name FROM `terms` WHERE `taxonomy` = 'category' AND id =\"".$assesment_result['grade_id']."\" "));
                              
                            ?>
                            
                            <div class="add_question_wrap clear fullwidth">
                                <?= '<b>Grade: </b>'.$folders['name'];?>   
                                <input type="hidden" name="grade" value="<?=$assesment_result['grade_id']?>" />
                             </div> <?php }else{?>
                  
                  <div class="add_question_wrap clear fullwidth" title="N">
                                                                <p>
                                    <label for="lesson_name">Choose Grade</label>
                                <!-- <select name="grade" class="required textbox" readonly="readonly"> -->
                               

                                <select name="grade" class="required textbox" 
                                onchange="open_asses('<?php print $base_url.'school_result_upload.php?gid='?>', $(this).val());">

                                        <option value=""> Grade<?php//=$_GET['gid']?></option>
                                        <?php  foreach($grade_arr2 as $id=>$name){ 
                                            // ?grade
                                            $sel=(isset($_GET['gid'])&&$id==$_GET['gid'])?'selected':'';

                                            ?>
                                        <option  <?=$sel?> value="<?=$id?>"><?=$name?></option>
                                        <?php }?></select>
                                </p>
                             </div>
                              <?php } ?>

                               <div class="add_question_wrap clear fullwidth">
                               <?php  

                                $district_id=$school_det['district_id'];
 $master_school=$school_det['master_school_id'];
                       $selected_grade=$_GET['gid'];

                                $sql="SELECT a.*, access.* FROM assessments a LEFT JOIN assessments_access access ON a.id = access.assessment_id WHERE a.grade_id='$selected_grade' AND a.created_by IN(0,".$school_id.") AND  
       ( (a.access_level = 'ALL') OR (a.access_level = 'school' AND access.access_level = 'school' AND access.entity_id =".$master_school." ) 
         
        OR (a.access_level = 'district' AND access.access_level = 'district' AND access.entity_id =".$district_id." ) 
        ) GROUP BY a.id ORDER BY a.created_by DESC ";  
       
          $childs =mysql_query($sql);
              $tot_records=mysql_num_rows($childs) ;   


                               ?>
                                <p>
                                    <label for="lesson_name">Assessment:<?=$tot_records?></label>
                                    <select name="assessment" class="required textbox" >
                                    <?php if(isset($_GET['gid'])){ 
                                        while ($row=mysql_fetch_assoc($childs)) {
                                $sel=($row['id']==$_POST['assessment'])?'selected':'';    
                                            # code...
                                        //}

                                        ?>
                                <option <?=$sel?> value="<?=$row['id']?>"><?=$row['assesment_name']?></option> 
                                       

                                                    <?php } 
                                                    }?>           
                                                          </select>
                                </p>
                            
                            </div>

                            <div class="add_question_wrap clear fullwidth">
                                <p>
                                    <label for="lesson_name">Choose(CSV)</label>
                                    <input  type="file" name="csv_upload"  class="required textbox" >
                                    
                                </p>
                            
                            </div>



                  
                  
                  
                  
                  
                             
                            
                            
                  
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                           
                                <?php 
                             

                              // $district=mysql_fetch_array(mysql_query('SELECT * from loc_district WHERE id='.$school_det['district_id']));
                              
                              // $master=mysql_fetch_array(mysql_query('SELECT * from master_schools WHERE id='.$school_det['master_school_id']));


                              // school_name
                                
                              
                              ?>

                 

                           
                            <?php // if(isset($_SESSION['qn_list'])&&count($_SESSION['qn_list']) > 0) {?>
                            
                            <p class="text-center">
                                <input type="submit" name="upload" id="" class="form_button submit_button" value="Submit" />

                                
                               

                            </p> <?php  //}?>
                            

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
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>