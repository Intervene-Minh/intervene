<?php

function checkStatus(){
	$user_id = $_SESSION['login_id'];
        if($user_id >0) {
	$sql = "SELECT status FROM `users` WHERE `id`=$user_id LIMIT 1;";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)==1){
		$row = mysql_fetch_assoc($query);
		return $row['status'];
	}
        }else if($_SESSION['demo_user_id']) {
            
        $user_id = $_SESSION['demo_user_id'];
            $sql = "SELECT status FROM `demo_users` WHERE `id`=$user_id LIMIT 1;";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)==1){
		$row = mysql_fetch_assoc($query);
		return $row['status'];
        }
	
	return 0;
}
}
?>