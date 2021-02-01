<?php
/*
@
//ini_set('display_errors', 1);
$districts = ('SELECT loc.* from loc_district loc INNER JOIN schools s ON s.district_id = loc.id GROUP BY loc.id ORDER BY district_name ASC ');
 echo $districts ; die; 
@ june 14
**/ 

include('questions/inc/connection.php');
require_once('old_header.php');

 // echo '==========';





$foldersxx = mysql_query("
	SELECT *
	FROM `terms`
	WHERE `taxonomy` = 'category'
	AND `parent` =1
	AND `active` =1
	AND `id` NOT IN ( 2634, 2635 )
	ORDER BY `terms`.`name` ASC
");


////////////////
$folders= mysql_query("
	SELECT *
	FROM `terms`
	WHERE `taxonomy` = 'category'
	AND `active` =1
        AND `parent` IN (1,2617,2618)
	AND `id` NOT IN ( 2634, 2635,2619,2620,2900,2901 )
	ORDER BY parent ASC
"); 


$districts = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
////////2=districts:Onlyschol registerd+districts/////////////



 $res_district= mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');



$states = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut"
,'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois"
,'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland"
,'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana"
,'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York"
,'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania"
,'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah"
,'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");


$roles = mysql_query("SELECT * FROM `role`");



if (isset($_POST['get_access'])){

   //print_r($_POST); die;

    
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $other_district = $_POST['other_district'];
    if($other_district == ''){
       $district_id = $_POST['district'];
       $dist_sql = mysql_query("SELECT `district_name` from loc_district where id='$district_id'");
        if (mysql_num_rows($dist_sql) > 0) {
            $row = mysql_fetch_assoc($dist_sql);
            $dist_mail_names = $row['district_name'];
        }
    }else{
        $district_id = 0;
       $district_name = $_POST['other_district']; 
    }
    $other_school = $_POST['other_school'];
    if($other_school == ''){
    $school = $_POST['school'];
    $school_sql = mysql_query("SELECT `school_name` from master_schools where id= '$school'");
         if (mysql_num_rows($school_sql) > 0) {
            $row = mysql_fetch_assoc($school_sql);
            $school_mail_names = $row['school_name'];
        }
    }else{
       $school = 0; 
       $school_name = $_POST['other_school'];
    }
    $phone_number = $_POST['phone'];
    $role = $_POST['role'];
    $role_sql = mysql_query("SELECT `name` FROM `role` where id='$role'");
    if (mysql_num_rows($role_sql) > 0) {
            $row = mysql_fetch_assoc($role_sql);
            $role_name = $row['name'];
        }
       $subject = $_POST['subject'];
       //print_r($subject); die;
       $value_array = array();
       foreach($subject as $value){
           $sb_query = mysql_query("
	SELECT name
	FROM `terms`
	WHERE `id` = '$value'
        ");
          if (mysql_num_rows($sb_query) > 0) {
            $row = mysql_fetch_assoc($sb_query);
            $value_array[] = $row['name'];
        }
       }
       $smart_prep_mail_name = implode(",",$value_array);
       //print_r($smart_prep_mail_name); die;
    $smart_prep = implode(",",$subject);
    $data_dash = $_POST['permission'];
    if($data_dash){
     $dd_query = mysql_query("
	SELECT name
	FROM `terms`
	WHERE `id` = '$data_dash'
        ");
          if (mysql_num_rows($dd_query) > 0) {
            $row = mysql_fetch_assoc($dd_query);
            $data_dash_mail_name = $row['name'];
        }
    }
    $today=date('Y-m-d H:i:s');
    $expiry_date= date('Y-m-d H:i:s', strtotime($today. ' + 14 days'));// 20 to 14

    // 


    if($district_name == ''){
        $dist_mail_name = $dist_mail_names;
    }else{
        $dist_mail_name = $district_name;
    }
    if($school_name == ''){
        $school_mail_name = $school_mail_names;
    }else{
        $school_mail_name = $school_name;
    }
    //echo $school_mail_name; die;
    $code = substr( md5(rand()), 0, 10);
    $schoolInsert = mysql_query("INSERT INTO `demo_users` (`email`, `password`, `first_name`, `last_name`, `district_id`, `school_id`, `master_school_id`, `phone_number`, `role`, `other_district`, `other_school`, `expiry_date`, `data_dash`,`smart_prep`,`active_code`)
                 VALUES ('$email','$password', '$first_name', '$last_name', '$district_id', '$school', 1, '$phone_number', '$role', '$district_name', '$school_name', '$expiry_date', '$data_dash', '$smart_prep', '$code')");
    $userId = mysql_insert_id();
    require_once('./questions/inc/demo_function-active-user.php');// SendEmail
    //sendNoticeToAdmin($first_name, $last_name,$school_mail_name,$dist_mail_name,$role_name);
    
    
    sendEmailToActive($userId);
    $_SESSION['temp_role_id']=$role;
    $_SESSION['temp_email']=$email;
    $_SESSION['temp_firstname']=$first_name;
    $_SESSION['temp_lastname']=$last_name;
    $_SESSION['temp_dist_name']=$dist_mail_name;
    $_SESSION['temp_role_name']=$role_name;
    $_SESSION['temp_school_name']=$school_mail_name;
    $_SESSION['temp_phone']=$phone_number;
    $_SESSION['temp_smart_preb']=$smart_prep_mail_name;
    $_SESSION['temp_data_dash'] = $data_dash_mail_name;
    
   echo   sendNoticeToAdmin($first_name, $last_name,$school_mail_name,$dist_mail_name,$role_name);
    
    // header("Location:demo_thanks.php");exit; // demo_thanks to
     // login demo user
    // Smart Prep
   
    
    ?> 
    
   
  <script type="text/javascript">
        document.location.href='demo_thanks.php';
    </script>
    <?php
}

/*---------------------------------Send Email--------------------------------*/



						

?>

<div class="container" style="padding: 20px 15px; text-align: center;">
	<h3>Access the Demo</h3>
	<p>We are offering access to a demo of our Student Gap Analysis Tool.<br />
	Check your email after submitting this form for a direct link.</p>
	<hr class="bottom-line">
</div>
  
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-8 col-md-12 col-md-12">
			<?php
			if( isset($_SESSION['errors']) && count($_SESSION['errors']) > 0 ) {
				foreach($_SESSION['errors'] as $error)
					echo '<p class="text-danger">' . $error . '</p>';
				unset($_SESSION['errors']);
			}
			?>
			<form id="checkout" method="POST" action="">
				<div class="form-group">
					<label for="state">State</label>
					<select name="state" id="state" class="form-control required">
						<option value="TX">Texas</option>
					</select>
					<small class="error text-danger">Please select your state!</small>
				</div>
				<div class="checkbox">
					<label for="exampleSelectMany" style="padding-left: 0;"><strong>Select up to 2 grades for your trial access</strong></label>
				</div>
				<?php
				if( $folders )
					while($row = mysql_fetch_assoc($folders)) {
						$name = $row['name'] . ' STAAR Question Database & Student Gap Analysis';
						echo '<div class="checkbox">
							<label><input type="checkbox" id="'.$row['id'] .'" name="subject[]" class="subject" value="' . $row['id'] . '"> ' . $name . '</label>
						</div>';
					}
				else
					echo '<p>No subject supported!</p>';
				?>
				<br />
                                
                                <div class="checkbox">
                                <label for="exampleSelectMany" style="padding-left: 0;"><strong>Data Dash Permission</strong></label>
                            </div>
                                <?php
                                $foldersxx = mysql_query("
                                                                SELECT *
                                                                FROM `terms`
                                                                WHERE `taxonomy` = 'category'
                                                                AND `parent` =1
                                                                AND `active` =1
                                                                AND `id` NOT IN ( 2634, 2635 )
                                                                ORDER BY `terms`.`name` ASC
                                                        ");
                                
                               $folders= mysql_query("
	SELECT *
	FROM `terms`
	WHERE `taxonomy` = 'category'
	AND `active` =1
        AND `parent` IN (1,2617,2618)
	AND `id` NOT IN ( 2634, 2635,2619,2620,2901 )
	ORDER BY parent ASC
"); 
                                
                            if ($folders) {
                                $run = 0;
                                while ($rowData = mysql_fetch_assoc($folders)) {
                                    //print_r($rowData['id ']);
                                    $name = $rowData['name'] . ' Data Dash';
                                    // echo $arrayList[$run]['id'];
                                    if (in_array($rowData['id'], $grade_id_list)) {
                                        echo '<div class="checkbox">
                                            <label><input type="radio" name="permission[' . $rowData['id'] . ']" class="subject" value="' . $rowData['name'] . '" checked> ' . $name . '</label>
                                            </div>';
                                    } else {

                                        echo '<div class="checkbox">
                                    <label><input type="radio" name="permission" class="subject" value="' . $rowData['id'] . '"> ' . $name . '</label>
                                    </div>';
                                    }
                                    $run++;
                                }
                            } else {
                                echo '<p>No subject supported!</p>';
                            }

                            ?>
                            <br/>
				
				<div class="form-group">
					<label for="firstname">Your First Name</label>
					<input type="text" name="firstname" id="firstname" class="form-control required" />
					<small class="error text-danger">Please enter your first name!</small>
				</div>
				<div class="form-group">
					<label for="lastname">Your Last Name</label>
					<input type="text" name="lastname" id="lastname" class="form-control required" />
					<small class="error text-danger">Please enter your last name!</small>
				</div>
				<div class="form-group">
					<label for="title">Title / Role</label>
					<select name="role" id="title" class="form-control required">
                                            <option value="">Select Role </option>
                                            <?php
				    if( $roles ){
					while($row = mysql_fetch_assoc($roles)) { ?>
						<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                    <?php } } ?>
					</select>
					<small class="error text-danger">Please enter your title!</small>
				</div>
				<div class="form-group">
					<label for="email">Your Email</label>
					<input type="email"  name="email" id="email" class="form-control required" placeholder="" />
					<p><small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small></p>
					<small class="error text-danger">Please enter valid email address!</small>
				</div>
				<div class="form-group">
					<label for="confirmmail">Confirm email address</label>
					<input type="email" id="confirmmail" class="form-control required" placeholder="" />
					<small class="error text-danger">Your email does not match!</small>
				</div>
				<div class="form-group">
					<label for="district">District Name</label>
					<select name="district" id="district"  class="form-control required">
						<option value=""> Select District<?php //=mysql_num_rows($res_district)?> </option>
						<?php
						if( mysql_num_rows($res_district) > 0 ) {
							while( $district = mysql_fetch_assoc($res_district) )
								echo "<option value='{$district['id']}'>{$district['district_name']}</option>";
						}
                        // other options
                        echo ' <option value="other">Other</option>';

						?>
                                               
					</select>
                  <input type="text" name="other_district" 
                  placeholder="Please Enter District Name" class="form-control" id="oth-dist"  />


					<small class="error text-danger">Please enter your district!</small>
				</div>
				<div class="form-group">
					<label for="school">School Name</label>

					<div id="school_div">
                    <input  type="text" placeholder="Select district" readonly="" 
                     id="other_opn" class="form-control required">
					</div>
					
					
                  <!-- other_school -->
                 <input type="text" name="other_school" placeholder="Please Enter School Name" 
                     class="form-control" id="oth-school">

					<!-- <small class="error text-danger">Please enter your school!</small> -->
				</div>







				  <!-- Phone NUmber -->
				<div class="form-group">
					<label for="phone">Phone Number</label>
					<input type="tel"  name="phone" id="phone" class="form-control required" />
					<small class="error text-danger">Please enter your phone number!</small>
				</div>
                                 <div class="form-group">
					<label for="phone">Password</label>
					<input type="password" name="password" id="password" class="form-control required" />
					<small class="error text-danger">Please enter your Password!</small>
				</div>
				<br />
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <input type="hidden" name="get_access" value="get access" />
						<button type="submit" name="get_access" class="btn btn-primary" style="cursor: pointer; margin-top: 20px;">GET ACCESS</button>
					</div>
				</div>
                                
			</form>
		</div>

<!--		<div class="text-center align-center col-lg-4 col-md-4 col-md-12 col-md-12">
			<p class="h2">Have You Seen Our Samples?</p>
			<p class="lead">Click on a file below to download a sample for the grade level of your choosing. Our misconception analysis helps your interventionist to find a targeted lesson plan for each student</p>
			<a href="/samples/3rdSTAARMathEnglish.pdf" title="Download 3rd Grade Math Sample">
				<img border="0" src="/samples/3rd.png" alt="3rd" width="104" height="104">
			</a>
			<a href="/samples/4thSTAARMathEnglish.pdf" title="Download 4th Grade Math Sample">
				<img border="0" src="/samples/4th.png" alt="4th" width="104" height="104">
			</a>
		</div>-->
	</div>
</div>




<script type="text/javascript">
 //function SelectDist(val){

 // var element=document.getElementById('oth-dist');

 // if(val=='other'){
 //   element.style.display='block';
 //   document.getElementById('school').style.display='none';
 //   document.getElementById('oth-school').style.display='block';
 // }else{
 //   element.style.display='none';
 //    document.getElementById('oth-school').style.display='none';
 //    document.getElementById('school').style.display='block';
 // }


//}

////////Get district_school//////





    // var get_url='https://intervene.io/questions/get-school-district.php';
      var get_url='https://intervene.io/ajax/get-school-district.php';

	$(document).ready(function(){
        $('#oth-school').hide();
        $('#oth-dist').hide();
		 console.log('URL:'+get_url);

		$('#district').chosen();
		$('#school').chosen();
		$('#district').on('change', function(){
			var district = $(this).val();
			//alert(district+':district');
			///////
			var element=document.getElementById('oth-dist');

                if(district=='other'){
                	 console.log('Other selected:'+district);
                	$('#oth-dist').show(); $('#oth-school').show();
                    $('#school_div').html('<span></span>');
                	$('#school_div').hide();
                //document.getElementById('school').style.display='none';
                return false;
                 }else{// hide other
                 		$('#oth-dist').hide(); $('#oth-school').hide();
                 		$('#school_div').show();
                 	}
                    


                 





			//////////////////////////
			
			//$("#school option[value!='']").remove();
                        
			if( district == '' ){
				alert('Please select a district!');
			   }else{
				$.ajax({
					type	: "POST",
					url		:get_url,
					data	: {district : district},
					success	: function(response) {  $('#school_div').html(response); 
					$('#school').chosen();
					}
				});
			}
		});
       ////Schooo selection //
       
       // var school_val=$(this).val();
       




      /////////////////////////////////

		
		$('#checkout').on('submit', function(event){
			event.preventDefault();
			
			var checkout = $(this);
			var validate = true;
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			/* Validate checkbox */
			var counter = 0;
			$(checkout).find('.subject').each(function(){
				if( $(this).is(':checked') )
					counter++;
			});
			if( counter < 1 ) {
				alert('Please select at least one grade!');
				$('.subject').eq(0).focus();
				return false;
			} else if( counter > 3 ) {
				alert('Please select up to 2 grades for your trial access!');
				$('.subject').eq(0).focus();
				return false;
			}
			
                        var namm= /^[a-zA-Z ]*$/; // firstname lastname
                        var fname=$('#firstname').val();
                         var lname=$('#lastname').val();
			$(checkout).find('.required').each(function(){
				if( $.trim($(this).val()) == '' ||
				  ( $(this).attr('type') == 'email' && !filter.test($(this).val()) ) ||
				  ( $(this).attr('id') == 'confirmmail' && $(this).val() != $('#email').val() ) ||
				  ( $(this).attr('id') == 'confirm' && $(this).val() != $('#password').val() ) ||
                                   ( !namm.test(fname)||!namm.test(lname))
				) {
					validate = false;
                                      //// 
                                      if(!namm.test(fname))
                                      alert('First Name-alphabets Only!');
                                  if(!namm.test(lname))
                                      alert('Your Last Name-alphabets Only!');
                                        
					$(this).focus();
					$(this).siblings('.error').show();
					return false;
				} else {
					$(this).siblings('.error').hide();
				}
			});
			if( !validate )
				return false;
			
			/* Validate existed email */
			var email = $.trim($('#email').val());
			$.ajax({
				type	: "POST",
				url		: "orders/ajax-check-email.php",
				data	: {email : email},
				success	: function(response) {
							if(response == 0) {
								validate = false;
								alert('Your email is already existed!');
								$('#email').focus();
							}
						},
				async	: false
			});
			if( !validate )
				return false;
			
			$(checkout).unbind('submit').submit();
		});
	});
</script>

<script>
// $('#myCheckbox').prop('checked', true); // Checks it
  // $('#myCheckbox').prop('checked', false); // Unchecks it

$(document).ready(function () {
    $("input[name='subject[]']").change(function () {
        var maxAllowed = 2;
        var cnt = $("input[name='subject[]']:checked").length;
       
       var arlene = [];
       
       
        if (cnt > maxAllowed) {
          //  $(this).prop("checked", ""); // do not check cureent
            /////
            $("input[name='subject[]']:checked").each(function(){
				
				//var id = $(this).val();
             //$('#0').prop('checked', false); // Unchecks it   
                var id =this.id;
              // arlene[]=this.id;
               arlene.push(this.id);
              //alert(id);
				//$('#arr-user').val(val+','+id);
			});
          var artot=arlene.length;
            //alert(arlene.length);  //  array length 
            
      
            //for(i=0; i<arlene.length; ++i){
   // alert(arlene[i]+"- id");   }
      /////////////////////////
       // 1 st un check
       $('#'+arlene[0]).prop('checked', false); // Unchecks it
       
      // list check 
      $('#'+arlene[2]).prop('checked', true); // Unchecks it : 3rd element checekde
      //   exit();
       arlene='';
      //////////////////////
    
            
            
           //alert('You can select maximum ' + maxAllowed + ' technologies!!');
        }
    });
});


</script>



<!--ContactSec-->

<?php require_once('footer.php');?>
<style>
	#school_chosen{width:100%!important;}
</style>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css" />

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
