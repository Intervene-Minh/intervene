<?php
/***
SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, (
(
SUM( s_x_s.corrected ) / count( s_x_s.qn_id )
) *100
) AS percentage, student_id, rel.objective_id
FROM students_x_assesments s_x_s
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id
WHERE s_x_s.assessment_id = '3'
AND rel.objective_id
IN ( 205, 235,234 )
AND rel.question_id = s_x_s.qn_id
GROUP BY s_x_s.student_id
LIMIT 0 , 30

**/
/// dd
//echo 'XXDatadash report';
include("header.php");
  //echo  $_SESSION['schools_id'].'====school id'; die;
if (!isset($_SESSION['schools_id'])) {
    header("Location: login_principal.php");
    exit();
}
/// mysql_query

 $readiness_res = ('SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name FROM terms t '
            . 'LEFT JOIN term_relationships rel ON rel.objective_id = t.id '
            . 'LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id '
            . 'WHERE a_x_s.assesment_id = \'' . $_GET['assesment'] . '\'  AND t.objective_type = "R" GROUP BY t.id ');


//echo '<pre>',$readiness_res; die;

//echo '<pre>'; print_r($_GET); die;
////////////////////////////////////

 $email = $_SESSION['temp_email'];
    $firstname = $_SESSION['temp_firstname'];
    $lastname = $_SESSION['temp_lastname'];
    $dist_mail_name =  $_SESSION['temp_dist_name'];
    $master_school_name = $_SESSION['temp_master_school'];
    $school_mail_name = $_SESSION['temp_school_name'];
    $phone_number = $_SESSION['temp_phone'];
    $smart_preb_name = $_SESSION['temp_smart_preb'];
    $data_dash_name = $_SESSION['temp_data_dash'];
    $address = $_SESSION['temp_address'];
    $city = $_SESSION['temp_city_name'];
    $zipcode = $_SESSION['temp_zipcode'];
    $billing_state = $_SESSION['temp_billing_state'];
    if($_SESSION['temp_email']){
?>
       <script type="text/javascript">
            var _dcq = _dcq || [];
            var _dcs = _dcs || {};
            _dcs.account = '7926835';

            (function() {
              var dc = document.createElement('script');
              dc.type = 'text/javascript'; dc.async = true;
              dc.src = '//tag.getdrip.com/7926835.js';
              var s = document.getElementsByTagName('script')[0];
              s.parentNode.insertBefore(dc, s);
            })();
          _dcq.push(["identify", {
            email: "<?php print $email; ?>",
            first_name: "<?php print $firstname; ?>",
            last_name: "<?php print $lastname; ?>",
            your_school: "<?php print $school; ?>",
            billing_address: "<?php print $address; ?>",
            phone_number: "<?php print $phone; ?>",
            billing_city: "<?php print $city; ?>",
            data_dash: "<?php print $data_dash_name; ?>",
            billing_zipcode: "<?php print $zipcode; ?>",
            billing_state: "<?php print $billing_state; ?>",
            tags: ["Customer Admin"]
          }]);

          </script>
       
       
     <?php
    }
    
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_dist_name']) )unset($_SESSION['temp_dist_name']);
        if( isset($_SESSION['temp_master_school']) )unset($_SESSION['temp_master_school']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);
        if( isset($_SESSION['temp_phone']) )unset($_SESSION['temp_phone']);
        if( isset($_SESSION['temp_smart_preb']) )unset($_SESSION['temp_smart_preb']);
        if( isset($_SESSION['temp_data_dash']) )unset($_SESSION['temp_data_dash']);
        if( isset($_SESSION['temp_address']) )unset($_SESSION['temp_address']);
        if( isset($_SESSION['temp_city_name']) )unset($_SESSION['temp_city_name']);
        if( isset($_SESSION['temp_zipcode']) )unset($_SESSION['temp_zipcode']);
        if( isset($_SESSION['temp_billing_state']) )unset($_SESSION['temp_billing_state']);

if($_GET['view'] == 'teacher' && $_GET['id'] > 0) {
     $login_teacher = "SELECT * FROM users WHERE id = " . $_GET['id'];
    $teacher_data = mysql_fetch_assoc(mysql_query($login_teacher));

    $_SESSION['login_id'] = $teacher_data['id'];
    $_SESSION['login_user'] = $teacher_data['user_name'];
    $_SESSION['login_mail'] = $teacher_data['email'];
    $_SESSION['login_role'] = 1;
    $_SESSION['login_status'] = 1;
     echo "<script type='text/javascript'>window.location.href = 'folder.php';</script>";
	exit();
}

