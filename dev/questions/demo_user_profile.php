<?php
include("header.php");

$error = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //if not admin but want to edit return index
    require_once('inc/check-role.php');
    $login_role = checkRole();
    if ($login_role != 0 || !isGlobalAdmin()) {
        header('Location: index.php');
        exit;
    }
} else {
    $id = $_SESSION['login_id'];
}




$results = mysql_query("SELECT u. * ,  r.`name`,d.`district_name`,s.`SchoolName`,dd.`name` as `data_dash_name` FROM `demo_users` u "
        ."LEFT JOIN `role` r ON u.`role` = r.`id` "
        ."LEFT JOIN `loc_district` d ON u.`district_id` = d.`id` "
        ."LEFT JOIN `schools` s ON u.`school_id` = s.`SchoolId` "
        ."LEFT JOIN `terms` dd ON u.`data_dash` = dd.`id` "
        . "WHERE u.`id` = '$id'", $link);
if (mysql_num_rows($results) > 0) {
    if (mysql_num_rows($results) == 1) {
        $row = mysql_fetch_assoc($results);
    } else {
        $error = 'Error';
    }
} else {
    $error = 'Item not found';
}
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
         <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="folder_wrap" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Demo User Profile Details - <?php if ($row['status']) {
                echo 'Enabled';
                } else {
                    echo 'Disabled';
                } ?></h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                    <?php
                    if ($error != '') {
                        echo '<p class="error">' . $error . '</p>';
                    } else {
                        ?>
                           
                                <div class="profile-top col-md-12">
                                    
                                    <div class="col-md-9">
                                        <div class="profile-item">
                                            <div class="left col-md-4">
                                                <label for="profile-username">Profile Name:</label>
                                            </div>
                                            <div class="right col-md-8">
                                                <?php echo $row['first_name'].' '.$row['last_name']; ?>
                                            </div>
                                        </div>

                                        <div class="profile-item">
                                            <div class="left col-md-4">	
                                                <label>Active User:</label>
                                            </div>
                                            <div class="right col-md-8">
                                          <?php if ($row['status'] == 0): ?>
                                                    <span class="btn btn-sm btn-warning"\>Verify Your Email</span>
                                    <?php else: ?>
                                                    <span class="btn btn-sm btn-success">Activated</span>
                                    <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-center col-md-12">
                                    <h3 class="title">Customer Information</h3>
                                    <div class="box col-md-12">
                                        <div class="left col-md-6">
                                            <p> <b>First Name:</b> <?php echo $row['first_name']; ?> </p>
                                        </div>
                                        <div class="right col-md-6">
                                            <p> <b>Last Name:</b> <?php echo $row['last_name']; ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Email:</b> <?php echo $row['email']; ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>District:</b> <?php 
                                             if($row['district_name']==''){
                                                 echo $row['other_district'];
                                             }else{
                                             echo $row['district_name'];
                                             } ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Schools:</b> <?php 
                                             if($row['SchoolName'] == ''){
                                                echo $row['other_school'];
                                             }else{
                                             echo $row['SchoolName']; 
                                             } ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Phone:</b> <?php echo $row['phone_number']; ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Role:</b> <?php echo $row['name']; ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Expiry Date:</b> <?php 
                                             $expiry_date_arr = explode('-', $row['expiry_date']);
                                             $date = strtotime($expiry_date_arr[2].'-'.$expiry_date_arr[1].'-'.$expiry_date_arr[0]); 
                                             echo date('M d Y', $date);
                                             
                                             ?> </p>
                                        </div>
                                        <div class="left col-md-6">
                                             <p> <b>Data Dash:</b> <?php echo $row['data_dash_name']; ?> </p>
                                        </div>
                                        <?php
                                       $smart_prep_value = $row['smart_prep'];
                                        $smart_results = mysql_query("SELECT sp.name as smart_prep_name FROM demo_users u,terms sp WHERE u.id = '$id' AND FIND_IN_SET(sp.id, '$smart_prep_value')");
                                        $samart_data = array();
                                          if (mysql_num_rows($smart_results) > 0) {
                                              while($smart_row = mysql_fetch_assoc($smart_results)){
                                                  $samart_data[] = $smart_row['smart_prep_name'];
                                              }
                                          } else {
                                              $error = 'Item not found';
                                          } 
                                          ?>
                                        <div class="left col-md-6">
                                            <p> <b>Smart Prep:</b> <?php echo implode(" , ", $samart_data);; ?> </p>
                                        </div>
                                        
                                    </div>
                                </div>
    <?php
}
?>

                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div>
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->
<?php
//alert(Send Email To Active);
if (isset($_GET['send']) && $_GET['send'] != '') {
    if ($_GET['send'] == 'true') {
        print('<script>alert("An activation link has been sent to the email address you\'ve provided!");</script>');
    } else {
        print('<script>alert("Activation link can not be sent. Please try again later!");</script>');
    }
}
?>

<?php include("footer.php"); ?>