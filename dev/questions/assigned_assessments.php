<?php
/****
 $start_date = date('Y-m-d h:i:s');

  @Completed Assessment
  @ assigned_assessments.php
 * **/
 $page_name='Assigned Assessments List';
 $page_name='Assigned Data Dash Tests';
 $no_record='No record found.!';
include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}


///@@@@@@@@@@@@
$data=array();// Listing array 
include("student_inc.php");
$msg='Pending ==pending_assessments.php';
 // print_($_SESSION);

// $sql="SELECT * FROM `teacher_x_assesments_x_students` WHERE `teacher_id` = '".$student_det['teacher_id']."' AND `student_id` =".$_SESSION['student_id'];

$student_school=$student_det['school_id'];
$student_teacher=$student_det['teacher_id'];// 

$sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
Left Join assessments a ON  sa.assessment_id=a.id
WHERE sa.teacher_id = '$student_teacher' AND sa.student_id= '".$_SESSION['student_id']."' ";
// Completed
 $sql.=$change=" AND sa.status='Assigned' ";

 // echo '<pre>', $sql;  die;
$result=mysql_query($sql);
while ($row= mysql_fetch_assoc($result)) {  
   //$row['asssessment']='Demo asement';
  $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];

  $data[]=$row;
  # code...
}






/////////////Display
//echo $msg;
 // echo '<pre>';
 // print_r($data);
 // echo 'Student==';
 // print_r($student_det);

?>

<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom: 100px;">
                
                <div style=" width:auto;" title="">
                 <?php include("nav_students.php"); ?>
       

        
                
                      </div>    





            </div>
           <!--  NExt col -->
           <div class="align-center col-md-12">
            <p class="text-success page-title"><?=$page_name?> </p>

          
    <form name="principal_action" id="principal_action" method="POST" action="">
        <table class="table table-hover">
            <tr>
                <th>Assessment</th>
                <th>Detail</th>
                <th>Other</th>
               
                <th>Status/Result</th>
            </tr>
            <?php
        
     
   
       $total_records=count($data); // assesment_name
       if($total_records>0){
       $i=0;
      foreach ($data as $key => $arr) {
        # code...
       //Only pending 
        //if($arr['status']!='In Progress')continue;
       // echo $arr['assesment_name'].'==<br/>';

           $start_btn=($arr['status']=="Assigned")?'START':'Resume';

  

              
        
          ?>
          <tr>
                <td>
                 <span>  <strong>assesment_name: </strong><?=$arr['assesment_name']?> <br/> </span> 
                   
                 
                   <span>  <span class="btn btn-default btn-sm"><?=$arr['status']?></span> <br/> </span> 
                    
           
            <!--  <span class="btn btn-success btn-sm">   11:20 am             </span>  -->
              
                </td>

                
                        <td>
                  <span>  <strong>Teacher: </strong><?=$arr['teacher']?> <br/> </span> 

                  <a target="_blank" href="startnow_assessment.php?aid=<?=$arr['assessment_id']?>"
                   class="btn btn-green">TEST</a>
                 <!--  <span>  <strong>assesment_name: </strong><?=$arr['assesment_name']?> <br/> </span>  -->
                    

                      </td>
                 
                                    
                 
                 
                
                 
                  
                  <td> <span>  <strong>Result: </strong>NA<?php //=$arr['teacher']?> <br/> </span>  </td>

                  <td>
                     <?php if($arr['status']!="Completed"){  // Resume?> 
                 <a target="_blank" href="startnow_assessment.php?aid=<?=$arr['assessment_id']?>"
                   class="form_button submit_button"><?=$start_btn?></a> 

                      
                               
                        <?php }else echo 'Completed, <br/>Score:NA'?>            
                
                   
                  </td>
                
            </tr>
            
           
          <?php   $i++;     }   }else{ //echo $no_record;?>

          <tr><td colspan="4" align="align-center"> <?=$no_record?> </td> </tr>   
           

          <?php }?>

            
            
            
            
            
            
            
            
        </table>
        
        
        
        
        
        
        
    </form>
           
<!--                dffffdf-->
                
            </div>

           <!-- C0ntent -->



        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>

