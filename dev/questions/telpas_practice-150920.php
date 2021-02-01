<?php
require_once("student_header.php");

  if (!$_SESSION['student_id'])
  {
    header('Location: login.php');
    
  }

	require_once("student_inc.php");
	require_once("MoodleWebServices/get_courseListOnly.php");
  require_once("telpas_query_page.php");
?>
<div id="home main" class="clear fullwidth tab-pane fade in active">
   <div class="container">
      <div id="items_id" style="display:none;">Checking Sesion</div>
        <!-- row -->
      <div class="row">
         <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
            <div style=" width:auto;" title="">
               <?php require_once("nav_students_2.tpl.php"); ?>
            </div>
         </div>
         <!--  NExt col --> 
         <div class="align-center col-md-12">
            <!-- StudentWelcome -->
            <div id="content" style="padding:0px;"> 
               <div class="content_wrap">
                  <div class="ct_heading clear" style="text-align: left;">
                     <h3>Welcome</h3>
                  </div>
                  <!-- /.ct_heading -->
                  <div class="ct_display clear">
                     <div class="item-listing clear">
                        <h3 class="notfound align-center">
                           <a href="#" Sut="<?php echo $_SESSION['student_id']?>">Welcome 
                            <?=(!empty($_SESSION['student_name']))?$_SESSION['student_name']:''?></a>
                        </h3>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- this course-->
         <?php
         $coursesArr=[];
         foreach ($responsCourse['courses'] as $row){
            if($row['categoryid'] > 0){

                  $course_id = $row['id'];
                  $course_name = strtolower($row['fullname']);
                  $categoryid = $row['categoryid'];
                  $categoryname = $row['categoryname'];
                  $course_visible = $row['visible'];
                  $sortorder =$row['sortorder'];
                  // Name 
                 
                  $coursesArr[$categoryid]['categoryname']=$categoryname;
                  $coursesArr[$categoryid]['catID']=$categoryid;
                  $coursesArr[$categoryid]['shortOrder']=$sortorder;
                  $coursesArr[$categoryid][$course_name]=array(
                    'course_id'=>$course_id,
                    'course_name'=>$course_name,
                    'course_visible'=>$course_visible,
                    );}  
                }
          ?>
          <div class="col-md-12">
            <?php
          
