<?php
    /**
        a. Students
        b. Objective
        c. Grade :XX
        d. Notes
        e. Exit quiz
    **/

    $error = '';
    $author = 1;
    $datetm = date('Y-m-d H:i:s');
    $today = date("Y-m-d H:i:s"); 
    $created = date('Y-m-d H:i:s');

    include("header.php");

    $user_id = $_SESSION['login_id'];
    $query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
    $rows = mysql_num_rows($query);
    if ($rows == 1) {
        $row = mysql_fetch_assoc($query);
        $school_id = $row['school'];
    }
    $error = '';
    
    // Add Students 
    if (isset($_GET['ses'])) {
        $master= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_schools_x_sessions_log WHERE id=".$_GET['ses'])); 
        @extract($master);
        $quiz_det= mysql_fetch_assoc(mysql_query(" SELECT grade_id FROM int_quiz WHERE id=".$quiz_id));  
        $sel_grade_id=$quiz_det['grade_id'];

        // gather data for attendance here while we have session id
        $strAttendanceQuery = "SELECT 
            sx.slot_id,
            ss.ses_start_time,
            ss.ses_end_time,
            nru.newrow_room_id,
            group_concat(nru.newrow_user_id) as nr_student_ids,
            group_concat(s.id ) as student_ids,
            group_concat(CONCAT(s.first_name,' ', s.last_name)) as student_names
            FROM 
                int_slots_x_student_teacher sx
            JOIN students s ON 
                sx.student_id=s.id
            JOIN newrow_room_users nru ON
                nru.ses_tutoring_id=sx.slot_id AND nru.intervene_user_id=s.id
            JOIN int_schools_x_sessions_log ss ON
                ss.id=sx.slot_id
            WHERE 
                sx.slot_id=".(int)$_REQUEST['ses']."
        ";
        $resultAttendance = mysql_fetch_assoc(mysql_query($strAttendanceQuery));

    } else {
        $error = 'Page not found';
    } 
    ////////////////////////    
    @extract($_POST);
    //XXXXXXXXXXXXXXX

    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1)
            return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnpqrstuvwxyz";
        $codeAlphabet .= "123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
        }

        return $token;
    }

    ///////////////////
    $teacher_grade_res = mysql_query("
        SELECT  
            GROUP_CONCAT( grade_level_id SEPARATOR ',' ) AS shared_terms
        FROM `techer_permissions`
        WHERE teacher_id = {$user_id} 
    ");
    $t_grades = mysql_fetch_assoc($teacher_grade_res);
    $teacher_grade = $t_grades['shared_terms'];
    if ($_GET['class_id'] > 0) {
        $edit_class = mysql_fetch_assoc(mysql_query('SELECT * FROM classes WHERE id = \'' . $_GET['class_id'] . '\' '));
    
        if ($edit_class['id'] != $_GET['class_id']) {
            $error = 'This is not valid class.';
        }
    }
?>
<style>
    iframe {
        width: 100%;
        border: none;
        height: 1000px;
    }    
</style>
<link href="js/enjoyhint/enjoyhint.css" rel="stylesheet">
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>    <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-clock-o"></i>Intervention Session Details</h3>
                    </div>    <!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_class" id="form_class" method="post"  enctype="multipart/form-data">
                            <!--   <h4>Intervention Session Details here:</h4> -->
                            <?php  
                                $curr_time= date("Y-m-d H:i:s"); 
                                $in_sec= strtotime($ses_start_time) - strtotime($curr_time);///604800  
                           
                                //  echo 'curr_Time-'.$curr_time;
                             
                                $sdate=date_format(date_create($ses_start_time), 'F d,Y');
                                $at_time=date_format(date_create($ses_start_time), 'h:i a');
                            ?>
                            <h4 style="color: #1b64a9; font-size:16px">Session Date/Time:&nbsp;<?=$sdate?> at <?=$at_time?>
                                <?php //=($in_sec>-3600)?NULL:",&nbsp;Can't Re-assign students, session expired";?>
                            </h4>    
                            <div class="add_question_wrap clear fullwidth">
                                <script>
                                    $(document).ready(function(){
                                        $("#gradeid").change(function(){
                                            var gjd = $("#gradeid").val();
                                        // alert('hghgh'+gjd); // alert(this.val()); 
                                            $.post("aj_grade_class.php",
                                            {
                                            gradid:gjd,
                                            city: "Duckburg"
                                            },
                                            function(data,status){
                                                $("#tclassid").html(data);
                                                //alert("Data: " + data + "\nStatus: " + status);
                                            });
                                        });
                                    });
                                </script>
                                <?php 
                                    // $master    
                                    // $sel_grade_id    
                                    // find student list 
                                    $res11= mysql_query(" SELECT student_id FROM int_slots_x_student_teacher WHERE slot_id=".$_GET['ses']);   
                                    $st_arr=array();
                                    while ($row= mysql_fetch_assoc($res11)) {
                                        $st_arr[]=$row['student_id'];
                                    }
                                
                                    $sel_class_id=$class_id;// master session
                                    if (isset($_GET['cl'])) {
                                        $clss_det= mysql_fetch_assoc(mysql_query(" SELECT grade_level_id FROM classes WHERE id=".$_GET['cl'])); 
                                        $sel_grade_id=$clss_det['grade_level_id'];
                                        $sel_class_id=$_GET['cl'];
                                        $st_arr=array();// re- assign
                                        //  Grade id .    
                                    } else {
                                 
                                    }
                                ?>
                                
                                <?php 
                                    // $master  $mater['lesson_id'] grade_id 
                                    $master_lessons=mysql_fetch_assoc(mysql_query(" SELECT * FROM master_lessons WHERE id=".$master['lesson_id']));
                                    $quiz_det=mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id=".$master['quiz_id']));
                                    $terms=mysql_fetch_assoc(mysql_query(" SELECT id,name FROM terms WHERE id=".$master['grade_id']));
                                    // 
                                    // echo $terms['name'];
                                ?>
                                <div id="textarea" style="display: block">
                                    <span> <strong class="text-primary">
                                        Grade:</strong> </span> <?=$terms['name']?> <br/>
                                   <span> <strong class="text-primary">
                                        Quiz:</strong> </span> <?=$quiz_det['objective_name']?> <br/>
                                   <span> <strong class="text-primary">
                                        Lesson:</strong> </span> <?=$master_lessons['name']?>  <br/>                                    
                                    <!-- <p class="text-success">  -->
                                    <span> <strong class="text-primary">
                                        Student List :</strong> </span> 
                                   <?php 
                                        $teacher_id = $_SESSION['login_id'];
                                        $ses_id=$_GET['ses'];
                                        $sql_student="SELECT 
                                            sx.slot_id,sx.student_id,s.id as sid,
                                            s.first_name,s.middle_name,s.last_name 
                                            FROM int_slots_x_student_teacher sx
                                            left join students s
                                            ON sx.student_id=s.id
                                            WHERE sx.slot_id= '$ses_id' AND sx.int_teacher_id='$teacher_id' 
                                        "; 
                                            
                                        $res=mysql_query($sql_student);
                                        while ($row= mysql_fetch_assoc($res)) {
                                            $name=$row['first_name'];
                                            echo $name.',&nbsp;';
                                        }
                                    ?>     
                                </div>
                            </div>
                            <p> 
                                <a title="Back" 
                                    href="javascript:window.history.back();" 
                                    class="btn btn-success btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                                &nbsp; &nbsp;
                                <a id="btnPrint" href="print_stu_sheet.php?ses=<?=$ses_id?>" class="btn btn-danger btn-sm">Print Student List</a>
                                &nbsp; &nbsp;
                                <a id="btnAttendance" href="javascript:void(0);" class="btn btn-info btn-sm">
                                Attendance
                                </a>
                                
                            </p>                            
                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>    <!-- /.ct_display -->
                </div>
                <iframe id="attframe">
                              
                </iframe>
            </div>    <!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>    <!-- /#header -->

<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="js/enjoyhint/enjoyhint.min.js"></script>
<script>
    //initialize instance
    var enjoyhint_instance = new EnjoyHint({});
    var enjoyhint_script_steps = [];
    $(document).ready(function(e) {    

        //simple config.
        //Only one step - highlighting(with description) "New" button
        //hide EnjoyHint after a click on the button.
        enjoyhint_script_steps = [
            {
                'next #textarea' : 'Session details are shown here',
                showNext: true
            },  
            {
                'next #btnPrint' : 'Click this button to print the sheet of student logins for this session',
                showNext: true
            },  
            {
                'next #btnAttendance' : 'Click this button to take attendance for this session',
                showNext: true
            },  
            {
                selector: '',
                event_type: "auto",
                description: "Done"

            }  
        ];

        //set script config
        enjoyhint_instance.set(enjoyhint_script_steps);

        //run Enjoyhint script
        enjoyhint_instance.run();
    });
</script>
<script type="text/javascript">
    <?php 
        if ($error != '') 
            echo "alert('{$error}')"; 
    ?>
    var districts = [];
    var schools = [];
    var students = [];    
    var startDate = null;
    var endDate = null;
    var currentSchoolID = "";
    var currentDistrictID = "";
    var detail = false;
    var loadtime = 0;
    var totalAvg = 0;
    var totalItems = 0;
    var totalSessions = 0;
    var currentSession = <?php echo json_encode($_SESSION); ?>;
    var resultAttendance = <?php echo json_encode($resultAttendance); ?>;

    var frame = null;
    
    console.log(resultAttendance);
    // Student Attendance - load  
    var generateStudentAttendance = (sessions, callback) => {
        let out = "";
        let schoolRateSum = 0;

        for (let k = 0; k < sessions.length; k++) {
            let session = sessions[k];
            let student_nr_user_ids = session.nr_student_ids == null ? [] : session.nr_student_ids.split(',');           
            let student_ids = session.student_ids == null ? [] : session.student_ids.split(',');            
            let student_names = session.student_names == null ? [] : session.student_names.split(',');            
            for (let s = 0; s < student_nr_user_ids.length; s++) {
                let nruid = student_nr_user_ids[s];                
                getStudentAttendance(nruid, session.id, session.ses_start_time, session.ses_end_time, (wasThere, stats) => {
                    let attobj = {
                            'id': student_ids[s] || "",
                            'nruserID': nruid || "",
                            'name': student_names[s] || "",
                            'totalSessions': 1,
                            'totalAttended': 0,
                            'totalAbsent': 0,
                            'totalAverage': 0,
                            'totalDuration': stats.duration,
                            'totalAttention': stats.attention
                    };
                    if (wasThere == true) {   
                        attobj.totalAttended = 1;                        
                        addStudentAttendance(attobj);
                    } else {
                        attobj.totalAbsent = 1;                        
                        addStudentAttendance(attobj);
                    }                                      
                });
            }
        }
        
    }
    var getStudentAttendance = (nruserID, sessionID, start_time, end_time, callback) => {
        isLoading(true);
        //$("#studentResults_"+nruserID).html('');
        $("#attframe").contents().find("#status").html(`${$.active} items`);
        $("#attframe").contents().find("#status").addClass('text-danger');
        totalItems = $.active > totalItems ? $.active : totalItems;
        $.ajax({
            type:'POST',
            data:{
                'action': 'attendees',
                'user_id': nruserID
            },
            url:'../tutorgigs.io/myadmin/k/newrow.php',
            success:function(data) {    
                let result = JSON.parse(data);   
                if (result.status == "success") {             
                    let att = result.data.session_attendance;
                    let duration = 0;
                    let attention = 0;
                    let nameid = "";
                    let elem = $("#studentResults_"+nruserID+"_"+sessionID);
                    for (let j = 0; j < att.length; j++) {
                        let attrow = att[j];
                        let time_joined = moment.unix(attrow.time_joined).format("lll");
                        let start = moment(start_time).format("lll");
                        let end = moment(end_time).add("lll");
                        
                        if (moment(time_joined).isAfter(start) && moment(time_joined).isBefore(end) && attrow.duration > 0) {
                            duration +=Math.round(attrow.duration);
                            attention += parseInt(attrow.attention);
                            nameid = `${attrow.user_name} - userID: ${attrow.user_id} - `;                        
                            /*elem.append(`
                                ${attrow.user_name} - userID: ${attrow.user_id} - Duration: ${Math.round(attrow.duration)} minutes - Attention: ${attrow.attention} - ${moment.unix(attrow.time_joined).format("lll")}<br/>                                 
                            `);*/
                        }
                    }
                    // write student attendance row
                    if (duration >= 1) {
                        /*elem.append(`
                            <i class="fas fa-check-circle"></i>
                            &nbsp;                            
                            ${Math.round(duration)} minutes - Attention: ${Math.round(attention/att.length)} %                                 
                        `);
                        elem.addClass("text-success");*/
                        callback(true, {
                            'nruserID': nruserID,
                            'duration': Math.round(duration),
                            'attention': Math.round(attention/att.length)
                        });
                    } else {
                        /*elem.append(`
                            <i class="fas fa-times-circle"></i>
                            &nbsp;
                            Absent
                        `);
                        elem.addClass("text-danger");*/
                        callback(false, {
                            'nruserID': nruserID,
                            'duration': 0,
                            'attention': 0
                        });
                    }              
                }
                
            }
        });
    }
    var generateStudentTable = (sessionData) => {
        if (students.length > 0) {
            students.sort(sortByName);
            totalAvg = 0;
            let out = `<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Attended</th>
                                </tr>
                            </thead>
                            <tbody id="studentResults">
            `;
            for (let k = 0; k < students.length; k++) {
                let student = students[k];         
                totalAvg += parseInt(Math.round(student.totalAverage).toFixed(0));                    
                // student rows
                let didAttend = student.totalAttended > 0;
                out += `    <tr id="${student.id}" nruserid="${student.nruserID}">
                                <td>${student.id}</td>
                                <td><strong class="text-primary"><a href="edit_student.php?sid=${student.id}">${student.name}</a></strong></td>
                                <td><i class="text-${ didAttend ? 'success' : 'danger'} fas fa-${ didAttend ? 'check' : 'times'}-circle"></i></td>                           
                            </tr>
                `;                
            }
            out += `
                            </tbody>
                        </table>
            `;
            
            out += `
                        <hr>
            `;
            
            return out;
        }
    }  
    var addStudentAttendance = (rec) => {
        let updated = false;
        for(let i=0; i<students.length; i++) {
            let stud = students[i];
            if (stud.nruserID == rec.nruserID) {
                //update
                stud.totalSessions += rec.totalSessions;
                stud.totalAttended += rec.totalAttended;
                stud.totalAbsent += rec.totalAbsent;
                stud.totalAverage = ((stud.totalAttended/stud.totalSessions) * 100);
                stud.totalDuration += rec.totalDuration;
                stud.totalAttention += rec.totalAttention;
                updated = true;
            }  
        }
        if (updated == false) {
            //add
            rec.totalAverage = ((rec.totalAttended/rec.totalSessions) * 100);
            students.push(rec);
        }
    }
    function sortByName(a, b) {
        const studentA = a.name.toUpperCase();
        const studentB = b.name.toUpperCase();

        let comparison = 0;
        if (studentA > studentB) {
            comparison = 1;
        } else if (studentA < studentB) {
            comparison = -1;
        }
        return comparison;
    }
    var resetAttendanceUI = () => {
        schools = [];
        students = [];
        startDate = null;
        endDate = null;
        currentSchoolID = "";
        currentDistrictID = "";
        detail = false;
        loadtime = 0;
        totalAvg = 0;
        totalItems = 0;
        totalSessions = 0;
        
        $("#attframe").contents().find("#studentResults").html('');
    }

    $('#btnAttendance').on('click', (e) => {
        resetAttendanceUI();  
        $("#attframe").contents().find('#btnRefresh').css("display", "");
        // Start checking for XHR complete
        var interval = setInterval(function() { 
            loadtime++;
            totalItems = $.active > totalItems ? $.active : totalItems;
            $("#attframe").contents().find("#status").html(`${$.active} items`);            
            if ($.active == 0) {
                clearInterval(interval);
                isLoading(false);
                if (students.length > 0) {
                    $("#attframe").contents().find("#studentResults").html(generateStudentTable());
                }
                $("#attframe").contents().find("#status").html(`build time: ${loadtime} seconds`);
                $("#attframe").contents().find("#status").removeClass('text-danger');
                //buildChart((totalAvg/students.length).toFixed(0));
                //console.log('Report Complete');
            }
            
        }, 1000); 
        generateStudentAttendance([resultAttendance]);
    });

    // - Loading Indicator -
    var isLoading = (state) => {
        // start spinner, loading...
        if (state == true) {
            $("#attframe").contents().find('#btnRefresh').addClass('fa-spin');
            loadtime=0;
            totalItems = 0;
        } else 
        if (state == false) {
            $("#attframe").contents().find('#btnRefresh').removeClass('fa-spin');
        } else {
            $("#attframe").contents().find('#btnRefresh')[0].classList.toggle('fa-spin');
        }
    };   

    var frameContent = `
        <html>
            <head>
                <link type="text/css" href="../tutorgigs.io/myadmin/inc/fa5/css/all.css" rel="stylesheet" />
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
                <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
            </head>
            <style>
                #attendanceContainer {
                    overflow-y: auto; /* Hide vertical scrollbar */
                    overflow-x: auto; /* Hide horizontal scrollbar */
                }
            </style>
            <body>
                <div id="attendanceContainer">
                    <div style="margin-top: 6px;">                                            
                        <i id="btnRefresh" class="fab fa-superpowers text-success" style="float: right; margin-right: 12px; display:none;" title="Indicator shows when report is running in the background"></i>
                        <small id="status" class="text-muted" style="float: left; margin-left: 12px;"></small>
                    </div>
                    <div id="studentResults">                   
                        <p id="studentResults2" class="text-success"></p>
                    </div>  
                </div>
            </body>
        </html>     
    `;

    $(document).ready(function() {  
       frame = $('#attframe').contents().find('body');
       frame.html(frameContent);
	});
</script>

<?php include("footer.php"); ?>
