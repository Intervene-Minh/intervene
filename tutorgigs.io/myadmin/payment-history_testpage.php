<?php

/**
@List of tutoors-those completed session &feedback
*/
//
include("header.php");

$login_role = $_SESSION['login_role'];
$curr_time= date("Y-m-d H:i:s");
//echo  $curr_time ; die; // Current time 
$page_name="Unpaid Payment History";
$error='';
$id = $_SESSION['login_id'];
if(isset($_POST['submit_mark_paid'])){

      $arr = $_POST['arr-user'];
      // print_r($arr); die;
      if(!empty($arr))
      $ses_arr= explode(",", $arr);
      $tot_records=count($ses_arr);
      if($arr!=""){

      $query = mysql_query(" UPDATE int_schools_x_sessions_log SET payment_status='1',payment_date='$curr_time' WHERE id IN ($arr)", $link);
      }
      $msg=($tot_records>0)?$tot_records.'-Record Mark as-Paid':"Select a record"; 
      //$msg="-Record Mark as-Paid ";
      // Selected record mark as Payment done   
      echo "<script>alert('".$msg."');location.href='payment-history.php';</script>";
    }
//////////////Paypal payment///////
if(isset($_POST['delete_user']))
{    
 
  ///

    include("paypal_api.php"); //LIVE
    //include("paypal_api_sandbox.php"); //Dev-LIVE
    // payment_date
    $arr = $_POST['arr-user'];
    $warnings=array();
    if(!empty($arr))
    $ses_arr= explode(",", $arr);
    $tot_records=count($ses_arr);

    $ses_id_str=$arr;
    
    if($arr!=""){
 
    /// Process for Each Session /////


    $insert_batch=mysql_query(" INSERT INTO ses_payout_batchs SET batch_ses_ids='$arr',created_at='$curr_time' ");
    $curr_batch_id= mysql_insert_id(); //  $curr_batch_id=99;

    


    // add batch info
    $res['sender_batch_header']=array('sender_batch_id'=>$bach_number=$curr_batch_id,
    'email_subject'=>'You have a payout! ',
    'email_message'=>'You have received a payout! Thanks for using our service! ');





    $sql_ses="SELECT ses.id as sesid,ses.tut_teacher_id as ses_tutor,t.* FROM int_schools_x_sessions_log ses
    LEFT JOIN gig_teachers t 
    ON ses.tut_teacher_id=t.id
    WHERE ses.id IN (".$ses_id_str.")
    GROUP BY  email";
    $result=mysql_query($sql_ses);

    $data_arr=array();
    $session_pay_amount=15; //payout . Should be $15
    $item_arr=array();




    while($line=mysql_fetch_assoc($result))
    {

      $arr1['id']=$line['id'];
      $arr1['email']=$line['email'];
      $arr1['ses_id']=$line['sesid'];

      $session_pay_amount=$tot_records*$session_pay_amount;
      ###
      $line['payment_em']=(!empty($line['payment_em']))?$line['payment_em']:$line['email'];
      #############
      $item_arr[]=array('recipient_type'=>'EMAIL', 
      'amount'=>array('value'=>$session_pay_amount,'currency'=>'USD'),
      'note'=>'Thanks for your interest!', 
      'sender_item_id'=>$line['id'], 
      'receiver'=>$line['payment_em'], 
      'receiver'=>$line['payment_em'], 
      'sessionID'=>$ses_id_str,
      );

      ##################################
      $data_arr[]=$arr1;
      unset($arr1);

    }

    //

    $res['items']=$item_arr;


    ///////Test////

 //echo 'Testing==:' , $tot_records;
 //echo '<pre>::';
 //print_r($res);   
 //die;  

// 

  echo   $new_json=json_encode($res);
    $get_token=getToken(); 
    $payout_res= requestPayout($get_token);
    $pay_obj=json_decode($payout_res);



    // Update request Batch info 
    $sql=mysql_query("UPDATE ses_payout_batchs SET batch_request='$new_json',
    updated_at='$curr_time',batch_curr_state='$payout_res' WHERE id=".$curr_batch_id);


if(isset($pay_obj->name)&&$pay_obj->name=='INSUFFICIENT_FUNDS')
{

$warnings[]=$msg='INSUFFICIENT_FUNDS,'.$pay_obj->message;
echo "<script>alert('".$msg."');location.href='payment-history.php';</script>";  
die;
}



    $batchStatus=$pay_obj->batch_header->batch_status;
    $payoutBatch_id=$pay_obj->batch_header->payout_batch_id;

    if(isset($pay_obj->batch_header->batch_status)&&$batchStatus=='PENDING')
    { //Request done for payment.
          ///  Update Succes Response //
          $sql=mysql_query("UPDATE ses_payout_batchs SET payout_batch_id='$payoutBatch_id',batch_status='$batchStatus' WHERE id=".$curr_batch_id);
          //die; 
          $msg='Payment Request Done,Status is '.$batchStatus;
          // $msg.=($tot_records>0)?$tot_records.'-Record Mark as-Paid':"Select a record"; 
          ///////////Mark inPaid//////////////////
          $query = mysql_query(" UPDATE int_schools_x_sessions_log SET batch_id='$curr_batch_id',payment_status='1',payment_date='$curr_time' WHERE id IN ($arr)", $link); 
          echo "<script>alert('".$msg."');location.href='payment-history.php';</script>";
          die;

    }

        
  } // end Arr str

  //


       $msg=($tot_records>0)?$tot_records.'-Record Mark as-Paid':"Select a record"; 
      echo "<script>alert('".$msg."');location.href='payment-history.php';</script>";     



}


 //schools
  $schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");

 ?>



<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
}); 

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
      return confirm('Are you sure?');
			//return confirm('Are you sure '+count+' record?');
		});

    ///Mannul mark as paid//
    $('#mark-as-paid').on('click',function(){
                    //alert('fdf');
            var count = $('#form-manager .checkbox:checked').length;
            $('#arr-user').val("");
            $('#form-manager .checkbox:checked').each(function(){
            var val = $('#arr-user').val();
            var id = $(this).val();
            $('#arr-user').val(val+','+id);
            });
            var str = $('#arr-user').val();
            $('#arr-user').val(str.replace(/^\,/, ""));
            return confirm('Are you sure.?');
            //return confirm('Are you sure '+count+' record?');
    });
     ///Mannul mark as paid//
	});
        
