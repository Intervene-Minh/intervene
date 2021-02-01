<?php    
    @extract($_GET);
    @extract($_POST);
    
    include("header-k.php");

    $login_role = $_SESSION['login_role'];
    $page_name="Attendance";
    ///////////////////////////////
    $error=''; 
    $id = $_SESSION['login_id'];       
?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

</style>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<!--<div id="sidebar" class="col-md-3" style="transition-duration: 1s;">
				<?php //include("sidebar.php"); ?>
            </div>-->                       
            <div class="container">
                <nav class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="javascript:void(0)" onclick="window.location.href='list-tutor-sessions.php'" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar">
                        <i id="btnMenu" class="fa fa-bars" style="color: orange" title="Toggle Menu"> </i>&nbsp; &nbsp;
                        Attendance Report
                    </a>  
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cogs"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <span class="dropdown-item-text text-muted">Current: Sessions</span>
                            <button class="dropdown-item" 
                                type="button" 
                                onclick="window.location.href='attendance2.php'"
                            ><i class="fas fa-arrow-circle-left text-success"></i> Go to Student Attendance</button>
                            <button id="btnStop" class="dropdown-item text-danger" type="button">
                                <i class="fas fa-stop-circle"></i> Stop!</button>
                        </div>
                    </div>  
                    <!-- Filters -->      
                    <form class="form-inline">
                        <span class="navbar-text">
                            <span class="badge badge-primary">1</span> 
                        </span>
                        &nbsp;&nbsp;
                        <select id="selectDistrict" name="district" style="width:200px;">
                        </select>
                        &nbsp;&nbsp;
                        <span class="navbar-text">
                            <span class="badge badge-primary">2</span> 
                        </span>
                        &nbsp;&nbsp;
                        <select id="selectSchool" name="school" style="width:200px;">
                        </select>
                        &nbsp;&nbsp;
                        <span class="navbar-text">
                            <span class="badge badge-primary">3</span> 
                        </span>
                        &nbsp;&nbsp;
                        <input id="datefilter" type="text" name="datefilter" value="" placeholder="Date Range" title="Optional Date Range" style="height: 23px; width:160px;" />
                        &nbsp;&nbsp;
                        <button id="btnRun" type="button" class="btn btn-success btn-sm" title="Run the report">Run</button>
                    </form>            
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
	$(document).ready(function() {  
        $('#selectDistrict').chosen();      
        $('#selectSchool').chosen();      
        getDistricts();
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
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });
</script>

<!-- Report Functions -->
<script>    
    var districts = [];
    var schools = [];
    var students = [];
    var startDate = null;
    var endDate = null;
    var currentSchoolID = "";
    var currentDistrictID = "";

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
            url:'k/attendance-ajax.php',
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
            url:'k/attendance-ajax.php',
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
            url:'k/attendance-ajax.php',
            success:function(data) {    
                let sessions = JSON.parse(data);     
                if (sessions.length > 0) {           
                    $("#sessionResults").html(generateSessionRows(sessions));
                    generateStudentAttendance(sessions);
                } else {
                    $("#sessionResults").html(`There are no sessions for this school.`);
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
            url:'k/newrow.php',
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
                            <i class="fas fa-check-circle"></i>
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
                            <i class="fas fa-times-circle"></i>
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
        if (currentSchoolID != "") {
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
<?php include("footer-k.php"); ?>