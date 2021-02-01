<?php
// sessions-list
// payment-history-new
//print_r($_SERVER); die;
include("header.php");

$login_role = $_SESSION['login_role'];
 $curr_time= date("Y-m-d H:i:s");
 //echo  $curr_time ; die; // Current time 
  $page_name="Payment History";
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}
///////////////Test
$ses="2018-03-12 10:30:00";


/////////Psyment history:: Feedback Givn page/////////////////

$error='';
$id = $_SESSION['login_id'];





$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script>
     
    
     
    ////////////////
    
	$(document).ready(function(){
            $("#checkAll").click(function(){
         alert('hii');
    $('input:checkbox').not(this).prop('checked', this.checked);
}); 
            
            
          ////////////////  
		$('#delete-user').on('click',function(){
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
			return confirm('Are you want to delete '+count+' record?');
		});
	});
        
   //////////Drop Search///////////
   
      $(document).ready(function () {

        $('#districtqzz').chosen();

        $('#districtqzz').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
//            $.ajax({
//                type: "POST",
//                url: "ajax.php",
//                data: {district: district, action: 'get_multiple_schools', 
//                    school_id: ''},
//                success: function (response) {
//                    $('#district_school').html(response);
//                    $('#d_school').chosen();
//                },
//                async: false
//            });
        });
        $('#districtqzz').change();
    });
    
      
        
