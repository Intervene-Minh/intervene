<?php
session_start();
	ob_start();
   if(!isset($_SESSION['login_id'])) {
	#header('Location:https://tutorgigs.io/login.php');exit; // live
	
}  
///////////////
        
        
  ////////////////     
        
@extract($_GET) ;
@extract($_POST); 
//include('inc/connection.php'); 
//include('inc/public_inc.php'); 
//$link = @mysql_connect('localhost', 'root', 'root')or die(mysql_error());
//$conn=mysql_select_db('lonestaar', $link)or die(mysql_error());
/////////////////
/****
 * $link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);
 * **/
$link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);

        
        //echo 'demo test landing'; die;
global $base_url;
$base_url="https://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
$home_url="https://tutorgigs.io/"; 
$login_url="https://tutorgigs.io/login.php"; 

$int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name FROM users WHERE id=9"));
 //echo $int_th['first_name']."--Last name of teacher ";

?>


<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>Tutor sessions </title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700">
  <link rel="stylesheet" href="https://weloveiconfonts.com/api/?family=fontawesome">
  
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style-personal.css">
  <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <script src="assets/js/simplecalendar.js" type="text/javascript"></script>
  
  
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"> <a href="https://tutorgigs.io/myadmin/teachers-list.php">Home</a>|List of Tutor sessions
        </h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="calendar hidden-print">
          <header>
            <h2 class="month"></h2>
            <a class="btn-prev fontawesome-angle-left" href="#"></a>
            <a class="btn-next fontawesome-angle-right" href="#"></a>
          </header>
          <table>
            <thead class="event-days">
              <tr></tr>
            </thead>
            <tbody class="event-calendar">
              <tr class="1"> </tr>
              <tr class="2"></tr>
              <tr class="3"></tr>
              <tr class="4"></tr>
              <tr class="5"></tr>
            </tbody>
          </table>
          <div class="list">
           
              
              
              
              
              <?php 
              $Date = "2018-02-03"; // Feb 60 days
              for($i = 1; $i <= 60; $i++){ 
                 // echo date('Y-m-d', strtotime($Date. ' +'.$i.' days')); echo '<br/>';
                  $getdate= date('Y-m-d', strtotime($Date. ' +'.$i.' days')); //echo '<br/>';
                  $arr= explode("-", $getdate);//Y-m-d
                  $end_date=$getdate." 23:59:59.999";
                       // check for > Total session in particular day  , else continu;
                $qq=" SELECT * FROM `int_schools_x_slots_master` WHERE 1
AND slot_date_time between '$getdate' and '$end_date' ";  
                $tot_ses=0;//$dff=mysql_query($qq);d
                $results=mysql_query($qq);
                $tot_ses= mysql_num_rows($results);
                ///Total session in Date if > 0 else continue;  
                  if($tot_ses<1)continue;
                  
              ?><div class="day-event" date-day="<?=intval($arr[2])?>" date-month="<?=intval($arr[1])?>" date-year="<?=$arr[0]?>"  data-number="1">
              <a href="#" class="close fontawesome-remove"></a>
              <h2 class="title">
                  <?=$getdate?><br/>
                  <?=$tot_ses?>-Session today</h2>
              
              
              <?php if($tot_ses>0){
                  while( $row = mysql_fetch_assoc($results) ) {
           $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));          
                $tutor_name="Tutor Not assigned"; 
           if($row['tut_teacher_id']>0)
           $tutor_name=$tut_th['f_name']." ".$tut_th['lname'];
           // echo $row['slot_date_time']; 
           // date('H:i:s A T',strtotime($date));
           ?>
              
              <p class="text-danger"><?=date('H:i:s A ',strtotime($row['slot_date_time'])); ?>, 
                  
                  <strong class="text-primary" > Session by-<?=$tutor_name?> </strong>  </p>
              
              
              
              
              <?php
                  }
              
              
                  } ?>
              
              
              
              <label class="check-btn">
              <input type="checkbox" class="save" id="save" name="" value=""/>
              <span>Save to personal list!</span>
              </label>
            </div>
              <?php  }?>
             
              
              
              
              
              
          </div>
        </div>
      </div>
        
     
        
        
        
    </div>
      
      
  </div>
</body>
</html>
