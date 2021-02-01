<?php 

$count = mysql_num_rows(mysql_query('SELECT * FROM demo_users'));
if($count > 0) {
    $count = '('.$count.')';
}
?>
<h4 class="widget-title"><i class="fa fa-users"></i><a href="manager_demo_user.php">Manager Demo Users <?php print $count; ?></a></h4>