</script>
<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab li:eq(1) a").tab('show');
});
</script>
<style type="text/css">
	.bs-example{
		margin: 20px;
	}
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
</style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
                        
                        	
                            <?php 
                            
                            
                            // $curr_time
                            
                 /* Process Search */
                             $start_date = date('Y-m-d h:i:s');
                               
                         $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
                         $sql.=" AND ses_start_time<'".$curr_time."'";
                         $sql.=" AND payment_status='1' ";//paid
                         $sql.=" ORDER BY ses_start_time DESC "; 
                              
                               
                                  
                 
                 
                 
                                 
                                  
					@extract($_GET)	;			
                                   if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                       
                                  // print_r($_GET); die;
                           $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
                         $sql.=" AND ses_start_time<'".$curr_time."'";
                          $sql.=" AND payment_status='1' ";//paid
                                  if(!empty($tutor_id)){ // 
                                $sql.=" AND tut_teacher_id='$tutor_id' "; 
                                }
                               
                                  
                                  
                            if(!empty($from_date)){
                           
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
			////////////////////
                                

                              //  echo $sql;
                              $results = mysql_query($sql);
                                   $tot_record=mysql_num_rows($results);                                           
                                                                        
                            //table-responsive
                            ?>
                        
			<div id="content" class="col-md-8">
				
				<div class="bs-example">
					<ul class="nav nav-tabs" id="myTab">
                 <li onclick="window.location.href='payment-history.php'"><a data-toggle="tab" href="#sectionA">Unpaid</a></li>
                      <li ><a data-toggle="tab" href="#sectionB">Paid</a></li>
						
						
					</ul>
					<div class="tab-content">
						<div id="section99" class="tab-pane">
                                                  
                                                
                                                </div>
                                            
                                            
						<div id="sectionB" class="tab-pane fade in active">
							 <form id="search-users" method="GET" action=""  >
						
                   
                                             <select name="tutor_id" id="districtqzz" style="color: red;">
                        <option 
                         value=""  >Tutor</option>
                        <?php
                      // Tutor have sessions///
             $sql=" SELECT gt.id,gt.f_name FROM `int_schools_x_sessions_log` AS s JOIN gig_teachers gt "
                     . " ON s.tut_teacher_id=gt.id GROUP BY gt.id,gt.f_name ORDER BY gt.f_name ";    
             
                    
                        
                        $res=mysql_query($sql);
                    //    print_r($res); die;
                    while( $row = mysql_fetch_assoc($res) ) {
                       
                        $sel=(isset($tutor_id)&&$row['id']==$tutor_id)?"selected":NULL;
                        ?>
                        
                
                  
                  
                     <option 
                         value="<?=$row['id']?>" <?=$sel?>  ><?=$row['f_name']?></option>
                    <?php } ?>
                                </select>
                                             From:
                    <input name="from_date"  value="<?=(isset($from_date))?$from_date:NULL;?>"
                           class="datepicker" data-date-format="mm/dd/yyyy"  >
                                           
                   To:
                   <input name="end_date"
                          value="<?=(isset($end_date))?$end_date:NULL;?>"  
                          class="datepicker" data-date-format="mm/dd/yyyy"  >
                        
                    
                    
                    &nbsp;<input name="action" class="btn" value="Search" type="submit">    
                
                
					</form>

                                                    
                                                    
                                                    
                                                    
                                           <!--            formms -->
                                                    
                                                    <form id="form-manager" class="content_wrap" action="" method="post">
                                                    
								<div class="ct_heading clear">
						             <h3><?=$page_name?>(<?=$tot_record?>)</h3>
										
					                              </div>
							  <div class="clear">
								<table class="table-manager-user col-md-12">
								
								<tbody>
										<tr>
                                                                                   
											<th>Date/Time</th>
											<th>Details</th>
										</tr> 
                                                                          
                                                                           							
															
								   </tbody>
                                                                   
                                                                   
                                                                   
                                                                   
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
         ///  Ses Batch iD   
             if($row['batch_id']>0){
             $payout=mysql_fetch_assoc(mysql_query(" SELECT  payout_batch_id,batch_status FROM ses_payout_batchs WHERE id=".$row['batch_id']));
           }

           /// Paypal Detail
           $profile= mysql_fetch_assoc(mysql_query("SELECT id,payment_email,payment_phone FROM tutor_profiles WHERE tutorid=".$row['tut_teacher_id']));
           

            $ses_paypal_email=(!empty($row['paypal_email']))?$row['paypal_email']:$profile['payment_email'];
          ?>
										
                                                                
                                                                
                                                                <tr>
											
                                                                                        
						<td>
                                            <span>
                                           
                                  <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>     
                                       
                                    </span>  
                                    
                            <span class="btn btn-success btn-xs" title="Start Time">
                    <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></span> 
                                    <br/>
                                    
                                    
                                    <strong class="text-primary">
                                 Objective:</strong><?=$quiz['objective_name']?><br/>
                                 <?php  if($row['batch_id']>0){ ?>
                                   <strong class="text-primary">
                                 Batch status:</strong> <span class="btn btn-danger btn-xs"><?=$payout['batch_status']?></span><br/>
                                   <strong class="text-primary">
                                 Batch ID:</strong> <?=$payout['payout_batch_id']?><br/>
                                 <?php }?>
                                 <strong class="text-primary">PayPal Email:</strong> <?=$ses_paypal_email?>
                                
                                 
                                
                                 
                                            
                                     
                                
                          
                                
                                    
                                                    
                                            </td>
                                          
                                            
                                            
                   
                                                
                                                
                                            <td >
                                               
                                           <strong class="text-primary">
                                 Tutor:</strong><?=$tut_th['f_name']." ".$tut_th['lname']?><br/>      
                                           
                                 
                                  <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                                  <strong class="text-primary">Payment Date:</strong>
                            <?=date_format(date_create($row['payment_date']), 'F d,Y, h:i a');?><br/>
                                          
                                             
                                   <?php if($row['payment_status']==1){?>
                                   
                                   <strong class="text-primary">
                                 Status:</strong>  <a class="btn btn-success btn-xs" >Paid</a>
                                   <?php }else{?>
                                 <strong class="text-primary">
                                 Status:</strong>  <a class="btn btn-danger btn-xs" >Unpaid</a>
                                   <?php } ?>
                                
                                 <br/>
                                    <a href="session-details.php?getid=<?=$row['id']?>" 
                                   
                                   style="text-decoration:underline;">View</a> 
                                            
                                            
                                            </td>
                                  
                              
                                
                                            
                                           
                                                                                        
                                                                                        
                                                                                        
										</tr>
								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
					        </div>


						<input type="hidden" id="arr-user" name="arr-user" value=""/>
                                                    </form>	  
					    </div>
					</div>
				</div>
			
                            
                            
                            
                           
				
                            
                            
                            
                            
				
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<script>
     $(document).ready(function () {
         $('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});});
    
    </script>
<?php include("footer.php"); ?>
