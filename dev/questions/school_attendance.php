<?php    
    @extract($_GET);
    @extract($_POST);
    
    include("header.php");

    $login_role = $_SESSION['login_role'];
    $page_name="Attendance";
    ///////////////////////////////
    $error=''; 
    $id = $_SESSION['login_id'];       
    $schoolID = $_SESSION['schools_id'];   
    if (!isset($_SESSION['schools_id']) || empty($_SESSION['schools_id'])) {
        echo "<script>alert('School ID is missing.".$_SESSION['schools_id']."');</script>";
    }    

    // 1. Get the last session for this school
    $queryLastSession = "SELECT 
        * 
        FROM 
        int_schools_x_sessions_log 
        WHERE 
        school_id = $schoolID
        ORDER BY 
        ID 
        DESC 
        LIMIT 1;";
    $resultLS = mysql_query($queryLastSession) or die (mysql_error());
    if (mysql_num_rows($resultLS) < 1) {
        echo "<script>alert('There are no sessions for this school.');</script>";
    }
    $lastSessionRow = mysql_fetch_assoc($resultLS);
    $lastSessionDate = $lastSessionRow['ses_start_time'];
    // 2. Pop datarange default
?>
<link type="text/css" href="../tutorgigs.io/myadmin/inc/fa5/css/all.css" rel="stylesheet" />
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link type="text/css" href="../tutorgigs.io/myadmin/css/jquery-ui.min.css" rel="stylesheet" />
<link type="text/css" href="css/style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    .roomlist {
        min-width: 200px;
        height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        text-align:left;
    } 
    #inputPage {
        width: 64px;
        height: 26px;
        display: inline;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
        height:30px; 
    }
    .navbar {
        align-items: baseline;
        margin-right: auto;
        justify-content: normal;

    }
    .users {
        margin-left: auto;
    }

</style>

<div id="main" class="clear fullwidth">
	<div class="container">
    <?php 
        //print_r($_SESSION);
        //print_r($lastSessionDate);
    ?>
		<div class="row">
			<!--<div id="sidebar" class="col-md-3" style="transition-duration: 1s;">
				<?php //include("sidebar.php"); ?>
            </div>-->                       
            <div class="container">
                <nav class="navbar navbar-light bg-light">
                    <a href="javascript:void(0)" onclick="window.history.back();" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar">
                        <i id="btnMenu" class="fa fa-arrow-left" style="color: #313e59;" title="Go Back"></i>&nbsp; &nbsp;
                        
                    </a>  
                    <a class="navbar-brand" href="javascript:void(0)" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar">
                        <i id="btnMenu" class="fa fa-bars" style="color: #313e59;" title="Attendance"> </i>&nbsp; &nbsp;
                        Attendance Report
                    </a>  
                                      
                    <!-- Filters -->      
                    <form class="form-inline" style="margin-left: auto;">
                        <input id="datefilter" type="text" name="datefilter" value="" placeholder="Date Range" title="Optional Date Range" style="height: 23px; width:160px;" />
                        &nbsp;&nbsp;
                        <button id="btnRun" type="button" class="btn btn-success btn-sm" title="Run the report">Run</button>
                    </form>
                    &nbsp;&nbsp;            
                    <div class="btn-group pull-right" role="group">                                            
                        <i id="btnRefresh" class="fab fa-superpowers text-success" title="Indicator shows when report is running in the background"></i>
                    </div>
                </nav>
                <br/>
                <div>
                    <small id="txtDistrictName" class="text-muted"><small id="txtDistrictID"></small></small>                    
                    <h4 id="txtSchoolName"><small id="txtSchoolID"></small></h4>
                    <div id="schoolAttendanceRate" class="alert alert-warning"></div>                    
                </div>   
                <hr>
                <div>                   
                    <p id="sessionResults" class="text-success"></p>
                </div>   
            </div>            
			<div class="clearnone">&nbsp;</div>

            
		</div><!-- ./div row -->
	</div><!-- ./div container -->
