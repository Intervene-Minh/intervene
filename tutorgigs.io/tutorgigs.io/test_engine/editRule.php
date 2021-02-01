<?php
   
    require_once 'includes/db_connect.php';
    
    // Update the Site Rule
    if ( !empty($_POST['rID']) && !empty($_POST['rValue']) ) {

          
            // Update the row
            $result = mysqli_query($connection, "UPDATE 
                                                          siteRules SET 
                                                          Rule  = '".$_POST['rRule']."',
                                                          Value = '".$_POST['rValue']."',
                                                          Notes = '".$_POST['rNotes']."' 
                                                          WHERE ID = ".$_POST['rID']
                      ) or die(mysqli_error($connection));
           
            if( !$result ) {
                   $_SESSION['error'] = "The rule has not been updated";
            }
            else
                 $_SESSION['success'] = "The rule has been updated successfully";

            mysqli_close($siteRules->connection);
            
            header("Location:".BASE_URL."siterules.php");
            exit;


    }

?>