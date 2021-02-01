<?php
///
include("header.php");

$login_role = $_SESSION['login_role'];
if($login_role!=0 || !isGlobalAdmin()){
	header("location: index.php");
}

//////// Login as Principal
// principal
if(isset($_GET['view'])&&$_GET['view']=="principal"&& $_GET['id'] > 0) {
     $qq= "SELECT * FROM schools WHERE SchoolId= " . $_GET['id'];
    $school= mysql_fetch_assoc(mysql_query($qq));

    $_SESSION['schools_id'] = $school['SchoolId'];
  
   // $_SESSION['login_role'] = 1;
    //$_SESSION['login_status'] = 1;
      header("Location: school.php"); exit;
}



//////// Login as Principal

if($_GET['upload_logo'] == 'yes') {
    $error = 'School logo has been updates successfully.';
}

    $email = $_SESSION['temp_email'];
    $firstname = $_SESSION['temp_firstname'];
    $lastname = $_SESSION['temp_lastname'];
    $dist_mail_name =  $_SESSION['temp_dist_name'];
    $master_school_name = $_SESSION['temp_master_school'];
    $school_mail_name = $_SESSION['temp_school_name'];
    $phone_number = $_SESSION['temp_phone'];
    $smart_preb_name = $_SESSION['temp_smart_preb'];
    $data_dash_name = $_SESSION['temp_data_dash'];
    $address = $_SESSION['temp_address'];
    $city = $_SESSION['temp_city_name'];
    $zipcode = $_SESSION['temp_zipcode'];
    $billing_state = $_SESSION['temp_billing_state'];
    if($_SESSION['temp_email']){
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
            first_name: "<?php print $firstname; ?>",
            last_name: "<?php print $lastname; ?>",
            district: "<?php print $dist_mail_name; ?>",
            data_dash: "<?php print $data_dash_name; ?>",
            smart_preb: "<?php print $smart_preb_name; ?>",
            master_school: "<?php print $master_school_name; ?>",
            your_school: "<?php print $school_mail_name; ?>",
            billing_address: "<?php print $address; ?>",
            phone_number: "<?php print $phone_number; ?>",
            billing_city: "<?php print $city; ?>",
            billing_zipcode: "<?php print $zipcode; ?>",
            billing_state: "<?php print $billing_state; ?>",
            tags: ["Customer Admin"]
          }]);

          </script>
    <?php } 
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_dist_name']) )unset($_SESSION['temp_dist_name']);
        if( isset($_SESSION['temp_master_school']) )unset($_SESSION['temp_master_school']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);
        if( isset($_SESSION['temp_phone']) )unset($_SESSION['temp_phone']);
        if( isset($_SESSION['temp_smart_preb']) )unset($_SESSION['temp_smart_preb']);
        if( isset($_SESSION['temp_data_dash']) )unset($_SESSION['temp_data_dash']);
        if( isset($_SESSION['temp_address']) )unset($_SESSION['temp_address']);
        if( isset($_SESSION['temp_city_name']) )unset($_SESSION['temp_city_name']);
        if( isset($_SESSION['temp_zipcode']) )unset($_SESSION['temp_zipcode']);
        if( isset($_SESSION['temp_billing_state']) )unset($_SESSION['temp_billing_state']);
    ?>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3><?php echo isset($_GET['id']) ? 'Order Details' : 'Manager Orders'; ?></h3>
						<?php if( isset($_GET['id']) ) : ?>
						<ul>
							<li><a href="manager-orders.php" style="text-decoration: none;">
								<span class="fa fa-arrow-circle-o-left"></span> Go Back</a>
							</li>
						</ul>
						<?php endif; ?>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if( isset($_GET['id']) )
							if($_GET['ac'] == 'delete'){
								$del = "SELECT `schoolID` FROM `purchases` WHERE `id` = ". $_GET['id'];
								$delSelect  = mysql_query($del);
								$delscId = mysql_fetch_assoc($delSelect);

								$sql = "DELETE FROM `purchases` WHERE `id` = " . $_GET['id'];
								mysql_query($sql);

								$sql1 = "DELETE FROM `schools` WHERE `schoolID` = " . $delscId['schoolID'];
								mysql_query($sql1);

								$sql2 = "DELETE FROM `purchasemeta` WHERE `purchaseId` = " . $_GET['id'];
								mysql_query($sql2);
								
								$sql3 = "DELETE FROM `shared` WHERE `schoolId` = " . $delscId['schoolID'];
								mysql_query($sql3);
								
								header("Location: manager-orders.php");
								exit();
							}else{
								include("manager-orders-single.php");
							}
						else
							include("manager-orders-browser.php");
						?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
					<input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->
<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<?php include("footer.php"); ?>