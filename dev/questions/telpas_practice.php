<?php
require_once("student_header.php");

  if (!$_SESSION['student_id'])
  {
    header('Location: login.php');
    
  }

  require_once("student_inc.php");
  require_once("MoodleWebServices/get_courseListOnly.php");
  require_once("MoodleWebServices/get-category-list.php");
  require_once("telpas_query_page.php");
  $siteURL="https://" . $_SERVER['SERVER_NAME'].'/questions/'; 
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

         $catOrderArr=[];

            array_multisort(array_map(function($element) {
            return $element['sortorder'];
            }, $responCategory), SORT_ASC, $responCategory);

            foreach ($responCategory as $k => $caT) {
            # code...
            if($caT['visible'] > 0){
            $catOrderArr[$caT['id']]= $caT['name'];
           // $catOrderArr[]= $caT['id'];
            }

            }

           // echo "<pre>";
            //print_r($catOrderArr);
            //echo "</pre>";
         $coursesArr=[];
         foreach ($responsCourse['courses'] as $row){
            if($row['categoryid'] > 0){


                $course_id = $row['id'];
                 $str = strtolower($row['fullname']);
                 $course_name = str_replace(' ', '', $str);
                  $categoryid = $row['categoryid'];
                  $categoryname = $row['categoryname'];
                  $course_visible = $row['visible'];
                  // Name 
                 
                  $coursesArr[$categoryid]['categoryname']=$categoryname;
                  $coursesArr[$categoryid]['catID']=$categoryid;
                  $coursesArr[$categoryid][$course_name]=array(
                    'course_id'=>$course_id,
                    'course_name'=>$course_name,
                    'course_visible'=>$course_visible,
                    );}  
                }
          ?>
          <div class="col-md-12">
            <?php
      

    // echo '<pre>';
//print_r($coursesArr);
    // echo '</pre>';
           // foreach ($coursesArr as  $courseRow) {
          //print_r($responCategory);
     foreach ($responCategory as $k => $caT) {
          
          if($caT['visible'] > 0){


                    $R_ID  =         $coursesArr[$caT['id']]['reading']['course_id'];
                    $L_ID  =         $coursesArr[$caT['id']]['listening']['course_id'];
                    $S_ID  =         $coursesArr[$caT['id']]['speaking']['course_id'];
                    $W_ID  =         $coursesArr[$caT['id']]['writing']['course_id'];
                    $catID =         $coursesArr[$caT['id']]['catID'];

                    $statusR =    $arrStatus[$R_ID]['status'];
                    $statusL =    $arrStatus[$L_ID]['status'];
                    $statusS =    $arrStatusS[$S_ID]['status'];
                    $statusW =    $arrStatusW[$W_ID]['status'];

                    $buttonR  =   $arrStatus[$R_ID]['btn'];
                    $buttonL  =   $arrStatus[$L_ID]['btn'];
                    $buttonS  =   $arrStatusS[$S_ID]['btn'];
                    $buttonW  =   $arrStatusW[$W_ID]['btn'];




                ?>
   <div class="w-1000" style="margin: 20px 0px;">
    <div class="row text-center">
         <h3 title="Week Practice:" class="text-center" style="margin-bottom: 30px;"><strong><?php echo $caT['name']?></strong>
         </h3>
              <?php 


              if($coursesArr[$caT['id']]['reading']['course_visible'] > 0 ){?>
         <!-- Reading-->
         <div class="col-md-3" style="border-right:2px solid #ccc;">
      
            <p style="letter-spacing:1;" title="Course: Reading">
               <strong><?php echo ucfirst($coursesArr[$caT['id']]['reading']['course_name']);?></strong> 
            </p>
           

            <p>Progress- <?php $progress= $arrR[$R_ID];
                 $score=($progress>0)? $progress :0; 
                  echo number_format($score);
                 ?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php
             $status=($statusR=='')? 'Not started' :$statusR;
             echo $status; ?></strong></span> </p>
          <?php $button=($buttonR=='')? 'START' :$buttonR;?>

          <a href="<?php echo $siteURL?>telpas_enroll.php?el=<?php echo $R_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
            
         </div>

 <?php } ?>
 <?php if($coursesArr[$caT['id']]['listening']['course_visible'] > 0 ){?>
 <!-- Listening-->
         <div class="col-md-3" style="border-right:2px solid #ccc;">

            <p style="letter-spacing:1;" title="Course: Listening">
               <strong><?php echo ucfirst($coursesArr[$caT['id']]['listening']['course_name']);?></strong> 
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
            <a href="<?php echo $siteURL?>telpas_enroll.php?el=<?php echo $L_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
          
         </div>
<?php } ?>
<!-- Speaking-->
  <?php if($coursesArr[$caT['id']]['speaking']['course_visible'] > 0 ){

    $arrStaus=get_statusSW('telpas_student_speaking_grades',$S_ID);?>

         <div class="col-md-3" style="border-right:2px solid #ccc;">
         
            <p style="letter-spacing:1;" title="Course: Speaking">
               <strong><?php echo ucfirst($coursesArr[$caT['id']]['speaking']['course_name']);?></strong> 
            </p>
           
            <p>Progress- <?php

            $progress=$arrStaus['prog'];
            $score=($progress > 0 )? $progress :0;
            echo number_format($score);?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php 
            $status=($arrStaus['status']=='')? 'Not started' :$arrStaus['status'];
            echo $status;
            ?></strong></span> </p>
            <?php $button=($arrStaus['btn']=='')? 'START' :$arrStaus['btn'];?>
            <a href="<?php echo $siteURL?>telpas_enroll.php?el=<?php echo $S_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
     

         </div>

<?php } ?>
   <?php if($coursesArr[$caT['id']]['writing']['course_visible'] > 0 ){

 $arrStaus=get_statusSW('telpas_student_writing_grades',$W_ID);

    ?>
<!-- Writing-->
         <div class="col-md-3" style="border-right:1px solid #fff;">
                  
            <p style="letter-spacing:1;" title="Course: Writing">
               <strong><?php echo ucfirst($coursesArr[$caT['id']]['writing']['course_name']);?></strong> 
            </p>

   
            <p>Progress- <?php 
               $progress=$arrStaus['prog'];
              $score=($progress>0)? $progress :0;
              echo number_format($score);
            ?>% </p>
            <p> Status- <span style="color:#337ab7"><strong><?php
                $status=($arrStaus['status']=='')? 'Not started' :$arrStaus['status'];
            echo $status;?></strong></span> </p>
               <?php $button=($arrStaus['btn']=='')? 'START' :$arrStaus['btn'];?>
            <a href="<?php echo $siteURL?>telpas_enroll.php?el=<?php echo $W_ID?>&cr=<?php echo $catID?>" class="btn btn-primary btn-lg active" 
              ><?php echo $button;?></a>
         
         </div>
       <?php }?>
      </div>
   </div>
   <?php } }   ?>
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