/* Process logout */
if (isset($_POST['logout'])) {
    unset($_SESSION['schools_id']);
    /////////////////////////////////////////////////////
     unset($_SESSION['is_passage']); 
     unset($_SESSION['list']); 
        unset($_SESSION['ses_taxonomy']); 
        unset($_SESSION['qn_list']); 
        unset($_SESSION['is_passage_grade']); 
    //////////////Unsset all created by:School//////////////////
    header("Location: login.php");
    exit();
}
$error = '';


if ($_POST['upload_logo']) {
    // print_r($_FILES); die;
    $cwd = getcwd();
    $uploads_dir = $cwd . '/uploads/schoollogo';

    $school_logo_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$_SESSION['schools_id']}"));
    if (trim($school_logo_res['schoollogo']) != '') {
        unlink($uploads_dir . '/' . $school_logo_res['schoollogo']);
    }
    $tmp_name = $_FILES["schoollogo"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = $_SESSION['schools_id'] . '_' . basename($_FILES["schoollogo"]["name"]);
    //echo "$uploads_dir/$name"; die;

    move_uploaded_file($tmp_name, "$uploads_dir/$name");
    mysql_query("UPDATE schools SET schoollogo = \"" . $name . "\" WHERE `SchoolId` = {$_SESSION['schools_id']}  ");
    $error = 'ZZLogo has been updated successfully.';
}


$school = mysql_fetch_assoc(mysql_query("SELECT s.*, d.district_name FROM `schools` s LEFT JOIN loc_district d ON s.district_id = d.id WHERE `SchoolId` = {$_SESSION['schools_id']}"));
$folders = mysql_query("
	SELECT *
	FROM `terms`
	WHERE `id`
	IN (
		SELECT `termId`
		FROM `purchasemeta`
		WHERE `purchaseId` = (
			SELECT `id`
			FROM `purchases`
			WHERE `schoolID` = {$_SESSION['schools_id']}
		)
	)
	ORDER BY `name` ASC
");

function sendEmailNoticeShared($email, $teacher) {
    require 'inc/PHPMailer-master/PHPMailerAutoload.php';

    $sqluser = "SELECT * FROM users WHERE id = " . $teacher;
    $sqlQu = mysql_query($sqluser);

    $toData = mysql_fetch_assoc($sqlQu);
    $to = $toData['email'];

    $message = "Dear {$toData['first_name']},
	<br /><br />
	Congratulations, you now have access to the Intervene Question Database.<br />
	Login https://intervene.io/questions/login.php to get started! Let us know if you need any help.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io<br />
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    // Set the subject line
    $mail->Subject = 'Access Granted to Intervene Question Database';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
    // send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}
$curr_school=$_SESSION['schools_id'];
/* Process share folders */
if (isset($_POST['share'])) {
   //echo '<pre>';   print_r($_POST); 
    
    $allow_status=(isset($_POST['sessions_access']))?'yes':'no'; 
 // echo  $allow_status.'==go Session access'; die;
   
    $userId = $_POST['share'];
    
    // Tut. session permisson to Teacher
    $sql="SELECT * FROM user_session_access WHERE role='teacher' AND user_id=".$userId;
  $get=mysql_num_rows(mysql_query($sql));
  
  if($allow_status=='yes'&&$get==0)
      $q="INSERT into user_session_access SET user_id='$userId',role='teacher',school_id=".$curr_school;
  elseif($get==1){  // delete with role and school : singl row only-teacher
      $q=" DELETE FROM user_session_access WHERE role='teacher' AND user_id='$userId' AND school_id=".$curr_school;
      }
     
  $query=mysql_query($q);
   
 
    // Tut. session permisson to Teacher
    
    ////////////
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
    if (isset($_POST['teacher_permission'][$userId])) {
        
        foreach ($_POST['teacher_permission'][$userId] as $item) {
            $grade_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$item}) AND `active` = 1"));
            mysql_query('INSERT INTO techer_permissions SET '
                    . 'teacher_id = \''.$userId.'\' , '
                    . 'permission = \'data dash\' , '
                    . 'grade_level_id = \''.$item.'\' , '
                    . 'grade_level_name = \''.$grade_res['name'].'\' , '
                    . 'school_id = \''.$_SESSION['schools_id'].'\' ');
        }
    }
    if (isset($_POST['folders'][$userId])) {
        // Delete existed data
        mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");

        // Insert new data
        foreach ($_POST['folders'][$userId] as $item) {
            mysql_query("INSERT INTO `shared` (`schoolId`, `userId`, `termId`) VALUES ({$_SESSION['schools_id']}, {$userId}, {$item})");
        }

        //Send email
        sendEmailNoticeShared($school['SchoolMail'], $userId);
    } else {
        echo "<script type='text/javascript'>alert('Please choose your registered folder(s) to share!');</script>";
    }
}

