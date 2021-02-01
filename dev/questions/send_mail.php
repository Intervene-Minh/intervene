<?php
include("header.php");

$user_id = $_SESSION['demo_user_id'];
$roles = mysql_query("SELECT * FROM `role`");
$districts = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');

$user_data= mysql_fetch_assoc(mysql_query('SELECT * from demo_users WHERE id='.$_SESSION['demo_user_id']));

if (isset($_POST['sendmail'])) {
   //var_dump($_POST);
        
    $name = $_POST['firstname'];
    $role = $_POST['role'];
    $district_other = $_POST['district'];
    $confirmmail = $_POST['confirmmail'];
    $email = $_POST['email'];
     if($district_other == 'other'){
        $district = 0;
       $district_name = $_POST['other_district'];
       $school = 0; 
       $school_name = $_POST['other_school'];
    }else{
        $district = $_POST['district'];
        $dist_sql = mysql_query("SELECT `district_name` from loc_district where id='$district'");
        if (mysql_num_rows($dist_sql) > 0) {
            $row = mysql_fetch_assoc($dist_sql);
            $dist_mail_names = $row['district_name'];
        }
        $school = $_POST['school'];
        $school_sql = mysql_query("SELECT `SchoolName` from schools where SchoolId= '$school'");
         if (mysql_num_rows($school_sql) > 0) {
            $row = mysql_fetch_assoc($school_sql);
            $school_mail_names = $row['SchoolName'];
        }
    }
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
    $role_sql = mysql_query("SELECT `name` FROM `role` where id='$role'");
    if (mysql_num_rows($role_sql) > 0) {
            $row = mysql_fetch_assoc($role_sql);
            $role_name = $row['name'];
        }
        $district=intval($district); $school=intval($school);
  ///////////////INsert data and send mail
       /***
        *  $mail_insert = mysql_query("INSERT INTO `mail_info` (`name`, `user_id`, `district_id`, `school_id`, `email`, `role_id`, `other_dist`, `other_school`)
                 VALUES ('$name','$user_id','$district', '$school', '$email', '$role', '$district_name', '$school_name')") or die(mysql_error()); 
        $last_id = mysql_insert_id();
        * 
        * ***/
         $mail_insert = mysql_query("INSERT INTO `mail_info` (`name`, `user_id`, `district_id`, `school_id`, `email`, `role_id`, `other_dist`, `other_school`)
                 VALUES ('$name','$user_id','$district', '$school', '$email', '$role', '$district_name', '$school_name')") or die(mysql_error()); 
        $last_id = mysql_insert_id();
        
        $send_name=$name;
         $sendto_email=$email;  // new user
         // demo_function_send.php #  demo_function-active-user.php
       require_once('inc/demo_function_send.php');
   //echo  sendExpiredToAdmin($name,$email,$school_mail_name,$dist_mail_name,$role_name);
    // sendEmailToActive
         // send_name, send_email
       
   sendEmailToActive($user_id,$send_name,$sendto_email);
     ?>
    <script type="text/javascript">
        alert('Information Sent. !');document.location.href='send_mail.php';
    </script>

         
         <?php   
        
  ///////////     
     
}



//////////////////send-mail
if (isset($_POST['sendmail9999'])) {
 
     if($confirmmail == $email){   
    $mail_insert = mysql_query("INSERT INTO `mail_info` (`name`, `user_id`, `district_id`, `school_id`, `email`, `role_id`, `other_dist`, `other_school`)
                 VALUES ('$name','$user_id','$district', '$school', '$email', '$role', '$district_name', '$school_name')"); 
        $last_id = mysql_insert_id();
       require_once('inc/demo_function-active-user.php');
    sendExpiredToAdmin($name,$email,$school_mail_name,$dist_mail_name,$role_name);
    
    
    
    ?>
    <script type="text/javascript">
      var _dcq = _dcq || [];
      var _dcs = _dcs || {};
      _dcs.account = '7926835';

      (function() {
        var dc = document.createElement('script');
        dc.type = 'text/javascript'; dc.async = true;
        dc.src = '//tag.getdrip.com/7926835.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(dc, s);
      })();
    _dcq.push(["identify", {
      email: "<?php print $email; ?>",
      first_name: "<?php print $name; ?>",
      district: "<?php print $dist_mail_name; ?>",
      title: "<?php print $role_name; ?>",
      your_school: "<?php print $school_mail_name; ?>",
      mail_type: "Expire Demo User",
      tags: ["Customer"]
    }]);

    _dcq.push(["subscribe", { campaign_id: "7926835", 9: { email: "sales@vrinfotech.in" }}]);
    </script>
<?php
     }else{
    echo 'email does not match';
}
}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php  include("demo_sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            
            
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><?php echo $title; ?></h3>
                        <?php if ($role == 0 && !$childs_passage): ?>
                            <ul>
                                <li><a href="javascript: void(0);" class="popup_form" data-target="#folder_dialog" title="New Folder"><i class="fa fa-plus-circle"></i></a></li>
                                <li><a href="javascript: void(0);" class="popup_edit" data-target="#folder_dialog"><i class="fa fa-pencil"></i></a></li>
                                <?php if (isGlobalAdmin()) : ?>
                                    <li><a href="javascript: void(0);" class="remove_items" data-type="category"><i class="fa fa-trash"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>		<!-- /.ct_heading -->

                    <div class="ct_display clear">
                        <div class="clear"></div>
                        <form id="checkout" name="mail-form" action="" method="POST">
                            <div class="form-group">
                                <label for="firstname">Administrator Name</label>
                                <input type="text"  placeholder="Administrator Name" name="firstname" id="firstname" class="form-control" />
                            </div>             
                            <div class="form-group">
                                <label for="title">Title / Role</label>
                                <select name="role" id="role" class="form-control ">
                                    <option value="">Select Role </option>
                                    <?php
                                    if ($roles) {
                                        while ($row = mysql_fetch_assoc($roles)) {
                                            ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                       <?php } } ?>
                                </select>
                                
                            </div>
                            <div class="form-group">
					<label for="district">District Name</label>
					<select name="district" id="district" onchange='SelectDist(this.value);' class="form-control required">
						<option value=""> Select District </option>
						<?php
						if( mysql_num_rows($districts) > 0 ) {
							while( $district = mysql_fetch_assoc($districts) )
								echo "<option value='{$district['id']}'>{$district['district_name']}</option>";
						}
						?>
                                                <option value="other">Other</option>
					</select>
                                        <input type="text" name="other_district" placeholder="Please Enter District Name" class="form-control" id="oth-dist" style='display:none;'/>
					
				</div>
				<div class="form-group">
					<label for="school">School Name</label>
					<select name="school" id="school" class="form-control " style='display:block;'>
						<option value=''></option>
					</select>
                                        <input type="text" name="other_school" placeholder="Please Enter School Name"  class="form-control" id="oth-school" style='display:none;'/>
					
				</div>
                            
                            <div class="form-group">
                                <?php  // =$user_data['email'] // DemoUsrEmail?>
                                <label for="email">Administrator Email</label>
                                <input type="email" name="email" 
                                       id="email" placeholder="Administrator Email"  value="" class="form-control required" placeholder="" />
                                
                                
                            </div>
                            <div class="form-group">
                                <label for="confirmmail">Confirm administrator email
                                    <? //=$_SESSION['demo_user_id'];?></label>
                                <input type="email" id="confirmmail" value="" placeholder="Confirm administrator email"
                               name="confirmmail" class="form-control required" placeholder="" />
                               
                            </div>
                            <input type="submit" class="btn btn-success" name="sendmail" value="Submit">
                        </form>
                        
                        
                        
                        
                        
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<script type="text/javascript">
function SelectDist(val){
 var element=document.getElementById('oth-dist');
 if(val=='other'){
   element.style.display='block';
   document.getElementById('school').style.display='none';
   document.getElementById('oth-school').style.display='block';
 }
 else  {
   element.style.display='none';
    document.getElementById('oth-school').style.display='none';
    document.getElementById('school').style.display='block';
 }
}

</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#district').on('change', function(event){
                    event.preventDefault();
			var district = $(this).val();
			$("#school option[value!='']").remove();
                        
			if( district == '' ) {
				alert('Please select a district!');
			} else {
				$.ajax({
					type	: "POST",
					url		: "get-school-district.php",
					data	: {district : district},
					success	: function(response) {
					$('#school').append(response);
					}
				});
			}
		});
                $('#checkout').on('submit', function(){
                           var firstname = document.getElementById("firstname").value;
                           if(firstname == ''){
                               alert('first name is required');
                               return false;
                           }
                           var sel = document.getElementById('role');
                            var sv = sel.options[sel.selectedIndex].value;
                            //alert(sv);
                            if(sv == ''){
                                alert('please select role');
                                return false;
                            }
                             var district = document.getElementById('district');
                            var dist = district.options[district.selectedIndex].value;
                            //alert(sv);
                            if(dist == ''){
                                alert('please select district');
                                return false;
                            }
                            
                           
                            var email = document.getElementById("email").value;
                            if(email == ''){
                                alert('Email is reqired');
                                return false;
                            }
                            var confirmmail = document.getElementById("confirmmail").value;
                            if(confirmmail != email){
                                alert('confirm email does not match');
                                return false;
                            }
                            
                           //alert(firstname);
			
		});
		
	});
</script>
<?php include("footer.php"); ?>
<?php ob_flush(); ?>