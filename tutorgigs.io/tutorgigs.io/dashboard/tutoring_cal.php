<?php

/***** 
 * //if($login_role!=0 || !isGlobalAdmin()){
//  header("location: index.php");
//}

 * @  sessions-calendar
 * @ jobs-board-calendar
 * 
 * @ Tutor : all jobs -Open 
   @ 
 * ****/

   $link = @mysql_connect('localhost', 'mhl397', 'Developer2!');
mysql_query('SET NAMES utf8');
mysql_select_db('lonestaar', $link);
ini_set("date.timezone", "CST6CDT");
   ///////////////////////


function checkRole(){
  if( isset($_SESSION['login_role']) ) {
    return $_SESSION['login_role'];
  }else{
    return -1;
  }
}



   function curPageName() {
    return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
  }
  
  function isGlobalAdmin() {
    $global_admin = array(
      'mehuls85@gmail.com',
      'mhshah2@uh.edu',
      'hx.email.me@gmail.com',
    );
    
    return in_array($_SESSION['login_mail'], $global_admin) ? true : false;
  }

###########################
 @extract($_GET) ;
@extract($_POST) ;
$page_name='Jobs Board Calendar';

session_start();ob_start();
define("TUTOR_BOARD", "groupworld");
$home_url="https://tutorgigs.io/"; 




// include('inc/public_inc.php');
$status_arr=array("ASSIGNED"=>"Session assigned",
    "STU_ASSIGNED"=>"Students assigned in Session",
    "SES_ASSIGNED"=>"Session assigned to Teacher",
    "SES_NOT_ASSIGNED"=>"New Session",// Tut.teacjer not assigned
    "SEES_RE_ASSIGNED"=>"Session Re-assigned to Teacher");


#############################################

  
  

       




          // $month = "03"; $year = "2018";
          $month =date('m'); $year =date('Y');
         
          $fdate=date('Y-m-1');
          @extract($_GET); @extract($_POST);
            if(isset($nextmonth)){
            $fdate = $nextmonth;
            $year= date('Y', strtotime($fdate));
             $month = date('m', strtotime($fdate));
             
            }elseif(isset($premonth)){
            $fdate= $premonth;
            $year= date('Y', strtotime($fdate));
             $month = date('m', strtotime($fdate));
            
            }
  
 
          
        /////////////////////////////////////
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);

