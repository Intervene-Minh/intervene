<?php
 // teacher-tutor-sessions :: of a Teacher 
 // // teacher-sessions-calendar
// fg ::
include("header.php");
$user_id = $_SESSION['login_id'];
$status_arr=array("ASSIGNED"=>"Session assigned",
    "STU_ASSIGNED"=>"Students assigned in Session",
    "SES_ASSIGNED"=>"Session assigned to Teacher",
    "SES_NOT_ASSIGNED"=>"New Session",// Tut.teacjer not assigned
    "SEES_RE_ASSIGNED"=>"Session Re-assigned to Teacher");


$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}
//$classes_res = mysql_query('SELECT stu.class_id, COUNT(stu.id) as total_student, class.class_name '
//        . 'FROM students stu LEFT JOIN classes class '
//        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY stu.class_id');


if($_POST['action'] == 'update_class_name') {
    $edit_class_name = $_POST['edit_class_name'];
    $edit_class_id = $_POST['hdn_class_id'];
    $query = mysql_query("UPDATE classes SET class_name='$edit_class_name' WHERE id='$edit_class_id'");
    $error = 'Update Successfully';
}

$classes_res = mysql_query('SELECT class.id as class_id, class.grade_level_name as grade_name, count(stu.id) as total_student, class.class_name,class.created '
        . 'FROM classes class LEFT JOIN  students stu '
        . 'ON class.id = stu.class_id WHERE class.teacher_id = \'' . $user_id . '\' GROUP BY class.id ORDER BY class.created DESC ');


?>
<style>
     .table-manager-user {
    padding: 15px !important;
}
           </style>
           <script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
       </script> 
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
<?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            
            
        <?php 
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
      
             
         for($i=$start_time; $i<$end_time; $i+=86400){
      $list2[] = date('Y-m-d-D', $i);
   
   $getdate=  date('Y-m-d', $i);
   $dayval=  date('d', $i);$dayval= intval($dayval);
   

//echo  date('Y-m-').$i.'<br/>';#2
 $end_date=$getdate." 23:59:59.999";
  $qq=" SELECT * FROM `int_schools_x_sessions_log` WHERE 1
AND ses_start_time between '$getdate' AND '$end_date'  AND teacher_id='$user_id'";  
                $tot_ses=0;//$dff=mysql_query($qq);d
                $results=mysql_query($qq);
                $tot_ses= mysql_num_rows($results);
                $slot_str='';$k=1;
                 while($row = mysql_fetch_assoc($results) ) {
                   if($row['tut_status']=="STU_ASSIGNED"){
                    $url="edit-session.php?ses=".$row['id']; 
                    $str_class="btn btn-success btn-xs";
                    $str_title="Edit";
                   }else{
                     $url="assign-students.php?ses=".$row['id']; 
                     $str_class="btn btn-danger btn-xs";
                     $str_title="Assign";
                     
                   } 
                     
                     # STU_ASSIGNED  ASSIGNED
                  
            $old_end= date_format(date_create($row['ses_start_time']), 'h:i a');
         //$str_class=($row['tut_status']=="STU_ASSIGNED")?"btn btn-success btn-xs":"btn btn-danger btn-xs";
                    $slot_str.='<a  title='.$str_title.' style=" margin:1% 0;" href="'.$url.'"  class="'.$str_class.'">'.$old_end.'</a><br/>';#1
                   //if($k>2){
                      // $slot_str.='<a href="view-sessions.php?date='.$getdate.'"  class="text-success">View More</a>';#Last
                       //break; }
                    
                    $k++;
                 } ///
                 //  view more if > ses>5 indate
                 
                  
                 
                 $date_ses[$dayval]=$slot_str;  //  per sessions
                }
  
      // print_r($date_ses);// Sess

         // Show dateM
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
            
            
            
            
            
            
            <div id="content" class="col-md-8">
                
                
                
                
                <form name="" id="" method="GET" action="teacher-sessions-calendar.php">
                
                  <h3 class="text-danger text-right">
                   <button type="submit" class="btn btn-success btn-xs" name="premonth"
                            value="<?=$preMonth?>">-Prev</button>
                    <?=date('F, Y', strtotime($fdate));?>
                     
                 <button type="submit" class="btn btn-success btn-xs"  name="nextmonth"
                            value="<?=$nxtMonth?>">+Next</button>
               
               </h3>
                </form>
                
                
                    <div class="ct_heading clear">
                        
                        
                        
                        <h3>My Tutor Sessions</h3>
                        <ul>
                            <li><i class="fa fa-user"></i></li>
