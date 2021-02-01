<?php
    // connection include
    include('inc/conn.php'); 

    if ( !isset($_POST['action']) ) {
        //kickResponse("Unauthorized Action");
        //exit();
    }
    function kickResponse($error_msg) {
        $data = '{"error": "true", "message": "'.$error_msg.'';
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function getTutorList() {
        global $link;
        $query = "SELECT id, payment_em, payment_phone, f_name, lname, phone FROM gig_teachers WHERE all_state = 'Yes' ORDER BY f_name ASC;";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        $tutorList = array();
        while( $rows = mysqli_fetch_assoc($results) ) {
            $tutorList[] = $rows;
        }
        return json_encode($tutorList);
    }

    function getTutorListCount() {
        global $link;
        $query = "SELECT id FROM gig_teachers WHERE all_state = 'Yes';";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        return $numRows;
    }
    
    function getTutorList_HTML() {
        global $link;
        $query = "SELECT id, payment_em, payment_phone, f_name, lname, phone, email FROM gig_teachers WHERE all_state = 'Yes' ORDER BY f_name ASC;";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        $output = "";
        if ( $numRows > 0) {
            while ( $rows = mysqli_fetch_assoc($results) ) {
                $output.= "<tr>";
                $output.= "<td>".$rows['id']."</td>";
                $output.= "<td>".$rows['f_name'].' '.$rows['lname']."</td>";
                $output.= "<td>".$rows['email']."</td>";
                $output.= "<td>".$rows['phone']."</td>";
                $output.= "</tr>";
            }
        }
        return $output;
    }

    
?>