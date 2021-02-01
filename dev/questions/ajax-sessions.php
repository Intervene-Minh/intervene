<?php
//print_r($_POST);
 //echo 'testtt'; die;


include('inc/connection.php'); 
session_start();
	ob_start();
$action = $_POST['action'];
switch($action) {
    // case 'get_schools':
    //     $district_id = $_POST['district'];
    //     $school_id = $_POST['school_id'];
    //     $query =  mysql_query("SELECT * FROM `master_schools` WHERE  district_id =  ".$district_id);
    //     $select = '<select id="d_school" name="master_school">';
    //     while($schools = mysql_fetch_assoc($query)) {
    //         $selec = '';
    //         if($schools['id'] == $school_id) {
    //             $selec = 'selected="selected"';
    //         }
    //         $select .= '<option value="'.$schools['id'].'" '.$selec.'>'.$schools['school_name'].'</option>';
    //     }
    //     $select .= '</select>';
    //     echo $select; exit();
    //     break;


    case 'get_multiple_schools':
        $district_id = $_POST['district'];
        $school_id = $_POST['school_id'];
        // print_r($_POST); die;
 $query=mysql_query("SELECT * FROM schools WHERE district_id='$district_id' ORDER BY SchoolName ASC ");
        
       
 //$query =  mysql_query("SELECT * FROM `master_schools` WHERE  district_id=".$district_id);



        $select = '<select  id="d_school" name="master_school[]"  >';
        while($schools = mysql_fetch_assoc($query)) {
            $selec = '';

            // if(in_array($schools['id'],explode(',',$school_id))) {
            //     $selec = 'selected="selected"';
            // }
         // $select .= '<option value="'.$schools['id'].'" >'.$schools['school_name'].'</option>';

         $select .= '<option value="'.$schools['SchoolId'].'" >'.$schools['SchoolName'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;

        // Get Grade//////////
        case 'get_school_grades':
        $schoolid = $_POST['schoolid'];

        if($schoolid==130){
            $str="SELECT  DISTINCT grade_level_common FROM `classes` WHERE `school_id` = ".$schoolid;
            $sql_grades=mysql_query($str);
            $select = '<select  name="grade" id="grade_id" class="required textbox">';
            while($schools = mysql_fetch_assoc($sql_grades)) {
            $selec = '';
            $select .= '<option value="'.$schools['grade_level_common'].'" >'.$schools['grade_level_common'].'</option>';
            }
            $select .= '</select>';
        }
        else{
                $sql_grades=mysql_query("SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
                LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$schoolid);
                $select = '<select  name="grade" id="grade_id" class="required textbox">';
                while($schools = mysql_fetch_assoc($sql_grades)) 
                {
                    $selec = '';
                    $select .= '<option value="'.$schools['id'].'" >'.$schools['name'].'</option>';
                }
                    $select .= '</select>';
            }
            echo $select; 

            exit();

            break;


        // Multiple Students 
         case 'get_multiple_students':
		$school_id = $_POST['schoolid'];
		$sql="SELECT  ST.first_name ,ST.last_name,ST.id, SXC.student_id FROM   `students_x_class` SXC INNER JOIN students as ST
		ON SXC.student_id= ST.id WHERE   SXC.grade_level_id = '".$_POST['grade_id']."'&& ST.school_id='".$school_id."' ORDER BY ST.first_name ASC ";

		$query= mysql_query($sql); 

		$select = '<select class="form-control" name="student[]" id="students_id" multiple="true">';

        while($row = mysql_fetch_assoc($query)) {
            $selec = '';

            $st_name=$row['first_name']." ".$row['last_name'];  


            $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$st_name.'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;
}