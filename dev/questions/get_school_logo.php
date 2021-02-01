<?php
include('inc/connection.php'); 
extract($_REQUEST);
if($userID > 0 ){

$str="SELECT sc.schoollogo
FROM `schools`  as sc INNER JOIN  `students` as stu ON stu.school_id = sc.SchoolId

WHERE stu.`id`= $userID";

$qr =mysql_query($str);

$logo = mysql_fetch_assoc($qr);
echo 'https://englishpro.us/questions/uploads/schoollogo/'.$logo['schoollogo'];

}

?>