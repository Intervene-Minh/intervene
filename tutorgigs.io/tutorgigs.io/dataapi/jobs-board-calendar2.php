<?php
/**
@ /jobs-board-calendar2.php
*/
 @extract($_GET) ;
@extract($_POST) ;

include("header.php");
//////////Validate Site Access//////////
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

$login_role = $_SESSION['login_role'];
$page_name="Jobs Board Calendar";
//if($login_role!=0 || !isGlobalAdmin()){
//  header("location: index.php");
//}

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}




$error='';
$id = $_SESSION['ses_teacher_id'];

if(isset($_POST['delete-user'])){
  $arr = $_POST['arr-user'];
  if($arr!=""){
    //$query = mysql_query("DELETE FROM demo_users WHERE id IN ('$arr')", $link);
            
            //// Delete Role Table...
            $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
  }
        
        echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";
        ///
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");

//////////Calendar/////////

$sql="SELECT * FROM interv_sessions WHERE 1 ";

 if(isset($_GET['school_id'])&&$_GET['school_id']>0){
 $sql.=" AND school_id=".$_GET['school_id'];
 }

 if(isset($_GET['grade_id'])&&$_GET['grade_id']>0){
 $sql.=" AND grade_id=".$_GET['grade_id'];
 }

 //echo $sql;




//$sql.=" Limit 4 ";
$results=mysql_query($sql);
$eventData=array();$id_arr=array();
  while($row = mysql_fetch_assoc($results) ) {
    $sesid=$row['id'];
    $parent=($row['parent_id']>0)?$row['parent_id']:$row['id'];
    $ses_start_time=$row['ses_start_time'];
    $ses_end_time=$row['ses_end_time'];
    $URL="https://tutorgigs.io/dashboard/Jobs-Board-List.php?id=1171-".$row['id'];
     $eventData[] = array('title'=>'Session:'.$parent,'id'=>$sesid,
      'start'=>$ses_start_time,'end'=>$ses_end_time,'url'=>$URL);

     $start_date=date('Y-m-d',strtotime($row['ses_start_time']));
     
     /////View detail//
     $id_arr[]=array('id'=>$row['id'],'start'=>$start_date); //$row['id']
     


  }

//echo '<pre>'; print_r($eventData); die;

// Repeat Sessions//////////// 
// $eventData[] = array('title'=>'Demo session',
//       'start'=>'2018-12-11','end'=>'2018-12-14');
 


///////parent Session /////
 $parent_sql=mysql_query("SELECT parent_id, count( id ) totses
FROM `interv_sessions`
WHERE parent_id >0
GROUP BY parent_id");



 //echo mysql_num_rows($parent_sql).'=Repeat Sessions' ; 
  if(!isset($_GET['grade_id'])&&!isset($_GET['school_id'])){  
 if(mysql_num_rows($parent_sql)>0){
  while($row = mysql_fetch_assoc($parent_sql) ) {
    $session=mysql_fetch_assoc(mysql_query("SELECT * FROM interv_sessions WHERE id=".$row['parent_id']));
    $total_ses=$row['totses']+1;//1 self
     $Date=$session['ses_start_time'];
   $start_date=date('Y-m-d',strtotime($session['ses_start_time']));
  $end_date=   date('Y-m-d', strtotime($Date. ' + '.$total_ses.' days'));  //die;
    $eventData[] = array('title'=>'ID:'.$session['id'],
      'start'=>$start_date,'end'=>$end_date);

    


  }

}
}  //Search   if(!isset($_GET['grade_id'])&&!isset($_GET['school_id'])){  


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
    $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
    
    ////////////////
     $(document).ready(function () {

        $('#districtqzz').chosen();

        $('#districtqzz').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', 
                    school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#districtqzz').change();
    });
    
   ///22
   $(document).ready(function () {

        $('#distric444').chosen();

        $('#distric444').change(function () {
            district = $(this).val();

            //$('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', 
                    school_id: ''},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#distric444').change();
    });
    
    
    
    
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" rel='stylesheet' >
        <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" rel='stylesheet'  >
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>    <!-- /#sidebar -->
            <div id="content" class="col-md-8">

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
                $(document).ready(function() {
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      defaultDate: '2018-12-12',
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events:<?php  echo json_encode($eventData); ?>
    });
    
  });     
       </script>
            <!-- <p>Datfddddddddddddd</p> -->



            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>    <!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>