<?php
function updateQuestionsRemaining($user_id,$sum_question,$role){
	/* 
	if( $role == 3 ) {
		$sql = "UPDATE `users` SET `q_remaining` = 0, `role` = $role WHERE `id` = $user_id";
	} else {
		$sql = "UPDATE `users` SET `q_remaining` = `q_remaining` + $sum_question, `role` = $role WHERE `id` = $user_id";
	}
	 */
	$sql = "UPDATE `users` SET `q_remaining` = `q_remaining` + $sum_question, `role` = $role WHERE `id` = $user_id";
	return mysql_query($sql);
}
function getQuestionsRemaining($user_id){
	$sql = "SELECT `q_remaining` FROM `users` WHERE `id` = $user_id LIMIT 1";
	$return  = mysql_query($sql);
	if($return && mysql_num_rows($return)>0){
		return mysql_fetch_assoc($return)['q_remaining'];
	}
	return 0;
}

function is_unlimited($user_id){
	$sql = "
	SELECT `u`.`id` FROM `users` u
	INNER JOIN `packages` p ON `u`.`role` = `p`.`id`
	WHERE `u`.`id` = $user_id AND `p`.`limited` = 0
	LIMIT 1";
	$return  = mysql_query($sql);
	if($return && mysql_num_rows($return)==1){
		return true;
	}
	return false;
}

function updateAfterPrint($user_id,$printed){
	if(is_unlimited($user_id)){
		$sql = "UPDATE `users` SET `q_printed` = `q_printed` + $printed WHERE `id` = $user_id";
	}else{
		$sql = "UPDATE `users` SET `q_remaining` = `q_remaining` - $printed, `q_printed` = `q_printed` + $printed WHERE `id` = $user_id";
	}
	return mysql_query($sql);
}
?>