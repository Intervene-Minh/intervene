<?php
    include('inc/connection.php'); 
    $sql="SELECT
        id,
        class_name
        FROM 
        classes 
        WHERE 
        school_id = 85   
        AND
        grade_level_id = 23
        ORDER BY
        class_name ASC            
    "; 
    $query = mysql_query($sql);  
    
    while ($row = mysql_fetch_assoc($query)) {
        echo 'Row: '.$row['class_name'];
    }


?>