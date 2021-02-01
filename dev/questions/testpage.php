<?php 
include('inc/connection.php'); 


      $str="SELECT `IntervenID`,TelUserID FROM `Tel_UserDetails` WHERE 1"; 

      $audio=mysql_query($str);

      while ($row = mysql_fetch_assoc($audio)) 
      {

        $str="UPDATE `Tel_CourseComplete` SET `intervene_student_id`='".$row['IntervenID']."' WHERE `TelUserID`=".$row['TelUserID'];
        mysql_query($str);



        

      }
   
?>