<?php

	session_start();

    

    // db connection

	include 'dbtutor.ini';



	// Connect to tutor database

	$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB);

	mysqli_select_db($connection, "$tDB");

    

    // 1. Reset Tutor Attempts in DB

    $rs = mysqli_query($connection, "UPDATE tutorSubjects SET Certified=0 WHERE TutorID=".$_GET['tID']." AND SubjectID=".$_GET['sID']) or $msg(mysqli_error($connection));

    

    // 2. Delete the Question

    $rs = mysqli_query($connection, "DELETE from testResults WHERE ID=".$_GET['trID']) or $msg(mysqli_error($connection));

    if ($rs) {

        $msg = "Test Result Deleted.";

    } else {

        $msg = "dtr15: Test Result could not be deleted.";

    }

    

    // - Done -

    header( "Refresh: 0; URL=https://".$_SERVER['HTTP_HOST']."/adm/lte/pages/tables/testresults.php?&msg=".$msg);

    

    // Close db connection

	mysqli_close($connection);

    

    ?>