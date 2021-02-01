<?php

function checkRole(){
	if( isset($_SESSION['login_role']) ) {
		return $_SESSION['login_role'];
	}else{
		return -1;
	}
}


?>