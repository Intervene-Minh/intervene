<?php
// Add Quiz|edit|duplicat
/****
Quiz: step 1
1. Choose Grade
2 . select lesson  ONLY
$step_1_url="quiz_step1.php";

$step_2_url="quiz_step2.php";

$step_3_url="quiz_step2_new.php";
$step_4_url="quiz_step3.php";// Arrange Questions

**/
 //echo 'call';  //
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");

// if ($_SESSION['login_role'] != 0) { //not admin
//     header('Location: folder.php');
//     exit;
// }

$error = '';

// // not required
// if (count($_SESSION['qn_list']) <= 0) {
//     $error = 'Please select questions to create assesment!';
// }


///Postto Choose Folder//
 
    if(isset($_POST['qz_submit'])) {

        $_SESSION['ses_quiz_grade']=$_POST['grade'];
        $_SESSION['ses_quiz_lesson']=$_POST['lesson'];  // lesson
      
        $les=mysql_fetch_assoc(mysql_query("SELECT id,objective_id FROM `master_lessons` WHERE id=".$_POST['lesson']));
       
        $_SESSION['ses_quiz_objective']=$les['objective_id'];  // objective_id
        $step_2_url="quiz_step2.php?taxonomy=".$_POST['grade'];
         header('Location:'.$step_2_url);  exit;
}
// Add Quiz

$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['id']);
    $result_qz= mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($result_qz['objective_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN int_quiz_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.quiz_id= \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_SESSION['assess_id']);
    $result_qz= mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}

$district_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id= \'' . $a_id . '\' AND access_level = "district" ');
$assessment_district = array();
while ($district = mysql_fetch_assoc($district_level_res)) {
    $assessment_district[] = $district['entity_id'];
}
$school_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id= \'' . $a_id . '\' AND access_level = "school" ');
$assessment_school = array();
while ($school = mysql_fetch_assoc($school_level_res)) {
    $assessment_school[] = $school['entity_id'];
}

$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
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
            </div>		<!-- /#sidebar -->
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
      defaultDate: '2016-12-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2016-12-01'
        },
        {
          title: 'Long EventX',
          start: '2016-12-07',
          end: '2016-12-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2016-12-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2016-12-16T16:00:00'
        },
        {
          title: 'Session 34',
          start: '2016-12-11',
          end: '2016-12-13'
        },

        {
          title: 'Session 2',
          start: '2016-12-11',
          end: '2016-12-14'
        },
        {
          title: 'Meeting',
          start: '2016-12-12T10:30:00',
          end: '2016-12-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2016-12-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2016-12-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2016-12-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2016-12-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2016-12-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'https://google.com/',
          start: '2016-12-28'
        }
      ]
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