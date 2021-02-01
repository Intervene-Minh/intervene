<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Intervene - TELPAS Practice</title>
      <!-- Bootstrap core CSS -->
      <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <meta charset="utf-8" />
   </head>
   <body>
      <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
     
      <a class="navbar-brand" href="https://www.intervene.io">
      <div class="logo-image">
            <img src="https://www.intervene.io/images/intervene.png" height="23px" width="173px" class="img-fluid">
      </div>
</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<!-- Page Content -->
  <br>
      <div class="container">
      <div class="row .d-inline-block" display="inline" style='border-bottom:8px solid #4264BA; border-top:2px solid #000000; border-right:2px solid #000000; border-left:2px solid #000000;'>

 
      <div class="col-12" style="padding:10px;">
        <?php

         $page=$_GET['page'];
        if(isset($_GET['cid'])){

          $couserID = $_GET['cid'];
          session_unset();
        }
     
      if(isset($_SESSION['courseID']))
      {
        $couserID=$_SESSION['courseID'];
      }
      else{
              $_SESSION['courseID'] = $_GET['cid'];
      } 



                require_once'../questions/MoodleWebServices/get-course-content.php';
                $next=1;

               

                /* this is question instant ID */
                $instanceID = $instance[$Qustion[$page]];
                require_once'../questions/MoodleWebServices/get-course-intro.php';

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

        $next= $phpself."?page=".$next;

        $prev  = $phpself."?page=".$pre;

if($page > 1 and $page <count($Qustion)){
?>
<a href="<?php echo $prev?>"><img src="https://www.intervene.io/images/arrowleft.png" width="70" height="55"></a>
<?php } 

if($page < count($Qustion)){
?>
<a href="<?php echo $next?>"><img src="https://www.intervene.io/images/arrowright.png" width="70" height="55"></a>
<?php } ?>

<?php  require_once'../questions/MoodleWebServices/get-coursename.php';?>
<div class="row" style="margin-left: 1px;">
    <strong> Category : <?php echo $responsName['courses'][0]['categoryname']; ?> </strong> &nbsp;&nbsp;&nbsp;
     <strong>Assignment : <?php echo $responsName['courses'][0]['displayname'];?></strong>
</div>
</div>

</div>
</div>

<div class="container">
  <?php if(count($Qustion) > 0){ ?>
   <div class="row" style='border-bottom:2px solid #000000; border-right:2px solid #000000; border-left:2px solid #000000;'>
<?php if(!empty($introOfQuestion)){?>
<div class="col-12" style="text-align: center;padding-top: 20px;font-weight: 700;">
    <?php echo $introOfQuestion;?>
  </div>
<?php } ?>
    <iframe src="https://ellpractice.com/intervene/moodle/mod/hvp/embed.php?id=<?php echo $Qustion[$page];?>" width="100%" height="512" frameborder="0" allowfullscreen="allowfullscreen" allow="microphone ; camera ;">
      </iframe>
<script src="https://ellpractice.com/intervene/moodle/mod/hvp/library/js/h5p-resizer.js" charset="UTF-8"></script>
 <div class="col-12" style="padding: 20px;text-align: right;">

<?php
if($page==count($Qustion)){?>

<a href="https://intervene.io/questions/telpas_practice.php" class="btn" style="color:#fff;background: #4164ba">Complete</a>
<br>
<?php }?>
</div> 
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
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.slim.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>