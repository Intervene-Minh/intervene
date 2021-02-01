<?php
  /*
  @List -Telpas Courses
  @Telpas-QUiz and H5p Courses for practice
  **/  
  echo '===';
    include("student_header.php");
    if (!$_SESSION['student_id'])
    {
       header('Location: login.php');
       exit;
   }
   include("student_inc.php");
    /////////////////////
   
    $str = "SELECT * FROM `Tel_UserDetails` WHERE  `IntervenID`=".$_SESSION['student_id'];
    $moodle_data = mysql_fetch_assoc(mysql_query($str));
    $TelPasUserID =$moodle_data['TelUserID'];

/* set compelete status*/
if(isset($_GET['iscom']))
{


$str = "SELECT count(IsComplete) cnt FROM `Tel_CourseComplete` WHERE TelUserID='".$TelPasUserID."' AND CourseID='".$_GET['iscom']."'";

$Telpas_students=mysql_query($str);
$row = mysql_fetch_assoc($Telpas_students);
$cnt=$row['cnt'];
if($cnt==0){
        $strIn="INSERT INTO Tel_CourseComplete SET TelUserID='".$TelPasUserID."',CourseID='".$_GET['iscom']."',IsComplete='1'";
        mysql_query($strIn);
        header('Location: telpas_practice.php');
        exit;
}

}

    $TeluserName =base64_encode($moodle_data['UesrName']);
    $TelPas  =   base64_encode($moodle_data['PassWord']);
    /* get course details from moodle*/
    include_once('MoodleWebServices/get-enroll-course.php');



$loginToTelpas='no';
 
if(_CheckTelpassPermissionStudent()==1||_CheckTelpassPermissionStudent()=='1')
{  //Telpas allowed
  $loginToTelpas='yes';
}else{
   header('Location: welcome.php');
   exit;
}
/////Login automatically//////////


/* this script use for login in moodle
@URL- URL::https://ellpractice.com/intervene/moodle/telpasLogin.php?username=aW52ZW50aXZlXzEzNTAy&password=c3R1ZGVudElAMQ==&courseID=5
*/
    if(isset($_GET['practStart']))
    {
        $url= $domainname."/telpasLogin.php?username=".$TeluserName."&password=".$TelPas."&courseID=".$_GET['practStart'];
        //echo 'URL::',$url; die; 
      header("location:".$url);
    }


//////////////////////////
# Check for Telpas Allowed or not 
$Telpas_allowed=_CheckTelpassPermissionStudent();
?>


<div id="home main" class="clear fullwidth tab-pane fade in active">
   <div class="container">

      <div id="items_id" style="display:none;">Checking Sesion</div>
         
     
        <!-- row -->
      <div class="row">
         <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
            <div style=" width:auto;" title="">
               <?php include("nav_students.php"); ?>
            </div>
         </div>
         <!--  NExt col -->
         <div class="align-center col-md-12">
            <!-- StudentWelcome -->
            <div id="content" style="padding:0px;" > 
               <div class="content_wrap">
                  <div class="ct_heading clear" style="text-align: left;">
                     <h3>Welcome</h3>
                  </div>
                  <!-- /.ct_heading -->
                  <div class="ct_display clear">
                     <div class="item-listing clear">
                        <h3 class="notfound align-center">
                           <a href="#" Sut="<?php echo $_SESSION['student_id']?>">Welcome <?=(!empty($_SESSION['student_name']))?$_SESSION['student_name']:''?></a>
                        </h3>
                     </div>
                  </div>
               </div>
               <?php 
               if(empty($respons['errorcode'])){ ?>
                <table class="table">
                  <thead>
                    <tr>

        <th class="text-center" >Sno</th><!--
         <th class="text-center">Course ID</th>-->
        <th class="text-center">Assignment@@</th>
        <th class="text-center">Progress</th>
        <th class="text-center">Status</th>
        <th class="text-center">Completion</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $i=1;
    include_once('MoodleWebServices/get-stdent-grade.php');
      //echo '<pre>'; print_r($respons);
    


      foreach ($respons as $row)
        { 
           $prog= getProcessGread($row['id'],$TelPasUserID);

          if($prog < 100 && $prog > 0)
          {
            $buttonState = "RESUME";
  
            $Status="<span style='color:red'><strong>Incomplete</strong></span>";
          }
          elseif($prog ==100)
          {
             $buttonState = "RE START";
             $Status="<span style='color:#1d6d1f'><strong>Complete</strong></span>";
          } 
          else
          {
            $buttonState ="START";
            $Status="<span style='color:##337ab7'><strong>Not Started</strong></span>";
        } 
        //////////
        
        $url_quiz_start="telpas_start_quiz_iframe.php?cid=".$row['id'];

        ?>
        <tr>
        <td class="text-center"><?php echo $i; ?></td><!--
        <td class="text-center"><?php //echo $row['id']; ?></td>-->
        <td class="text-center"><?php echo $row['fullname']?></td>
        <td class="text-center"><?php echo number_format($prog);?>%</td>
        <td class="text-center"><?php echo $Status?></td>
        <td class="text-center">
           <?php // 
            if(stripos($row['idnumber'], 'recording_') === 0||
              $row['idnumber']=='recording_2020'){
            ?>
            <a href="<?=$url_quiz_start?>" class="btn btn-primary btn-lg"><?php echo $buttonState?> Quiz</a>
           <?php }else{?>
          <a href="https://intervene.io/questions/telpas_practice.php?practStart=<?php echo $row['id']?>" class="btn btn-primary btn-lg"><?php echo $buttonState?></a>
           <?php  }//}else{?>

        </td>

<?php  $i++;} ?>
</tbody>
  </table>
<?php } else{?>
<br>
<a href="https://intervene.io/questions/telpas_practice.php?crta=1" class="btn btn-primary btn-lg">Please Click here to Start</a>
<?php }?>
            </div>
      
         </div>
         <!-- C0ntent -->
      </div>
   </div>
</div>
<?php
/* use CURL Request for Signin Ans Signup Process in moodle */
if(isset($_GET['crta']))
{

    $courseID=$_GET['course'];
    require_once('MoodleWebServices/moodle-create-account.php');
}
?> 