<?php
    /*
        @intervene
        @https://intervene.atlassian.net/browse/IIDS-162
        {After clicking on a tutoring session from the calendar, 
        the session details show up but the link to Group world is missing. 
        }
        $val1 = date("Y-m-d H:i:s"); 
    */

    $arrTypeTutoring=array('intervene'=>'Intervene','drhomework'=>'Drhomework' );
    $arrBoardResource=array('groupworld','newrow');
    //2 Type Board Resource Allowd in System.

    @extract($_GET);
    @extract($_POST);

    define("TUTOR_BOARD", "groupworld");
    include("header-k.php");

    $login_role = $_SESSION['login_role'];
    $page_name="List of Tutor Sessions";

    ///////////////////////////////

    if(isset($_GET['del_id'])) {
        // Can session , delete detail        
        $getid=$_GET['del_id'];
        $sql="SELECT * FROM int_schools_x_sessions_log WHERE 1";
        $sql.=" AND id='$getid' ";
        $ses_det=mysql_fetch_assoc(mysql_query($sql));
        $school_id=$ses_det['school_id'];
        $school=mysql_fetch_assoc(mysql_query("SELECT * FROM schools WHERE SchoolId=".$school_id));

        $d1=mysql_query(" DELETE FROM int_slots_x_student_teacher WHERE slot_id=".$getid);
        $d2=mysql_query(" DELETE FROM int_schools_x_sessions_log WHERE id=".$getid);
        
        $remain_ses=intval($school['avaiable_slots'])+1;
        $a=mysql_query(" UPDATE schools SET avaiable_slots='".$remain_ses."' WHERE SchoolId='".$school_id."' "); //+1
        
        echo "<script>alert('Record deleted..');location.href='list-tutor-sessions.php';</script>"; 
    }

    $error=''; 
    $id = $_SESSION['login_id'];

    if(isset($_POST['delete-user'])){
	    $arr = $_POST['arr-user'];
	    if($arr!=""){		
            $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
	    }        
        echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";
        ///        
    }

    $schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>


<style type="text/css">
    #techtest {
        color: red;
        font-size: 16px;
        margin-left: 115px;
        border: 1px solid;
    }
    .card-header {
        padding-right: 0px;
    }
    .card-footer {
        padding-right: 10px;
    }
    .card-text {
        height: 100px;
        overflow: auto;
    }
</style>



