<?php

@extract($_REQUEST) ;
include("header.php");
$error='';
$login_role = $_SESSION['login_role'];
$page_name="List of Tutor Sessions";
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user']))
{
	     $arr = $_POST['arr-user'];

	   if($arr!="")
     {
     //// Delete Role Table...
        $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
	     }
        
      echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";
       
        
}
$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<script>
	$(document).ready(function(){
		$('#delete-user').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to delete '+count+' user?');
		});
	});
        
  /////////////////      
      function sent_form(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
	 form.setAttribute("target", "_blank");

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

///
$(document).ready(function() {
   $('#setdate').change(function() {
     var parentForm = $(this).closest("form");
     if (parentForm && parentForm.length > 0)
       parentForm.submit();
   });
});
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div><!-- /#sidebar -->
        <?php

          $month =date('m'); $year =date('Y');
          $fdate=date('Y-m-1');
        
            if(isset($nextmonth))
            {
              $fdate = $nextmonth;
              $year= date('Y', strtotime($fdate));
              $month = date('m', strtotime($fdate));
             
            }elseif(isset($premonth))
            {
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

          /////////////Search calendar/////////////
          $currMonth=date('Y-m-1');
          $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
          $preMonth = date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
            // 1. def ::,mar
             /////////////////
             if(isset($nextmonth)&&!empty($nextmonth))
             {
                  $currMonth=$nextmonth; #apr
                  $preMonth=$nextmonth; #apr
                  $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
                  $nxtMonth = date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            }
            elseif(isset($premonth))
            {
                // $nxtMonth=$premonth; // feb
                  $currMonth=$premonth; #feb
                  $preMonth= date('Y-m-d', strtotime("-1 months", strtotime($currMonth)));
                  $nxtMonth= date('Y-m-d', strtotime("+1 months", strtotime($currMonth)));
            }
         ////////////Search calendar///////////
             $year3= date('Y', strtotime($fdate)); // echo 'y/M';
             $month22 = date('M', strtotime($fdate));?>             
			<div id="content" class="col-md-8">
			<form id="" class="" action="" method="get">
        <div class="table-responsive">       
                  <h3 class="text-danger text-right">
                   <button type="submit" class="btn btn-success btn-xs" name="premonth" value="<?=$preMonth?>">-Prev</button>
                    <?=date('F, Y', strtotime($fdate));?><button type="submit" class="btn btn-success btn-xs"  name="nextmonth"
                            value="<?=$nxtMonth?>">+Next</button></h3></div>
                     <div class="ct_heading clear">
						<h3><?=$page_name?></h3>
						
					</div>		<!-- /.ct_heading -->
		   <div class="clear">
        <?php 
         
      
              //for($i=1; $i<=date('t'); $i++){
         for($i=$start_time; $i<$end_time; $i+=86400){
                $list2[] = date('Y-m-d-D', $i);

                $getdate=  date('Y-m-d', $i);
                $dayval=  date('d', $i);$dayval= intval($dayval);
                //echo  date('Y-m-').$i.'<br/>';#2
                $end_date=$getdate." 23:59:59.999";
                $qq=" SELECT * FROM `int_schools_x_sessions_log` WHERE 1
                AND ses_start_time between '$getdate' AND '$end_date' ";   # tut_status
                $qq.="  ORDER BY ses_start_time ASC ";
                // AND tut_status='STU_ASSIGNED' 
    // echo '<pre>'.$qq; die;
                $tot_ses=0;//$dff=mysql_query($qq);d
                $results=mysql_query($qq);
                $tot_ses= mysql_num_rows($results);
                $slot_str='';$k=1;
                 while($row = mysql_fetch_assoc($results) ) {
                 if($row['tut_teacher_id']>0)
               
                $det=mysql_fetch_assoc(mysql_query("SELECT f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));   
                $tutor_name=(!empty($det['f_name']))?$det['f_name']." ".$det['lname']:"Na";
                ///////////////////////////     SchoolId
                $det=mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE SchoolId=".$row['school_id']));   
                $scool_name=(!empty($det['SchoolName']))?$det['SchoolName']:"Na";

                $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));
                $quiz_name=(!empty($quiz['objective_name']))?$quiz['objective_name']:"Na";      
                # STU_ASSIGNED  ASSIGNED
                $sesid=$row['id'];
                $old_end= date_format(date_create($row['ses_start_time']), 'h:i a').'-CST'; #time 
                $str_class=($row['tut_teacher_id']>0)?"btn btn-success btn-xs":"btn btn-danger btn-xs";

                $str_title="Tutor:$tutor_name, School:$scool_name, Objective:$quiz_name";


                  $style='margin:1% 0;';
                  if($row['tut_teacher_id']>0&&$row['tut_status']=="STU_ASSIGNED"){
                  $str_class="btn btn-success btn-xs";
                  //$str_title="tutor and students assigned";   
                  }elseif($row['tut_teacher_id']==0){
                  $str_class="btn btn-danger btn-xs";  // is not assigned to tutor 
                  //$str_title="is not assigned to tutor?";
                  }elseif($row['tut_teacher_id']>0&&$row['tut_status']=="ASSIGNED"){
                  $str_class="btn btn-default btn-xs";  
                  // $str_title="assigned to a tutor & student is not assigned.";
                  $style='margin:1% 0;background-color: yellow;border-color:yellow;';
                  }

            /*check session type*/
          

            if(!empty($row['Tutoring_client_id'])&&$row['Tutoring_client_id']=='Drhomework123456')
            {
            $Sessiontype='Drhomework';
            }
            else{
            $Sessiontype='Intervention';
            }
         /////////////////////////
         // Post reques to assign teacher
                    $slot_str.='<a title="'.$str_title.'" style="'.$style.'"  href="javascript:void(0)"  SessionID='.$sesid.' action='.$Sessiontype.' class=" viewSession '.$str_class.'">'.$old_end.'</a><br/>';#1
                
                    
                    $k++;
                 } ///
                 
                 $date_ses[$dayval]=$slot_str;  //  per sessions
                }
  
     // print_r($date_ses); //  print_r($list2);die; date list

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
//  echo month2table(2, $calarr); // FEB
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
<script type="text/javascript">
$('.viewSession').click(function()
{
  var SessionID=$(this).attr('SessionID');
  var action = $(this).attr('action');
$.ajax({
   type:'POST',
   data:{SessionID:SessionID,action:action},
   url:'https://tutorgigs.io/dashboard/get_session-ajax.php',
   success:function(data){
    $('#ViewDetails').modal('show');
    $('.SeessionIDD').text(SessionID);
    $('.dataview').html(data);
   }
});

});
</script>
 <div class="modal fade" id="ViewDetails" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
          <h4 class="modal-title">Details Session ID <span class="SeessionIDD"></span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class=" dataview">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php include("footer.php"); ?>