$end_time = strtotime("+1 month", $start_time);


         
         //  get session by Day in advance
         $school_id=$_SESSION['schools_id'];#
         $date_ses=array();     
              
              
              
              ?>      
                    
                    <?php 
      

        
        /////////////Search calendar/////////////
            $currMonth=date('Y-m-1');
             $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
           $preMonth = date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
            // 1. def ::,mar
             /////////////////
             if(isset($nextmonth)&&!empty($nextmonth)){
               $currMonth=$nextmonth; #apr
                 $preMonth=$nextmonth; #apr
                  $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
                $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            }elseif(isset($premonth)){
            
           
             
              // $nxtMonth=$premonth; // feb
                $currMonth=$premonth; #feb
               $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
               $nxtMonth= date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            }
         ////////////Search calendar///////////
             $year3= date('Y', strtotime($fdate)); // echo 'y/M';
             $month22 = date('M', strtotime($fdate));

    
         
            ?>

            <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
  <!-- <script src="js/jquery.steps.js"></script> -->
  <!-- <script src="js/main.js"></script>   -->
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="js/tinymce/plugins/asciimath/js/ASCIIMathMLwFallback.js"></script>
 
  <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
  <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
  <link type="text/css" href="css/style.css" rel="stylesheet" />
  <link type="text/css" href="css/form.css" rel="stylesheet" />
  <link type="text/css" href="css/front-end.css" rel="stylesheet" />
  <link type="text/css" href="css/roboto-font.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
   
                        
                        
                        
                        <div id="content" class="col-md-8">
				
                            <form id="" class="" action="" method="get">
                                
                          	
                            <div class="table-responsive">
                                
                               
                  <h3 class="text-danger text-right">
                   <button type="submit" class="btn btn-success btn-xs" name="premonth"
                            value="<?=$preMonth?>">-Prev</button>
                    <?=date('F, Y', strtotime($fdate));?>
                     
                 <button type="submit" class="btn btn-success btn-xs"  name="nextmonth"
                            value="<?=$nxtMonth?>">+Next</button>
               
               </h3>
                                
                            </div>
                               
                          
                            
               <!--	<form id="form-manager" class="content_wrap" action="" method="post">-->
					<div class="ct_heading clear">
						<h3><?=$page_name?></h3>
                                                    
					</div>		<!-- /.ct_heading -->
                                        
                                 <?php 
                                 
                            $curr_time= date("Y-m-d H:i:s"); #currTime 
                              $curr_date= date("Y-m-d"); #currTime 
            $tutor_id= $_SESSION['ses_teacher_id'];
              //for($i=1; $i<=date('t'); $i++){
         for($i=$start_time; $i<$end_time; $i+=86400){
         $list2[] = date('Y-m-d-D', $i);
   
   $getdate=  date('Y-m-d', $i);
   $dayval=  date('d', $i);$dayval= intval($dayval);
  
             
             
             
   //echo  date('Y-m-').$i.'<br/>';#2// dr_sessions
 $end_date=$getdate." 23:59:59.999";
  $qq=" SELECT * FROM `dr_sessions` WHERE 1
AND ses_start_time between '$getdate' AND '$end_date' ";   # tut_status
     $qq.="  ORDER BY ses_start_time ASC ";

    // echo $qq; die; 



// $qq.=" AND ses_start_time>'$curr_time' ";  
  //$qq.=" AND tut_teacher_id='$id' ";#only Assigned  
  //echo $tutor_id; echo '<pre>'.$qq;
  
 // die;

                $tot_ses=0;//$dff=mysql_query($qq);d
                $results=mysql_query($qq);
                $tot_ses= mysql_num_rows($results);
                $slot_str='';$k=1;
                  $curr_time= date("Y-m-d"); #currTime
                
                 while($row = mysql_fetch_assoc($results) ) {
                  // echo  $curr_date ; die;  
                $in_sec= strtotime($row['ses_start_time']) - strtotime($curr_date);  
                
                     #  	tut_teacher_id
                     # STU_ASSIGNED  ASSIGNED
                     $sesid=$row['id'];
                     // $old_end= date_format(date_create($row['ses_start_time']), 'h:i a');

                      $old_end= date_format(date_create($row['ses_start_time']), 'h:i a').'-CST';
                      $hotimage=NULL;
                      if($row['tut_teacher_id']==0&&$in_sec>0&&$in_sec<172800){
                      $hotimage='<img alt="Hot Job" style=" height:25px;" title="Hot Job" src="../hot-job.png">';// below 48 hours to Claim Job
                      }
                      
                      
         $str_class=($row['tut_teacher_id']>0)?"btn btn-success btn-xs":"btn btn-danger btn-xs";
         $str_title=($row['tut_teacher_id']>0)?"Assigned to me":"Teacher, pendng to assign";# 
          $style='margin:1% 0;';
          if($row['tut_teacher_id']==0){// Not claimed yet 
            $str_class="btn btn-default btn-xs";
         $str_title="Not claimed yet";
         $style='margin:1% 0;background-color:orange; border:orange;color: #fff;';
          $url="Jobs-Board-List.php?id=".$sesid;
          if($in_sec<0){  // no open to claim passed job
              $url="javascript:void(0);";
              $str_title="Job Expired.";
               $style='margin:1% 0;background-color:#333; border:#333;color: #fff;text-decoration: line-through;';
          }
          
          }elseif($row['tut_teacher_id']>0){// Claim by the tutor
              $str_class="btn btn-success btn-xs";
         $str_title="Job Claimed by you"; 
          $url="sessions-listing.php?id=".$sesid;
          
          }
         
         
         //////////////////////////////
        
         // Post reques to assign teacher# session-details.php?getid='.$sesid.'
        // $in_sec=NULL;
        
                
          $slot_str.=$hotimage.'<a title="'.$str_title.'" href="'.$url.'" style="'.$style.'" '
                            . '  class="'.$str_class.'">'.$old_end.'</a><br/>';#1
                    
                   //if($k>2){
                      // $slot_str.='<a href="view-sessions.php?date='.$getdate.'"  class="text-success">View More</a>';#Last
                       //break; }
                    
                    $k++;
                 } ///
                 //  view more if > ses>5 indate
                 
                  
                 
                 $date_ses[$dayval]=$slot_str;  //  per sessions
                }
  
         //print_r($date_ses); die; 
         #######Date to print in session##########

         
         ?>                             
                                        
                                        
					<div class="clear">
						
							
					<?php 

 function year2array($year) {
    $res = $year >= 1970;
    if ($res) {
      // this line gets and sets same timezone, don't ask why :)
      date_default_timezone_set(date_default_timezone_get());

      $dt = strtotime("-1 day", strtotime("$year-01-01 00:00:00"));
      $res = array();
      $week = array_fill(1, 7, false);
      $last_month = 1;
      $w = 1;
      do {
        $dt = strtotime('+1 day', $dt);
        $dta = getdate($dt);
        $wday = $dta['wday'] == 0 ? 7 : $dta['wday'];
        if (($dta['mon'] != $last_month) || ($wday == 1)) {
          if ($week[1] || $week[7]) $res[$last_month][] = $week;
          $week = array_fill(1, 7, false);
          $last_month = $dta['mon'];
          }
        $week[$wday] = $dta['mday'];
        }
      while ($dta['year'] == $year);
      }
    return $res;
    }
    
    
    
   // print_r(year2array(2018));
    
    
    function month2table($month, $calendar_array) {
       global $date_ses;
    $ca = 'align="center"';
    $res = "<table class=\"table table-hover\" cellpadding=\"2\" cellspacing=\"1\" style=\"border:solid 1px #000000;font-family:tahoma;font-size:12px;background-color:#ababab\"><tr><td $ca>Mo</td><td $ca>Tu</td><td $ca>We</td><td $ca>Th</td><td $ca>Fr</td><td $ca>Sa</td><td $ca>Su</td></tr>";
    $class_on='day-on';
          $class_off='day-off';
    foreach ($calendar_array[$month] as $month=>$week) {
      $res .= '<tr>';
      foreach ($week as $day) {
          
          //$slot_str='<a href="session-detail.php?ed=2"  class="btn btn-success btn-xs">9:00-10:30</a><br/>';#1
           //$slot_str.='<a href="more-session.php?date=9"  class="text-danger">View More</a>';#Last
  
          $sesinfo=($day>0)?$date_ses[$day]:NULL;
          //$sesinfo.='#'.$day;
            // Make sess info,entry for each day ..
          // at the- end +add,
         ////end check ::
          

        $res .= '<td style="text-align: center;border-right: 1px solid;"  
        width="20" bgcolor="#ffffff"> <span class="'.($day ? $class_on :$class_off).'" > ' . ($day ? $day : '&nbsp;') . '</span> <br/>'.$sesinfo.'</td>';






        }
      $res .= '</tr>';
      }
    $res .= '</table>';
    return $res;
    }
    
    // $calarr = year2array(2018);
  //cho month2table(3, $calarr); // January
   $calarr = year2array($year);$month= intval($month);
  echo month2table($month, $calarr); // March, 2018 ::DEF
  
?>	
						
                                            	
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