//////////Drop Search///////////
      $(document).ready(function () {

        $('#districtqzz').chosen();
        $('#districtqzz').change(function () {
            district = $(this).val();
          });
        $('#districtqzz').change();
    });    
</script>
<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab li:eq(0) a").tab('show');
});
</script>
<style type="text/css">
.bs-example{ margin: 20px;}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    cursor: default;
    background-color: transparent;
    border: 1px solid #ddd;
    border-bottom-color: #fff;
    font-size: 16px;
    color:#1b64a9;
}
.nav-tabs {
    border-bottom: 1px solid #ddd;
}
.tab-content{
	padding:20px 10px;
	margin-top:0px;
	border:1px solid #ddd;
	border-top-color: transparent;
	float: left;
}
.nav>li>a {
    position: relative;
    display: block;
    padding: 12px 20px;
	font-size: 16px;
}
/**Custom css*/
#mark-as-paid{
    color: #fff;
    background-color: #449d44;
    padding: 10px 22px;
    border: none;
    line-height: 1;
}
}
</style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
      <?php 
          /* Process Search */
          $start_date = date('Y-m-d h:i:s');

          $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
          $sql.=" AND ses_start_time<'".$curr_time."'";
          $sql.=" AND payment_status='0' ";//Unpaid
          $sql.=" ORDER BY ses_start_time DESC ";


          @extract($_GET);
          if(isset($_GET['action'])&&$_GET['action']=="Search"){

                // print_r($_GET); die;
                $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
                $sql.=" AND ses_start_time<'".$curr_time."'";
                $sql.=" AND payment_status='0' ";//Unpaid
                if(!empty($tutor_id)){ // 
                
                  $sql.=" AND tut_teacher_id='$tutor_id' "; 
                }
                if(!empty($from_date))
                {

                    $date = DateTime::createFromFormat("m/d/Y" , $from_date);
                    $from_date1= $date->format('Y-m-d');
                    $sql.=" AND ses_start_time >= '$from_date1' "; // 2018-04-10
                }
                if(!empty($end_date)){

                    $date = DateTime::createFromFormat("m/d/Y" , $end_date);
                    $end_date1= $date->format('Y-m-d');
                    $end_date1.=" 23:59:59";
                    $sql.=" AND ses_start_time <='$end_date1' ";  

                }
                $sql.=" ORDER BY ses_start_time ASC ";
              } 
              // Result
              // echo '===:<pre>' ,$sql;
              // die;                                     
			        $results = mysql_query($sql);
              $tot_record=mysql_num_rows($results);
              // Tutor those completed tutoring & unpaid tutoring remaining
              ?>
                <?php 


          $sql_tutors="SELECT gt.id,gt.f_name , gt.lname
