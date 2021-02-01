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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" />
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

    @media print {
        .progress {
            position: relative;
        }
        .progress:before {
            display: block;
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            border-bottom: 2rem solid #eeeeee;
        }
        .progress-bar {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1;
            border-bottom: 2rem solid #337ab7;
        }
        .progress-bar-success {
            border-bottom-color: #67c600;
        }
        .progress-bar-info {
            border-bottom-color: #5bc0de;
        }
        .progress-bar-warning {
            border-bottom-color: #f0a839;
        }
        .progress-bar-danger {
            border-bottom-color: #ee2f31;
        }
    }

    .chart-container {
        width: 400px;
        /*height:140px;*/
        display: inline-block;
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
                            <span class="dropdown-item-text text-muted">Current: Student</span>
                            <button class="dropdown-item" 
                                type="button" 
                                onclick="window.location.href='attendance.php'"
                            ><i class="fas fa-arrow-circle-left text-success"></i> Go to Session Attendance</button>
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
                <div style="text-align: right;">
                    <small class="text-muted" id="status"></small>
                </div>
                <div>
                    <small id="txtDistrictName" class="text-muted"><small id="txtDistrictID"></small></small>                    
                    <h4 id="txtSchoolName"><small id="txtSchoolID"></small></h4>
                    <div class="alert alert-primary">
                        <span id="schoolStats" style="vertical-align:top;"></span>
                        <div class="chart-container"></div>
                    </div>                    
                </div> 
                <div id="studentResults">                   
                    <p id="studentResults2" class="text-success"></p>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
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
            loadtime=0;
            totalItems = 0;
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
    var sessions = [];
    var startDate = null;
    var endDate = null;
    var currentSchoolID = "";
    var currentDistrictID = "";
    var detail = false;
    var loadtime = 0;
    var totalAvg = 0;
    var totalItems = 0;
    var totalSessions = 0;
    
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
        $("#studentResults").html('');
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
        $("#status").html(`${$.active} items`);
            $("#status").addClass('text-danger');
        // Start checking for XHR complete
        var interval = setInterval(function() { 
            loadtime++;
            totalItems = $.active > totalItems ? $.active : totalItems;
            $("#status").html(`${$.active} items - ${totalItems}`);            
            if ($.active == 0) {
                clearInterval(interval);
                isLoading(false);
                if (totalSessions > 0) {
                    $("#studentResults").html(generateStudentTable());
                    $("#schoolStats").html(`
                        Total Students: <strong>${students.length}</strong>&nbsp;&nbsp; | &nbsp;&nbsp;Average Attendance Rate: <strong>${(totalAvg/students.length).toFixed(0)}%</strong>                                               
                    `);
                    $(".chart-container").html(`<canvas id="myChart" height="160px" width="500px"></canvas>`);
                }
                $("#status").html(`build time: ${loadtime} seconds (${totalItems} items)`);
                $("#status").removeClass('text-danger');
                buildChart((totalAvg/students.length).toFixed(0));
                //console.log('Report Complete');
            }
            
        }, 1000);
        $("#studentResults").html('');
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
                let resultSessions = JSON.parse(data);   
                sessions = resultSessions;
                if (resultSessions.length > 0) {       
                    totalSessions += resultSessions.length;    
                    //$("#studentResults").html(generateStudentRows(resultSessions));
                    generateStudentAttendance(resultSessions);
                } else {
                    $("#studentResults").html(`There are no sessions for this school.`);
                    isLoading(false);
                }
                //isLoading(false);
            }
        });
    }
    // Student Attendance - load  
    var getStudentAttendance = (nruserID, sessionID, start_time, end_time, callback) => {
        $("#studentResults_"+nruserID).html('');
        $("#status").html(`${$.active} items`);
        $("#status").addClass('text-danger');
        totalItems = $.active > totalItems ? $.active : totalItems;
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

    // *** ON CHANGE ****
    // Schools - on change
    $('#selectDistrict').on('change', function (e) {
        let selected = currentDistrictID = $(this).val();
        if (selected >= 0) {
            $('#txtDistrictName').text($('#selectDistrict option:selected').text());
            $('#txtDistrictID').text($('#selectDistrict').val());
            $('#txtSchoolName').text('');
            $('#studentResults').text('');
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
            $('#studentResults').text('');
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
            students = [];
            $('#schoolStats').html('');
            getSchoolSessions(currentSchoolID);
        }   
    });
    $('#btnStop').on('click', (e) => {
        $(document).ajaxStop();
        $('#status').html('Stopped');
    });
    // *** HELPER FUNCTIONS ****
    var generateOptions = (optdata, firstOptionLabel) => {
        let out = "<option>" + (firstOptionLabel || "Select an option") + "</option>";
        for (let k = 0; k < optdata.length; k++) {
            out+= `<option value="${optdata[k].id}">${optdata[k]['name']}</option>`;
        }
        return out;
    }

    
    var generateStudentTable = (sessionData) => {
        if (students.length > 0) {
            students.sort(sortByName);
            totalAvg = 0;
            let out = `<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" style="width: 200px;">Student</th>
                                    <th scope="col"># Sessions</th>
                                    <th scope="col"># Attended</th>
                                    <th scope="col"># Absent</th>
                                    <th style="text-align:right;" scope="col">Attendance Rate</th>
                                    <th scope="col" style="width: 150px;"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="studentResults">
            `;
            for (let k = 0; k < students.length; k++) {
                let student = students[k];         
                totalAvg += parseInt(Math.round(student.totalAverage).toFixed(0));                    
                // student rows
                out += `    <tr id="${student.id}" nruserid="${student.nruserID}">
                                <td>${student.id}</td>
                                <td> <strong class="text-primary">${student.name}</strong></td>
                                <td>${student.totalSessions}</td>
                                <td class="${student.totalAttended > 0 ? 'text-success font-weight-bold' : 'pull-left'}">${student.totalAttended}</td>
                                <td class="${student.totalAbsent > 0 ? 'text-danger font-weight-bold' : 'pull-left'}">${student.totalAbsent}</td>
                                <td style="text-align:center;">
                                    ${Math.round(student.totalAverage).toFixed(0)}%
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success text-success success" role="progressbar" style="width: ${Math.round(student.totalAverage).toFixed(0)}%" aria-valuenow="${Math.round(student.totalAverage).toFixed(0)}" aria-valuemin="0" aria-valuemax="100"></div>                                        
                                    </div>
                                    
                                </td>
                                <td><i class="fas fa-info-circle text-info"></i></td>
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

</script>

<script>
    var buildChart = (indata) => {
        if (document.getElementById('myChart') !== null ) {
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [(totalAvg/students.length).toFixed(0), 100-(totalAvg/students.length).toFixed(0)],
                        backgroundColor: ['#d3f2d6','#fccac7'],
                        borderColor: ['#000', '#000'],
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        'Present',
                        'Absent'
                    ]
                },
                options: {
                    responsive: true
                }
            });
        }
    }
</script>
<!-- Document Ready -->

<!-- Insert Footer - THIS FILE IS MISSING IN THIS DIRECTORY -->
<?php include("footer-k.php"); ?>