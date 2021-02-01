<?php
include("iframe-telpass-header.php");
//print_r($_SESSION); die; 

/////////////////
if (!$_SESSION['student_id'])
{
    header('Location: login.php');
    exit;
} 

?>
<style type="text/css">
  .atto_image_button_text-bottom{margin-left: 360px}

.content img{ margin-left:400px; }
</style>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div class="row"> <div class="clear" style="padding: 15px"></div>
      </div>
    </div>
  <div class="container">
      <div class="row .d-inline-block" display="inline" style='border-bottom:8px solid #4264BA; border-top:2px solid #000000; border-right:2px solid #000000; border-left:2px solid #000000;'>

      <div class="col-md-6" style="padding:10px;">
        <?php
         $page=$_GET['q'];
         $couserID = $_GET['c'];

          require_once'MoodleWebServices/get-course-content.php';
          $next=1;

          /* this is question instant ID */
      $instanceID = $instance[$Qustion[$page]];
      $dataString="?question_id=".$instanceID."&course_id=".$couserID;
          require_once'MoodleWebServices/get-course-intro.php';

      $pre=count($Qustion); 

       if(isset($page))
      {
          if($page < $pre )
          {
             $next = $page+1;
          } 

          if($page > 1)
          {
              $pre = $page-1;
          }
       }  

        $phpself = $_SERVER['PHP_SELF'];

        $next= $phpself."?q=".$next.'&c='.$couserID;

        $prev  = $phpself."?q=".$pre.'&c='.$couserID;

if($page > 1){
?>
<a href="<?php echo $prev?>"><img src="https://www.intervene.io/images/arrowleft.png" width="70" height="55"></a>
<?php } 

if($page < count($Qustion)){
?>
<a href="<?php echo $next?>"><img src="https://www.intervene.io/images/arrowright.png" width="70" height="55"></a>
<?php } ?>

<?php  require_once'MoodleWebServices/get-coursename.php';?> 
<div class="row" style="margin-left: 1px;">
    <strong> Category : <?php echo $responsName['courses'][0]['categoryname']; ?> </strong> &nbsp;&nbsp;&nbsp;
     <strong>Assignment : <?php echo $responsName['courses'][0]['displayname'];?></strong>
</div>
</div>

<div class="col-md-6" style="padding: 20px;text-align: right;">
<?php
if($page==count($Qustion)){?>

<a href="https://intervene.io/questions/telpas_practice.php?iscom=<?php echo $couserID?>" class="btn" style=" padding: 10px 30px; font-size: 20px; color:#fff;background: #4164ba">Complete</a>
<br>
<?php }?>
</div> 

</div>
<?php if(count($Qustion) > 0){ ?>
   <div class="row" style='border-bottom:2px solid #000000; border-right:2px solid #000000; border-left:2px solid #000000;'>
<?php if(!empty($introOfQuestion)){?>
<div class="col-12 content" style="text-align: left;padding: 20px;">
    <?php echo $introOfQuestion;?>
  </div>
<?php 

} 

if (strpos($questionName, 'Record') !== false) {
require 'audio/record.php';
}
else{
?>
    <iframe class="p-20" src="https://ellpractice.com/intervene/moodle/mod/hvp/embed.php?id=<?php echo $Qustion[$page];?>" width="100%" height="512" frameborder="0" allowfullscreen="allowfullscreen" allow="microphone ; camera ;">
      </iframe>

<?php } ?>

       </div> 
     <?php } 
     else{ ?>
      <div class="row" style='border-bottom:2px solid #000000; border-right:2px solid #000000; border-left:2px solid #000000;padding: 33px'>
        <div class="alert alert-warning alert-dismissible">
    <a href="https://intervene.io/questions/telpas_practice.php" class="close" aria-label="close">&times;</a>
    <strong>Opps! </strong> In this course not added any question  till Now.
  </div>

   </div> 
<?php }?>
</div>
</div>
<style>
body {
    margin: 0;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    font-weight: 400;
    line-height: 1.5;
    color: #373a3c;
    text-align: left;
}
.no-overflow p{
    margin: 0;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
    color: #373a3c;
    text-align: left;
}
h5, .h5 {
    font-size: 18px;
	margin-bottom: .5rem;
    font-weight: 300 !important;
    line-height: 1.2;
    color: #000 !important;
	font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .path-calendar .maincalendar .calendar-controls .current, .h3, .h4, .h5, .h6	{
    margin-bottom: .5rem;
    font-weight: 300 !important;
    line-height: 1.2;
    color: #000 !important;
	font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
}
b, strong {
    font-weight: bolder;
}
h5 li {
    list-style: disc;
    margin-bottom: 1rem;
}
h5 ul {
    margin-top: 0;
    margin-bottom: 10px;
    padding: 0px 20px;
}
.content img {
     margin: 0px auto;
}
.content audio {
    display: block;
    width: 31%;
    margin: 0rem auto;
}
.p-20{
    padding: 0px 0px 0px 20px !important;
}
video {
    display: block;
    vertical-align: baseline;
    margin: 0px auto;
}
</style>
<script src="../iframeTemplate/vendor/jquery/jquery.slim.min.js"></script>
<script src="../iframeTemplate/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="audio/record.css">
<!-- inserting these scripts at the end to be able to use all the elements in the DOM -->
<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="audio/js/app.js"></script>