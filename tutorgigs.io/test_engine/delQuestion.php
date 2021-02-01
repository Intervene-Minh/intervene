<?php

    require_once 'includes/db_connect.php';
    
    // 1. Gather Option IDs

    $query   = "SELECT OptionIDs FROM questions WHERE ID=".$_REQUEST['qID'];
    $result  = mysqli_query($connection,$query);
    $oiArray = mysqli_fetch_array($result);

   

    $optionIDs = $oiArray['OptionIDs'];

    $aOptionIDs = explode(",", $optionIDs);

    //echo 'Option 1: '. sizeOf($aOptionIDs) . '<br/>';

 

    // 2. Loop and Delete Options by ID

    $numDeleted = 0;

    for ($j = 0; $j < sizeOf($aOptionIDs); $j++) {

        

        $deleteID = $aOptionIDs[$j];

        // Don't delete generic options (6 of them)

        if ($deleteID > 6) {

            $rs = mysqli_query($connection, "DELETE from answers WHERE ID=".$deleteID);

            if ($rs){

                $numDeleted++;

            }                   

        }

    }

    

    // 3. Delete the Question

    $rs2 = mysqli_query($connection, "DELETE from questions WHERE ID=".$_REQUEST['qID']);

    if ($rs) {

        $msg = "1 Question Deleted. ".$numDeleted." Answers Deleted";

    }

    

    header( "Refresh: 0; URL=".BASE_URL."/questions.php?testID=".$_REQUEST['tID']."&msg=".$msg);


?>