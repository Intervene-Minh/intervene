<?php setcookie("variable", 1, time() + (86400), "/; SameSite=None; Secure");?>
<style type="text/javascript">
document.cookie = 'cross-site-cookie2=noneCookie; SameSite=None; Secure';    
document.cookie = 'safari_cookie_fix=true; path=/';
window.location.replace(document.referrer);
</style>
<style type="text/css">
  
  body
{
    font-family: "Times New Roman", Times, serif;
}
</style>
<?php
include("iframe-telpass-header.php");

if (!$_SESSION['student_id'])
{
    header('Location: login.php');
    exit;
} 
$_SESSION['TelpasLogin'] = 'Yes';
?>
<style>
.p-20 .image-hotspot-question {
    text-align:center ;
}
.p-20 .h5p-image-hotspot-question .image-wrapper {
    text-align: initial;
}
.p-20 {
  min-width: 600px;
}
.modal {
  position: absolute;
  float: left;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
</style>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div class="row"> <div class="clear" style="padding: 15px"></div>
      </div>
    </div>
  <div class="container">
      <div class="row .d-inline-block inlincss">
        <div class="col-md-6 col-sm-6" style="padding:10px;">
        <?php
          $page=$_GET['q'];
          $couserID = $_GET['c'];
          require_once('MoodleWebServices/get-course-content.php');
         
//echo '<pre>';
         // print_r($respons);
        // echo '</pre>';

         //die;

          $next=1;
          /* this is question instant ID */
         echo  $instanceID = $instance[$Qustion[$page]];
          
          $dataString="?question_id=".$instanceID."&course_id=".$couserID;
          require_once('MoodleWebServices/get-course-intro.php');
          //print_r($responseData);
          //print_r($instanceID);
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

          $phpself   = $_SERVER['PHP_SELF'];
          $next      = $phpself."?q=".$next.'&c='.$couserID;
          $prev      = $phpself."?q=".$pre.'&c='.$couserID;
          if($page > 1){ ?>
<a class="next-ques" 
href="<?php echo $prev?>"><img src="https://www.intervene.io/images/arrowleft.png" width="70" height="55"></a>
<?php } 
if($page < count($Qustion)){
?>
<a  class="next-ques2"
href="<?php echo $next?>"><img src="https://www.intervene.io/images/arrowright.png" width="70" height="55"></a>
<?php }
require_once'MoodleWebServices/get-coursename.php';
$cat_id=$responsName['courses'][0]['categoryid']; ?> 
<div class="row" style="margin-left: 1px;">
    <strong> Category : <?php echo $responsName['courses'][0]['categoryname']; ?> </strong> &nbsp;&nbsp;&nbsp;
     <strong>Assignment : <?php echo $caname=$responsName['courses'][0]['displayname'];?></strong>
</div>
</div>
<div class="col-md-6 col-sm-6" style="padding: 20px;text-align: right;">
<?php if($page==count($Qustion)){?>
<a  href="javascript:void(0)" class="btn next-done" data_id="<?php echo $couserID?>" id="IScomp"
courseName=<?php echo strtolower($caname)?>
  >Complete</a>
<br>
<?php }?>
</div> 
</div>
<?php if(count($Qustion) > 0){ ?>
 <div class="row inline2" >
<?php if(!empty($introOfQuestion)){?>
<div class="col-12 content" style="text-align: left;padding: 20px;">
    <?php echo $introOfQuestion;


    ?>
  </div>
<?php
} 

//echo $questionName;
if (strpos($questionName, 'Record') !== false) {
    require 'audio/record.php';


   // echo 'testttttt';
}
else if (strpos($questionName, 'Writing') !== false) {
require 'writing_text.php';
}
else{ ?>
    <div width="100%" height="100%">
      <iframe class="p-20" src="https://ellpractice.com/dev/mod/hvp/embed.php?id=<?php echo $Qustion[$page];?>" frameborder="0" allowfulscreen="allowfullscreen" allow="microphone ; camera ;"  referrerpolicy="unsafe-url"></iframe>      
    </div>
      <?php } ?>
    </div> 
     <?php } 
     else{ ?>
      <div class="row inline3">
        <div class="alert alert-warning alert-dismissible">
    <a href="https://intervene.io/questions/telpas_practice.php" class="close" aria-label="close">&times;</a>
    <strong>Opps! </strong> In this course not added any question  till Now.
  </div>
</div> 
<?php }?>
</div>
    <!-- TITU STRAT -->
    <div class="modal fade" id="my_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Notice</h4>
      </div>
      <div class="modal-body">
        <p>There is technical issue and It might don't work properly on some browsers. Please use FireFox or Opera, We will fix this problem soon, Sorry for inconvenience</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- TITU END -->
</div>


<script src="../iframeTemplate/vendor/jquery/jquery.slim.min.js"></script>
<script src="../iframeTemplate/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="audio/style-min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script> 
  $('#IScomp').click(function() {
  
    $(this).attr('disabled','disabled');

    var courseType= $(this).attr('courseName');
    var courseID=$(this).attr('data_id');
    if(courseType=='speaking' || courseType=='writing') {
      checkValid(courseID,courseType);
    } else {
      $.ajax({
        type: 'POST',
        url: 'is_compelete_ajax.php',
        timeout: 3000,
        data: {
          course_id:$(this).attr('data_id'),
          iscom:'yes',
          course_cat_id: +'<?php echo $cat_id; ?>',
          courseName:$(this).attr('courseName')
        },
        success: function (data) {
          if (data > 0 ) {
            window.location='https://englishpro.us/questions/telpas_practice.php';
          }
        }
      });
    } 
  });

  function checkValid (courseID, CourseType) {
    $.ajax({
      type: 'POST',
      url: 'is_check_ajax.php',
      data:{
        course_id:courseID,
        CourseType:CourseType
      },
      success: function (data) {
        if(data > 0) {
          window.location='https://englishpro.us/questions/telpas_practice.php';
        } 
      }

    });
  }
</script>
<!-- TITU STRAT -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
 if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ){
          
        }
        else if(navigator.userAgent.indexOf("Chrome") != -1 ){
           // $('#my_modal').modal('show');
        }
        else if(navigator.userAgent.indexOf("Safari") != -1){
          //  $('#my_modal').modal('show');
        }
        else if(navigator.userAgent.indexOf("Firefox") != -1 ){
             
        }
        else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )){
          
        }  
        else{
           $('#my_modal').modal('show');
        }
    
    </script>

<!-- TITU END -->