<?php
   $error = 'rosters_list';
   $up=0;
   extract($_REQUEST);
   $author = 1;
   $datetm = date('Y-m-d H:i:s');
    include("header.php");
    if ($_SESSION['login_role'] != 0) { //not admin
       header('Location: folder.php');
       exit;
   }
   
   //
function _gradeName($id){

      $str="SELECT `id`,`name` FROM `terms` WHERE id=$id";
      $qr=mysql_query($str);
      $res = mysql_fetch_assoc($qr);
      return $res['name']; 
                           
}


function _schoolName($id){

     $str="SELECT  `SchoolId` ,`SchoolName`FROM `schools` WHERE SchoolId=$id";
      $qr=mysql_query($str);
      $res = mysql_fetch_assoc($qr);
      return $res['SchoolName']; 
                           
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$arrG=[];
$str="SELECT id,name FROM `terms` WHERE `active` = 1 && `taxonomy` = 'category'";
 $qr=mysql_query($str);
 while($r = mysql_fetch_assoc($qr)){
  $gname= $r['name'];
  $name = strtolower(str_replace(' ','', $gname));
  $keyN= str_replace('-','', $name);
  $arrG[$keyN]= $r['id'];

}

 if(isset($_POST['importSubmit'])){
   
   // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
      // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){


        $temp = explode(".", $_FILES["file"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_dir = "uploads/rosters/";
        $target_file = $target_dir.$School.'_'.$newfilename;
         if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

         $str="INSERT INTO `school_master_rosters` SET `school_id`='".$School."',`filename`='".$target_file."',`status`='progress' ,created='".$datetm."'";
           mysql_query($str);
          $roseterID=mysql_insert_id();
            if($roseterID >0){
           $uploads_dir = $target_file;
             
             if (($handle = fopen($uploads_dir, "r")) !== FALSE) 
              while($line = fgetcsv($handle, 1000, ",")){
                if($d >= 1) {

                      $teacher_first_name =$line[8];
                      $teacher_last_name =$line[9];
                      $teacher_email =$line[10];
                      $Tuname=$teacher_last_name.'123';
                      $teacher_unique_id =$line[11];
                      $grade= $line[4];
                      $subject= $line[5];
                      $grade_name = $grade.'grade'.$subject;
                      $GerdKey = strtolower(str_replace(' ','', $grade_name));
                      $Grade=$arrG[$GerdKey];
                       $Tpass=md5(123456);
                    $res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM users WHERE email='".$teacher_email."' Group by id"));
                    if($res['cnt'] > 0 ){
                     $teacher_id= $res['id'];
                   }
                   else{ 

                     $str="  INSERT INTO `users` SET `is_subdmin`='no',`user_name`='".$Tuname."',`email`='".$teacher_email."', `password`='".$Tpass."',`first_name`='".$teacher_first_name."',`last_name`='".$teacher_last_name."',`school`='".$School."',`role`=1,`status`=1, 
                  `date_registered`='".$datetm."', `district_id`=0, `master_school_id`=0, `q_remaining`=0";
                  mysql_query($str);
                  $teacher_id= mysql_insert_id();
                   }
                   if($teacher_id > 0 ){


                        $className=$line[6];
                        $roseterID;
                        $class_code=$line[7];
                       $grade_level_id=$Grade;
                        $school_id=$School;
                        $res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM classes WHERE class_name='".$className."' && `school_id`=$school_id Group by id"));
                        if($res['cnt'] > 0 ){
                         $class_ID= $res['id'];
                        }
                        else{

                          $str="INSERT INTO  `classes` SET `class_name`='".$className."',`roster_id`= $roseterID,`class_code`='".$class_code."',`teacher_id`=$teacher_id,`grade_level_id`='".$grade_level_id."',
                          `grade_level_name`='',`grade_level_common`='',`school_id`=$school_id, `created`='".$datetm."'";
                          mysql_query($str);
                          $class_ID=mysql_insert_id();
                        }
                      }

                      
                      if($class_ID>0){

                            $str="INSERT INTO `class_x_teachers` SET `teacher_id`=$teacher_id,`class_id`=$class_ID,`roster_id`=$roseterID,
                            `teacher_unique_id`='".$teacher_unique_id."'";
                            mysql_query($str);
                            $class_teach_ID=mysql_insert_id();

                                $stuFName=$line[0];
                                $stuLName= $line [1];
                                $stuBdy=$line[2];
                                $stuUID=$line[3];
                           
                            if($class_teach_ID>0){

                                $StuPass=base64_encode(rand(10, 99));
                                $StuUname=generateRandomString(4);
                                $res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM students WHERE uid='".$stuUID."' Group by id"));
                                if($res['cnt'] > 0 ) {
                                  $student_id= $res['id'];
                                   }
                                   else{


                                  $str= "INSERT INTO `students` SET  `first_name` ='".$stuFName."',  `last_name`  ='".$stuLName."', `student_bdy`='".$stuBdy."',  `username`  = '".$StuUname."',  `password`  ='".$StuPass."',   `school_id`  ='".$School."',  `grade_level_id` ='".$Grade."',   `roster_id` ='".$roseterID."', `uid` ='".$stuUID."',  `status` =1, `created` ='".$datetm."'";

                                    mysql_query($str);
                                    $student_id =mysql_insert_id();
                                     }
                                     if($student_id>0){

                             // $res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM students_x_class WHERE
                            //  `class_id`=$class_ID && `student_id`=$student_id Group by id"));

$res = mysql_fetch_assoc(mysql_query("SELECT id ,count(id) as cnt FROM students_x_class WHERE
                              `class_id`=$class_ID && `student_id`=$student_id && `grade_level_id`='".$Grade."' Group by id"));

                              if($res['cnt'] ==0 ){

                    // mysql_query("INSERT INTO`students_x_class` SET `class_id`=$class_ID,`student_id`=$student_id,`roster_id`=$roseterID");

                                 $str="INSERT INTO`students_x_class` SET `class_id`=$class_ID,`student_id`=$student_id,`grade_level_id`='".$Grade."',`roster_id`=$roseterID";
                                 mysql_query($str);
                                $up=1;

                           }
                         }
                       
                        }
                      } 
                    }
                      $d++;
                }
                $up_status=mysql_query("UPDATE school_master_rosters SET updated='$datetm',status='Completed' WHERE id=".$roseterID);
              }
         
      } else {
              $msg="Sorry, there was an error uploading your file.";
            }
             } else {
             $msg="Use Only CSV file.";
            }
   }

/*Delete Roster*/
if($delid >0 ){

mysql_query("DELETE FROM school_master_rosters WHERE id=$delid");
mysql_query("DELETE FROM students_x_class WHERE roster_id=$delid");
mysql_query("DELETE FROM class_x_teachers WHERE roster_id=$delid");
mysql_query("DELETE FROM students WHERE roster_id=$delid");
mysql_query("DELETE FROM classes WHERE roster_id=$delid");


echo '<script>window.location="add_new_roster.php"</script>';
   }
   ?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
    $('#School').chosen();
    $('#Grade').chosen(); 
   });
</script>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div id="single_question" class="content_wrap">
               <div class="ct_heading clear">
                  <h3> <i class="fa fa-file"> </i>Upload Roster </h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <?php echo $msg?>
                  <form  method="post" action="" enctype="multipart/form-data">
                     <div class="col-md-4">
                        <!-----add_question_wrap clear fullwidth---->
                        <label for="grade_level_name">Choose School:</label><br />
                        <select name="School" id="School" required="required">
                           <option value="">Please Select School</option>
                           <?php 
                              $str="SELECT  `SchoolId` ,`SchoolName`FROM `schools` WHERE 1";
                              $qr=mysql_query($str);
                              while ($res = mysql_fetch_assoc($qr)) { ?>
                           <option  value="<?php print $res['SchoolId']; ?>"><?php print $res['SchoolName']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                   
                     <div class="col-md-4">
                        <label for="grade_level_name">CSV File:</label><br />
                        <input type="file" name="file" accept=".csv" required="required">
                     </div>
                     <div class="col-md-4" style="text-align: right;">
                      <label> </label><br />
                        <input type="submit" name="importSubmit" style=" margin-top: 10px;" class="form_button submit_button" value="Upload Roster" />
                     </div>
                   </form>
                  <div class="clearnone">&nbsp;</div>
               </div>
               <!-- /.ct_display -->
            </div>
            <!-- ROster List-->
            <div class="ct_display clear">
               <div class="clearnone">&nbsp;</div>
            </div>
            <div id="single_question" class="content_wrap">
               <div class="ct_heading clear">
                  <h3> <i class="fa fa-list"> </i>Roster List </h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <div class="col-md-12">
                     <table id="example" class="display" style="width:100%">
                        <thead>
                           <tr>
                              <th>Sno.</th>
                              <th>School</th>
                              <th>Roster</th>
                              <th>Action/Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $str="SELECT * FROM `school_master_rosters` WHERE 1 ORDER BY id DESC";
                              $i=1;
                              $qr=mysql_query($str);
                              while ($res = mysql_fetch_assoc($qr)) { ?>
                              <tr>
                              <td><?php echo $i;?></td>
                              <td><?php echo _schoolName($res['school_id'])?></td>
                              <td><a href="<?php echo $base_url.$res['filename'];?>" download class="btn btn-danger" >Download</a></td>
                              <td align="center">
                                <a class="label label-success"><?php echo $res['status']?> </a>
                                <a href="add_new_roster.php?delid=<?= $res['id']?>" style="color: red"onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a>
                              </td>
                            </tr>
                           <?php 
                              $i++;  } ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="clearnone">&nbsp;</div>
               </div>
               <!-- /.ct_display -->
            </div>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $('#example').DataTable();
   } );
   
</script>