FROM int_schools_x_sessions_log s

JOIN gig_teachers gt 
ON s.tut_teacher_id=gt.id

WHERE 1 AND feedback_id>0  
AND ses_start_time<'".$curr_time."' 
AND payment_status='0' 

GROUP BY gt.id,gt.f_name ORDER BY gt.f_name ;";

                 // Tutor have sessions///
            $sql=" SELECT gt.id,gt.f_name FROM `int_schools_x_sessions_log` AS s JOIN gig_teachers gt "."ON s.tut_teacher_id=gt.id GROUP BY gt.id,gt.f_name ORDER BY gt.f_name ";    
            $res=mysql_query($sql_tutors);
         

              ?>





              <div id="content" class="col-md-8">
                <div class="bs-example">
					<ul class="nav nav-tabs" id="myTab">
              <li><a data-toggle="tab" href="#sectionB">Unpaid</a></li>
              <li onclick="window.location.href='payment-history_paid.php'"><a data-toggle="tab" href="#sectionA">Paid</a></li>
            </ul>
					<div class="tab-content">
						<div id="sectionA9" class="tab-pane"></div>             
						<div id="sectionB" class="tab-pane fade in active">
            


							<form id="search-users" method="GET" action="" class="row">
                <div class="col-md-3">
                <select name="tutor_id" id="districtqzz"  style="color: red; padding: 5px;">
                    <option value="">Tutor(<?=mysql_num_rows($res) ?>)</option>
                    <?php
                   


                    //print_r($res); die;
            while( $row = mysql_fetch_assoc($res) ) {
              $sel=(isset($tutor_id)&&$row['id']==$tutor_id)?"selected":NULL;?>
              <option value="<?=$row['id']?>" <?=$sel?>><?=ucfirst($row['f_name'].' '. $row['lname']) ?> </option>
              <?php } ?>
               </select>
               </div>
               <div class="col-md-3">
               From: <input name="from_date"  value="<?=(isset($from_date))?$from_date:NULL;?>"
                           class="datepicker" data-date-format="mm/dd/yyyy" style="padding: 5px; width: 100%">
               </div>
               <div class="col-md-3">            
               To: <input name="end_date" value="<?=(isset($end_date))?$end_date:NULL;?>"  
                          class="datepicker" data-date-format="mm/dd/yyyy" style="width:100%; padding: 5px">
                </div> 
                <div class="col-md-3">          
                          &nbsp;<input name="action" class="btn" value="Search" type="submit" style="width:100%; padding: 5px" ></div>
                        </form>

            <input id="myInput" type="text" placeholder="Please enter Paypal Email ID" class="form-control"
