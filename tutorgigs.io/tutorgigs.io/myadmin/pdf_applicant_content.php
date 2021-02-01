<?php
include('inc/connection.php'); 
include('inc/public_inc.php'); 


function quiz_result($p2db,$getid,$test_id){
    //$test_id=$tdata['quiz_1_id']; //Default 

$attempted=("SELECT * FROM `tutor_result_logs` WHERE `tutor_id` =".$getid." AND `test_id` =".$test_id);
 $get_result=mysqli_query($p2db,$attempted);
  $quiz_1=array();
   $quiz_2=array(); 
  $total_attempted=mysqli_num_rows($get_result);
     $correct=0;
        while ($row = mysqli_fetch_assoc($get_result)) {
     //echo $row['attempt_id']; echo '<br/>';
          // attempt_id  answer_id
          if($row['answer_id']==$row['attempt_id']){
            $correct=$correct+1;
          }      
    }
 
  $get_scored=($correct*100)/$total_attempted;
  return  $get_scored=round($get_scored,2);



 } 

//////////////////////

     if (isset($_GET['tid'])) {
    	$getid=$_GET['tid'];
    }

 $tdata=$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$getid));

  $get_state_arr=unserialize($tutor_det['signup_state']);
   //  print_r($get_state_arr);

  //////Profile data////


 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$getid));

$edit=unserialize($data2['profile_1']);

  //echo '<pre>', print_r($edit);

 //print_r($data2['subjects']); echo '<br/>';

include('inc/sql_connect.php'); 
   $p2db=p2g();

 if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){

  $test_id=$tdata['quiz_1_id']; //Default 
  $quiz_1_score=quiz_result($p2db,$getid,$test_id);
}


///Quiz 2 result 
if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){
  $test_id=$tdata['quiz_2_id']; //Default 
  $quiz_2_score=quiz_result($p2db,$getid,$test_id);
}


//print_r($quiz_1_score); 
  
  // print_r($quiz_2_score); 



/////////////
//echo 'Applicationxx';
?>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        
        <div class="content" style="width:100%">

