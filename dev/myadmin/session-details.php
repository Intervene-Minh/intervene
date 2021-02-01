<?php
$error='';
include("header.php");
// $_SESSION['login_role']

$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
//$sql.=" AND ses_start_time >= '$startTime' and ses_start_time <= '$endTime' ";
$sql.=" AND id=".$_GET['sid'];  // sessionID

$Tutoring=mysql_fetch_assoc(mysql_query($sql));   
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>

             <div id="content" class="col-md-8">
            <div id="folder_wrap" class="content_wrap">

               <div class="ct_heading clear">
                  <h3>Tutoring Session details</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <?php    if ($error != '') {  echo '<p class="error">' . $error . '</p>';   }  // Inlude  message    ?>



                  <?php  if($Tutoring['Tutoring_client_id']=='Drhomework123456'){
                     include "views/drhomework_tutoring_info.php";
                  } 
                  else{
                     include "views/intervene_tutoring_info.php";
                      } ?>
                  <div class="clearnone">&nbsp;</div>
               </div>  
                <!-- End:ct_display clear -->

            </div>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- /#header -->
<?php include("footer.php"); ?>