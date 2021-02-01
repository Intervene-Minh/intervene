<?php
    include('inc/connection.php'); 
    session_start();
    ob_start();
    $action = $_POST['action'];
    switch ($action) {        
        case 'get_multiple_schools':
            $district_id = $_POST['district'];
            $school_id = $_POST['school_id']; 

            $query=mysql_query("SELECT * FROM schools WHERE district_id='$district_id' ORDER BY SchoolName ASC ");

            $select = '<select id="d_school" name="master_school[]">';
            while($schools = mysql_fetch_assoc($query)) {
                $selec = '';
                $select .= '<option value="'.$schools['SchoolId'].'" >'.$schools['SchoolName'].'</option>';
            }
            $select .= '</select>';
            echo $select; 
            exit();
        break;
        case 'get_school_grades':
            $schoolid = $_POST['schoolid'];
            // print_r($_POST); die;

            $sql_grades=mysql_query("SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
            LEFT JOIN terms t ON p.grade_level_id = t.id WHERE p.school_id =".$schoolid);

            $select = '<select  name="grade" id="grade_id" class="required textbox">';

            while ($schools = mysql_fetch_assoc($sql_grades)) {
                $selec = '';
                $select .= '<option value="'.$schools['id'].'" >'.$schools['name'].'</option>';
            }
            $select .= '</select>';
            echo $select; 
            exit();
        break;
        case 'get_teachers':
            $school_id = $_POST['schoolid'];            
            $sql="SELECT
                id,
                first_name,
                last_name
                FROM 
                `users` 
                WHERE 
                `school` = $school_id AND `role` = 1               
            "; 
            $query= mysql_query($sql);  
            
            $select = '<select class="form-control" name="teacher" id="teacher_id">';

            while ($row = mysql_fetch_assoc($query)) {
                $selec = '';                
                $st_name=$row['first_name']." ".$row['last_name'];  
                $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$st_name.'</option>';
            }
            $select .= '</select>';
            echo $select; 
            exit();
        break;
        case 'get_classes':
            $school_id = $_POST['school_id'];   
            $grade_id = $_POST['grade_id'];        
            $sql="SELECT
                id,
                class_name
                FROM 
                classes 
                WHERE 
                school_id = $school_id   
                AND
                grade_level_id = $grade_id
                ORDER BY
                class_name ASC            
            "; 
            //echo $sql;
            $query = mysql_query($sql);  
            //print_r($query);
            $select = '<select class="form-control" name="class" id="class_id">';
            while ($row = mysql_fetch_assoc($query)) {
                $selec = '';                
                $class_name=$row['class_name'];  
                $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$class_name.'</option>';
            }            
            $select .= '</select>';
            echo $select; 
            exit();
        break;
        case 'get_multiple_students': 
            $class_id = $_POST['class_id'];
            $school_id = $_POST['school_id'];
            $grade_id = $_POST['grade_id'];
        /*    $sql="SELECT 
                s.id,
                s.teacher_id,
                s.first_name,
                s.middle_name,
                s.last_name, 
                s.grade_level_id,
                s.class_id,
                s.school_id,
                c.class_name
                FROM 
                `students` s 
                JOIN classes c ON
                c.id = s.class_id
                WHERE 
                s.grade_level_id = $grade_id
                AND
                s.school_id = $school_id   
                AND
                c.id = $class_id 
                ORDER BY first_name ASC            
            "; 
*/

            $sql="SELECT 
            ST.first_name ,
            ST.last_name,
            ST.id,
            SXC.student_id
            FROM   
            `students_x_class` SXC
            INNER JOIN students as ST
            ON SXC.student_id= ST.id 
            WHERE  
            SXC.grade_level_id = '".$grade_id."'
            && SXC.class_id = '".$class_id."'  
            && ST.school_id='".$school_id."'
            ORDER BY ST.first_name ASC ";


            $query= mysql_query($sql);  
            
            $select = '<select class="form-control" name="student[]" id="students_id" multiple="true">';

            while ($row = mysql_fetch_assoc($query)) {
                $selec = '';
              
                $st_name=$row['first_name']." ".$row['last_name'];  
                $select .= '<option value="'.$row['id'].'" '.$selec.'>'.$st_name.'</option>';
            }
            $select .= '</select>';
            echo $select; 
            exit();
        break;
}