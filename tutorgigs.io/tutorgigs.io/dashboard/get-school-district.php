<?php
include('inc/connection.php');
session_start();
$id = $_SESSION['demo_user_id'];
$sql = mysql_query("SELECT `school_id` FROM `demo_users` WHERE `id` = $id ");
if (mysql_num_rows($sql) > 0) {
    if (mysql_num_rows($sql) == 1) {
        $school_id = mysql_fetch_assoc($sql);
    } else {
        $error = 'Error';
    }
}
$district = $_POST['district'];
$schools = mysql_query("SELECT * FROM `master_schools` WHERE `district_id` = $district");

$respond = "";
if( mysql_num_rows($schools) > 0 ) {
	while($row = mysql_fetch_assoc($schools)) {
            $selected = ($school_id['school_id'] == $row['id']) ? ' selected' : '';
		$respond .= "<option value='{$row['id']}' {$selected}>{$row['school_name']}</option>";
	}
}

echo $respond;
die();
?>