<!--                            <li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
                            <li>
                                <button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
                            </li>-->
                        </ul>
                    </div>		<!-- /.ct_heading -->
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
    foreach ($calendar_array[$month] as $month=>$week) {
      $res .= '<tr>';
      foreach ($week as $day) {
          
          
           //$slot_str.='<a href="session-detail.php?ed=2"  class="btn btn-success btn-xs">10:00-10:30</a><br/>';#4
           # view more session
           //$slot_str.='<a href="more-session.php?date=9"  class="text-danger">View More</a>';#Last
        
         $sesinfo=($day>0)?$slot_str:NULL;  // Call function for edit..
           $sesinfo=($day>0)?$date_ses[$day]:NULL;
          // Make sess info,entry for each day ..
          // at the- end +add,
         ////end check ::
        $res .= '<td style="text-align: center;border-right: 1px solid;"  width="20" bgcolor="#ffffff">' . ($day ? $day : '&nbsp;') . '<br/>'.$sesinfo.'</td>';
        }
      $res .= '</tr>';
      }
    $res .= '</table>';
    return $res;
    }
    
    // $calarr = year2array(2018);
 // echo month2table(3, $calarr); // January
   $calarr = year2array($year);$month= intval($month);
  echo month2table($month, $calarr); // March, 2018 ::DEF
  
?>
       
                            
                            
                            
                            
                            
                            
                        <div class="clearnone">&nbsp;</div>
                    </div>
                    
                    
                    
            </div>		<!-- /#content -->
            
            
        
            
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<script type="text/javascript">
               $(document).ready(function(){
                   
                    $(".delete-classes").click(function(){
                        var class_id = $(this).data('cid'); 
                        var flag = confirm('Are you sure you want to delete the selected class.');
                          if(flag) {
                              $.ajax({
                              type:"post",
                              url:"delete_student.php",
                              data:"classes_id="+class_id+"&action=deleteClasses",
                              success:function(data){
                                  data = $.trim(data);
				if(data=='true'){
                                $('#'+class_id).remove();
                                $("#response-msg").html('<strong>Thank you!</strong> Class has been successfully deleted.').removeClass('alert alert-danger').addClass('alert alert-success').show(500);
                            }
                              }
                              });
                                }else{

                                }
 
                    });
               });
       </script>
       
       
       <script>
//	(function($){
//		$(".popup-form").submit(function(e){
//			e.preventDefault();
//                        var data = $('.form-value').val();
//                        alert(data);
//            var f = 1 ;
//			$(".popup-form input.required").each(function(){
//				var va = $(this).val();
//				if( va == '' || typeof(va) == 'undefined' || va == null ){
//					f = 0;
//					if( $(this).next('label.error').length == 0 ){
//						var lbl = '<label class="error alert-danger">This field is required</label>';
//						$(this).after(lbl);
//					}
//				}
//			});
////                    if( f == 1 ){   
////			$.ajax({
////                              type:"post",
////                              url:"ajax.php",
////                              data:"classes_id="+class_id+"&action=editClasses",
////                              success:function(data){
////                                  data = $.trim(data);
////				if(data=='true'){
////                                $('#'+class_id).remove();
////                                $("#response-msg").html('<strong>Thank you!</strong> Class has been successfully deleted.').removeClass('alert alert-danger').addClass('alert alert-success').show(500);
////                            }
////                              }
////                              });
////		}
//		
//             
//		});
//		
//	})(jQuery);
    </script>

<?php include("footer.php"); ?>