<div class="col-md-4" ><img src="images/logo.png"></div>
            <div class="col-md-4">Tutor Application Details</div>
            <div class="col-md-4" style="text-align: right">Date - <?php echo date('m/d/Y H:ia');?></div>
            <br> <br>
									  <form id="regForm" action="" method="post" enctype="multipart/form-data">
										  <div class="tab">
										      <div class="inner">
										      <?php 
										      //echo '=='.$data2['is_legal_1'].':=is_legal_2'.$data2['is_legal_2'];

										      if($data2['is_legal_1']==1&&$data2['is_legal_2']==1){?>
										   <div class='col-md-12'>
                                                                                        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Legal Staff</h3>
  </div>
  <div class="panel-body">
										   


										      <?php  if(!empty($data2['is_legal_3'])){?>
										      
										      <?php  
										      $form_3_arr=unserialize($data2['legal_form3_data']);
										      $tutorName=$tutor_det['f_name'].' '.$tutor_det['lname'];
										      // f_name  lname

										 // echo '<pre>'; print_r($form_3_arr);

										      ?>
                                                                                      <div class="col-md-12">
                                                                                           <h4 class="text-danger"><strong>Form W-9</strong></h4>
                                                                                      <hr>
                                                                                      <table class="table">
        <?php if(!empty($form_3_arr['name_income_tax'])) { ?> <tr><td class="warning">Income Tax Name</td><td><?php echo $form_3_arr['name_income_tax'];?></td></tr> <?php } ?>
        <?php if(!empty($form_3_arr['business_name'])) { ?> <tr><td class="warning">Business Name</td><td><?php echo $form_3_arr['business_name'];?></td></tr> <?php } ?>
        <?php if(!empty($form_3_arr['tax_classification'])) { ?> <tr><td class="warning">Federal Tax Classification</td>
            <td>
                <ul style="list-style-type:none;padding-left:0px">
                <?php 
                foreach($form_3_arr['tax_classification'] as $tax) {
                  if( $tax == 1) echo '<li>Individual/sole proprietor or single-member LLC</li>';
                  if( $tax == 2) echo '<li>C Corporation</li>';
                  if( $tax == 3) echo '<li>S Corporation</li>';
                  if( $tax == 4) echo '<li>Partnership</li>';
                  if( $tax == 5) echo '<li>Trust/estate</li>';
                  if( $form_3_arr['tax_classification'] == 6) echo '<li>Limited liability company. Enter the tax classification</li>';
                }
                  
                ?>
                    </ul>
            </td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['limited_liability_text'])) { ?> <tr><td class="warning">Other Tax Classification</td><td><?php echo $form_3_arr['limited_liability_text'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['exempt_payee_code'])) { ?> <tr><td class="warning">Exempt payee code</td><td><?php echo $form_3_arr['exempt_payee_code'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['exempt_from_fatca'])) { ?> <tr><td class="warning">Exemption from FATCA</td><td><?php echo $form_3_arr['exempt_from_fatca'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['income_tax_name_and_address'])) { ?> <tr><td class="warning">Requester's name and address</td><td><?php echo $form_3_arr['income_tax_name_and_address'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['income_tax_address'])) { ?> <tr><td class="warning">Address</td><td><?php echo $form_3_arr['income_tax_address'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['income_tax_address_city'])) { ?> <tr><td class="warning">City, state, and ZIP</td><td><?php echo $form_3_arr['income_tax_address_city'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['list_account_number'])) { ?> <tr><td class="warning">List account number(s)</td><td><?php echo $form_3_arr['list_account_number'];?></td></tr> <?php } ?>
         
         <?php if(!empty($form_3_arr['social_security_number'])) { ?> <tr><td class="warning">Social security number</td><td><?php echo $form_3_arr['social_security_number'];?></td></tr> <?php } ?>
         <?php if(!empty($form_3_arr['employer_identification_number '])) { ?> <tr><td class="warning">Employer identification number </td><td><?php echo $form_3_arr['employer_identification_number '];?></td></tr> <?php } ?>
         <tr><td class="warning" style="vertical-align:middle !important;">Signature </td><td><?php  echo  $data2['legal_form3_sign'];?></td></tr>
         </table>
                                                                                        <hr>
                                                                                      <h4 class="text-danger"><strong>Policy Form</strong></h4>
                                                                                    
                                                                                      <table class='table'>
                                                                                          <tr><td class="warning" style="vertical-align:middle;width:36%">Policy Form signature </td>
             <td>
                 <div > <?php  echo  $data2['pri_tutor_sign'];?></div>
                 <table class='table'>
                     <tr><td>Name</td><td><?=$tutorName?></td></tr>
                     <tr><td>Date</td><td><?=$data2['pri_tutor_date'];?></td></tr>
                    
                 </table>
             </td>
         </tr>
         </table>
         <?php $terms=unserialize($data2['terms_form_data']);?>
                                                                                       <hr>
                                                                                      <h4 class="text-danger"><strong>Terms Form</strong></h4>
                                                                                     
                                                                                      <table class='table'>
         <tr><td class="warning" style="vertical-align:middle;width:36%">Terms Form signature </td>
             <td>
                 <div > <?php  echo  $data2['term_tutor_sign'];?></div>
                 <table class='table'>
                     <tr><td>Name</td><td><?=$tutorName?></td></tr>
                     <tr><td>Date</td><td><?=$terms['term_form_date'];?></td></tr>
                   
                 </table>
             </td>
         </tr>
       
                                                                                      </table>
                                                                                          </div>
										   

										           <?php }?>
 </div></div></div>



										     <!-- Legal Form3 Data -->

										   

										     

													

												
										    
										     
										       

										        

													

													<?php } //if($data2['is_legal_1']==1&&$data2['is_legal_2']==1){?>

													<!-- Payment info provived -->
													<?php 
													if(!empty($data2['payment_email'])&&!empty($data2['payment_phone'])){


													 // payment_email payment_phone?>
<div class='col-md-12'>
                                                                                        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Payment information</h3>
  </div>
  <div class="panel-body">

							<table class='table'>
                                                            <tr><td class="warning">Paypal email address</td><td><?=$data2['payment_email'];?></td></tr>
                                                            <tr><td class="warning">Paypal Phone Number</td><td><?=$data2['payment_phone'];?></td></tr>
                                                            </table>

													 

													 </div></div></div>
													<?php }?>










                                 <!-- XXXXXXXXXXXXXXXX -->
								<div class='col-md-12'>
                                                                                        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Tutor application questions</h3>
  </div>
  <div class="panel-body">

													<div class="form-row">
													    <label>Email Address  </label> 
														<div class="form-holder form-holder-2">
															
															   
													          <?=$tutor_det['email']?>
																  
															
														</div>
													</div>


													<div class="form-row">
													    <label>Last name  </label> 
														<div class="form-holder form-holder-2">
															   
																<?=$tutor_det['lname']?>
															
														</div>
													</div>

													



													<div class="form-row">
													    <label>What is your phone number?  </label>    
														<div class="form-holder form-holder-2">
															
															   <?=$tutor_det['phone']?>
															
														</div>
													</div>


													 <?php  if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){ 
													 	$test_1='Math';
													 	$test_2='English';

													 	?>

											     <div class="form-row">
													    <!-- <label>Quiz 1 Result (%)</label>  -->
													   
                                                                                                            <?php if($tdata['quiz_1_id'] == 5) { ?>
													    <label class='text-danger'><?=$test_1?> Result (%)<div></div></label>
                                                                                                            <?php } else if($tdata['quiz_1_id'] == 6) {?>
                                                                                                            <label class='text-danger'><?=$test_2?> Result (%)<div></div></label>
                                                                                                            <?php } ?>
														<div class="form-holder form-holder-2">
															
															   
										 <?=$quiz_1_score?>%
													       
																  
															
														</div>
													</div>
													<?php } ?>



											<?php  if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){ ?>

											     <div class="form-row">
													    <label class='text-danger'><?=$test_2?> Result (%)<div></div></label>
														<div class="form-holder form-holder-2">
															<?=$quiz_2_score?>%
														</div>
													</div>
													<?php } ?>

											        <!-- Step1 --> 



														
														<?php 
														$yes_checked='';
														if($edit['is_computer']=='no'){
															$no_checked='checked';

														}

														?>
														<div class="form-row">
														   <label><?php //=$edit['is_computer']?>Do you have a computer or tablet and reliable internet access? </label>     
															<div class="form-holder form-holder-2">
																
																<input type="radio"
																 <?=($edit['is_computer']=='yes')?'checked':null?>
																 name="is_computer" id="radio-r" value="yes"  >&nbsp; Yes
																	
															</div>
														</div>
														<div class="form-row">
														
															<div class="form-holder form-holder-2">
																
																<input type="radio" 
																 <?=($edit['is_computer']=='no')?'checked':null?> name="is_computer"   value="no" id="radio-r" >
																	&nbsp;No
															</div>
														</div>
														

														<div class="form-row">
														    <label>How did you hear about us?</label>
															<div class="form-holder form-holder-2">
																	<?=$edit['hear']?>
								
															</div>
														</div>
														
														<div class="form-row">
														<label>When would you like to get started Tutoring?</label>
															<div class="form-holder form-holder-2">
																<?=$edit['started_date']?>
								
															</div>
														</div>






														<!-- Form2 data -->


														<!-- <div class="form-row">
												  <label>Show student how to answer the following question (enter URL to recording as answer)</label>
													<div class="form-holder form-holder-2">

													    <img src="https://lh5.googleusercontent.com/rPp_8U70780ebbYSLxB4Sj8_GvPJi_rj2RoMRUwndFEDj2HX_JsHlv17ncw393wQAdr3qb9KvQ=w740" class="img-responsive" title="" alt="Captionless Image">
														<label class="form-row-inner">
														  <input type="text" name="f2_q_1" oninput="this.className = ''" value="<?php //=$edit['f2_q_1']?>" class="form-control">
														</label>
													</div>
												</div> -->


											

												<!-- Form3 -->






														
										       


										        <div class="form-row">
												 <label>Why do you want to Tutor?</label> 
													<div class="form-holder form-holder-2">
													   <?=$edit['f3_q_1']?>
													</div>
												</div>
												<div class="form-row">
												 <label>What makes a good Tutor?</label> 
													<div class="form-holder form-holder-2">
													   <?=$edit['f3_q_2']?>
													</div>
												</div>
												
												<div class="form-row">
												    <label> Have you ever tutored before?  </label>     		
													<div class="form-holder form-holder-2">
														
														<input type="radio"
														 <?=($edit['f3_q_3']=='yes')?'checked':null?>
														 name="f3_q_3" id="radio-r" value="yes"   >
															&nbsp;Yes
													</div>
												</div>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														
														<input type="radio" 
														 <?=($edit['f3_q_3']=='no')?'checked':null?> name="f3_q_3"  value="no" id="radio-r" >
															&nbsp;No
													</div>
												</div>
												     		
												<div class="form-row">
												    <label> Have you ever Tutored online?  </label> 
													<div class="form-holder form-holder-2">
														
														<input type="radio" 
														 <?=($edit['f3_q_4']=='yes')?'checked':null?>
														 name="f3_q_4" id="radio-r" value="yes"  >
															&nbsp;Yes
													</div>
												</div>
												<div class="form-row">
													<div class="form-holder form-holder-2">
														
														<input type="radio"  
														 <?=($edit['f3_q_4']=='no')?'checked':null?> name="f3_q_4" 
														  value="no" id="radio-r" >
															&nbsp;No
													</div>
												</div>
												<div class="form-row">
												 <label>If you have tutored, where and what did you tutor?</label> 
													<div class="form-holder form-holder-2">
													    <?=$edit['f3_q_5']?>
													</div>
												</div>
												
												<div class="form-row">
												    <label> How many years have you Tutored ?  </label> 
													<div class="form-holder form-holder-2">
														
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_1')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_1"  >
															&nbsp;Less than a year
														
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_2')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_2" >
															&nbsp;1-3 years
														
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_3')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_3"  >
															&nbsp;3-5 year
														
														<input type="radio"
														 <?=($edit['f3_q_6']=='opn_4')?'checked':null?>
														 name="f3_q_6" id="radio-r" value="opn_4"   >
															&nbsp;More than 5 years
													</div>
												</div>
												
												 <?php 
												  // Edit array
												  $save_grades_arr=explode(',',$data2['grade_levels']);

												  $grade_arr=array('grade_1'=>'Elementary School (3-5)',
													'grade_2'=>'Middle School (6-8)',
													'grade_3'=>'High School (9-12)'
													
													          );

												  ?>
												<div class="form-row">
												    <label> What grade levels do you want to Tutor?  </label> 
													<div class="form-holder form-holder-2">
														  <?php 
                                                     foreach ($grade_arr as $key => $value) {
                                                     	# code...
                                                     	if(is_array($save_grades_arr))
                                                    $checked=(in_array($key,$save_grades_arr))?"checked":null; // checked
                                                    else $checked=null;
                                                     ?>
														
														<input type="checkbox" <?=$checked?>	  name="grade_levels[]" 
														id="radio-r" value="<?=$key?>" required>
															&nbsp;<?=$value?>

														<?php }?>

														


													</div>
												</div>
												
												<div class="form-row">
												<label> What subjects can you Tutor ? </label> 
												  <?php 
												  // Edit array
												  $save_subj_arr=explode(',',$data2['subjects']);

												  $subj_arr=array('math'=>'Math',
													'english'=>'English Comprehension & Reading',
													'esl'=>'ESL',
													'languages'=>'Languages'
													          );

												  ?>
													<div class="form-holder form-holder-2">

                                                     <?php 
                                                     foreach ($subj_arr as $key => $value) {
                                                     	# code...
                                                     	if(is_array($save_subj_arr))
                                                    $sel=(in_array($key,$save_subj_arr))?"checked":null; // checked
                                                    else $sel=null;
                                                     ?>

														
														<input type="checkbox" <?=$sel?>  name="subjects[]" id="radio-r"
														 value="<?=$key?>" >
															&nbsp;<?=$value?>
														<?php }?>


														


														<!-- <label class="form-row-inner">
														<input type="checkbox"  <?=($data2['subjects']=='other')?'checked':null?>    name="subjects_other" 
														 id="radio-r" value="other"  >
															<span class="label">
															Other:<label class="form-row-inner">
														  <input type="text" name="subjects_other_text"    style="width: 80%"
														  value="<?=(isset($data2['subjects_other']))?$data2['subjects_other']:null?>" class="form-control">
														</label>

															</span>
															
															
														</label> -->

														

													</div>
												</div>

												<div class="form-row">
												 <label>What languages do you speak aside from English?</label> 
													<div class="form-holder form-holder-2">
													   <?=$edit['f3_q_7']?>
													</div>
												</div>
												
												<div class="form-row">
												    <label>Are you a certified Teacher?</label>
													<div class="form-holder form-holder-2">
														
														<input type="radio"
														 <?=($edit['f3_q_8']=='yes')?'checked':null?>
														 name="f3_q_8" id="radio-r" value="yes"    >
															&nbsp;Yes
														
														<input type="radio"
														 <?=($edit['f3_q_8']=='no')?'checked':null?>
														 name="f3_q_8" id="radio-r" value="no"    >
															&nbsp;No
													</div>
												</div>
												
												<?php 
												$familiar=array('opn_1'=>'Not familiar',
													'opn_2'=>'I have heard of it, but not very familiar',
													'opn_3'=>'Somewhat familiar',
													'opn_4'=>'Very familar',
													          'opn_5'=>'I am a specialist');

												?>
												<div class="form-row">
												    <label>How familiar are you with TEKS & STAAR?</label> 
													<div class="form-holder form-holder-2">
														
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_1')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_1" >
															&nbsp;Not familiar
														
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_2')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_2" >
															&nbsp;I have heard of it, but not very familiar
														
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_3')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_3" >
															&nbsp;Somewhat familiar
														
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_4" >
															&nbsp;Very familar
														
														<input type="radio"
														 <?=($edit['f3_q_9']=='opn_4')?'checked':null?>
														 name="f3_q_9" id="radio-r" value="opn_4" >
															&nbsp;I'm a specialist
													</div>
												</div>

												<!-- PHOTO ID -->
												<div class="form-row">
												 <label>Photo ID</label> 
												 <?php 
												// Image if exitst 
												$photo_id='https://llabel.googleusercontent.com/KqYRICZo55OamI7-_aJNtFTJffEY5cRdAh003Yc_uqG7B_jmTmYiN0OR_oFCOCBNBzYYxULUmQ=w464';

												if(!empty($data2['photo_id'])){
												$photo_id='../tutor-images/'.$data2['photo_id'];	
												}


												?>
													<div class="form-holder form-holder-2">
													   <!--  <label class="form-row-inner">
														  <input type="text" name="f3_q_7"  
														  value="<?=$edit['f3_q_7']?>" class="form-control">
														</label> -->
														<img src="<?=$photo_id?>" class="img-responsive" 
														title="Applicant ID" alt="ID Photo">


													</div>
												</div>


</div> </div>
										  </div>


										     </div>
										  </div>


										</form>


									
								
									

									<!-- Start tutor  -->






								
							<!-- </form>  -->
							<!-- End form -->
						</div>
    </body>
        