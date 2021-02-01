<?php
/***


@ SELECT * FROM int_schools_x_sessions_log WHERE 1 AND type='drhomework' AND is_drhomework='yes' ORDER BY ses_start_time DESC
====================
$Arr_ses_type=array( 'homework' , 'intervention', 'drhomework');
Url - https://drhomework.com/parent/students/

**/


include("header.php");
// Allow session to this page .

$AllowDrhomeworkSession='yes'; // Session list From-  []
$AllowSessionType='drhomework';// SelectOnlydrhomeworkSeesionList

$url_homeowrk_detail='drhomework_session_info.php';





$login_role = $_SESSION['login_role'];
if($login_role!=0 || !isGlobalAdmin()){
	header("location: index.php");
}

$error='';
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user'])){
    //  Delete session and increase counter  //in princple account . 
   
	$arr = $_POST['arr-user'];
       if(!empty($arr))
       $ses_arr= explode(",", $arr);
       
 ////////////////////////// Delete sessions//////////////////   
       $tot_records=count($ses_arr);
       //print_r($tot_records); die;
 foreach ($ses_arr as $getid) {
    // $getid :sesid
     
  $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1";
    $sql.=" AND id='$getid' ";
 $ses_det=mysql_fetch_assoc(mysql_query($sql));
  $school_id=$ses_det['school_id'];// school_id
 
  $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$school_id));
   $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);
   $d2=mysql_query(" DELETE FROM int_schools_x_sessions_log WHERE id=".$getid);
   #3.crease:1 sess in account.
   
    $remain_ses=intval($school['avaiable_slots'])+1;
  $a=mysql_query(" UPDATE schools SET avaiable_slots='".$remain_ses."' WHERE SchoolId='".$school_id."' "); //+1
  
     
 //    
 }
 ////////////////////////// Delete sessions//////////////////   
  
        $tot_records=($tot_records>0)?$tot_records.'-Record deleted.':"Select a record";
      echo "<script>alert('".$tot_records."');location.href='sessions-list.php';</script>";
       
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
######### Updated form Dr-homwork #################

                  
                            
                            
                            
                 /* Process Search */
                             $start_date = date('Y-m-d h:i:s');
                               
                                 $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND type='$AllowSessionType' ";
                                //  $sql.=" AND tut_status='STU_ASSIGNED' ";//Live
                               // $sql.=" AND ses_start_time>'".$start_date."'";

                                $sql.=" AND is_drhomework='$AllowDrhomeworkSession' ";

                                $sql.=" ORDER BY ses_start_time DESC ";   
                 
                 
                 
                                 
                                  
          @extract($_GET) ;     
                                   if(isset($_GET['action'])&&$_GET['action']=="Search"){
                                   
                            $sql=" SELECT * FROM int_schools_x_sessions_log WHERE 1 AND type='$AllowSessionType' ";
                                  if(!empty($school_id)){ // school_id
                                $sql.=" AND school_id='$school_id' ";  
                               
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
                            $sql.=" AND is_drhomework='$AllowDrhomeworkSession' ";
                                   
                            
                            
                                  
                                  
                              
                                
                                  $sql.=" ORDER BY ses_start_time ASC ";     
                                   
                                }                                      
      ////////////////////
                                

                               //  echo   'Dr. Homework' ,$sql;  //die; 


                              $results = mysql_query($sql);
                                   $tot_record=mysql_num_rows($results); 

    /////////Get Dr Homework session Detail //////////////                                                                         
                                                                        
                            
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
			return confirm('Are you want to delete '+count+' record?');
		});
	});
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				
                           
                            
                            
                             <!-- School filter not required For - DrHomework -->

                            <div class="table-responsive" style="display: none;" >

					 <form id="search-users" method="GET" action=""  >
						
                    <?php  // echo (isset($session_type)&&$session_type=="all")?'selected':NULL; ?>
                    <select name="school_id">
                        <option 
                         value=""  >School</option>
                        <?php
                        //$sql=" SELECT * FROM `schools` WHERE 1 ";
                      //  $sql.=" AND avaiable_slots>0 ";
                     $sql=" SELECT DISTINCT(school_id) FROM `int_schools_x_sessions_log` WHERE 1 "; 
                        
                        $res=mysql_query($sql);
                    while( $row = mysql_fetch_assoc($res) ) {




                 $data= mysql_fetch_assoc(mysql_query(" SELECT SchoolName FROM `schools` WHERE SchoolId=".$row['school_id']))  ;     
                 
                     $arr_school[$row['school_id']]=$data['SchoolName'];
                    }
                      $d= asort($arr_school);
                   // print_r($arr_school);
                    
                    foreach ($arr_school as $key => $value) {
                    $sel=(isset($school_id)&&$school_id==$key)?'selected':NULL;
 
                  ?>
                     <option 
                         value="<?=$key?>" <?=$sel?>  ><?=$value?></option>
                    <?php } ?>
                                </select>From:
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
						<h3>DrHomework Sessions(<?=$tot_record?>)</h3>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="intervention_list.php"  title="Manage Intervention Session" 
            class="btn btn-success btn-md">Manage Intervention Session</a>
						<ul>
							
							<li>
								<button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
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
									<col width="280">
									
									
									
									<col width="155">
								</colgroup>
								<tr>
									<th>#</th>
									<th>Date/Time</th>
									<th>Details</th>
                                                                        
									
									<th>Date Created</th>
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
        $curr_time= date("Y-m-d H:i:s");
         
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
                                List of students:</strong>NA
                                <br/>
                                 
                                 
                                 
                                            
                                     
                                
                          
                                
                                    
                                                    
                                            </td>
                                          
                                            
                                            
<!--                                            <td>
                                               na
                                            </td>
                                            
                                            <td>
                                                nh</td>-->
                                                
                                                
                                            <td >
                                    <strong class="text-primary">
                                School:</strong><?=$int_school['SchoolName']?><br/>  
                                
                               <strong class="text-primary">
                                District:</strong><?=$districtName?><br/> 
                                
                           <strong class="text-primary">Tutor Assigned:</strong> #TutorName  <br/>
                           <strong class="text-primary">Parent:</strong> #TutorName  <br/>
                          
                           
                           
                              
                                
                                            </td>
                                            <td>
                                              <strong class="text-primary">Create Date:</strong><br/>       
                                           <?=date_format(date_create($row['created_date']), 'F d,Y');?> 
                                                
                                                <br/>
                                          <strong class="text-primary">Session:</strong>incomplete<br/>       
                                          <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                                          
                                          <a href="<?=$url_homeowrk_detail ?>?getid=<?=$row['id']?>" 
                                   
                                   style="text-decoration:underline;">View Detail</a> 


                                   &nbsp;
                                  


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
