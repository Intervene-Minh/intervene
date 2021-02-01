<?php

/****
@Calendar
{
          title: 'Click for Google',
          url: 'https://google.com/',
          start: '2016-12-28'
        }
        $sql_calendar_school_grade="SELECT *
FROM `int_schools_x_sessions_log`
WHERE `school_id` =94
AND `grade_id` =2622";

**/
 //echo 'call';  //
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
// echo 'intervention_list calendar';

if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}


///////Search/////////////
    if(isset($_POST['qz_submit'])) {

        $_SESSION['ses_quiz_grade']=$_POST['grade'];
        $_SESSION['ses_quiz_lesson']=$_POST['lesson'];  // lesson
      
        $les=mysql_fetch_assoc(mysql_query("SELECT id,objective_id FROM `master_lessons` WHERE id=".$_POST['lesson']));
       
        $_SESSION['ses_quiz_objective']=$les['objective_id'];  // objective_id
        $step_2_url="quiz_step2.php?taxonomy=".$_POST['grade'];
         header('Location:'.$step_2_url);  exit;
}
// Add Quiz






///////////////////////////
//$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
$sql="SELECT * FROM int_schools_x_sessions_log WHERE 1 AND type='intervention' ";
 if(isset($_GET['school_id'])&&$_GET['school_id']>0){
 $sql.=" AND school_id=".$_GET['school_id'];
 }

 if(isset($_GET['grade_id'])&&$_GET['grade_id']>0){
 $sql.=" AND grade_id=".$_GET['grade_id'];
 }

 //echo $sql;



  //echo '<pre>', $sql;  die; 
//$sql.=" Limit 4 ";
$results=mysql_query($sql);
$eventData=array();$id_arr=array();
  while($row = mysql_fetch_assoc($results) ) {
    $sesid=$row['id'];
    $parent=($row['parent_id']>0)?$row['parent_id']:$row['id'];
    $ses_start_time=$row['ses_start_time'];
    $ses_end_time=$row['ses_end_time'];
    $view_url="https://intervene.io/questions/intervention_list.php?sid=".$row['id'];


    //$parent=$row['type'].'-'.$row['id']; //TEST

     $eventData[] = array('title'=>'Ses'.$parent,'id'=>$sesid,
      'start'=>$ses_start_time,'end'=>$ses_end_time,'url'=>$view_url);
     $start_date=date('Y-m-d',strtotime($row['ses_start_time']));
     
     /////View detail//
     $id_arr[]=array('id'=>$row['id'],'start'=>$start_date); //$row['id']
     


  }



// Repeat Sessions//////////// 
// $eventData[] = array('title'=>'Demo session',
//       'start'=>'2018-12-11','end'=>'2018-12-14');
 


///////parent Session /////
 $parent_sql=mysql_query("SELECT parent_id, count( id ) totses
FROM `int_schools_x_sessions_log`
WHERE parent_id >0 AND type='intervention'
GROUP BY parent_id");



 //echo mysql_num_rows($parent_sql).'=Repeat Sessions' ; 
  if(!isset($_GET['grade_id'])&&!isset($_GET['school_id'])){  
 if(mysql_num_rows($parent_sql)>0){
   //$eventData=array();
  while($row = mysql_fetch_assoc($parent_sql) ) {
    $session=mysql_fetch_assoc(mysql_query("SELECT * FROM int_schools_x_sessions_log WHERE id=".$row['parent_id']));
    $total_ses=$row['totses']+1;//1 self
     $Date=$session['ses_start_time'];
   $start_date=date('Y-m-d',strtotime($session['ses_start_time']));
  $end_date=   date('Y-m-d', strtotime($Date. ' + '.$total_ses.' days'));  //die;
    $eventData[] = array('title'=>'ID:'.$session['id'],
      'start'=>$start_date,'end'=>$end_date);

    


  }

}
}  //Search

 //print_r($eventData) ; die;

//Link ///////////
 //print_r($id_arr); die;
  // foreach ($id_arr as $line) {
  //    $view_url="https://intervene.io/questions/intervention_list.php?sid=".$line['id'];
  //   # code...
  //    $eventData[] = array('title'=>'View Detail:'.$line['id'],
  //     'url'=>$view_url,'start'=>$line['start']);
  // }
//echo '<pre>'; print_r($eventData);

?>



<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    // $(document).ready(function () {

    //     $('#district').chosen();

    //     $('#district').change(function () {
    //         district = $(this).val();

    //         $('#district_school').html('Loading ...');
    //         $.ajax({
    //             type: "POST",
    //             url: "ajax.php",
    //             data: {district: district, action: 'get_multiple_schools', school_id: ''},
    //             success: function (response) {
    //                 $('#district_school').html(response);
    //                 $('#d_school').chosen();
    //             },
    //             async: false
    //         });
    //     });
    //     $('#district').change();
    // });
    
    ////////////////
    
</script>
   <!-- Calendar CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" rel='stylesheet' >
        <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" rel='stylesheet'  >
        <!-- Calendar CSS -->




<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">

            <div class="table-responsive">
           <form id="search-users" method="GET" action="">
             <?php 
             //echo $_GET['school_id'], '==G'.$_GET['grade_id']; //die;

             ?>
                                        <select name="school_id">

                        <option value="">School</option>
                        <?php 
                        $school_list=mysql_query("SELECT s. * , ses.school_id
FROM int_schools_x_sessions_log ses
INNER JOIN schools s ON ses.school_id = s.SchoolId
WHERE 1
GROUP BY ses.school_id
ORDER BY s.SchoolName");

                         while( $row = mysql_fetch_assoc($school_list) ) { 
              $sel=($_GET['school_id']==$row['school_id'])?'selected':'';
                          ?>
               <option <?=$sel?> value="<?=$row['school_id']?>"><?=$row['SchoolName']?></option>
                        <?php } ?>
                             </select>
                                
                                          

                                          <!-- Grade -->
                                  <select name="grade_id">
                        <option value="">Grade</option>
                        <?php 
                        $school_list=mysql_query("SELECT t. * , ses.grade_id
FROM int_schools_x_sessions_log ses
LEFT JOIN terms t ON ses.grade_id = t.id
WHERE 1
GROUP BY ses.grade_id
ORDER BY t.name");

                         while( $row = mysql_fetch_assoc($school_list) ) {
          $sel=($_GET['grade_id']==$row['id'])?'selected':'';
                          ?>
               <option <?=$sel?>  value="<?=$row['id']?>"><?=$row['name']?></option>
                        <?php } ?>
                        </select>
                        
                    
                    
                    &nbsp;<input name="action" class="btn" value="Search" type="submit">    
                
                
          </form>
        </div>

               <!-- Filter  -->

             <div class="ui container">
        <div class="ui grid">
          <div class="ui sixteen column">
            <div id="calendar"></div>
          </div>
        </div>
    </div>
      
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>
      <script>
          var today_date='<?php  echo date("Y-m-d"); ?>';

                $(document).ready(function() {
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      }, 
      defaultDate:today_date, // '2016-12-12'
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: <?php  echo json_encode($eventData); ?>
    });
    
  });     
       </script>
            <!-- <p>Datfddddddddddddd</p> -->



            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>