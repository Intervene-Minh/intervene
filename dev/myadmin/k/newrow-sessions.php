<?php
    include('inc/conn.php');
    //include('newrow.functions.php');
    include('newrow.php');
    
    session_start();
    $today = date("Y-m-d H:i:s"); 
    $thisHour = date("Y-m-d H", strtotime('-1 hour')); 
    $error='';
    //extract($_REQUEST);
    //$token=$_SESSION['getToken']=_get_token(); 
    //$newrow_id = 38892;
    //$newrow_room_id = $roomID;//25953;

    if ($_REQUEST['action'] == 'session_x_room') {
        if (!isset($_REQUEST['roomID'])) {
            die('no data');
        }
        $res = mysqli_query($link, "SELECT
            nr.id,
            nr.newrow_room_id,
            nr.ses_tutoring_id,
            ss.tut_teacher_id,
            ss.ses_start_time,
            ss.ses_end_time,
            tuts.f_name,
            tuts.lname,
            (SELECT 
                group_concat(intervene_user_id) as ids
                FROM lonestaar.newrow_room_users WHERE newrow_room_id = nr.newrow_room_id and user_type = 'student') as student_ids,
            (SELECT 
                COUNT(id) as numStudents
                FROM lonestaar.newrow_room_users WHERE newrow_room_id = nr.newrow_room_id and user_type = 'student') as numStudents
            FROM 
                newrow_rooms nr
            JOIN 
                int_schools_x_sessions_log ss 
            ON
                ss.id = nr.ses_tutoring_id
            JOIN
                gig_teachers tuts 
            ON
                tuts.id = ss.tut_teacher_id                        
            WHERE nr.newrow_room_id = ".$_REQUEST['roomID']." 
        "); 
        print_r(json_encode(mysqli_fetch_assoc($res)));       
    }
    
    if ($_REQUEST['action'] == 'student') {
        if (!isset($_REQUEST['studentID'])) {
            die('no data');
        }
        $res = mysqli_query($link, "SELECT
            
        ");
        print_r(json_encode(mysqli_fetch_assoc($res)));   
    }

    if ($_REQUEST['action'] == 'students') {
        if (!isset($_REQUEST['studentIDs'])) {
            die('no data');
        }
        $rows = array();
        $res = mysqli_query($link, "SELECT
            nrs.id,
            newrow_email,
            newrow_username,
            newrow_ref_id,
            s.id as student_id,
            s.first_name,
            s.last_name
            FROM 
            newrow_students nrs
            JOIN
            students s 
            ON 
            s.id = nrs.stu_intervene_id
            WHERE 
            stu_intervene_id IN (".$_REQUEST['studentIDs'].")

        "); 
        while($stu = mysqli_fetch_assoc($res)) {
            $rows[] = $stu;
        }
        print json_encode($rows);
    }
    
    if ($_REQUEST['action'] == 'current_sessions') {        
        $thisHour = isset($_REQUEST['start']) && !empty($_REQUEST['start']) ? date("Y-m-d H", strtotime($_REQUEST['start'],'-1 hour')) : $thisHour;
        //print_r ($thisHour); die;
        $rows = array();
        $res = mysqli_query($link, "SELECT
            * 
            FROM 
            int_schools_x_sessions_log
            WHERE
            ses_start_time >= ".$thisHour."
        "); 
        while($stu = mysqli_fetch_assoc($res)) {
            $rows[] = $stu;
        }
        //echo $thisHour;
        print json_encode($rows);
    }

    if ($_REQUEST['action'] == 'xfer_student') {
        if (
            isset($_REQUEST['newrow_user_id'] ) && !empty($_REQUEST['newrow_user_id']) &&
            isset($_REQUEST['newrow_room_id'] ) && !empty($_REQUEST['newrow_room_id']) &&
            isset($_REQUEST['sessionID']) && !empty($_REQUEST['sessionID'])
        ) {            
            $nr_response = addNewRowUserToRoom($_REQUEST['newrow_user_id'], $_REQUEST['newrow_room_id']);
            $upresult = mysqli_query($link, "
                UPDATE `newrow_room_users`
                SET `newrow_room_id` = ".$_REQUEST['newrow_room_id']."
                WHERE 
                newrow_user_id = ".$_REQUEST['newrow_user_id']."
                AND
                ses_tutoring_id =  ".$_REQUEST['sessionID']."
                AND user_type = 'student'
            ");
            return $nr_response + ' ' + $upresult;
        } else {
            echo 'no data'; die;
        }
    }
?>