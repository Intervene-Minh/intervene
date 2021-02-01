<?php
//print_r($_POST);
 //echo 'testtt'; die;
//////////

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


        $select="<script>$(document).ready(function () {
 
$('#d_school').change(function () {
  //console.log('d_school');
  console.log($(this).val());
  // (this).val();
});
});</script>";
        $select= '<select  id="d_school" name="master_school[]"  >';
        while($schools = mysql_fetch_assoc($query)) {
            $selec = '';
         $select .= '<option value="'.$schools['SchoolId'].'" >'.$schools['SchoolName'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();
        break;
        // Get Grade//////////
        case 'get_school_grades':
        $schoolid = $_POST['schoolid'];
      // print_r($_POST); die;

$sql_grades=mysql_query("SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$schoolid);



        $select = '<select  name="grade" id="grade_id" class="required textbox">';


        while($schools = mysql_fetch_assoc($sql_grades)) {
            $selec = '';

         $select .= '<option value="'.$schools['id'].'" >'.$schools['name'].'</option>';
        }
        $select .= '</select>';
        echo $select; exit();

        break;




        // Multiple Students 
         case 'get_multiple_students':

        // grade_id schoolid
            $school_id = $_POST['schoolid'];
          /*  if($school_id==130){

                $sql="SELECT * FROM students WHERE school_id='".$school_id."' AND 
                    grade_level_common='".$_POST['grade_id']."'";
            }
            else{

               $sql="SELECT  ST.first_name ,ST.last_name,ST.id, SXC.student_id FROM   `students_x_class` SXC INNER JOIN students as ST
		ON SXC.student_id= ST.id WHERE   SXC.grade_level_id = '".$_POST['grade_id']."' && SXC.roster_id=16 && ST.school_id='".$school_id."'";

           
            }
            */

  $sql="SELECT  ST.first_name ,ST.last_name,ST.id, SXC.student_id FROM   `students_x_class` SXC INNER JOIN students as ST
		ON SXC.student_id= ST.id WHERE   SXC.grade_level_id = '".$_POST['grade_id']."'&& ST.school_id='".$school_id."' ORDER BY ST.first_name ASC 
		";


        
            $query= mysql_query($sql);  
            echo 'Students Found:'.mysql_num_rows($query).'<br/>';
            $select = '<select class="form-control" name="student[]" id="students_id" multiple="true">';

        while($row = mysql_fetch_assoc($query)) {
            $selec = '';

            $st_name=$row['first_name']." ".$row['last_name'];  


            $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$st_name.'</option>';
        }
        $select .= '</select>';

        echo $select;

         exit();
        break;

        
        // Multiple Students 
         case 'get_multiple_studentsEdit':
          $school_id = $_POST['schoolid'];
            $student_id_arr= explode('S',$_POST['selected']);
           /* if($school_id==130){


                    $str="SELECT id,name FROM `terms` WHERE `id`=".$_POST['grade_id'];
                    $query= mysql_query($str);  
                    $row = mysql_fetch_assoc($query);
                    $sql="SELECT * FROM students WHERE school_id='".$school_id."' AND 
                    grade_level_common='".$row['name']."'";
            }
            else{

              $sql="SELECT * FROM students WHERE school_id='".$school_id."' AND grade_level_id=".$_POST['grade_id'];
            }
            
            $sql.=" ORDER BY first_name ASC  ";
        */


		 $sql="SELECT  ST.first_name ,ST.last_name,ST.id, SXC.student_id FROM   `students_x_class` SXC INNER JOIN students as ST
		ON SXC.student_id= ST.id WHERE   SXC.grade_level_id = '".$_POST['grade_id']."' && ST.school_id='".$school_id."' ORDER BY ST.first_name ASC ";

            $query= mysql_query($sql);  
            echo 'Students Found:'.mysql_num_rows($query).'<br/>';
            $select = '<select class="form-control" name="student[]" id="students_id" multiple="true">';
            while($row = mysql_fetch_assoc($query)) 
            {
                if (in_array($row['id'],$student_id_arr))
                {

                $selec = 'selected';
                }
                else{

                   $selec = ''; 
                    }
                

                $st_name=$row['first_name']." ".$row['last_name'];  
                $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$st_name.'</option>';
            }
                $select .= '</select>';
                echo $select;
                exit();
                break;
}