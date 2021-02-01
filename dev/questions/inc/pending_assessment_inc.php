<?php
   $page_name='Pending Assessments List';
   $no_record='No record found.!';
   $data=array();// Listing array 
   $msg='Pending ==pending_assessments.php';
   $sql="SELECT sa.*,a.assesment_name,a.grade_level_name FROM teacher_x_assesments_x_students sa
   Left Join assessments a ON  sa.assessment_id=a.id
   WHERE sa.teacher_id IN ($student_teacher) AND sa.student_id= '$studentId' ";
   $sql.=" AND sa.status!='Completed' ";
   $sql.=" AND sa.assessment_id>0";
   $result=mysql_query($sql);

   while ($row = mysql_fetch_assoc($result)) {  
      $teacher=mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE id = '".$row['teacher_id']."'"));
      $row['teacher']=$teacher['first_name'].''.$teacher['last_name'];
      $data[]=$row;
   }
   if(!empty($data)>0):
   ?>
<form name="principal_action" id="principal_action" method="POST" action="" >
   <p class="text-success page-title">Pending Assessments</p>
   <table class="table table-hover">
      <tr>
         <th width="26%">Assessment</th>
         <th  width="15%">Teacher</th>
         <th>&nbsp;</th>
      </tr>
      <?php
         $total_records=count($data); // assesment_name
         $i=0;
         foreach ($data as $key => $arr) {



           // echo 'testtt',$arr['status'];
             $start_btn=($arr['status']=="Assigned")?'START':'Resume';
            ?>
      <tr>
         <td>
            <span>  <strong><?=$arr['assesment_name']?> </strong> <br/> </span> 
         </td>
         <td>
            <span>  <strong></strong><?=ucwords($arr['teacher'])?> <br/> </span> 
         </td>
         <td>
            <?php if($arr['status']!="Completed"){  // Resume?> 
            <a  href="startnow_assessment.php?aid=<?=$arr['assessment_id']?>"
               class="btn btn-primary btn-lg"><?=$start_btn?></a> 
            <?php }else echo 'Completed, <br/>Score:NA'?>            
         </td>
      </tr>
      <?php   $i++;     } ?>
   </table>
</form>
<?php endif;?>