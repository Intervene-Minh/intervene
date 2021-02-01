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
?>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        
        <div class="content" id="print_content">

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
										    <!--   Legal Form Data -->
                                                                                    
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
                                                                                           <?php
        if ((int)$_SESSION['login_role'] > 0) {
    ?>
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
        <?php } ?> 
                                                                                        <hr>
                                                                                      <h4 class="text-danger"><strong>Policy Form</strong></h4>
                                                                                    
                                                                                      <table class='table'>
                                                                                          <tr><td class="warning" style="vertical-align:middle;width:36%">Policy Form signature </td>
             <td>
                 <div > <?php  echo  $data2['pri_tutor_sign'];?></div>
                 <table class='table'>
                     <tr><td>Name</td><td><?=$tutorName?></td></tr>
                     <tr><td>Date</td><td><?=$data2['pri_tutor_date'];?></td></tr>
                     <tr><td colspan="2"><a href="legal_stuff_form.php?tid=<?=$_GET['tid']?>"class="btn btn-xs btn-danger">view full form</a></td></tr>
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






<div class='col-md-12'>
                                                                                        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Tutor application questions</h3>
  </div>
  <div class="panel-body">

							<table class='table'>
                                                            <tr><td class="warning">Email Address</td><td><?=$tutor_det['email']?></td></tr>
                                                            <tr><td class="warning">First name</td><td><?=$tutor_det['f_name']?></td></tr>
                                                            <tr><td class="warning">Last name</td><td><?=$tutor_det['lname']?></td></tr>
                                                            <tr><td class="warning">What is your phone number?</td><td><?=$tutor_det['phone']?></td></tr>
                                                            <?php  if($tdata['quiz_1_status']=='completed'&&$tdata['quiz_1_id']>0){ 
													 	$test_1='Math';
													 	$test_2='English';

													 	?>
                                                            <tr><td class="warning"><?=$test_1?> Result (%)</td><td><?=$quiz_1_score?>%</td></tr>
                                                            <?php } ?>
                                                            <?php  if($tdata['quiz_2_status']=='completed'&&$tdata['quiz_2_id']>0){ ?>
                                                            <tr><td class="warning"><?=$test_2?> Result (%)</td><td><?=$quiz_2_score?>%</td></tr>
                                                            <?php } ?>
                                                            </table>

													 

													 </div></div></div>


													 

											     
													



											

											        <!-- Step1 --> 

											        <!-- <div class="form-row">
														   <label>								Do you have a computer or tablet and reliable internet access? </label>     
															<div class="form-holder form-holder-2">
																<label class="form-row-inner">
																<input type="radio" name="is_computer" id="radio-r" value="yes" disabled="">
																	<span class="label">
																	Yes</span>
																</label>
															</div>
														</div> -->


                                             <?php 
                                            if(!empty($tutor_det['SpecialtySubjects'])){
                                            ?>
                                                                                                <div class='col-md-12'>
                                                                                        <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Specialty/Subjects *</h3>
  </div>
  <div class="panel-body">
      <table class="table">
                                             

                                             	
                                             
                                             	<?php 
													$sub = explode(',', $tutor_det['SpecialtySubjects']);
													foreach ($sub as $value) {
														?>
                                             <tr><td><input type="checkbox" checked></td><td> <?php echo $value;?></td></tr>
                                             	<?php } ?>
                                             </table></div></div></div>
                                         <?php } ?>
                                             
										</form>


									
								
									

									<!-- Start tutor  -->






								
							<!-- </form>  -->
							<!-- End form -->
						</div>
					</div>
				</div>
    </body>
        