array_multisort(array_map(function($element) {
      return $element['catID'];
  }, $coursesArr), SORT_ASC, $coursesArr);
  

  /*echo '<pre>';
  print_r($coursesArr);
  echo '</pre>';
*/
            foreach ($coursesArr as  $courseRow) {

                      $R_ID  =         $courseRow['reading']['course_id'];
                      $L_ID  =         $courseRow['listening']['course_id'];
                      $S_ID  =         $courseRow['speaking']['course_id'];
                      $W_ID  =         $courseRow['writing']['course_id'];
                      $catID =         $courseRow['catID'];

                      $statusR =    $arrStatus[$R_ID]['status'];
                      $statusL =    $arrStatus[$L_ID]['status'];
                      $statusS =    $arrStatusS[$S_ID]['status'];
                      $statusW =    $arrStatusW[$W_ID]['status'];

                      $buttonR  =   $arrStatus[$R_ID]['btn'];
                      $buttonL  =   $arrStatus[$L_ID]['btn'];
                      $buttonS  =   $arrStatusS[$S_ID]['btn'];
                      $buttonW  =   $arrStatusW[$W_ID]['btn'];

                 if($courseRow['reading']['course_visible'] >0 &&
                  $courseRow['listening']['course_visible'] > 0 && 
                  $courseRow['speaking']['course_visible']> 0 &&
                  $courseRow['writing']['course_visible']> 0
                ){


              ?>
   <div class="w-1000" style="margin: 20px 0px;">
    <div class="row text-center">
         <h3 title="Week Practice:" class="text-center" style="margin-bottom: 30px;"><strong><?php echo $courseRow['categoryname']?></strong>
         </h3>
         <!-- Reading-->
         <div class="col-md-3" style="border-right:2px solid #ccc;">
           <?php if($courseRow['reading']['course_visible'] > 0 ){?>
            <p style="letter-spacing:1;" title="Course: Reading">
               <strong><?php echo ucfirst($courseRow['reading']['course_name']);?></strong> 
            </p>
           

            <p>Progress- <?php $progress= $arrR[$R_ID];
                 $score=($progress>0)? $progress :0; 
                  echo number_format($score);
                 ?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php
             $status=($statusR=='')? 'Not started' :$statusR;
             echo $status; ?></strong></span> </p>
          <?php $button=($buttonR=='')? 'START' :$buttonR;?>

            <?php if($arrCrs[$R_ID] == $R_ID){ ?>
            <a href="https://intervene.io/questions/practice_qustion.php?q=1&c=<?php echo $R_ID?>" class="btn btn-primary btn-lg active"><?php echo $button?></a>
            <?php } else{?>

              <a href="https://intervene.io/questions/telpas_enroll.php?el=<?php echo $R_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
            <?php } }?>
         </div>


 <!-- Listening-->
         <div class="col-md-3" style="border-right:2px solid #ccc;">
           <?php if($courseRow['listening']['course_visible'] > 0 ){?>
            <p style="letter-spacing:1;" title="Course: Listening">
               <strong><?php echo ucfirst($courseRow['listening']['course_name']);?></strong> 
            </p>
           
            <p>Progress- <?php
                $progress=$arrR[$L_ID];
                $score=($progress>0)? $progress :0;
                echo number_format($score);
             ?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php 
            $status=($statusL=='')? 'Not started' :$statusL;
            echo $status;?></strong></span> </p>
            <?php $button=($buttonL=='')? 'START' :$buttonL;?>

            <?php if($arrCrs[$L_ID] == $L_ID){ ?>
            <a href="https://intervene.io/questions/practice_qustion.php?q=1&c=<?php echo $L_ID?>" class="btn btn-primary btn-lg active"><?php echo $button?></a>
            <?php } else{?>

              <a href="https://intervene.io/questions/telpas_enroll.php?el=<?php echo $L_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
            <?php } }?>
         </div>

<!-- Speaking-->


         <div class="col-md-3" style="border-right:2px solid #ccc;">
           <?php if($courseRow['speaking']['course_visible'] > 0 ){?>
            <p style="letter-spacing:1;" title="Course: Speaking">
               <strong><?php echo ucfirst($courseRow['speaking']['course_name']);?></strong> 
            </p>
           
            <p>Progress- <?php

            $progress=$arrS[$S_ID];
            $score=($progress>0)? $progress :0;
            echo number_format($score);?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php 
            $status=($statusS=='')? 'Not started' :$statusS;
            echo $status;
            ?></strong></span> </p>
            <?php $button=($buttonS=='')? 'START' :$buttonS;?>

            <?php if($arrCrs[$S_ID] == $S_ID){ ?>
            <a href="https://intervene.io/questions/practice_qustion.php?q=1&c=<?php echo $S_ID?>" class="btn btn-primary btn-lg active"><?php echo $button?></a>
            <?php } else{?>

              <a href="https://intervene.io/questions/telpas_enroll.php?el=<?php echo $S_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
            <?php } }?>

         </div>


<!-- Writing-->
         <div class="col-md-3" style="border-right:1px solid #fff;">
                   <?php if($courseRow['writing']['course_visible'] > 0 ){?>
            <p style="letter-spacing:1;" title="Course: Writing">
               <strong><?php echo ucfirst($courseRow['writing']['course_name']);?></strong> 
            </p>

   
            <p>Progress- <?php 
              $progress=$arrW[$W_ID];
              $score=($progress>0)? $progress :0;
              echo number_format($score);
            ?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php
            $status=($statusW=='')? 'Not started' :$statusW;
            echo $status;?></strong></span> </p>
            <?php $button=($buttonW=='')? 'START' :$buttonW;?>
            <?php if($arrCrs[$W_ID] == $W_ID){ ?>
            <a href="https://intervene.io/questions/practice_qustion.php?q=1&c=<?php echo $W_ID?>" class="btn btn-primary btn-lg active"><?php echo $button?></a>
            <?php } else{?>

              <a href="https://intervene.io/questions/telpas_enroll.php?el=<?php echo $W_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
            <?php } }?>
         </div>
      </div>
   </div>
   <?php } }  ?>
</div>
<!-- end -->
      </div>
   </div>
</div> 
<style>
   .w-1000{
     width:100%;
     display:inline-block;
     margin-bottom:40px;
     border:2px solid #ddd;
     padding-bottom:30px;
   }
</style>