style="width: 100%">
      <br>
              <form id="form-manager" class="content_wrap" action="" method="post">
                <div class="ct_heading clear">

                <h3><?=$page_name?>(<?php echo  $tot_record; ?> Sessions) - <?php echo  $tot_record; ?> Total Hours</h3>
										<ul>
											<li>				
                        <button id="delete-user" style="color: #fff; background-color:#d9534f;padding: 10px 22px" type="submit" name="delete_user">Pay by Paypal</button>
											</li>

                      <li title="paid manual">            
              <button id="mark-as-paid"  type="submit" name="submit_mark_paid">Mark as Paid</button>
                      </li>
										</ul>
					          </div>
							  <div class="clear">
                  
								<table class="table-manager-user col-md-12">
                  
										<tr>
                      <th style="width: 10%">
                      <input id="checkAll" type="checkbox"></th>
											<th>Date/Time</th>
											<th>Details</th>
										</tr> 
								  <tbody id="myTable">
                   <?php
                   if( mysql_num_rows($results) > 0 ) {
                    while( $row = mysql_fetch_assoc($results) ) {

                $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));
                $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                $tot_std=($tot_std>0)?$tot_std:"XX";
                $tut_th= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname FROM gig_teachers WHERE id=".$row['tut_teacher_id']));    

                $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     
                // district_id 
                if($int_school['district_id']>0){
                    $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     
                    $districtName=$district['district_name'];

                }
          
            /// inAdmin Info SELECT * FROM `users` WHERE 1 
            $admin=mysql_fetch_assoc(mysql_query(" SELECT * FROM `users` WHERE id=1 ")); // Def

            // Exp time
            $sesStartTime=$row['ses_start_time'];


            $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days

            $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));  
            $quiz['objective_name']=(!empty($quiz['objective_name']))?$quiz['objective_name']:"NA";
         //// list of students 
           $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
            $q.=" WHERE ses.slot_id='".$row['id']."' ";
            $resss=mysql_query($q);
            $stud_str=array(); // middle_name
            while ($row2=mysql_fetch_assoc($resss)) {
                $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];
            }  

            $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";
            //  Tutor Profile  tut_teacher_id
            $profile= mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM tutor_profiles WHERE tutorid=".$row['tut_teacher_id']));
           
            $ses_paypal_email=(!empty($row['paypal_email']))?$row['paypal_email']:$profile['payment_email'];

           // print_r($profile);die;
           
            //  if feedbackGvien:ses_have_captureEmailthen-CHanged

          ?>
           <tr>
											<td>
												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>
                                                                                        
						<td>
              <span><?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br></span> 
              <span class="btn btn-success btn-xs" title="Start Time"><?=date_format(date_create($row['ses_start_time']), 'h:i a');?></span><br/>
              <strong class="text-primary">Objective:</strong><?=$quiz['objective_name']?><br/>
               <strong class="text-primary">PayPal Email:</strong> <?=$ses_paypal_email?> </td>
                <td ><strong class="text-primary">Tutor:</strong><?=$tut_th['f_name']." ".$tut_th['lname']?><br/><strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                  <?php if($row['payment_status']==1){?><strong class="text-primary">
                                 Status:</strong>  <a class="btn btn-success btn-xs" >Paid</a>
                                   <?php }else{?>
                                 <strong class="text-primary">
                                 Status:</strong>  <a class="btn btn-danger btn-xs" >Unpaid</a>
                                 <br>
                                   <strong class="text-primary">
                                 Hours:</strong> :1
                                   <?php } ?>
                                
                                 <br/>
                        <a href="session-details.php?getid=<?=$row['id']?>" style="text-decoration:underline;">View</a> </td>
                                                               
										</tr>
								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
              </tbody>
							</table>
					        </div>
						<input type="hidden" id="arr-user" name="arr-user" value=""/></form>	  
					    </div>
					</div>
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>
<style type="text/css">
  .chosen-container{
    width:100% !important;
  }
  .chosen-container-single .chosen-single {
    position: relative;
    display: block;
    overflow: hidden;
    padding: 0 0 0 8px;
    margin: 20px 0px 0px;
    height: 33px !important;
    border: 1px solid #aaa;
    border-radius: 0px !important;
    background: #fff !important;
    box-shadow: 0 0 3px #fff inset, 0 1px 1px rgba(0,0,0,.1);
    color: #444;
    text-decoration: none;
    white-space: nowrap;
    line-height: 33px !important;
}
.chosen-container-single .chosen-single div {
    top: 4px !important;
}
</style>		<!-- /#header -->
<script>
  $(document).ready(function () {
  $('.datepicker').datepicker({
  format: 'mm/dd/yyyy',
  startDate: '-3d'
  });});
</script>
<?php include("footer.php"); ?>
<script type="text/javascript">
  


$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