</div><!-- / main -->


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
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!--
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
-->
<script>
    var districts = [];
    var schools = [];
    var students = [];
    var sessions = [];
    var startDate = moment('<?=$lastSessionDate?>').startOf('isoweek').format("YYYY-MM-DD"); // Monday
    var endDate = moment('<?=$lastSessionDate?>').endOf('isoweek').add('days', -2).format("YYYY-MM-DD"); // Friday
    var currentSchoolID = <?=$_SESSION["schools_id"]?>;
    var currentDistrictID = "";

	$(document).ready(function() {  
        $('#selectDistrict').chosen();      
        $('#selectSchool').chosen();      
        //getDistricts();
        //getSchoolSessions(<?=$_SESSION["schools_id"]?>)
	});    
    
    // - Loading Indicator -
    var isLoading = (state) => {
        // start spinner, loading...
        if (state == true) {
            $('#btnRefresh').addClass('fa-spin');
        } else 
        if (state == false) {
            $('#btnRefresh').removeClass('fa-spin');
        } else {
            $('#btnRefresh')[0].classList.toggle('fa-spin');
        }
    };    

    // - Date Range Picker
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: true,
        autoApply: true,
        "startDate": this.startDate,
        "endDate": this.endDate,
        locale: {
            format: "YYYY-MM-DD",
            cancelLabel: 'Clear'
        }
    });
</script>

