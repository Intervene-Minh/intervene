<?php
// sessions-list
// payment-history-new
include("header.php");

$login_role = $_SESSION['login_role'];
 $curr_time= date("Y-m-d H:i:s");
 //echo  $curr_time ; die; // Current time 
 
//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}
///////////////Test
$ses="2018-03-12 10:30:00";


/////////Psyment history:: Feedback Givn page/////////////////

$error='';
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user'])){
      // payment_date
   $arr = $_POST['arr-user'];
    if(!empty($arr))
       $ses_arr= explode(",", $arr);
        $tot_records=count($ses_arr);
      //  print_r($arr); die;
	if($arr!=""){
	$query = mysql_query(" UPDATE int_schools_x_sessions_log SET payment_status='1',payment_date='$curr_time' WHERE id IN ($arr)", $link);	
           // $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
	
        
        }
       $msg=($tot_records>0)?$tot_records.'-Record Mark as-Paid':"Select a record"; 
      //$msg="-Record Mark as-Paid ";
  // Selected record mark as Payment done   
    echo "<script>alert('".$msg."');location.href='payment-history.php';</script>";
       
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

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
			<div id="content" class="col-md-8">
				
				<div class="bs-example">
					<ul class="nav nav-tabs" id="myTab">
						<li><a data-toggle="tab" href="#sectionA">Paid</a></li>
						<li><a data-toggle="tab" href="#sectionB">Unpaid</a></li>
						
					</ul>
					<div class="tab-content">
						<div id="sectionA" class="tab-pane fade in active">
							<h3>Section A</h3>
							<p>Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
						</div>
						<div id="sectionB" class="tab-pane fade">
							

								<form class="form-inline">

									<div class="form-group">
											<select name="tutor_id" class="form-control">
											 <option value="">Tutor</option>
											 <option value="1">Rohit</option>
											 <option value="24">Sanayya</option>
											 <option value="21">Sophia</option>
											 <option value="20">Steven</option>
											 <option value="26">Taiwo</option>
											 <option value="12">Tyshawna</option>
											 <option value="29">Wesley</option>
										  </select>
									</div>

									<div class="form-group">
									        <label for="inputPassword">From:</label>
											<input type="password" class="form-control" id="inputPassword" placeholder="">
									</div>
									<div class="form-group">
									        <label for="inputPassword">To:</label>
											<input type="password" class="form-control" id="inputPassword" placeholder="">
									</div>
									    <button type="submit" class="btn btn-primary">Search</button>

								</form>
								<div class="ct_heading clear">
						             <h3>Payment History(65)</h3>
										<ul>
											<li>					  
						                       <button id="delete-user" style="color: #fff; background-color:#449d44;padding: 10px 22px" type="submit" name="delete-user">Mark as Paid</button>
											</li>
										</ul>
					          </div>
							  <div class="clear">
								<table class="table-manager-user col-md-12">
								
								<tbody>
										<tr>
											<th>#</th>
											<th>Date/Time</th>
											<th>Details</th>
										</tr>                               
										<tr>
													<td>
														<input type="checkbox" class="checkbox" value="851">
													</td>
																								
													<td>
														<span>May 02,2018<br> </span>  
														<span class="btn btn-success btn-xs" title="Start Time"> 03:20 pm</span<br>
														<strong class="text-primary"> Objective:</strong>3.3(F)<br>
													</td>
												  
													<td>
													   <strong class="text-primary">Tutor:</strong>Imane  Ennadi<br>      
													   <strong class="text-primary">Session id:</strong>851<br>
													   Status:</strong>  <a class="btn btn-danger btn-xs">Unpaid</a><br>
														 <a href="session-details.php?getid=851" style="text-decoration:underline;">View</a>
													</td>                                         
										</tr>
																
															
								   </tbody>
								</table>
					        </div>


							  
					    </div>
					</div>
				</div>
				
                            <?php 
                            
                            
                            // $curr_time
                            
                 /* Process Search */
                             $start_date = date('Y-m-d h:i:s');
                               
                         $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
                         $sql.=" AND ses_start_time<'".$curr_time."'";
                         $sql.=" AND payment_status='0' ";//Unpaid
                         $sql.=" ORDER BY ses_start_time DESC "; 
                              
                               
                                  
                 
                 
                 
                                 
                                  
					@extract($_GET)	;			
                                   if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                       
                                  // print_r($_GET); die;
                           $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND feedback_id>0 ";
                         $sql.=" AND ses_start_time<'".$curr_time."'";
                         $sql.=" AND payment_status='0' ";//Unpaid
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
                                

                                //echo $sql;
                              $results = mysql_query($sql);
                                   $tot_record=mysql_num_rows($results);                                           
                                                                        
                            //table-responsive
                            ?>
                            
                            
                            
                            <div class="">
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
				</div>
				
                            
                            
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Payment History(<?=$tot_record?>)</h3>
						<ul>
							
							<li>
                                                          
         <button id="delete-user" style="color: #fff; background-color:#449d44;padding: 10px 22px" type="submit" name="delete-user">Mark as Paid</button>
							</li>
						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						
                                              
                                                    
                                                    ?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									<col width="30">
									<col width="220">
									<col width="435">
									
									
								</colgroup>
								<tr>
									<th>#</th>
									<th>Date/Time</th>
									<th>Details</th>
                                                                        
									
									
								</tr>
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
          ?>
										
                                                                
                                                                
                                                                <tr>
											<td>
												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>
                                                                                        
						<td>
                                            <span>
                                  <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>     
                                       
                                    </span>  
                                    
                            <span class="btn btn-success btn-xs" title="Start Time">
                    <?=date_format(date_create($row['ses_start_time']), 'h:i a');?></span> 
                                    <br/>
                                    
                                    
                                    <strong class="text-primary">
                                 Objective:</strong><?=$quiz['objective_name']?><br/>
                                
                                 
                                
                                 
                                            
                                     
                                
                          
                                
                                    
                                                    
                                            </td>
                                          
                                            
                                            
                   
                                                
                                                
                                            <td >
                                               
                                           <strong class="text-primary">
                                 Tutor:</strong><?=$tut_th['f_name']." ".$tut_th['lname']?><br/>      
                                           
                                 
                                  <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                                          
                                             
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
						<?php } ?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
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