<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<!--<div id="sidebar" class="col-md-3" style="transition-duration: 1s;">
				<?php //include("sidebar.php"); ?>
            </div>		
             /#sidebar -->            
            <div id="sessionCardsContainer" class="col-md-12" style="transition-duration: 1s;">
                <?php
                    $datePresent = date("Y-m-d H:i:s");
                    // check for past, present, future filters
                    $action = 'present';
                    $filter_ses_start_time = " AND ses.start_date = CURDATE() ORDER BY ses.ses_start_time ASC";
                    if (isset($_GET['ppf'])) {
                        $action = $_GET['ppf'];
                        if ($action == "past") {
                            $filter_ses_start_time = " AND ses.start_date > (CURDATE() - 14) AND ses.start_date < CURDATE() ORDER BY ses.ses_start_time DESC";
                        } else
                        if ($action == "future") {
                            $filter_ses_start_time = " AND ses.start_date > CURDATE() ORDER BY ses.ses_start_time ASC";
                        } 
                    }
                    
                    $strQuerySessions = "SELECT 
                    ses.*,
                    tut.id as tut_id,
                    tut.f_name as tut_first_name,
                    tut.lname as tut_last_name,
                    tut.url_aww_app as tut_url,
                    nru.newrow_user_id,
                    nru.newrow_room_id,
                    les.name as lesson_name,
                    sc.SchoolName as school_name
                    FROM 
                        int_schools_x_sessions_log ses
                    JOIN gig_teachers tut ON
                        tut.id = ses.tut_teacher_id
                    JOIN newrow_room_users nru ON
                        nru.intervene_user_id = ses.tut_teacher_id
                    JOIN lessons les ON
                        les.id = ses.lesson_id
                    JOIN schools sc ON
                        sc.SchoolId = ses.school_id
                    WHERE 1
                        $filter_ses_start_time
                    ";
                    //echo $strQuerySessions; die();
                    // Gather Sessions Tutor info List into array    
                    $resultSessions = mysql_query($strQuerySessions) or die(mysql_error());     
                    while($sess_row = mysql_fetch_assoc($resultSessions)) {  
                        $sessions[$sess_row['id']] = $sess_row;
                    }
                    foreach ($sessions AS $session) {
                        // Gather Students info for each Session into the subarray 
                        $studentQuery = "SELECT nru.id, 
                            nru.newrow_user_id, nru.newrow_room_id, 
                            nru.ses_tutoring_id, nru.intervene_user_id, 
                            nru.user_type ,
                            s.first_name,
                            s.last_name,
                            s.id as student_id
                            FROM `newrow_room_users` nru 
                            JOIN students s ON
                            s.id = nru.intervene_user_id
                            WHERE 
                            ses_tutoring_id = ".$session['id']." 
                            AND 
                            nru.user_type = 'student'";
                        $resultStudents =  mysql_query($studentQuery) or die(mysql_error());                         
                        while ($students = mysql_fetch_assoc($resultStudents)) {
                            $sessions[$session['id']]['students'][] = $students;                            
                        }
                    }                    
                    //$resultNRroom = mysql_query("SELECT newrow_room_id FROM `newrow_rooms` WHERE `ses_tutoring_id` ='".$session['id']."'");
                    //$newrow_room_id=mysql_fetch_assoc();
                    //$int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));
                    //$tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                    //$tot_std=($tot_std>0)?$tot_std:"XX";
                    //$Tutor= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname,url_aww_app FROM gig_teachers WHERE id=".$row['tut_teacher_id']));         
                    //$int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));                    
                ?>
                <nav class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="javascript:void(0)" onclick="window.location.href='list-tutor-sessions.php'" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar">
                        <i id="btnMenu" class="fa fa-bars" style="color: orange" title="Toggle Menu"> </i>&nbsp; &nbsp;
                        Tutor Sessions (<span><?=mysql_num_rows($resultSessions) ?></span>)
                    </a>  
                    <!-- Filters -->                  
                    <div class="btn-group pull-right" role="group">
                        <form id="formFilter" method="GET">
                            <input id="ppf" type="hidden" name="ppf" value="">
                            <button type="button" 
                                value="past" 
                                class="btn btn-outline-secondary <?=$action == 'past' ? 'active' : ''?>" 
                                onclick="$('#ppf').val('past'); $('#formFilter').submit()">Past</button>
                            <button type="button" 
                                value="present" 
                                class="btn btn-outline-secondary <?=$action == 'present' ? 'active' : '' ?>" 
                                onclick="$('#ppf').val('present'); $('#formFilter').submit()">Present</button>
                            <button type="button" 
                                value="future" 
                                class="btn btn-outline-secondary <?=$action == 'future' ? 'active' : '' ?>" 
                                onclick="$('#ppf').val('future'); $('#formFilter').submit()">Future</button>
                            <!--<div class="btn-group" role="group">
                                <button id="btnFilterGroup" type="button" 
                                class="btn btn-outline-secondary dropdown-toggle" 
                                data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false"></button>
                                <div class="dropdown-menu" aria-labelledby="btnFilterGroup">
                                    <a class="dropdown-item disabled"  href="#">Cancelled</a>
                                    <a class="dropdown-item disabled"  href="#">Un-assigned</a>
                                </div>
                            </div>-->
                        </form>                        
                        <!-- search and layout -->                
                        <!--<div class="btn-group" role="group" >   
                            <button type="button" disabled class="btn btn-link active">Search</button>
                            <input type="text" class="form-control" style="min-width: 150px;" placeholder="ie., session id">
                            <button type="button" class="btn btn-outline-dark" title="Card view layout"><i class="fas fa-th"></i></button>
                            <button type="button" class="btn btn-outline-dark" title="Table view layout"><i class="fas fa-bars"></i></button>                    
                        </div>-->
                    </div>
                </nav>
                <div class="card-deck">
                    <?php
                       foreach ($sessions AS $sess_row) {
                    ?>
                        <div id="session_<?=$sess_row['id']; ?>" class="card" style="min-width: 200px; margin-bottom: 20px; margin-top: 20px;">   
                            <div class="card-header">
                                <small class="text-muted">
                                    <i class="fas fa-chalkboard-teacher"></i> <?=$sess_row['id'] ?>                                
                                    <div class="dropdown" style="display:inline-flex; float: right;">
                                        <div class="btn-group">
                                            <a class="btn btn-default btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <?php
                                                // Gather newrow_room_id for joining as observer
                                                $newrow_room_id = mysql_fetch_assoc(mysql_query("SELECT newrow_room_id FROM `newrow_rooms` WHERE `ses_tutoring_id` ='".$sess_row['id']."'"));
                                            ?>
                                            <ul class="dropdown-menu">
                                                <h6 class="dropdown-header">Join as</h6>
                                                <li>                                                                                                      
                                                    <a title="Join as Observer" 
                                                        target="_blank" 
                                                        href="https://tutorgigs.io/myadmin/add-observer.php?roomID=<?=$newrow_room_id['newrow_room_id'] ?>"                                                             
                                                        class="dropdown-item">
                                                        <i class="far fa-eye"></i>&nbsp;&nbsp; Observer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a title="Join as Instructor" 
                                                        target="_blank" 
                                                        href="moderator_join_room.php?sesid=<?=$sess_row['id'] ?>"                                                             
                                                        class="dropdown-item">
                                                        <i class="fas fa-user-graduate" style="color: orange;"></i>&nbsp;&nbsp;Instructor
                                                    </a>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <h6 class="dropdown-header">Tutor</h6>
                                                <li>
                                                    <?=$Tutor['f_name']." ".$Tutor['lname']?>                                                    
                                                    <a title="Re-assign/Un-assign this tutor" 
                                                        target="_blank" 
                                                        href="javascript:void(0)"
                                                        onclick="sent_form('assign_a_tutor.php', {getid:'<?=$sess_row['id']?>',productname:'101',detail:'this is a text.'});"                                                             
                                                        class="dropdown-item">
                                                        <i class="fas fa-user-graduate" ></i>&nbsp;&nbsp;Re-assign
                                                    </a>                                                    
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <h6 class="dropdown-header">Session</h6>
                                                <li>
                                                    <a title="View session info and details"
                                                        class="dropdown-item viewSession" 
                                                        href="javascript:void(0);" 
                                                        SessionID="<?=$sess_row['id']?>"
                                                        action="<?=$Sessiontype='Intervention';?>">                                                        
                                                        <i class="fa fa-info-circle text-info"></i> info & details
                                                    </a>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <li>
                                                    <a title="Send ALL to a new room or drag'n'drop"
                                                        class="dropdown-item btnrelocate text-danger" 
                                                        href="javascript:void(0);" 
                                                        SessionID="<?=$row['id']?>">
                                                        <i class="fas fa-users"></i> <i class="fas fa-random"></i> new room
                                                    </a>
                                                </li>
                                                <li>
                                                    <a title="... or you may drag and drop students individually to another session to add them to that session's room"
                                                        disabled class="dropdown-item disabled" 
                                                        href="javascript:void(0);" SessionID="<?=$row['id']?>">... or drag'n'drop</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </small> 
                                <small class="text-muted">
                                    <strong><?=date_format(date_create($sess_row['ses_start_time']), 'D M jS');?></strong>
                                    
                                </small>                               
                                <span class="badge badge-primary"><?=date_format(date_create($sess_row['ses_start_time']), 'h:i a');?></span>
                            </div>
                            <div class="card-body" ondrop="drop(event)" 
                                    ondragover="allowDrop(event)" >
                                <h5 class="card-title">
                                    <i class="fas fa-chalkboard"></i> <?
                                        $class = mysql_fetch_assoc(mysql_query("SELECT class_name FROM classes WHERE grade_level_id = ".$sess_row['grade_id']." LIMIT 1"));
                                        echo $class['class_name'];
                                    ?>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-school"></i> <?=$sess_row['school_name']?></h6>
                                <div class="card-text" style="border-top: 1px 0 0 1px dotted; border-color: gray;" sessionid="<?=$sess_row['id']; ?>" nrroomid="<?=$sess_row['newrow_room_id']?>">
                                    <div 
                                        id="tutor_session_<?=$sess_row['tut_id'] ?>_<?=$sess_row['id']; ?>" 
                                        title="user id: <?=$sess_row['tut_id'] ?>, nr user id: <?=$sess_row['newrow_user_id']?> - nr room id: <?=$sess_row['newrow_room_id']?>"
                                        usertype="tutor"
                                        sessionid="<?=$sess_row['id']; ?>"
                                        userid="<?=$sess_row['tut_id']; ?>"
                                        username="<?=$sess_row["tut_first_name"]." ".$sess_row["tut_last_name"]; ?>"
                                        nruserid="<?=$sess_row['newrow_user_id']?>"
                                        nrroomid="<?=$sess_row['newrow_room_id']?>" 
                                        draggable="true" 
                                        ondragstart="drag(event)"
                                    >
                                        <i class="fa fa-user-graduate" style="color: orange;"></i> &nbsp;<?=$sess_row["tut_first_name"]." ".$sess_row["tut_last_name"] ?>
                                        
                                        <i class="fas fa-handshake <?= !empty($sess_row['app_url']) ? 'text-success': 'text-danger'; ?>" title="Tutor <?= !empty($sess_row['app_url']) ? 'Accepted': 'has not accepted'; ?>"></i>
                                    </div>    
                                    <?php
                                        foreach ($sess_row['students'] AS $student_row) {                                        
                                    ?>                            
                                        <div 
                                            id="student_session_<?=$student_row['student_id']?>_<?=$sess_row['id']; ?>" 
                                            title="user id: <?=$student_row['student_id']?>, nr user id: <?=$student_row['newrow_user_id']?> - nr room id: <?=$student_row['newrow_room_id']?>"
                                            usertype="student"
                                            sessionid="<?=$sess_row['id']; ?>"
                                            userid="<?=$student_row['student_id']; ?>"
                                            username="<?=$student_row['first_name'].' '.$student_row['last_name']; ?>"
                                            nruserid="<?=$student_row['newrow_user_id']?>"
                                            nrroomid="<?=$student_row['newrow_room_id']?>"
                                            draggable="true" 
                                            ondragstart="drag(event)" 
                                        >
                                            <i class="fa fa-user" style="color: darkblue;"></i> &nbsp; 
                                            <?=$student_row['first_name'].' '.$student_row['last_name']?>                     
                                        </div>
                                    <?php
                                        }
                                    ?>                      
                                </div>
                            </div>
                            <div class="card-footer">
                                <div style="float:left;">
                                    <small class="text-muted badge badge-default" title="The class size is <?=count($sess_row['students']) ?>"><?=count($sess_row['students']) ?>&nbsp;<i class="fas fa-users"></i></small>
                                    <!--<small class="text-muted">nr_rm_id:<?=$sess_row['newrow_room_id']?></small>-->
                                    <!--<small class="text-muted">
                                        <?=date_format(date_create($sess_row['ses_start_time']), 'D M jS');?>
                                        <?=date_format(date_create($sess_row['ses_start_time']), 'h:i a');?>
                                    </small>-->
                                </div>
                                <span style="float:right;">
                                    <a title="View session info and details"
                                        class="viewSession" 
                                        href="javascript:void(0);" 
                                        SessionID="<?=$sess_row['id']?>"
                                        action="<?=$Sessiontype='Intervention';?>">                                                        
                                        <i class="fas fa-info-circle text-info"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    <?php
                       } 
                    ?>                
                </div>
            </div>
            <div id="content" class="col-md-12" style="display: none;">
                <?php include("msg_inc_1.php"); ?>
                <div class="table-responsive">
                    <?php
                        $start_date = date("Y-m-d H:i:s");
                        
                        $sql_ses=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                        if ($_GET['s_id'] > 0) {
                            $sql_ses.=" AND id = '".$_GET['s_id']."'";  
                        }
                        // $sql_ses.=" AND tut_status='STU_ASSIGNED' ";//Live
                        $sql_ses.=" AND ses_start_time>'".$start_date."'";
                        $sql_ses.=" ORDER BY ses_start_time ASC LIMIT 40";
                
                        $session_type=(isset($session_type))?$session_type:"upcoming" ;
                        if (isset($_GET['action'])&&$_GET['action']=="Search") {                           
                            if ($session_type=="past") {
                                $sql_ses=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                            
                                $sql_ses.=" AND ses_start_time<'".$start_date."'";
                                $sql_ses.=" ORDER BY ses_start_time DESC LIMIT 40"; 
                               
                            } elseif ($session_type=="upcoming") {
                                $sql_ses=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";
                                $sql_ses.=" AND ses_start_time>'".$start_date."'";
                                $sql_ses.=" ORDER BY ses_start_time ASC LIMIT 40";   
                            } elseif ($session_type=="all"){
                                $sql_ses=" SELECT * FROM int_schools_x_sessions_log WHERE 1 ";

                                if($_GET['s_id'] > 0) {
                                    $sql_ses.=" AND id = '".$_GET['s_id']."'";  
                                }
                                $sql_ses.=" ORDER BY ses_start_time ASC LIMIT 40";  
                            }
                        }
                    ?>                    
                    <form id="search-users" method="GET" action="">
						<table class="table">
							<tbody>
                                <tr>
                                    <td>
                                        <label>Filter:</label>
                                    </td>		
                                    <td>
                                        <select name="session_type">
                                            <option value="all" <?php echo (isset($session_type)&&$session_type=="all")?'selected':NULL; ?> >All</option>
                                            <option value="upcoming" <?php echo (isset($session_type)&&$session_type=="upcoming")?'selected':NULL; ?> >Upcoming sessions</option>
                                            <option value="past" <?php echo (isset($session_type)&&$session_type=="past")?'selected':NULL; ?>>Past sessions</option>                                    
                                        </select>
                                        &nbsp;
                                        <input name="action" class="btn" value="Search" type="submit"> 
                                        <a target="_blank" href="https://tutorgigs.io/techtest/" id="techtest" class="btn text-danger">Technology Test</a>                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
					</form>
				</div>    
                <?php                        
                    //// $limit
                    $results = mysql_query($sql_ses);
                    $tot_record=mysql_num_rows($results);
                ?>
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3><?=$page_name?>(<?=$tot_record?>)</h3>
                    </div>		
                    <!-- /.ct_heading -->
					<div class="clear">
						<?php
                            if ($error != '') {
                                echo '<p class="error">'.$error.'</p>';
                            } else {             
                        ?>
						<table class="table-manager-user col-md-12">
                            <colgroup>									
                                <col width="190">									
                                <col width="220">
                                <col width="120">									
                                <col width="155">
                            </colgroup>
                            <tr>									
                                <th>Session Date/Time</th>
                                <th>Detail</th>                                                                    
                                <th> Status</th>  
                                <th>Session details</th>
							</tr>
						    <?php
                                if( mysql_num_rows($results) > 0 ) {
                                    while( $row = mysql_fetch_assoc($results) ) {
                                        /* get new row room ID*/
                                        $newrow_room_id=mysql_fetch_assoc(mysql_query("SELECT newrow_room_id FROM `newrow_rooms` WHERE `ses_tutoring_id` ='".$row['id']."'"));
                                        $int_th= mysql_fetch_assoc(mysql_query("SELECT id,first_name,last_name FROM users WHERE id=".$row['teacher_id']));
                                        $tot_std=mysql_num_rows(mysql_query("SELECT * FROM int_slots_x_student_teacher WHERE slot_id=".$row['id']));
                                        $tot_std=($tot_std>0)?$tot_std:"XX";
                                        $Tutor= mysql_fetch_assoc(mysql_query("SELECT id,f_name,lname,url_aww_app FROM gig_teachers WHERE id=".$row['tut_teacher_id']));         
                                        $int_school= mysql_fetch_assoc(mysql_query("SELECT district_id,SchoolName FROM schools WHERE SchoolId=".$row['school_id']));     
                                        // district_id 
                                        if($int_school['district_id']>0) {
                                            $district=mysql_fetch_assoc(mysql_query(" SELECT  district_name FROM loc_district WHERE id=".$int_school['district_id']));     
                                            $districtName=$district['district_name'];
                                        }
          
                                        /// inAdmin Info SELECT * FROM `users` WHERE 1 
                                        $admin=mysql_fetch_assoc(mysql_query(" SELECT * FROM `users` WHERE id=1 ")); // Def
          
                                        // Exp time
                                        $sesStartTime=$row['ses_start_time'];
                                        $curr_time= date("Y-m-d H:i:s");
         
                                        $in_sec= strtotime($sesStartTime) - strtotime($curr_time);///604800 #days>+7 days
         
                                        $quiz= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$row['quiz_id']));  
                                        $quiz['objective_name']=(!empty($quiz['objective_name']))?$quiz['objective_name']:"NA";
                                        //// list of students 
                                        $q=" Select sd.middle_name,sd.first_name,ses.* FROM int_slots_x_student_teacher ses LEFT JOIN students sd ON ses.student_id =sd.id ";
                                        $q.=" WHERE ses.slot_id='".$row['id']."' ";
                                        $resss=mysql_query($q);
                                        $stud_str=array(); // middle_name
                                        while ($row2=mysql_fetch_assoc($resss)) {
                                            $stud_str[]=$row2['first_name'].' '.$row2['middle_name'];
                                        }  
                                        $stdList=(count($stud_str)>0)? implode(",", $stud_str):"NA";
                                        
                                        ## lesson in session. ##
                                        $lesson_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `master_lessons` WHERE id=".$row['lesson_id']));
                                        $lesson_download="https://intervene.io/questions/uploads/lesson/".$lesson_det['file_name'];// 4358Question.pdf.

                                        //////Type Tutoring- Client:: 10 -Sept-2019 /////////////
                                        if(!empty($row['Tutoring_client_id'])&&$row['Tutoring_client_id']=='Drhomework123456') {
                                            $Sessiontype='Drhomework';
                                        } else {
                                            $Sessiontype='Intervention';
                                        }

                                        $ses_det_url='session-details.php?sid='.$row['id'];          
                            ?>      <!-- still inside php while loop -->                            
                            <tr>
                                <!-- Session Date/Time Column -->
                                <td> 
                                    <?php  //= (!empty($Tutor['url_aww_app']))?$Tutor['url_aww_app']: 'Board URL empty';?>
                                    <span>
                                        <?=date_format(date_create($row['ses_start_time']), 'F d,Y');?>
                                        <br>  
                                    </span>
                                    <a href="<?=$ses_det_url?>" class="btn btn-success btn-xs" title="Start Time">
                                        <?=date_format(date_create($row['ses_start_time']), 'h:i a');?>
                                    </a> 
                                    <br/>
                                    <br> 
                                    <span>
                                        <strong class="text-primary">Class From:</strong> 
                                    </span>
                                    <?php  
                                        if($row['Tutoring_client_id']=='Drhomework123456') { 
                                    ?>
                                            <span class="btn btn-danger btn-xs"> Homework Help </span> 
                                    <?php 
                                        }
                                    ?>
                                    <?php  
                                        if($row['Tutoring_client_id']=='Intervene123456'){ 
                                    ?>
                                            <span class="btn btn-primary btn-xs">Intervention</span> 
                                    <?php 
                                        }
                                    ?>
                                    <a class="btn btn-danger btn-xs viewSession" href="javascript:void(0)" 
                                        SessionID="<?=$row['id']?>"
                                        action="<?=$Sessiontype?>">Session Detail & Downloads</a> 
                                    <br>
                                    <br>
                                    <?php
                                        if($row['board_type']=='groupworld') {
                                    ?>
                                            <a title="Join as observer Groupworld" target="_blank" 
                                                href="<?=$Tutor['url_aww_app']?>" 
                                                class="btn btn-danger btn-xs">
                                                Join as observer Groupworld
                                            </a>
                                    <?php 
                                        } else { 
                                    ?>
                                            <a title="Join as observer Newrow Room" 
                                                target="_blank" 
                                                href="https://tutorgigs.io/myadmin/add-observer.php?roomID=<?php echo $newrow_room_id['newrow_room_id']?>" 
                                                class="btn btn-danger btn-xs">
                                                Join as observer Newrow Room
                                            </a>
                                    <?php 
                                        }
                                    ?>
                                    <br/>
                                    <br/>
                                    <?php 
                                        $mod_url="moderator_join_room.php?sesid=".$row['id']; 
                                    ?>
                                    <a target="_blank" class="btn btn-danger btn-xs" 
                                        href="<?=$mod_url?>">
                                        Join as instructor
                                    </a>
                                    <br/>
                                    <br/>
                                </td>
                                <!-- Detail Column -->
                                <td style="overflow: visible;">
                                    <a class="btn btn-danger btn-xs viewSession" 
                                        href="javascript:void(0)" 
                                        SessionID="<?=$row['id']?>"
                                        action="<?=$Sessiontype?>">
                                        See Details
                                    </a>
                                    <br/>
                                    <div class="dropdown">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Relocate ...
                                            </button>
                                            <ul class="dropdown-menu">
                                                <!--<li><a class="dropdown-item btnadduser" href="javascript:void(0);">adduser ...</a></li>
                                                <li><a class="dropdown-item btnadduser2room" href="javascript:void(0);" >adduser2room ...</a></li>
                                                <li><a class="dropdown-item btngetboardurl" href="javascript:void(0);">getboardurl ...</a></li>
                                                <li><a class="dropdown-item btngethtml" href="javascript:void(0);" >gethtml ...</a></li>
                                                <li><a class="dropdown-item btncreateroom" href="javascript:void(0);" >createroom ...</a></li>-->
                                                <li><a class="dropdown-item btnrelocate" href="javascript:void(0);" SessionID="<?=$row['id']?>">to New Room ...</a></li>
                                                <li><a disabled class="dropdown-item btnrelocate" href="javascript:void(0);" SessionID="<?=$row['id']?>">to Existing Room ...</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td> 
                                <!-- Status Column -->
                                <td>
                                    <?php
                                        $status='<span class="btn btn-warning btn-xs">Session expired!</span>';
                                        if ($in_sec < -3600) { // till 1 hour can
                                            //echo $in_sec.'-Exp session <br/>';
                                            echo $status;
                                        } else {
                                            if ($row['tut_teacher_id'] > 0) {
                                    ?>  
                                    <?php 
                                                if (!empty($row['app_url'])) { // ACCEPTED
                                    ?>
                                                    <br/>
                                                    <span class="btn btn-success btn-xs">
                                                        Session Assigned
                                                    </span>
                                    <?php  
                                                } else { // -waiting for Acceptance
                                    ?>
                                                    <span class="btn btn-warning btn-xs">
                                                        Session Assigned
                                                    </span>
                                    <?php  
                                                } 
                                    ?>
                                                <strong class="text-primary">
                                                    AssignedTo:
                                                </strong> 
                                                <?=$Tutor['f_name']." ".$Tutor['lname']?>
                                                <br/>
                                                <a href="javascript:void(0);" class="text-danger"
                                                    onclick="sent_form('assign_a_tutor.php', {getid:'<?=$row['id']?>',productname:'101',detail:'this is a text.'});"
                                                    style="text-decoration:underline;">
                                                    Re-assign Tutor
                                                </a> 
                                    <?php 
                                            } else {
                                    ?>
                                                <span class="btn btn-danger btn-xs">
                                                    <?=$status_arr['SES_NOT_ASSIGNED']?>
                                                </span>
                                                <a href="javascript:void(0);" 
                                                    onclick="sent_form('assign_a_tutor.php', {getid:'<?=$row['id']?>',productname:'58',detail:'this is a text.'});"
                                                    style="text-decoration:underline;">
                                                    Assign Tutor
                                                </a>
                                    <?php 
                                            } 
                                        }
                                    //exp
                                        $sesFrom=(!empty($row['Tutoring_client_id']) && $row['Tutoring_client_id']=='Drhomework123456')?'Homework Help':'intervene';
                                        $se_det_url='';
                                        $sesFromClass=(!empty($row['Tutoring_client_id'])&&$row['Tutoring_client_id']=='Drhomework123456')?'btn btn-danger btn-xs':'btn btn-primary btn-xs';
                                    ?>
                                    </td>
                                    <!-- Session Details Column -->
                                    <td>
                                        <span class="btn btn-primary btn-xs">
                                            Virtual board:<?=ucfirst($row['board_type']) ?> 
                                        </span> 
                                        <br/>
                                        <strong class="text-primary">
                                            Create Date:
                                        </strong>
                                        <br/>   
                                        <?=date_format(date_create($row['created_date']), 'F d,Y');?> 
                                        <br/>
                                        <strong class="text-primary">
                                            Session:
                                        </strong>
                                        incomplete
                                        <br/>
                                        <strong class="text-primary">
                                            Session id:
                                        </strong>
                                        <?=$row['id']?>
                                        <br/>
                                        <!--
                                            <strong  title="Braincert class" class="text-primary">
                                                Class id:
                                            </strong>
                                            <?php //=$row['braincert_class']?>
                                            <br/>
                                        -->
                                        <a href="<?=$ses_det_url?>"
                                            style="text-decoration:underline;">
                                            View
                                        </a> 
                                        &nbsp;
                                        <?php 
                                            if ($in_sec > 0) {
                                        ?>
                                        <?php 
                                                if($row['Tutoring_client_id']!='Drhomework123456') { 
                                        ?>
                                                    <a title="Delete,This session"
                                                        href="list-tutor-sessions.php?del_id=<?=$row['id']?>" 
                                                        class="btn btn-danger btn-xs">
                                                        Delete
                                                    </a>
                                        <?php 
                                                } else {
                                        ?>
                                                    <a onclick="alert('You can not delete Homework Help,Only Parent allowed for unclaimed job! ')"  
                                                        class="btn btn-danger btn-xs">
                                                        Delete
                                                    </a>
                                        <?php 
                                                } 
                                        ?>
                                        <?php 
                                            }
                                        ?>
                                    </td>
                            </tr>
							<?php
									} // end of while loop
								} else {
									echo '<div class="clear"><p>There is no item found!</p></div>';
								} // end of if mysql_num_rows($results)
							?>
						</table>
						<?php } ?>
						<div class="clearnone">&nbsp;</div>
                    </div>
                    <!-- /.ct_display -->
                    <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>
			</div> <!-- ./div #content -->
			<div class="clearnone">&nbsp;</div>
		</div><!-- ./div row -->
	</div><!-- ./div container -->
