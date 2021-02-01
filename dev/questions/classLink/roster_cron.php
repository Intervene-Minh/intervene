<?php

require_once 'function.php';
$str = "SELECT * FROM `class_link_keys` WHERE isActive=1";
$qr = mysql_query($str);
$arrRosterArr=[];
$className='';
$teacher_id=0;
$school_id=0;
$class_code=0;
$teacher_unique_id=0;
$grade_level_id=0;
$Grade=0;
$roseterID=rand();
$stuFName= '';
$stuLName= '';
$stuname= '';
$StuPass=base64_encode('020student');
$stuUID='';
$stuBdy='';
$datetm=date("Y-m-d");
$arrFinalRosetr=[];
while ($row = mysql_fetch_assoc($qr)) {


    $client_id=$row['client_id'];
    $client_secret= $row['client_secret'];
    $endpoint_url= $row['endpoint_url'];

    $oneRoster = new OneRoster($client_id, $client_secret);

    $str="SELECT linkclas.school_sourcedId,linkclas.sourced_id,linkclas.name As ClassName,linkterm.intervene_grade_id FROM
`classlink_class_x_classes` AS linkclas INNER JOIN 
 `classlink_grade_x_terms` AS linkterm ON linkterm.sourced_id= linkclas.grade_sourcedId 
WHERE linkclas.status=1 && linkterm.active=1 && linkterm.intervene_grade_id > 0 ";


    $qr = mysql_query($str);
    while ($row = mysql_fetch_assoc($qr)) {


        $class_name=$row['ClassName'];
        $intervene_grade_id= $row['intervene_grade_id'];
        $school_source_id= $row['school_sourcedId'];
        $class_source_id= $row['sourced_id'];

        $teacherArr =	_getRosterData($endpoint_url."ims/oneroster/v1p1/schools/$school_source_id/classes/$class_source_id/teachers");

        foreach ($teacherArr['users'] as $row) {
            $teacherSource =$row['sourcedId'];
            $arrRosterArr[]=array('school_source_id'=>$school_source_id,'classname'=>$class_name,'grade'=>$intervene_grade_id,
                'class_sourceID'=>$class_source_id,'teacher_source'=>$teacherSource);

        }


    }


    foreach ($arrRosterArr as $key => $row) {


        $class_name =  $row['classname'];
        $grade =  $row['grade'];
        $school_source_id =  $row['school_source_id'];
        $class_source_id=$row['class_sourceID'];
        $teacher_source_id=$row['teacher_source'];

        $studentArr=	_getRosterData($endpoint_url."ims/oneroster/v1p1/schools/$school_source_id/classes/$class_source_id/students");
        $teacher_id= getfiledval('users','id',"sourced_id='".$teacher_source_id."' && status=1");

        $school_id= getfiledval('schools','SchoolId',"sourced_id='".$school_source_id."' && status=1");
        foreach($studentArr['users'] as $rowStu) {
            $arrFinalRosetr[]=array(
                "school_id"=> $school_id,
                "teacher_id"=>$teacher_id,
                "class_name"=>$class_name,
                "class_code"=>$class_source_id,
                "grade_id"=>$grade,
                "stu_fname"=>$rowStu['givenName'],
                "stude_lastname"=>$rowStu['username'],
                "stude_email"=>$rowStu['email'],
                "stude_user"=>$rowStu['username'],
                "sdtu_uid"=>$rowStu['sourcedId'],


            );
        }
    }


}


//pre($arrFinalRosetr);

//upload data in roster

foreach($arrFinalRosetr as $dataRow){


    $school_id  =  $dataRow["school_id"];
    $teacher_id  =       $dataRow["teacher_id"];
    $className  =        $dataRow["class_name"];
    $class_code  =           $dataRow["class_code"];
    $Grade      =    $dataRow["grade_id"];
    $stuFName  =          $dataRow["stu_fname"];
    $stuLName  =       $dataRow["stude_lastname"];
    $stuEmail  =     $dataRow["stude_email"];
    $stuname  =     $dataRow["stude_user"];
    $stuUID   =   $dataRow["sdtu_uid"];
    // check teacher id is rather than 0
    if($teacher_id > 0 ){

       $str="SELECT id ,count(id) as cnt FROM classes WHERE 
        class_name='".$className."' && `school_id`=$school_id && `teacher_id`=$teacher_id  && `grade_level_id`='".$Grade."' Group by id";
        $qr=mysql_query($str);
        $res = mysql_fetch_assoc($qr);
        if($res['cnt'] > 0 ){
            $class_ID= $res['id'];
        }
        else{
            // insert class in db
            $str="INSERT INTO  `classes` SET `class_name`='".$className."',`roster_id`= $roseterID,`class_code`='".$class_code."',`teacher_id`=$teacher_id,`grade_level_id`='".$Grade."',`grade_level_name`='',`grade_level_common`='',`school_id`=$school_id, `created`='".$datetm."'";
            mysql_query($str);
            $class_ID=mysql_insert_id();
        }
    }


// end teacher block
// check class  id is grather than 0
    if($class_ID>0){
        $str="INSERT INTO `class_x_teachers` SET `teacher_id`=$teacher_id,`class_id`=$class_ID,`roster_id`=$roseterID,`teacher_unique_id`='".$teacher_unique_id."'";
        mysql_query($str);
        $class_teach_ID=mysql_insert_id();

        if($class_teach_ID>0){
            $res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM students WHERE uid='".$stuUID."' Group by id"));
            if($res['cnt'] > 0 ) {
                $student_id= $res['id'];
            }
            else{
               $str= "INSERT INTO `students` SET `email`='".$stuEmail."', `first_name` ='".$stuFName."',  `last_name`  ='".$stuLName."', `student_bdy`='".$stuBdy."',  `username`  = '".$stuname."',  `password`  ='".$StuPass."',   `school_id`  ='".$school_id."',  `grade_level_id` ='".$Grade."',   `roster_id` ='".$roseterID."', `uid` ='".$stuUID."',  `status` =1, `created` ='".$datetm."'";
                mysql_query($str);
                $student_id =mysql_insert_id();
            }
            if($student_id>0){

                $str="SELECT id ,count(id) as cnt FROM students_x_class WHERE `class_id`=$class_ID && `student_id`=$student_id && `grade_level_id`='".$Grade."' Group by id";
                $qr= mysql_query($str);
                $res = mysql_fetch_assoc($qr);
                if($res['cnt'] ==0 ){
                    $str="INSERT INTO`students_x_class` SET `class_id`=$class_ID,`student_id`=$student_id,`grade_level_id`='".$Grade."',`roster_id`=$roseterID";
                    mysql_query($str);
                    $up=1;
                }
            }
        }
    }
}