<!-- Report Functions -->
<script>        
    // *** LOAD LISTS ****
    // Districts - load list 
    var getDistricts = (search) => {
        isLoading(true);
        $('#selectDistrict').chosen("destroy");
        $('#selectDistrict').html('');
        $.ajax({
            type:'POST',
            data:{
                'action': 'districts'
            },
            url:'../tutorgigs.io/myadmin/k/attendance-ajax.php',
            success:function(data) {    
                districts = JSON.parse(data);
                let htmlopts = generateOptions(districts, "Select a District");                  
                $('#selectDistrict').html(htmlopts);
                $('#selectDistrict').chosen();
                isLoading(false);
            }
        });
    }
    // Schools - load list 
    var getSchools = (districtID) => {
        isLoading(true);
        $("#selectSchool").chosen("destroy");
        $('#selectSchool').html('');
        $("#sessionResults").html('');
        $.ajax({
            type:'POST',
            data:{
                'action': 'schools',
                'districtID': districtID
            },
            url:'../tutorgigs.io/myadmin/k/attendance-ajax.php',
            success:function(data) {    
                schools = JSON.parse(data);
                let htmlopts = generateOptions(schools, "Select a School");                  
                $('#selectSchool').html(htmlopts);
                $('#selectSchool').chosen();
                isLoading(false);
            }
        });
    }
    // School Sessions - load list 
    var getSchoolSessions = (schoolID) => {
        isLoading(true);
        $("#sessionResults").html('');
        $.ajax({
            type:'POST',
            data:{
                'action': 'school_sessions',
                'schoolID': schoolID,
                'startDate': startDate,
                'endDate': endDate
            },
            url:'../tutorgigs.io/myadmin/k/attendance-ajax.php',
            success:function(data) {    
                let result_sessions = JSON.parse(data);     
                if (result_sessions.length > 0) {    
                    sessions = result_sessions;       
                    $("#sessionResults").html(generateSessionRows(result_sessions));
                    generateStudentAttendance(result_sessions);
                } else {
                    $("#sessionResults").html(`There are no sessions for this school between ${startDate} and ${endDate}`);
                    isLoading(false);
                }
                //isLoading(false);
            }
        });
    }
    // Student Attendance - load  
    var getStudentAttendance = (nruserID, sessionID, start_time, end_time, callback) => {
        //isLoading(true);
        $("#studentResults_"+nruserID).html('');
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
                        elem.append(`
                            <i class="fa fa-check-circle"></i>
                            &nbsp;                            
                            ${Math.round(duration)} minutes - Attention: ${Math.round(attention/att.length)} %                                 
                        `);
                        elem.addClass("text-success");
                        callback(true, {
                            'duration': Math.round(duration),
                            'attention': Math.round(attention/att.length)
                        });
                    } else {
                        elem.append(`
                            <i class="fa fa-times-circle"></i>
                            &nbsp;
                            Absent
                        `);
                        elem.addClass("text-danger");
                        callback(false);
                    }              
                }
                
            }
        });
    }

    // *** ON CHANGE ****
    // Schools - on change
    $('#selectDistrict').on('change', function (e) {
        let selected = currentDistrictID = $(this).val();
        if (selected >= 0) {
            $('#txtDistrictName').text($('#selectDistrict option:selected').text());
            $('#txtDistrictID').text($('#selectDistrict').val());
            $('#txtSchoolName').text('');
            $('#sessionResults').text('');
            $('#schoolAttendanceRate').text('');
            currentSchoolID = "";
            getSchools(selected);
        }
    });
    $('#selectSchool').on('change', function (e) {
        let selected = currentSchoolID = $(this).val();
        if (selected >= 0) {
            $('#txtSchoolName').text($('#selectSchool option:selected').text());
            $('#txtSchoolID').text($('#selectSchool').val());
            $('#sessionResults').text('');
            $('#schoolAttendanceRate').text('');
            //getSchoolSessions(selected);   
        }
    });
    // Date Range - on change/cancel
    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
    });
    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        startDate = "";
        endDate = "";
    });
    $('#btnRun').on('click', (e) => {
        if (startDate == null || endDate == null) {
            alert("Please select a date range.");
            return;
        }
        if (currentSchoolID != "") {      
            $('#sessionResults').text('');
            $('#schoolAttendanceRate').text('');
            getSchoolSessions(currentSchoolID);
        }   
    });
    // *** HELPER FUNCTIONS ****
    var generateOptions = (optdata, firstOptionLabel) => {
        let out = "<option>" + (firstOptionLabel || "Select an option") + "</option>";
        for (let k = 0; k < optdata.length; k++) {
            out+= `<option value="${optdata[k].id}">${optdata[k]['name']}</option>`;
        }
        return out;
    }

    /*var generateSessionRows = (sessionData) => {
        let out = "";
        for (let k = 0; k < sessionData.length; k++) {
            let session = sessionData[k];
            let student_nr_user_ids = session.nr_user_ids.split(',');
            out += `<span class="badge badge-default"></span>`;
            out += moment(session.ses_start_time, "YYYY-MM-DD HH:mm:SS").format("lll") + `<br/> `;
            out += `
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${student_nr_user_ids.length} students
                        <span class="badge badge-primary badge-pill">${session.id}</span>
                        </li>
            `;
            // student attendance rows
            for (let s = 0; s < student_nr_user_ids.length; s++) {
                out += `<p id="studentResults_${student_nr_user_ids[s]}_${session.id}">${student_nr_user_ids[s]} - </p>`;
            }
            out += `                        
                    </ul>
                    <hr>
            `;
        }
        return out;
    }*/
    var generateSessionRows = (sessionData) => {
        let out = "";
        for (let k = 0; k < sessionData.length; k++) {
            let session = sessionData[k];
            let student_nr_user_ids = session.nr_student_ids ? session.nr_student_ids.split(',') : [];
            let student_ids = session.student_ids != null ? session.student_ids.split(',') : [];
            let student_names = session.student_names != null ?session.student_names.split(',') : [];
            out += `<table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width: 200px;">Date</th>
                                <th scope="col">Class</th>
                                <th scope="col"># Students</th>
                                <th scope="col">Attendance</th>
                                <th scope="col"> </th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            out += `
                            <tr class="alert-success">
                                <th scope="row">${session.id}</th>
                                <td>${moment(session.ses_start_time, "YYYY-MM-DD HH:mm:SS").format("lll")}</td>
                                <td>${session.lesson_name}</td>
                                <td>${student_nr_user_ids.length}</td>
                                <td id="sessionAttendanceRate_${session.id}">%</td>
                                <td><i class="fas fa-info-circle text-info"></i></td>
                            </tr>
            `;            
            // student rows
            for (let s = 0; s < student_nr_user_ids.length; s++) {
                out += `    <tr>
                                <td colspan=2 style="text-align:right">${student_names[s] || "-"}</td>
                                <td id="studentResults_${student_nr_user_ids[s]}_${session.id}" colspan=4></td>
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
        }
        return out;
    }

    var generateStudentAttendance = (sessions, callback) => {
        let out = "";
        let schoolRateSum = 0;
        for (let k = 0; k < sessions.length; k++) {
            let session = sessions[k];
            let student_nr_user_ids = session.nr_student_ids.split(',');
            let sessionTotalAttended = 0;
            for (let s = 0; s < student_nr_user_ids.length; s++) {
                let nruid = student_nr_user_ids[s];
                getStudentAttendance(nruid, session.id, session.ses_start_time, session.ses_end_time, (wasThere, stats) => {
                    if (wasThere == true) {
                        sessionTotalAttended++;        
                        rate =  parseFloat(sessionTotalAttended/student_nr_user_ids.length).toFixed(2) * 100;         
                        $('#sessionAttendanceRate_'+session.id).html(rate + "%");
                        schoolRateSum += rate;                       
                    } else {
                        if (sessionTotalAttended == 0) {
                            $('#sessionAttendanceRate_'+session.id).html("0%");
                        }
                    }
                    if (k == sessions.length -1) {
                        isLoading(false);
                        let schoolRate = parseFloat(schoolRateSum / sessions.length).toFixed(0);
                        $('#schoolAttendanceRate').html(sessions.length + " Sessions  |  ");// + schoolRate + "% Overall Attendance");
                    }
                });
            }
        }
        
    }
</script>
<!-- Document Ready -->

<!-- Insert Footer - THIS FILE IS MISSING IN THIS DIRECTORY -->
<?php //include("footer.php"); ?>