/* Process revoke */
if (isset($_POST['revoke'])) {
    // delete Tut. session settings 
    $curr_school=$_SESSION['schools_id'];
     $userId = $_POST['revoke'];
    $q=" DELETE FROM user_session_access WHERE role='teacher' AND user_id='$userId' AND school_id=".$curr_school;
    $query=mysql_query($q);
    //echo '<pre>';    print_r($_POST); die; 
    
    
   
    mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
}

$users = mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");

$registered_folders = array();
$arra_grade_id = array();
if (mysql_num_rows($folders) > 0) {
    while ($folder = mysql_fetch_assoc($folders)) {
        $registered_folders[$folder['id']] = $folder['name'];
        $arra_grade_id[] = $folder['id'];
    }
}

$students_result = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as count FROM students WHERE school_id = \''.$_SESSION['schools_id'].'\' '));

// Teachers Tut. allow status 
 //$sql=" SELECT * FROM `user_session_access` WHERE 1";


$res=mysql_query(" SELECT * FROM `user_session_access` WHERE role='teacher' AND  school_id=".$curr_school);
while($data=mysql_fetch_assoc($res)){
    $arr_allow_teacher[]=$data['user_id'];
}
  //print_r($arr_allow_teacher);    //die;
//  if(in_array(316, $arr_allow_teacher))
//          echo 'exit key'; else echo 'not '; die;
  
?>

<div class="container">
     <?php include "school_header.php";?>

 <?php 
// $_GET['assesment']

