<?php
    include('inc/conn.php');
    include('newrow.functions.php');
    
    session_start();

    //extract($_REQUEST);
    //$token=$_SESSION['getToken']=_get_token(); 
    //$newrow_id = 38892;
    //$newrow_room_id = $roomID;//25953;

    if ($_REQUEST['action'] == 'adduser') {
        $res = addNewRowUser(null, null, null, null, null, null);
        echo $res;
    }
    if ($_REQUEST['action'] == 'adduser2room') {
        $res = addNewRowUserToRoom(46503, 35247);
        print_r($res);
        //stdClass Object ( [status] => success [data] => stdClass Object ( [added] => 1 [removed] => 0 ) )
    }
    if ($_REQUEST['action'] == 'getboardurl') {
        //$res = getNewRowBoardURL(46503, 35247);
        $res = getNewRowBoardURL(33217, 34322); // test session i created
        print_r($res);
        //stdClass Object ( [status] => success [data] => stdClass Object ( [url] => https://smart.newrow.com/backend/room/join?hash=EGnNfdemkwxKQfJtWMhEDIoyLml-lGQTmhKgjeuseaEnpCyE7gXdSnVGONmOcY6VB9GmlArJRI8gIn9fOpA5HzLM8enj2tkCuGhL~vlH5Tiqs9022jBMPoCuiCPd8nbS81vYJ6UAWeBnh1RNjLhtoXcN2Mcz5xd66vLomrcFU9~O94uWTDKpIEB3neCCQcdd2HD6uNxiLFWyZlhCgGoHwQ__ ) )
        // reference this by using: obj->data->url
    }
    if ($_REQUEST['action'] == 'gethtml') {
        $objBoard = getNewRowBoardURL(4173,35281); //46503, 35247);
        if ($objBoard->status == 'error') {
            var_dump($objBoard);
            die();
        }
        $url2pass = $objBoard->data->url;
        
        $reshtml = getHTML_iframe_forNewRowBoard($url2pass);
        print_r('html -> '.$reshtml);

    }
    if ($_REQUEST['action'] == 'createroom') {
        $res = createRoom('testing_k');
        print_r($res);
        // returned stdClass Object ( [status] => success [data] => stdClass Object ( [id] => 35247 ) )
    }
    if ($_REQUEST['action'] == 'roomlist') {
        $res = getRooms(
            isset($_REQUEST['page']) ? $_REQUEST['page'] : null,
            isset($_REQUEST['limit']) ? $_REQUEST['limit'] : null
        );
        //print_r('<h5>Room list:</h5>');
        print_r($res);
    }

    // send all to a new room - you should select them by intervene session id 4173
    if ($_REQUEST['action'] == 'relocate') {
        $int_sessionID = $_REQUEST['sessionID']; //4173;

        // 1. Create Room
        $roomObj = createRoom('_reloc_'.$int_sessionID.'_'.time());        
        $roomID = $roomObj->data->id;
        //print_r($roomObj->data->id); die();//stdClass Object ( [status] => success [data] => stdClass Object ( [id] => 35474 ) )

        // 2. Add Users to Room
        //$oldroom = 35247;
        //$newroomnum = 35281;        
        //"SELECT * FROM `newrow_room_users` where newrow_room_id = $oldroom ORDER BY `id` DESC";
        $strQuery =  "SELECT 
            nru.id, 
            nru.newrow_user_id, nru.newrow_room_id, 
            nru.ses_tutoring_id, nru.intervene_user_id, 
            nru.user_type ,
            gt.f_name as first_name,
            gt.lname as last_name
            FROM `newrow_room_users` nru 
            JOIN gig_teachers gt ON
            gt.id = nru.intervene_user_id
            WHERE ses_tutoring_id = $int_sessionID AND nru.user_type = 'tutor'
            UNION ALL
            SELECT nru.id, 
            nru.newrow_user_id, nru.newrow_room_id, 
            nru.ses_tutoring_id, nru.intervene_user_id, 
            nru.user_type ,
            s.first_name,
            s.last_name
            FROM `newrow_room_users` nru 
            JOIN students s ON
            s.id = nru.intervene_user_id
            WHERE ses_tutoring_id = $int_sessionID AND nru.user_type = 'student'
        ";        
        //$strQuery = "SELECT * FROM newrow_room_users WHERE ses_tutoring_id = 4173 ORDER BY id DESC";
        $rmUsers = mysqli_query($link, $strQuery);
        $userCount = mysqli_num_rows($rmUsers);
        
        /*while ($rows = mysqli_fetch_assoc($rmUsers)) {
            $msg.= $rows['user_type'].' '.$rows['first_name'].' '.$rows['last_name'].' NEWROW_USER_ID: '.$rows['newrow_user_id'].'<br>';           
        }*/
        
        //////////////////
        //print_r($msg);
        /*die();*/
        //////////////////
        // 3. Update each NewRow Room User row with a new NewRow Room ID
        $msg = '<div class="container"><pre> Relocated '.$userCount.' Users: <br>';
        while ($rows = mysqli_fetch_assoc($rmUsers)) {
            addNewRowUserToRoom($rows['newrow_user_id'], $roomID);
            $upresult = mysqli_query($link, "
                UPDATE `newrow_room_users`
                SET `newrow_room_id` = $roomID
                WHERE id = ".$rows['id']
            );
            $msg.= $rows['user_type'].' '.$rows['first_name'].' '.$rows['last_name'].' NEWROW_USER_ID: '.$rows['newrow_user_id'].'<br>'; 
        }
        $msg.= ' </pre></div>';
        // Update the NewRow Room
        $strQuery2 = "UPDATE `newrow_rooms` SET `newrow_room_id` = $roomID WHERE ses_tutoring_id = $int_sessionID";
        $result = mysqli_query($link, $strQuery2) || die($mysqli -> error);
        //}
        print_r($msg.' - end');
        die();
    }

    /**
    * Add a (Tutor or Student) to NewRow Backend api as user
    * which then returns a New Row User ID for use
    *
    * @param string tutorORstudent $tutorORstudent string 'tutor' or 'student'
    *
    * @return int returns a newrow user id
    */ 
    
    function addNewRowUser ($newrow_userID, $email, $firstname, $lastname, $tutorORstudent, $intervene_userID) {    
        /* Check/Set the NewRow User Details*/           
        if (empty($newrow_userID)) {
            $newrow_userID = time();
        }
        if (empty($email)) {
            $email = 'InterveneUser@intervene.io';
        }
        if (empty($firstname)) {
            $firstname = 'Intervene';
        }
        if (empty($lastname)) {
            $lastname = 'User';
        }
        if (empty($tutorORstudent)) {
            $tutorORstudent = 'student';
        }
        if (empty($intervene_userID)) {
            $intervene_userID = 1;
        }

        $postData = [
            'user_name' =>$tutorORstudent.'_'.$newrow_userID,
            'user_email' =>$email, 
            'first_name' =>$firstname, 
            'last_name' =>$lastname,
            'role' => 'moderator', // Instructor | Student {CompanyUser}
        ];

        $ch = curl_init('https://smart.newrow.com/backend/api/users'); // Initialise cURL
        $postData = json_encode($postData); // Encode the data array into a JSON string
        $token=$_SESSION['getToken']=_get_token(); 
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Set the posted fields
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

        $result = curl_exec($ch); // Execute the cURL statement
        $newrow_result= json_decode($result); 
        curl_close($ch); 
        
        $newrow_user_id=$newrow_result->data->user_id;
        $_SESSION['newrow_user_id']=$newrow_user_id;
        return $newrow_user_id;
    }

    /**
    * Add a NewRow user id to NewRow room
    * returns a NewRow data row
    *
    * @param int newrow_tutor_id a newrow user id
    *
    * @return int newrow_room_id a newrow room id
    */ 
    function addNewRowUserToRoom ($newrow_user_id, $newrow_room_id) {

        !empty($newrow_user_id) ? $newrow_user_id = $newrow_user_id : 46503; // new userid created from addNewRowUser fn
        !empty($newrow_room_id) ? $newrow_room_id = $newrow_room_id : 35247; // returned stdClass Object ( [status] => success [data] => stdClass Object ( [id] => 35247 ) )
        $arrUsersToAdd[] =  $newrow_user_id;
        
        $postData = [
            'enroll_users' => $arrUsersToAdd,
        ];


        $RoomUrlLink='https://smart.newrow.com/backend/api/rooms/participants/'.$newrow_room_id;
        //$api_url='rooms/participants/<room_id>​';
        $ch = curl_init($RoomUrlLink); // Initialise cURL


        $postData = json_encode($postData); // Encode the data array into a JSON string
        $token=$_SESSION['getToken']=_get_token(); 
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Set the posted fields
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

        $result = curl_exec($ch); // Execute the cURL statement
        $newrow_result = json_decode($result); 
        curl_close($ch); 

        return $newrow_result;
    }
    
    /**
    * Gets the 'Board' URL from NewRow
    * returns a NewRow Board URL
    *
    * @param int $newrow_room_id a newrow room id
    * @param int $newrow_user_id a newrow user id
    * @return string URL
    */ 
    function getNewRowBoardURL ( $newrow_user_id, $newrow_room_id) {
        
        $newrow_api_url='https://smart.newrow.com/backend/api/rooms/url/'.$newrow_room_id.'?user_id='.$newrow_user_id;
        $ch = curl_init($newrow_api_url);

        $token=$_SESSION['getToken']=_get_token();
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // get stringified data/output. See CURLOPT_RETURNTRANSFER
        $result = curl_exec($ch);
        $newrowboard=json_decode($result);
        $info = curl_getinfo($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        
        return $newrowboard;
    }

    /**
    * Returns an HTML <iframe> with a newrow board attached
    * 
    * $newrowboard->data->url is the object
    * @param int $newrowboard a newrow board object, returned when 
    * @return string html of iframe
    */ 
    function getHTML_iframe_forNewRowBoard($newrowboard_url) {
        if (empty($newrowboard_url)) {
            return 'Please supply new row board:-> '.$newrowboard_url;
            die();
        }
        return '
            <!-- add room url in iframe-->
            <iframe allow="microphone *; camera *; speakers *; usermedia *; autoplay*;" allowfullscreen
                src="'.$newrowboard_url->data->url.'" height="100%" width="100%">
            </iframe>
        ';
    }


    function createRoom ($roomName) {  
        $post = [
            'name' =>'Intervention_room'.(string)$roomName,
            'description' => 'Relocated Room',            
            'tp_id' => 'Intervention_room'.(string)$roomName,
            //'gender'   => 1,
            //'avatar' => 'TestStudent',
            //'users' => 'Inst',
        ];
        $token=$_SESSION['getToken']=_get_token();   
        //$token=$_SESSION['ses_admin_token'];  //"cd3f2fcec5617f825e59a108405cd82e";
          
        $ch = curl_init('https://smart.newrow.com/backend/api/rooms'); // Initialise cURL
        $post = json_encode($post); // Encode the data array into a JSON string
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

        $result = curl_exec($ch); // Execute the cURL statement
        $user_row= json_decode($result); 
        curl_close($ch); // Close the cURL connection
        
        return $user_row;         
    }
    /*
        newrow_room created successuflly! Id--35281
        Newrow students ids added to room46530,46531,46532
        User added-{"status":"success","data":{"added":3,"removed":0}}
        Room created at newrow, students added to room , Click below- https://intervene.io/questions/intervention_list.php
    */

    function getRooms ($page, $limit) {        
        $limit = !empty($limit) ? $limit : 500;
        $page = !empty($page) ? $page : 0;
        $token=$_SESSION['getToken'] = _get_token();   
        //$token=$_SESSION['ses_admin_token'];  //"cd3f2fcec5617f825e59a108405cd82e";
        
        $ch = curl_init('https://smart.newrow.com/backend/api/files?model_type=room_list&model_id=&page='.$page.'&limit='.$limit); // Initialise cURL
        $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // get stringified data/output. See CURLOPT_RETURNTRANSFER
        $result = curl_exec($ch);
        
        //$result_rooms = json_decode($result); 
        $result_rooms = $result; 
        curl_close($ch); // Close the cURL connection
        /*
        $status = '';
        $status.= 'Status: '.$result_rooms->status.'<br/>';
        $status.= 'Room Count: '.$result_rooms->data->count.'<br/>';
        $status.= 'Current Limit: '.$result_rooms->data->limit.'<br/>';
        $status.= '<hr/>';
        
        foreach($result_rooms->data->content as $model) {
            $status .= '-- '.$model->id.'<br/>';
            $status .= 'Model Type: '.$model->model_type.'<br/>';
            $status .= 'Model ID:   '.$model->model_id.'<br/>';
            $status .= 'Name:  '.$model->name.'<br/>';
            $status .= 'Date Created:  '.date('Y-m-d H:i:s', $model->date_created).'<br/>';
            $status .= 'User Created:  '.$model->user_created.'<br/>';
        
        }
        */
        return $result_rooms;
    }
?>