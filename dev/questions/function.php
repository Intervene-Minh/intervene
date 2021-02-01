<?php
/* this is function file */

/*this function for check for test telpass for teacher*/
function _CheckTelpassPermission(){

    $str="SELECT * FROM `users` WHERE  `id`=".$_SESSION['login_id'];
    $result=mysql_query($str);
    $row=mysql_fetch_assoc($result);
    $str="SELECT count(school_id)  As cnt FROM `tbl_telpas_access` WHERE `school_id`=".$row['school'];
    $result=mysql_query($str);
    $row=mysql_fetch_assoc($result);
    return  $CheckPermission=$row['cnt'];
}
/*this function for check for test telpass for teacher*/
function _CheckTelpassPermissionStudent(){

    $str="SELECT * FROM `users` WHERE  `id`=".$_SESSION['teacher_id'];
    $result=mysql_query($str);
    $row=mysql_fetch_assoc($result);
    $str="SELECT count(school_id)  As cnt FROM `tbl_telpas_access` WHERE `school_id`=".$row['school'];
    $result=mysql_query($str);
    $row=mysql_fetch_assoc($result);
    return  $CheckPermission=$row['cnt'];
}



?> 