// School First assessment Report 
  $data_assesment=mysql_fetch_assoc(mysql_query("SELECT sa.assessment_id, a.id, a.assesment_name
FROM `students_x_assesments` sa
LEFT JOIN assessments a ON sa.assessment_id = a.id
WHERE sa.school_id ='".$_SESSION['schools_id']."'
GROUP BY sa.assessment_id LIMIT 0 , 1 "));
 if($data_assesment['assessment_id']){
   $_GET['assesment']=$get_assesment=$data_assesment['assessment_id'];
 }


 if(isset($_POST['assesment'])){
$_GET['assesment']=$_POST['assesment'];
  //print_r($_POST);
  $get_assesment=$_POST['assesment'];
$get_objectives=$_POST['objectives'];
$get_domain=$_POST['domain'];

 }

 // School Data Dash Report
 $schoolId=$_SESSION['schools_id'];

//  $get_assesment=$_GET['assesment'];
// $get_objectives=$_GET['objectives'];
// $get_domain=$_GET['domain'];




  $assessment_det=mysql_fetch_assoc(mysql_query("SELECT *
FROM `assessments`
WHERE `id` =".$get_assesment));
  //print_r($assessment_det);

  
// if objective selected. //////
///Overall Score//////
$sql="SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, (
(
SUM( corrected ) / count( qn_id )
) *100
) AS percentage, student_id,teacher_id,grade_id
FROM students_x_assesments
WHERE assessment_id =".$get_assesment."
AND `school_id` =".$schoolId."
GROUP BY student_id"; 

 //$is_domain_obj=1;
////Filter:Domain /////////// select_type
//if(!empty($get_domain)){
if($_POST['select_type']=="doamin"&&!empty($get_domain)){
  unset($get_objectives);
  

  $filter_column="Domain<br/>";
  $filter_column.='<span class="text-info"><br>'.$get_domain.'</span>';

  $is_domain_obj=1;

  $obj_arr=array(); // mysql_query
 $find_objectives=(" SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name,t.domain
FROM terms t
LEFT JOIN term_relationships rel ON rel.objective_id = t.id
LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id
WHERE a_x_s.assesment_id = '".$get_assesment."' AND t.domain='".$get_domain."'
GROUP BY t.id ");  //die;

$find_objectives=mysql_query($find_objectives);
while($row=mysql_fetch_assoc($find_objectives)){
  $obj_arr[]=$row['id'];
}
  
}elseif($_POST['select_type']=="objecitives"&&isset($get_objectives)&&count($get_objectives)>0){
   unset($get_domain);

   $is_domain_obj=1;
    $obj_arr=array(); //Objectives in a Dommains
//Objectives Selected in a assessment: NO domain 
  $obj_arr=$get_objectives;
   $filter_column="Objectives<br/>";
   
   $objectives_str1=implode(",", $obj_arr);
   $sql_objectives=mysql_query("SELECT *
FROM `terms`
WHERE `id`
IN ( ".$objectives_str1." ) ");
   $obj_names=array();
  while($row=mysql_fetch_assoc($sql_objectives))
   $obj_names[]=(!empty($row['obj_short']))?$row['obj_short']:$row['name'];

  $filter_column.='<span class="text-info"><br>'.implode(",", $obj_names).'</span>';


}

//echo 'Objextives::'; print_r($obj_arr); 

////////////////////////////////////////
//Dat by Objctive list. .. 
//if(isset($get_objectives)&&count($get_objectives)>0){

if(isset($obj_arr)&&count($obj_arr)>0){
  $cal_overall=1;

  $objectives_str=implode(",", $obj_arr);
   
$sql=" SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, (
(
SUM( s_x_s.corrected ) / count( s_x_s.qn_id )
) *100
) AS percentage, student_id, s_x_s.school_id,s_x_s.teacher_id,s_x_s.grade_id
FROM students_x_assesments s_x_s
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id ";

$changeXX=" WHERE s_x_s.assessment_id = '".$get_assesment."'
AND rel.objective_id
IN ( ".$objectives_str." )
AND rel.question_id = s_x_s.qn_id
GROUP BY s_x_s.student_id ";

$change=" WHERE s_x_s.school_id='".$schoolId."' AND s_x_s.assessment_id = '".$get_assesment."'
AND rel.objective_id
IN ( ".$objectives_str." )
AND rel.question_id = s_x_s.qn_id
GROUP BY s_x_s.student_id ";
$sql.=$change;
 // Domain wise list. 



 //echo '<pre>', $sql;  //die; 
}





 //echo  '<pre>',$sql;

$result=mysql_query($sql);
$data=array();// table data to print.
 
 while($row=mysql_fetch_assoc($result)){

    //Over All Score /////////////
   $over_all_score="";
   // if Domain or Objecitve Selected!!
   




    $data[$row['student_id']]['ID']=$row['student_id'];
   
    //Students
    $std_row=mysql_fetch_assoc(mysql_query(' SELECT CONCAT( first_name, " ", middle_name, " ", last_name ) AS fullname
FROM `students`
WHERE id ='.$row['student_id']));
   // print_r($std_row); die;

   $data[$row['student_id']]['fullname']=$std_row['fullname'];

     $data[$row['student_id']]['correct']=$row['correct'];
    $data[$row['student_id']]['total']=$row['total'];
   // $data[$row['student_id']]['percentage']=$row['percentage'];

  //Techer
    $teacher=mysql_fetch_assoc(mysql_query("SELECT first_name,last_name FROM `users` WHERE `id` =".$row['teacher_id']));
    $data[$row['student_id']]['teacher']=$teacher['first_name'].' '.$teacher['last_name'];
    //Grade : 
    $grade=mysql_fetch_assoc(mysql_query("SELECT *
FROM `terms`
WHERE `id` =".$row['grade_id']));
    $data[$row['student_id']]['grade_id']=$grade['name'];

     $data[$row['student_id']]['assessments']=$assessment_det['assesment_name'];

     ///////////////////
   
      //  $is_domain_obj=1;
    if(count($$obj_arr>0)&&$is_domain_obj==1){
      $studentId=$row['student_id'];
     $student_overall=mysql_fetch_assoc(mysql_query(" SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, (
(
SUM( corrected ) / count( qn_id )
) *100
) AS percent_all, student_id,teacher_id,grade_id
FROM students_x_assesments
WHERE assessment_id =".$get_assesment."
AND school_id=".$schoolId."  AND student_id=".$studentId." 
GROUP BY student_id ")); 

     //print_r($student_overall); die;
          $data[$row['student_id']]['domain_search']=1;// Domain or objective true
     
       $data[$row['student_id']]['overall']=$student_overall['percent_all']; //Student Overall
       $data[$row['student_id']]['percent_domain_obj']=$row['percentage'];// Lower<overall

     /// Domain or objective 7th column %
   }else{ //over_all 
       $data[$row['student_id']]['overall']=$row['percentage']; // objectives or Over all 

   }



}

 //echo '<pre>'; print_r($data) ; //die;


 // echo 'School allowed Tut. sessions-'.$ses_allowed; die;?>
      <!--   All report and Filter insid Form -->
    <form name="report_action"
     id="report_action" method="POST">
         <div class="add_question_wrap clear fullwidth row">
                       <div class="col-md-4">
                                
                       <!--  <label for="lesson_name">Assessment<? //= $_SESSION['schools_id'].'==';?></label> -->

                                    <?php 

                                    $res_assesment=mysql_query("SELECT sa.assessment_id, a.id, a.assesment_name
FROM `students_x_assesments` sa
LEFT JOIN assessments a ON sa.assessment_id = a.id
WHERE sa.school_id ='".$_SESSION['schools_id']."'
GROUP BY sa.assessment_id");


                                    ?>                   
                 <select name="assesment" class="required textbox" >
                  <option value="">assesment</option>
                  <?php while ($line= mysql_fetch_assoc($res_assesment)) { 
                     // assesment
                    $sel=(isset($_GET['assesment'])&&$line['id']==$_GET['assesment'])?'selected':'';
                    ?>
                   <option <?=$sel?> value="<?=$line['id']?>"><?=$line['assesment_name']?></option> <?php }?>
                                 </select>
                                  
                                </div>
                                <?php 
                               
                                 $res_domain_in_asess=mysql_query(" SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name,t.domain
FROM terms t
LEFT JOIN term_relationships rel ON rel.objective_id = t.id
LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id
WHERE a_x_s.assesment_id = '".$_GET['assesment']."' AND  t.domain!=''
GROUP BY t.domain ");


                                ?>

                                <div class="col-md-3">
                                 
                           <br/>
                           <script type="text/javascript">
                             $(function(){

                              $('input[type="radio"]').click(function(){
                                var info=$(this).val();
                                console.log(" Data::"+$(this).val());
                                $("#objectives_id").hide('Loading');// objectives_id filter_section domain_id
                               
                                 if(info=="objecitives"){
                                   $("#objectives_id").show();
                                   $("#domain_id").hide();//
                                 }


                                 if(info=="doamin"){
                                    $("#objectives_id").hide();
                                   $("#domain_id").show();//
                                 }

                                
                              });
                              /////
                              
 
                             });
                           </script>
                           <p >
                              <!--   <label for="ses_type"> Type:</label><br>   -->   
                              <?php 
                              // checked=""
                               $domain_checked=''; $objecitives_checked='';// checked
                              if($_POST['select_type']=="doamin")
                                $domain_checked='checked';
                              elseif($_POST['select_type']=="objecitives")
                                $objecitives_checked='checked';

                              ?>        
             <input name="select_type" id="#option_1" value="doamin"   <?=$domain_checked?> type="radio">Domain&nbsp;&nbsp;
                            <input name="select_type" id="#option_2" value="objecitives" <?=$objecitives_checked?> type="radio">Objectives
                            </p>
                                </div>




                                <?php 
                                // mysql_query
                                $res_objectives=mysql_query('SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name FROM terms t '
            . 'LEFT JOIN term_relationships rel ON rel.objective_id = t.id '
            . 'LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id '
            . 'WHERE a_x_s.assesment_id = \'' . $_GET['assesment'] . '\'   GROUP BY t.id ');
                             


                                ?>

                                <div class="col-md-3" id="filter_section" >
                                 <?php  
                                 // if(isset($_POST['domain'])&&!empty($_POST['domain'])){
                              $domain_section='none';
                               if($_POST['select_type']=="doamin"){
                                $domain_section='';
                              }

                             
                           // if(count($_POST['objectives'])>0){
                               $obj_section='none';
                             

                                if($_POST['select_type']=="objecitives"){ 
                                $obj_section=''; //echo 'SHow objective ';
                              }


                             ?>

                                  <select style="display:<?=$domain_section?>;"
                                    name="domain" id="domain_id"  class="required textbox" >
                               <option value="">Domain</option>
                                  <?php while ($line= mysql_fetch_assoc($res_domain_in_asess)) { 
                                     // assesment
                                    $sel=null;
                            $sel=(isset($get_domain)&&$line['domain']==$get_domain)?'selected':'';

                  
                    ?>
                  <option <?=$sel?> value="<?=$line['domain']?>"><?=$line['domain']?></option>
                  <?php }?>
                           </select>
                             <?php  
                             
                             ?>

                                  <select style="display:<?=$obj_section?>;" name="objectives[]" id="objectives_id" class="required textbox" multiple="" >
                                   <option>Objectives</option>
                                   <?php while ($line= mysql_fetch_assoc($res_objectives)) { 
                     // assesment
                                    $sel=null;
                    if(isset($get_objectives)&&count($get_objectives)>0){                
                
                    $sel=(in_array($line['id'],$get_objectives))?'selected':'';
                  }
                  $line['obj_short']=trim($line['obj_short']);
                  $line['obj_short']=(!empty($line['obj_short']))?$line['obj_short']:$line['name'];
                    ?>
                  <option <?=$sel?> value="<?=$line['id']?>"><?=$line['obj_short']?></option>
                  <?php }?>
                           </select>
                           
                                </div>
                                <div class="col-md-2">
                                <input type="submit" name="search" value="Search" class="btn btn-info">
                                </div>


                               
                            </div>


        <br/><br/>
        <table class="table table-hover" >
            <tr>
                <th>Grade</th>
                <th>Teacher</th>
                <th>Student(FullName)</th>
                 <th>Student ID</th>
                <th>Assessment</th>
                <th>Overall Score</th>
                <?php if(isset($is_domain_obj)){?>
                <th><?=$filter_column?></th>
                <?php  }?>
               
            </tr>
             <!-- Data -->
            <?php //foreach($data as );
            foreach ($data as $student_id => $arr) {
                # code...
            

            ?>
               <tr>
            <td><?=$arr['grade_id']?> </td>
            <td><?=$arr['teacher']?></td>
            <td><?=$arr['fullname']?></td>
            <td class="text-info">ID:<?=$arr['ID']?></td>

             <td><?=$arr['assessments']?></td>
                    
                            
                                                        
                    <td><?=round($arr['overall'],2)?>%</td>
                     <?php if(isset($is_domain_obj)){  /// percent_domain_obj?>
                     <td><?=round($arr['percent_domain_obj'],2)?>%</td>
                     <?php }?>

                </tr>
                <?php } ?>
            
        </table>
    </form>
</div>

<!-- Modal -->
  <?php include "invitations_modal.php";?>

<script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
    $(document).ready(function () {
<?php
if (isset($_GET['invited'])) {
    echo 'alert("Invites have been sent to your teachers. Please ask them to check their spam folders in case they don\'t receive it in 5 minutes!");';
    echo 'location.replace("school.php");';
}
?>

        /* Validate invitation form */
        $('#form-invitation').on('submit', function () {
            var validmail = false;
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            $('#mailboxes input').each(function () {
                if (filter.test($(this).val())) {
                    validmail = true;
                    return false;
                }
            });
            if (!validmail) {
                alert('Please enter at least a valid email address!');
                return false;
            }

            var validgrade = false;
            $('#form-invitation input.folders').each(function () {
                if ($(this).is(':checked')) {
                    validgrade = true;
                    return false;
                }
            });

            if (!validgrade) {
                alert('Please select at least a grade to share!');
                return false;
            }
        });

        $('button[name=share]').on('click', function () {
            var checked = false;
            $(this).closest('tr').find('input.folders').each(function () {
                if ($(this).is(':checked')) {
                    checked = true;
                    return false;
                }
            });
            if (checked) {
                return true;
            } else {
                alert('Please choose your registered folder(s) to share!');
                return false;
            }
        });

        $('button[name=revoke]').on('click', function () {
            return confirm('Are you sure you want to revoke this shared folder?') ? true : false;
        });
    });
</script>

<?php include("footer.php"); ?>