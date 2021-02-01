<?php
    
    @extract($_GET);
    @extract($_POST);
    
    include("header-k.php");

    $login_role = $_SESSION['login_role'];
    $page_name="Session Manager";

    ///////////////////////////////

    $error=''; 
    $id = $_SESSION['login_id'];

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
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
</style>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<!--<div id="sidebar" class="col-md-3" style="transition-duration: 1s;">
				<?php //include("sidebar.php"); ?>
            </div>		
             /#sidebar -->   
                    
            <div id="sessionCardsContainer" class="col-md-12" style="transition-duration: 1s;">
                <nav class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="javascript:void(0)" onclick="window.location.href='list-tutor-sessions.php'" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar">
                        <i id="btnMenu" class="fa fa-bars" style="color: orange" title="Toggle Menu"> </i>&nbsp; &nbsp;
                        vBoard Manager (<span id="nr_rooms_count"></span>)
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
                <!-- Top Tabs -->
                <ul class="nav nav-tabs" id="nrTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" 
                        id="nr_rooms_tab" data-toggle="tab" 
                        href="#roomsContainer" role="tab" 
                        aria-controls="roomsContainer" 
                        aria-selected="true">
                            nrRooms&nbsp; &nbsp; &nbsp; <i id="btnRefreshRoomList" class="fas fa-recycle"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="users-tab" data-toggle="tab" href="#usersContainer" 
                        role="tab" aria-controls="usersContainer" aria-selected="false">users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="files-tab" data-toggle="tab" href="#filesContainer" 
                        role="tab" aria-controls="filesContainer" aria-selected="false">files</a>
                    </li>
                </ul>
                <div class="tab-content col-md-12" id="myTabContent">
                    <div class="tab-pane fade show active" id="roomsContainer" role="tabpanel" aria-labelledby="nr_rooms_tab">
                        <!-- nrRooms filters row-->
                        <div class="row">
                            <div class="col-sm-1 col-md-2 col-lg-3">
                                <span id='nrpaging'></span> 
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-9">
                            
                            </div>
                        </div>
                        <!-- nrRooms list and content row -->
                        <div class="row">
                            <div class="col-sm-1 col-md-2 col-lg-3 roomlist">
                                <div class="nav flex-column nav-pills" id="v-rooms-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">...</a>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-9">
                                <div class="tab-content" id="v-rooms-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="usersContainer" role="tabpanel" aria-labelledby="users-tab">...</div>
                    <div class="tab-pane fade" id="filesContainer" role="tabpanel" aria-labelledby="files-tab">...</div>
                </div>    
            </div>            
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
<!-- Room Functions -->
<script>
    var rooms = [];  
    var roomDetailsAlreadyLoaded = []; 
    var roomXferList = [];
    var roomListCount = 0;
    var roomListPage = <?php echo isset($_REQUEST['page']) ? $_REQUEST['page'] : 0 ?>;
    var roomListLimit = <?php echo isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100 ?>;   
    var isLoading = (state) => {
        // start spinner, loading...
        if (state == true) {
            $('#btnRefreshRoomList').addClass('fa-spin');
        } else 
        if (state == false) {
            $('#btnRefreshRoomList').removeClass('fa-spin');
        } else {
            $('#btnRefreshRoomList')[0].classList.toggle('fa-spin');
        }
    };
    // load room list 
    var getRoomList = (search) => {
        // start spinner, loading...
        isLoading(true);
        $.ajax({
            type:'POST',
            data:{},
            url:'https://tutorgigs.io/myadmin/k/newrow.php?action=roomlist&page='+roomListPage+'&limit='+roomListLimit,
            success:function(data) {
                /*  return data format -> {
                        status: 'some status',
                        data: {
                            count: numRows, 
                            content: [array of items/rows]
                        }
                    }
                */
                roomResults = JSON.parse(data);
                rooms = roomResults.data.content;
                if (roomResults.status == 'success') {                    
                    //$('#nr_rooms_count').text(roomResults.data.count + ' rooms - ' + Math.round(roomResults.data.count/roomListLimit) + ' pages.');
                    // reset paging controls
                    populateRoomsListPaging(roomResults.data.count);
                    // populate room list and detail pane
                    populateRoomsList(roomResults.data.content);
                    populateRoomsListDetail(roomResults.data.content);
                    roomDetailsAlreadyLoaded = [];
                    isLoading(false);
                }
            }
        });
    }

    var getRoomSessionDetail = (roomID, elemID) => {
        if (roomDetailsAlreadyLoaded.includes(roomID)) {
            console.log('room detail already loaded for room ' + roomID)
            return;
        }
        isLoading(true);
        $.ajax({
            type:'POST',
            data:{
                "action": "session_x_room",
                "roomID": roomID
            },
            url:'https://tutorgigs.io/myadmin/k/newrow-sessions.php',
            success:function(data) {
                if (data !== "null") {
                    let ses = JSON.parse(data);
                    $('#'+elemID).html(function (n) {
                        this.innerHTML += `                            
                            <h4>Session</h4>
                            <p>
                                Session ID: <b>${ses.ses_tutoring_id}</b><br/>
                                Start: ${moment(ses.ses_start_time).format('llll')}<br/>
                                End: ${moment(ses.ses_end_time).format('llll')}<br/>
                            </p>
                            <h4>Tutor</h4>
                            <p>
                                Tutor: <b>${ses.f_name}&nbsp;${ses.lname}</b><br/>
                                Tutor ID: <b>${ses.ses_tutoring_id}</b><br/>
                            </p>
                            <h4>Students</h4>
                            <p>    
                                Student Count: ${ses.numStudents}<br/>
                                Student IDs: ${ses.student_ids}<br/>     
                                <div class="row">                           
                                    <div class="col-6">
                                        <span id="students_for_room_${roomID}"></span>
                                    </div>
                                    <div class="col-6">
                                        <span id="students_for_xfer">
                                        <span class="btn btn-link text-primary">
                                            Sessions in Progress 
                                            <select id="session_in_progress_list">
                                                <option>Current Sessions</option>
                                            </select>                                             
                                            <hr>
                                        </span>
                                        <hr>
                                    </div>
                                </div>

                            </p>
                        `;
                    });
                    // finish gathering student details
                    getRoomSessionStudents(ses.student_ids, 'students_for_room_'+roomID);
                } else {
                    $('#'+elemID).html(function (n) {
                        this.innerHTML += `No (Intervene) Session Data for this room. `;
                    });
                }
                roomDetailsAlreadyLoaded.push(roomID);
                isLoading(false);
            },
            error: function (data) {
                alert('Error: ' + String(data));
            }
            
        });
    }

    var getSessionsInProgressList = () => {
        $.ajax({
            type: 'POST',
            data: {'action': "current_sessions"},
            url:'https://tutorgigs.io/myadmin/k/newrow-sessions.php',
            success: function(data) {
                let sessions = JSON.parse(data);
                if (sessions !== "null" && sessions.length > 0) {              
                    for (i = 0; i < sessions.length; i++) {
                        let session = sessions[i];
                        $('#session_in_progress_list').html(function (n) {
                            return $(this.innerHTML + ` 
                                <option value="${session.id}">${session.id} @ ${session.ses_start_time}</option>
                            `); 
                        });
                    }                    
                } else {
                    $('#session_in_progress_list').html(function (n) {
                        return $(` 
                            <option>No available sessions</option>
                        `); 
                    });
                }
            }
        })
    }
    var getRoomSessionStudents = (studentIDs, elemID) => {
        isLoading(true);
        $.ajax({
            type:'POST',
            data:{
                'action': 'students',
                'studentIDs': studentIDs
            },
            url:'https://tutorgigs.io/myadmin/k/newrow-sessions.php',
            success:function(data) {
                if (data !== "null") {
                    let students = JSON.parse(data);
                    $('#'+elemID).html(function (n) {
                        return $(this.innerHTML + `
                            <span class="btn btn-link text-primary xferAll">Select All</span> | <span class="btn btn-link text-primary unxferAll">UnSelect</span> | <button class="btn btn-sm"><i class="fas fa-exchange-alt"></i> Transfer to room...</button></span>
                            <hr>
                        `);
                    });
                    for (j = 0; j < students.length; j++) {
                        let student = students[j];
                        
                        $('#'+elemID).html(function (n) {
                            return $(this.innerHTML + `   
                                <input class="xfer" type="checkbox" value="${student.newrow_ref_id}">      
                                <span class="badge badge-primary">${student.student_id}</span> &nbsp;
                                <span class="badge badge-danger">${student.newrow_ref_id}</span> 
                                <button class="btn btn-sm" title="NewRow ID: ${student.newrow_ref_id}">
                                    <i class="fas fa-info"></i>
                                </button> 
                                ${student.first_name} ${student.last_name} 
                                <br/>
                            `);
                        }); 
                    }
                    getSessionsInProgressList();
                    // Transfer Student tracker for IDs
                    roomXferList = [];
                    $('.xfer').on('click', function (e) {
                        if(e.target.checked == true) {
                            if (roomXferList.indexOf(e.target.value) < 0) {
                                roomXferList.push(e.target.value);
                                console.log(roomXferList);    
                            }                            
                        }
                        if (e.target.checked == false) {
                            if (roomXferList.indexOf(e.target.value) > -1) {
                                roomXferList.splice(roomXferList.indexOf(e.target.value),1);
                                console.log(roomXferList); 
                            }
                        }
                    });
                    $('.xferAll').on('click', function (e) {
                        $(".xfer").prop('checked', true);
                        roomXferList = [];
                        $(".xfer").each(function (n) {
                            roomXferList.push(this.value);
                        })
                    });
                    $('.unxferAll').on('click', function (e) {
                        $(".xfer").prop('checked', false);
                        roomXferList = [];
                    });
                }
            }
        })
    }
    
    var populateRoomsList = (roomlist) => {
        $('#v-rooms-tab').empty();
        for (let idx = 0; idx < roomlist.length; idx++) {
            let room = roomlist[idx];
            $('#v-rooms-tab').html(function(n) { 
                return this.innerHTML += `
                    <a  class="nav-link" 
                        id="v-rooms-profile-tab-${room.model_id}" 
                        data-toggle="pill" 
                        href="#v-rooms-tabContent-${room.model_id}" 
                        role="tab" 
                        aria-controls="v-rooms-profile" 
                        aria-selected="false"
                        onclick="getRoomSessionDetail(${room.model_id}, 'v-rooms-tabContent-${room.model_id}')">
                        ${room.name}
                    </a>
                `;
            });
        }
    };

    var populateRoomsListDetail = (roomlist) => {
        $('#v-rooms-tabContent').empty();
        for (let idx = 0; idx < roomlist.length; idx++) {
            let room = roomlist[idx];
            $('#v-rooms-tabContent').html(function (n) {
                let created = moment.unix(room.date_created).format('YYYY-MM-DD');
                return this.innerHTML += `
                    <div    class="tab-pane fade" 
                            id="v-rooms-tabContent-${room.model_id}" 
                            role="tabpanel" 
                            aria-labelledby="v-rooms-profile-tab-${room.model_id}">
                            <h4>${room.name}</h4>
                            <p>
                                NewRow Room ID: <b>${room.model_id}</b><br/>
                                Created: ${moment(created).format('llll')}<br/>
                            </p>
                    </div>
                `;
            });
        }
    };

    var populateRoomsListPaging = (total) => {
        roomListCount = total;
        $('#nrpaging').html(function () {
            return $(`
                <button class="btn btn-default" title="Page Up">
                    <i class="fas fa-angle-double-left text-success nrpagedown"></i>
                </button> 
                <span class="badge badge-warning" id="tbPage">
                    ${roomListPage}
                </span>&nbsp;&nbsp;of&nbsp;&nbsp;
                <span class="badge badge-warning" id="tbPageTotal">
                    ${Math.round(total/roomListLimit) - 1} 
                </span> 
                <button class="btn btn-default" title="Page Down">
                    <i class="fas fa-angle-double-right text-success nrpageup"></i>
                </button>
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input class="form-control" id="inputPage" type="number" min="0" max="${Math.round(total/roomListLimit) - 1}" value="${roomListPage}" validate title="Jump to Page Number">
            `);
        });

        // Paging events
        $('.nrpagedown').on('click', (e) => {
            if (roomListPage <= 0) {
                return;
            } else 
            if (roomListPage > 0) {
                roomListPage--;
                getRoomList();
            }
        });
        $('.nrpageup').on('click', (e) => {
            if (Math.round(roomListPage*roomListLimit) > roomListCount ) {
                return;
            } else 
            if (roomListPage*roomListLimit <= roomListCount) {
                roomListPage++;
                getRoomList();
            }
        });
        $('#inputPage').on('change', (e) => {
            if (parseInt(e.target.value) >= 0 && parseInt(e.target.value) <= Math.round(total/roomListLimit)) {
                roomListPage = parseInt(e.target.value);
                getRoomList();
            }
        });
        // ./Paging events

    };

    var xfer_students = (sessionID, newrow_user_id, newrow_room_id) => {
        $.ajax({
            type: 'POST',
            data: {'action': "xfer_students", 'sessionID': sessionID, 'newrow_user_id':newrow_user_id,'newrow_room_id':newrow_room_id},
            url:'https://tutorgigs.io/myadmin/k/newrow-sessions.php',
            success: function(data) {
                console.log(data);
            }
        });
    };