</div><!-- / main -->



<!-- Modal - View Details -->
<div class="modal fade" id="ViewDetails" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Details Session ID: <span class="session-info"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="dataview">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Relocation Result -->
<div class="modal fade" id="viewRelocate" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Relocate Details<span></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class=" dataview">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!--
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
-->

<!-- Document Ready -->
<script>
	$(document).ready(function(){
		$('#delete-user').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to delete '+count+' user?');
		});
	});
    
    /////////////////      
    function sent_form(path, params, method) {
        method = method || "post"; // Set method to post by default if not specified.

        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);
        // form.setAttribute("target", "_blank");//

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>

<!-- JS Ajax Scripts -->
<script type="text/javascript">  
    // View Session Details Modal
    $('.viewSession').click(function() {
        var SessionID=$(this).attr('SessionID');
        var action = $(this).attr('action');
        $.ajax({
            type:'POST',
            data:{SessionID:SessionID,action:action},
            url:'https://tutorgigs.io/dashboard/get_session-ajax.php',
            success:function(data) {
                $('#ViewDetails').modal('show');
                $('.session-info').text(SessionID);
                $('.dataview').html(data);
            }
        });
    });
    // View Session Details Modal
    $('.btnadduser').click(function() {
        var SessionID=$(this).attr('SessionID');
        $.ajax({
            type:'POST',
            data:{'action': 'adduser'},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
    // View Session Details Modal
    $('.btnadduser2room').click(function() {
        var SessionID=$(this).attr('SessionID');
        $.ajax({
            type:'POST',
            data:{'action': 'adduser2room'},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
    $('.btngetboardurl').click(function() {
        var SessionID=$(this).attr('SessionID');
        $.ajax({
            type:'POST',
            data:{'action': 'getboardurl'},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
    $('.btngethtml').click(function() {
        var SessionID=$(this).attr('SessionID');
        $.ajax({
            type:'POST',
            data:{'action': 'gethtml'},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
    $('.btncreateroom').click(function() {
        var SessionID=$(this).attr('SessionID');
        $.ajax({
            type:'POST',
            data:{'action': 'create', 'sessionID' : SessionID},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
    $('.btnrelocate').click(function() {
        var resp = confirm('Are you sure you wish to Relocate Tutor and Students to a New Room?');
        if (resp == false) {return;}
        $('#viewRelocate').modal('show');
        $('.dataview').html('Relocating...');
        var SessionID=$(this).attr('SessionID');        
        $.ajax({
            type:'POST',
            data:{'action': 'relocate', 'sessionID' : SessionID},
            url:'https://tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {
                $('#viewRelocate').modal('show');
                $('.dataview').html(data);
            }
        });
    });
</script>

<!-- Drag and Drop -->
<script>
    function allowDrop(ev) {
        ev.preventDefault();
        /*if (ev.target.getAttribute("draggable") == "true")
            ev.dataTransfer.dropEffect = "none"; // dropping is not allowed
        else
            ev.dataTransfer.dropEffect = "all"; // drop it like it's hot*/
    }

    function drag(ev) {
        ev.dataTransfer.setData("isTutor", ev.target.id.indexOf("tutor") > -1 ? true : false);
        ev.dataTransfer.setData("xferID", ev.target.id);
        ev.dataTransfer.setData("sessionID", ev.target.getAttribute("sessionid"));
        ev.dataTransfer.setData("userID", ev.target.getAttribute("userid"));
        ev.dataTransfer.setData("userName", ev.target.getAttribute("username"));
        ev.dataTransfer.setData("nrUserID", ev.target.getAttribute("nruserid"));
        ev.dataTransfer.setData("nrRoomID", ev.target.getAttribute("nrroomid"));
        ev.target.style.border = 2;
    }

    function drop(ev) {
        ev.preventDefault();
        // details for move...
        let userType = ev.dataTransfer.getData("isTutor") == "true" ? 'tutor' : 'student';
        let sessionID = ev.dataTransfer.getData("sessionID");
        let userID = ev.dataTransfer.getData("userID");
        let userName = ev.dataTransfer.getData("userName");
        let nrUserID = ev.dataTransfer.getData("nrUserID");
        let nrRoomID = ev.dataTransfer.getData("nrRoomID");
        let jqCardTarget = $(ev.target).closest('.card-body').find(".card-text")[0];
        let xferID = ev.dataTransfer.getData("xferID");
        let targetSessionID = jqCardTarget.getAttribute("sessionid");
        let targetRoomID = jqCardTarget.getAttribute("nrroomid");
        // confirm the move...
        let isConfirmed = false;
        console.log(ev.target);
        isConfirmed = confirm("Are you sure you wish to move this "+userType+" "+userName+/*" @(session:"+sessionID+", room:"+nrRoomID+") to @(session: "+targetSessionID+", room: "+targetRoomID+*/" ?");
        if (isConfirmed == true) {
            $.ajax({
                type:'POST',
                data:{
                    'action': 'move', 
                    'userType': userType,
                    'userID': userID,
                    'nrUserID': nrUserID,
                    'sessionID' : sessionID,
                    'nrRoomID': nrRoomID,
                    'targetSessionID': targetSessionID,
                    'targetRoomID': targetRoomID
                },
                url:'https://tutorgigs.io/myadmin/k/newrow.php',
                success:function(data) {
                    $('#viewRelocate').modal('show');
                    $('.dataview').html(data);
                    if (data.indexOf("Success") > -1) {
                        if (ev.dataTransfer.getData("isTutor") == "true") {
                            jqCardTarget.insertBefore(document.getElementById(xferID), jqCardTarget.firstChild);
                        } else {
                            jqCardTarget.appendChild(document.getElementById(xferID));
                        }
                    }
                }
            });
        }
        // done...
    }
</script>

<!-- Sidebar Menu Toggle -->
<script>  /*  
    var savedSideBar = document.
    $('#btnMenu').click( (e) => {  
        let sidebar = $('#sidebar');
        let cardcont = $('#sessionCardsContainer');
        let delay = 1000;
        if ( $('#sessionCardsContainer').hasClass('col-md-9') ) {
            // expand card container, hide menu
            $.when(
               sidebar.css("clip-path", "inset(0px 300px 0px 0px)").delay(delay)
            ).done( (e) => {
                //sidebar.hide();
                cardcont.toggleClass('col-md-12').toggleClass('col-md-9');   
            });                   
        } else {
            // shrink card container, show menu
            $.when(                
                sidebar.css("clip-path", "inset(0px 0px 0px 0px)").delay(delay)
            ).done( (e) => {   
                //sidebar.show();
                cardcont.toggleClass('col-md-9').toggleClass('col-md-12');
            });
        }
    });*/
</script>

<!-- Insert Footer - THIS FILE IS MISSING IN THIS DIRECTORY -->
<?php include("footer-k.php"); ?>