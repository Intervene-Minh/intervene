<?php
include('inc/connection.php'); 
session_start();
	ob_start();
$action = $_POST['action'];
switch($action) {
    case 'get_schools':
        $district_id = $_POST['district'];
        $school_id = $_POST['school_id'];
        $query =  mysql_query("SELECT * FROM `master_schools` WHERE  district_id =  ".$district_id);
        $select = '<select id="d_school" name="master_school">';
        while($schools = mysql_fetch_assoc($query)) {
            $selec = '';
            if($schools['id'] == $school_id) {
                $selec = 'selected="selected"';
            }
            $select .= '<option value="'.$schools['id'].'" '.$selec.'>'.$schools['school_name'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;
    case 'get_multiple_schools':
        $district_id = $_POST['district'];
        $school_id = $_POST['school_id'];
        
        
        $query =  mysql_query("SELECT * FROM `master_schools` WHERE  district_id IN  (".implode(',', $district_id).") ");
        $select = '<select id="d_school" name="master_school[]" multiple="multiple">';
        while($schools = mysql_fetch_assoc($query)) {
            $selec = '';
            if(in_array($schools['id'],explode(',',$school_id))) {
                $selec = 'selected="selected"';
            }
            $select .= '<option value="'.$schools['id'].'" '.$selec.'>'.$schools['school_name'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;
        case 'get_signup_schools':
        $district_id = $_POST['district'];
        $school_id = $_POST['school_id'];
        
        $query =  mysql_query("SELECT ms.school_name, school.Schoolid as id FROM `master_schools` ms "
                . "INNER JOIN schools school ON school.master_school_id = ms.id "
                . "WHERE  ms.district_id =  ".$district_id);
      //  $query =  mysql_query("SELECT * FROM `master_schools` WHERE  district_id =  ".$district_id);
        $select = '<select id="d_school" name="signup-school">';
        while($schools = mysql_fetch_assoc($query)) {
            $selec = '';
            if($schools['id'] == $school_id) {
                $selec = 'selected="selected"';
            }
            $select .= '<option value="'.$schools['id'].'" '.$selec.'>'.$schools['school_name'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;
}