
<?php
    /*
        Test to get courses from moodle via api
        
    */ 
    include("iframe-telpass-header.php");

if (!$_SESSION['student_id'])
{
    header('Location: login.php');
    exit;
} 
    require_once('MoodleWebServices/cur-moodlel.php');
    //$token = '14432b87ba8ea3a4896dc7707d10e71d';
    
    $domainname = 'https://ellpractice.com/intervene/moodle';

    //--------------------- ID 62 is Mehul's English 1 Inference Course -------------------------------------------------
    $str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`='".$_SESSION['student_id']."' ORDER BY ID DESC
		LIMIT 1";

    $moodle_data = mysql_fetch_assoc(mysql_query($str));
    $TeluserName =base64_encode($moodle_data['UesrName']);
    $TelPas  =   base64_encode($moodle_data['PassWord']);
    if(!isset($_REQUEST['t']) && $moodle_data['TelUserID']>0){

        $url= "https://ellpractice.com/intervene/moodle/telpasLoginByStu2.php?username=".$TeluserName."&password=".$TelPas."&uiId=".$_SESSION['student_id'];
        header('Location: '.$url);
    }

    if (isset($_REQUEST['t'])) { 
        //echo ('found t');       
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            $curl = new curl;
            $restformat = 'json'; 
            // Load Course
            $courseID = $_REQUEST['id']; 
            /*
            // Enroll First
            $tokenenrol_users="831c9d38c55c65b99a5dde1bc4677ae1";
            $functionnametokenenrol_users = 'enrol_manual_enrol_users';
            $enrolment = new stdClass();
            // Enrol student
            $enrolment->roleid = 5; // student -> 5; moderator(teacher) -> 4; professor(editing teacher) -> 3;
            $enrolment->userid = $_SESSION['student_id']; //$TeluserID; //$userID;
            $enrolment->courseid = $courseID; 
            $enrolments = array( $enrolment);
            $params = array('enrolments' => $enrolments);

            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $tokenenrol_users . '&wsfunction='.$functionnametokenenrol_users;
            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
            $respEn = $curl->post($serverurl . $restformat, $params);

            */
                    
            // Load Course Contents
            $token = '02104727338432bcc247cce5338abb18';
            $functionname = 'core_course_get_contents';
            $params = array('courseid' =>$courseID,); 

            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;        
            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';        
            $resp = $curl->post($serverurl . $restformat, $params);        
            $responsName = json_decode($resp,true);
            
            //echo ('COURSE ID # '.$courseID . ' <br/>');
            $pos = 0; // start at 1.. 0 is the announcement
            print_r('<style>body {text-align:center;}</style>');
            foreach ($responsName as $idx) {
                if ($pos++ == 0)
                    continue;
                $item = $idx;
                print_r('<h2>'.$item['name'].'</h2>');
                if (count($item['modules']) > 0) {
                    //print_r('----> Modules found: '.count($item['modules']).'<br/>');
                    foreach($item['modules'] as $module) {
                        print_r('<h2>Name: '.$module['name'].'</h2>');
                        print_r('<h4>Desc:'.var_dump($item).'</h4>');
                        print_r('
                            <iframe 
                                class="p-20 align-center" 
                                src="https://ellpractice.com/intervene/moodle/mod/hvp/embed.php?id='.$module["id"].'" 
                                width="500px" height="500px" 
                                frameborder="0" 
                                allowfullscreen="allowfullscreen" 
                                allow="microphone; camera;"  
                                referrerpolicy="unsafe-url"
                            >
                            </iframe>
                        ');
                        //print_r('url: '.$module['url'].'<br/>');
                    }
                }
                echo '<hr>';
            }
            
            

            
        } else if (!isset($_REQUEST['id'])) {    
            $token = '14432b87ba8ea3a4896dc7707d10e71d';
            //----------------------------------------------------------------------
            $curl = new curl;
            $restformat = 'json';         
            $function_name_courses_by_field = 'core_course_get_courses_by_field';
            $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$function_name_courses_by_field;
            $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
            $resp = $curl->post($serverurl . $restformat, '');
            $responsCourse = json_decode($resp,true);

            print_r($responsCourse);
        }

    }
?>
