<?php
require_once("../../../classes/class.TutorProfile.php");

if($_POST['tid']){
$tp=new TutorProfile();
$tp->select($_POST['tid']);
if(isset($_POST['Approve'])){
	$tp->picture_approved=1;
}else{
	$tp->picture_approved=-1;
}
$tp->save();
}
header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/adminImages.php?" );