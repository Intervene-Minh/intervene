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

    function getAdminList() {
        global $link;
        $query = "SELECT 	
            id, 
            user_name,
            email, 
            first_name,
            last_name, 
            latest_login, 
            status
            FROM gig_admins 
            ORDER BY email ASC;
        ";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        $adminList = array();
        while( $rows = mysqli_fetch_assoc($results) ) {
            $adminList[] = $rows;
        }
        return json_encode($adminList);
    }

    function getAdminListCount() {
        global $link;
        $query = "SELECT id FROM gig_admins";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        return $numRows;
    }
    
    function getAdminList_HTML() {
        global $link;
        $query = "SELECT 
            id, 
            user_name,
            email, 
            first_name,
            last_name, 
            latest_login, 
            status
            FROM gig_admins
            ORDER BY email ASC;
        ";
        $results = mysqli_query($link, $query);
        $numRows = mysqli_num_rows($results);
        $output = "";
        if ( $numRows > 0) {
            while ( $rows = mysqli_fetch_assoc($results) ) {
                $output.= "<tr>";
                $output.= "<td>".$rows['id']."</td>";
                $output.= "<td>".$rows['first_name'].' '.$rows['last_name']."</td>";
                $output.= "<td>".$rows['email']."</td>";
                $output.= "<td>".$rows['latest_login']."</td>";
                $output.= "</tr>";
            }
        }
        return $output;
    }

    
?>