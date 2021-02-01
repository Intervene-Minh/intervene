<?php
   
    require_once('cur-moodlel.php');
    $curl = new curl;
    $restformat = 'json'; 
    $domainname = 'https://ellpractice.com/intervene/moodle';
    $tokenUserCreate = 'fcc6f9e980dbdef0496fac348966499f';
    $functionnameUserCreate = 'core_user_create_users';
    //categoryid
    
    $userDetails = new stdClass();
    $ppas   =    'studentI@1';
    $uname  =    $_SESSION['student_name'].'_'.$_SESSION['student_id'];
    if(empty($_SESSION['last_name'])) {
        $_SESSION['last_name']=$_SESSION['student_name'];
    }

            $userDetails->username              =       str_replace(' ', '',strtolower($uname));
            $userDetails->password              =       $ppas;
            $userDetails->firstname             =       $_SESSION['student_name'];
            $userDetails->lastname              =       $_SESSION['last_name'];
            $userDetails->email                 =       str_replace(' ', '',$uname.'@intervene.com');
            $userDetails->auth                  =       'manual';
            $userDetails->idnumber              =       $_SESSION['student_id'];
            $users = array($userDetails);
            $params = array('users' => $users);
          //  print_r($params);
          
            /* create Account In Moodle*/
            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $tokenUserCreate . '&wsfunction='.$functionnameUserCreate;
            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
            $resp = $curl->post($serverurl . $restformat, $params);
            $respons        = json_decode($resp,true); 

       // print_r($respons);

            $userID         =    $respons[0]['id'];
            $username       =    $respons[0]['username'];
            $IntervenID     =    $_SESSION['student_id'];
            $PassWord       =   $ppas;
            if($userID > 0 )
            {

            $str="INSERT INTO Tel_UserDetails SET UesrName='".$username."',TelUserID='".$userID."',IntervenID='".$IntervenID."',PassWord='".$PassWord."'";
            mysql_query($str);

            $TeluserName =base64_encode($username);
            $TelPas  =   base64_encode($PassWord);

            $url= "https://ellpractice.com/intervene/moodle/telpasLoginByStu.php?username=".$TeluserName."&password=".$TelPas."&uiId=".$_SESSION['student_id'];

            header("location:".$url);
 
        }
?>