</script>
<!-- Document Ready -->
<script>
	$(document).ready(function() {        
        // Init
        getRoomList(); 

        $('#btnRefreshRoomList').on('click', function () {
            getRoomList();
        });
	});    
    
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
        ev.dataTransfer.setData("student", ev.target.id);
        ev.dataTransfer.setData("isTutor", ev.target.id.indexOf("tutor") > -1 ? true : false);
        ev.target.style.border = 2;
    }

    function drop(ev) {
        ev.preventDefault();
        let isConfirmed = false;
        isConfirmed = confirm("Are you sure you wish to move this (type of user) @(sessionID) to room @newRowRoomID ?? ");
        if (isConfirmed == true) {
            // make ajax calls here

            // continue here on ajax success
            var data = ev.dataTransfer.getData("student");
            //$(ev.target).closest('.card-body').find(".card-text")[0].appendChild(document.getElementByClassName(data));
            if (ev.dataTransfer.getData("isTutor") == "true") {
                $(ev.target).closest('.card-body').find(".card-text")[0].insertBefore(document.getElementById(data), $(ev.target).closest('.card-body').find(".card-text")[0].firstChild);
            } else {
                $(ev.target).closest('.card-body').find(".card-text")[0].appendChild(document.getElementById(data));
            }
            //ev.target.appendChild(document.getElementById(data));
        }
    }
</script>

<!-- NewRow Rooms -->
<script>
    $.ajax({});
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