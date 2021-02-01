<?php 
/****
@Default I-frame url->https://smart.newrow.com/room/?gdb-943&fr=lti
[ses_teacher_id] => 125

//$iframe_url=$default_tutor_board='https://www.groupworld.net/room/2816/lunden?iframe=true';

==============================

curr_active_board
@ Every Dr.homewor sesion open on Groupword
@ Only intervene Session option to on Newrow board. 

**/
 

 session_start();ob_start();
 $ses_arr=array();
 $home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 

// include('inc/public_inc.php'); 
// require_once('inc/check-role.php'); 


// if(!isset($_SESSION['ses_teacher_id'])){ 
//     header('Location:logout.php');exit;
// }




/////////////////////////////////////////
 if(!isset($_SESSION['live_ses_id'])){
  exit('Sessin id missing, launch again from my-sessions list');
 }

/////////////////////////////

  $curr_ses_id=$_SESSION['live_ses_id'];

    $Tutoring=mysql_fetch_assoc(mysql_query("SELECT *
FROM int_schools_x_sessions_log WHERE  id=".$curr_ses_id));

   // print_r($Tutoring);


/////////////Toggle Board :: //////////////
 if(isset($_GET['ac'])){
 	$update_board=''; #Default-- newrow
 	$toggle_board='groupworld';# groupworld || newrow
    $current_baord='';
    $curr_ses_id=$_SESSION['live_ses_id'];

    $Tutoring=mysql_fetch_assoc(mysql_query("SELECT *
FROM int_schools_x_sessions_log WHERE  id=".$curr_ses_id));
    
    if($Tutoring['curr_active_board']=='newrow'){
      
    $update_board='groupworld';
    }elseif($Tutoring['curr_active_board']=='groupworld'){
     	$update_board='newrow';

    }

    
    

 	////////////////////

 $up=mysql_query(" UPDATE int_schools_x_sessions_log  SET curr_active_board='$update_board' WHERE id=".$curr_ses_id);

   $_SESSION['curr_ses_board']=$update_board;
   header("Location:".$_SERVER['PHP_SELF'] ); exit; 

 }
 //////////////////////////////////////////////////
 /**
 1.check for Board type in Tutoring 
 2. Check for Tutor launch URL .


 ***/

  $tutor_id=$_SESSION['ses_teacher_id'];//'turor id'
$tutor_det= mysql_fetch_assoc(mysql_query(" SELECT * FROM gig_teachers WHERE 1 AND id=".$tutor_id));
 $tutor_new_board_url=$tutor_det['url_aww_app'];  # FROM gig_teachers #4-april 2019




if(isset($tutor_det['url_aww_app'])&&!empty($tutor_det['url_aww_app'])){
  $iframe_url=$tutor_det['url_aww_app'].'?iframe=true';
}

 

?>



<!-- Access Control- <a href="#"   class="btn btn-success btn-xs">
                              Switch / toggle to Newrow</a> |&nbsp; &nbsp; -->

                          



                              <hr/>
                              <br/>

  <!-- Board newrow -->
  
<?php  //if($_SESSION['curr_ses_board']=='groupworld'){  //IMP 
  //echo '=============BOARD TYPE'; 

  ?>



  <div id="main" class="clear fullwidth">

<!-- <div class="logo">
          
          <a href="https://tutorgigs.io/" title="Less Test Prep"><img alt="Intervene" src="images/logo.png"></a>
        </div> -->

  <div class="container">
    <div class="row">
    


      <div id="content" class="col-md-12">
        <?php 

      //echo 'URL-- '.$iframe_url;  die; 
        ?>

      <style>html,body { height: 100%; margin: 0px }</style> 
      <iframe allow="microphone; camera; display"  style="width: 100%;height:900px;" 
      scrolling="no" frameborder="0" src="<?=$iframe_url?>"></iframe>


      </div>
                  

      <div class="clearnone">&nbsp;</div>
    </div>
  </div>
</div>    <!-- /#header -->







