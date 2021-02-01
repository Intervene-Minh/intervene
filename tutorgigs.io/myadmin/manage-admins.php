<?php
    /****
    @ Manage Admins -
    **/
    include("header-k.php");

    // Global Admin Check    
    if (!isset($_SESSION['login_id']) || (int)$_SESSION['login_role'] > 10) {
        header('Location: index.php');
        exit;
    } else {
        $admin_id = $_SESSION['login_id'];
        // login_id,login_user,login_mail,login_role,login_status,firstlogin(bool) }
    }
    
    $hasMessages = false;
    $message = "";
    $messageType = "default";
    $messageTitle = "Message Title";
    
    // Form Submissions
    if ($_REQUEST['action'] == "create") {
        $sql = "INSERT INTO 
            gig_admins (
                `user_name`,
                `email`,
                `password`,
                `first_name`,
                `last_name`,
                `role`,
                `status`,
                `date_registered`
            ) VALUES (
                '".$_REQUEST["user_name"]."',
                '".$_REQUEST["email"]."',
                '".md5($_REQUEST["password"])."',
                '".$_REQUEST["first_name"]."',
                '".$_REQUEST["last_name"]."',
                '".$_REQUEST["role"]."',
                '".$_REQUEST["status"]."',
                CURRENT_TIMESTAMP
            );
        ";

        $resultCreate = mysql_query($sql) or die(mysql_error());
        //print_r($resultCreate); die;
        if ($resultCreate) {
            //echo "true"; die;
            $hasMessages = true;
            $messageType = "success";
            $messageTitle = "Created Admin";
            $message = "A new admin account was created!: $resultCreate";
        } else {
            $hasMessages = true;
            $messageType = "warning";
            $messageTitle = "Create Error";
            $message = "Error creating Admin: $resultCreate";
        }

    }
    if ($_REQUEST['action'] == "update") {
        $sql = "UPDATE gig_admins 
            SET 
            `first_name` = '".$_REQUEST["first_name"]."',
            `last_name` = '".$_REQUEST["last_name"]."',
            `user_name` = '".$_REQUEST["user_name"]."',
            `email` = '".$_REQUEST["email"]."',
            `role` = '".$_REQUEST["role"]."',
            `status` = '".$_REQUEST["status"]."'
            WHERE `id`=".$_REQUEST["id"]."
        ";
        $resultUpdate = mysql_query($sql) or die(mysql_error());
                
        if(mysql_affected_rows() > 0) {
            $hasMessages = true;
            $messageType = "success";
            $messageTitle = "Updated Status!";
            $message = "The user was successfully updated: ";//.mysql_info();
        } else {
            $hasMessages = true;
            $messageType = "error";
            $messageTitle = "Updated Error!";
            $message = "The user was not updated";
        }        
    }
    if ($_REQUEST['action'] == "update-status") {
        $sql = "UPDATE gig_admins 
            SET `status` = '".$_REQUEST["status"]."'
            WHERE `id`='".$_REQUEST["id"]."'
        ";
        $resultUpdate = mysql_query($sql) or die(mysql_error());
        
        
        if(mysql_affected_rows() > 0) {
            $hasMessages = true;
            $messageType = "success";
            $messageTitle = "Updated Status!";
            $message = "The status was successfully updated!";//.mysql_info();
        } else {
            $hasMessages = true;
            $messageType = "error";
            $messageTitle = "Updated Error!";
            $message = "The status was not updated";
        }        
    }
    if ($_REQUEST['action'] == "delete") {
        $sql = "DELETE 
            FROM gig_admins             
            WHERE `id`='".$_REQUEST["id"]."'
        ";
        $resultDelete = mysql_query($sql) or die(mysql_error());
        
        
        if(mysql_affected_rows() > 0) {
            $hasMessages = true;
            $messageType = "success";
            $messageTitle = "Deleted!";
            $message = "The account was successfully removed!";//.mysql_info();
        } else {
            $hasMessages = true;
            $messageType = "error";
            $messageTitle = "Delete Error!";
            $message = "The account was not removed!";
        }        
    }
    if ($_REQUEST['action'] == "change-password") {
        $sql = "UPDATE gig_admins 
            SET `password` = '".md5($_REQUEST["password"])."'
            WHERE `id`='".$_REQUEST["id"]."'
        ";
        $resultChangePW = mysql_query($sql) or die(mysql_error());
        
        
        if(mysql_affected_rows() > 0) {
            $hasMessages = true;
            $messageType = "success";
            $messageTitle = "Password Changed!";
            $message = "The password was successfully updated!";//.mysql_info();
        } else {
            $hasMessages = true;
            $messageType = "error";
            $messageTitle = "Password Change Error!";
            $message = "The password was not updated";
        }        
    }

    // Gather admin profile, admin users and roles
    $myProfile = mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_admins` WHERE id = ".$admin_id));
    if ((int)$_SESSION['login_role'] == 0) { 
        // admins can only see admins, not super admins
        $resultAdmins = mysql_query("SELECT * FROM `gig_admins` WHERE role = 0 AND id != 1");
        $resultAdminRoles = mysql_query("SELECT * FROM `gig_admin_roles` WHERE isActive != 0 AND value = 0");
    } else {
        // super admins gets everythings
        $resultAdmins = mysql_query("SELECT * FROM `gig_admins`");
        $resultAdminRoles = mysql_query("SELECT * FROM `gig_admin_roles` WHERE isActive != 0");
    }

    $listAdmins = array();
    $listAdminRoles = array();

    if (mysql_num_rows($resultAdmins) > 0) {
        while($row = mysql_fetch_assoc($resultAdmins)) {
            $listAdmins[] = $row;
        }
    }
    if (mysql_num_rows($resultAdminRoles) > 0) {
        while($row = mysql_fetch_assoc($resultAdminRoles)) {
            $listAdminRoles[] = $row;
        }
    }

    $jsonListAdmins = json_encode($listAdmins);
    $jsonListAdminRoles = json_encode($listAdminRoles);

    //var_dump($listAdmins);die;
?>


<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<style>
    input.error {
        border-color: red;
    }
    .nofocus:focus {
        outline: none;
    }
</style>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <!-- Heading and NAV -->
                <div class="row">                    
                    <nav class="navbar navbar-expand-lg navbar-light bg-light col-12">
                        <span class="navbar-brand mb-0 h1">Manage Admin Users</span>
                        <form class="form-inline">
                            <button class="btn btn-outline-success create-user" type="button"><i class="fas fa-plus-circle"></i> &nbsp;Create</button>
                        </form>
                    </nav>   
                </div>
                <?php  
                    if ($hasMessages == true) {
                ?>
                <!-- Messages -->
                <br>
                <div class="row alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <strong id="messageTitle"><?php echo $messageTitle; ?></strong>&nbsp;&nbsp;<?php echo $message; ?> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php 
                    }
                ?>
                <!-- User Tables -->
                <div class="row">                
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">User Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Last Login</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="adminRowContainer">
                                                    
                        </tbody>
                    </table>
                </div>
                       
            </div>
        </div>
    </div>
</div>		<!-- /#header -->
<!-- Modal -->
<div class="modal fade" id="passwordModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="passwordForm">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">               
                        <p id="txtPWUser"></p>         
                        <div id="pwContainer2" class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">*Password</label>
                            <div class="col-sm-10">
                                <div class="input-group ">
                                    <input id="pass1" type="password" name="password" value="" class="form-control col-sm-10" aria-describedby="button-showpw-gen">
                                    <div class="input-group-append " id="button-showpw-gen2">
                                        <button id="btnShowPassword2" class="btn btn-outline-secondary nofocus" type="button"><i class="fas fa-eye-slash"></i></button>                                        
                                    </div>
                                </div>        
                            </div>
                        </div>
                        <div id="pwConfirmContainer2" class="form-group row">
                            <label for="inputPassword2" class="col-sm-2 col-form-label">*Confirm</label>
                            <div class="col-sm-10">
                                <input id="pass2" type="password" class="form-control" value="" required>
                            </div>
                        </div>                        
                </div>
                <div class="modal-footer">
                    <span class="mr-auto">
                        
                    </span>
                    <span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>                        
                        <button id="btnPasswordSubmit" type="submit" class="btn btn-success"><i class="fas fa-key"></i> Change</button>
                    </span>
                    <span>
                        <input id="valAction2" type="hidden" name="action" value="change-password">
                        <input id="valUserID2" type="hidden" name="id" value="">
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="userModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="txtTitle">Create/Edit Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                    
                        <div class="form-group form-row">
                            <div class="col">
                                <input id="firstname" type="text" class="form-control" name="first_name"  placeholder="*First Name" required>
                            </div>                
                            <div class="col">
                                <input id="lastname" type="text" class="form-control" name="last_name"  placeholder="*Last Name" required>
                            </div>
                        </div>                
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input id="username" type="text" class="form-control" name="user_name"  placeholder="*Username (Displayed Name)" required>
                            </div>
                        </div>          
                        <hr>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">*Email/Login</label>
                            <div class="col-sm-10">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email address" required>                                
                            </div>
                        </div>
                        
                        <div id="pwContainer" class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">*Password</label>
                            <div class="col-sm-10">
                                <div class="input-group ">
                                    <input id="inputPassword" name="password" type="password" value="" class="form-control col-sm-10" aria-describedby="button-showpw-gen">
                                    <div class="input-group-append " id="button-showpw-gen">
                                        <button id="btnShowPassword" class="btn btn-outline-secondary nofocus" type="button"><i class="fas fa-eye-slash"></i></button>
                                        <button id="btnGeneratePassword" class="btn btn-outline-primary nofocus" type="button">Generate</button>
                                    </div>
                                </div>        
                            </div>
                        </div>
                        <div id="pwConfirmContainer" class="form-group row">
                            <label for="inputPassword2" class="col-sm-2 col-form-label">*Confirm</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" value="" id="inputPassword2" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="inputRole" class="col-sm-2 col-form-label">*Role</label>
                            <div class="col-sm-10">
                                <select id="selectRole" class="form-control" name="role" placeholder="Role" required>                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">*Status</div>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked id="status">
                                    <label class="form-check-label" id="lblStatus" for="status">
                                        Account is active
                                    </label>
                                </div>
                            </div>
                        </div>  
                </div>
                <div class="modal-footer">
                    <span class="mr-auto">
                        <button id="btnDelete" type="button" class="btn btn-danger"><i class="fas fa-trash"></i> DELETE</button>
                        <button id="btnChangePassword" type="button" class="btn btn-outline-warning"><i class="fas fa-key"></i> Change Password</button>
                    </span>
                    <span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                        
                        <button id="btnModalSubmit" type="button" class="btn"><i class="fas fa-plus-circle"></i> Create/Update</button>
                    </span>
                    <span>
                        <input id="valAction" type="hidden" name="action" value="create">
                        <input id="valUserID" type="hidden" name="id" value="">
                        <input id="valStatus" type="hidden" name="status" value="">
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- JS -->
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<!-- JS -->
<script type="text/javascript">
    var listAdmins = [];
    var hasEditedDisplayName = false;
    var currentSession = <? echo json_encode($_SESSION) ?>;
    $(document).ready(function () {
        // get the list
        listAdmins = <?php echo $jsonListAdmins ?>;
        listAdminRoles =  <?php echo $jsonListAdminRoles ?>;
        generateAdminTableRows(listAdmins);
        populateRolesList(listAdminRoles)

        const inputs = document.querySelectorAll("input, select, textarea");
        inputs.forEach(input => {
            input.addEventListener(
                "invalid",
                event => {
                    input.classList.add("error");
                },
                false
            );
        });
        
        // Events
        
        $('.create-user').on('click', (e) => {
            let form = $('#userForm')[0];  
            
            // Clear all form input fields
            $('#userModal :input').val('');
            // Set the form Title
            $('#userModal #txtTitle').html(`Create an Admin`);
            // Set the form controls
            $('#valAction').val('create');
            
            //
            $('#btnModalSubmit').html(`Create`);
            $('#btnModalSubmit').attr("type","submit");
            $('#btnModalSubmit').addClass("btn-success");
            //
            form.inputPassword.disabled = false;
            form.inputPassword2.disabled = false;

            // status checkbox
            $("#valStatus").val(0); // 0 = active
            $("#status").prop('checked', true);
            $('#lblStatus').removeClass('text-danger');
            $('#lblStatus').addClass('text-success');
            $('#lblStatus').html('Activate Account');

            $('#pwContainer').show();
            $('#pwConfirmContainer').show();
            $('#btnChangePassword').hide();

            $('#btnDelete').hide();

            // Set internal variables
            hasEditedDisplayName = false;
            // Display form for edit
            $('#userModal').modal('show');
        });
        $('.edit-user').on('click', (e) => {
            let userid = $(e.currentTarget).attr('data-userid');  
            let edituser = getAdminByID(userid);
            let form = $('#userForm')[0];     

            // Clear all form input fields
            $('#userModal :input').val('');            
            // Set the form Title
            $('#userModal #txtTitle').html(`Now Editing ${edituser.user_name.toUpperCase()}`);
            
            // Set the form controls
            $('#valAction').val('update');
            //
            $('#btnModalSubmit').html(`Update`);
            $('#btnModalSubmit').attr("type","submit");
            $('#btnModalSubmit').addClass("btn-primary");
            //
            form.inputPassword.disabled = true;
            form.inputPassword2.disabled = true;
            $('#pwContainer').hide();
            $('#pwConfirmContainer').hide();
            $('#btnChangePassword').show();
            $('#btnChangePassword').attr('data-userid', userid);
            
            $('#btnDelete').show();
            $('#btnDelete').attr('data-userid', userid);
            $('#btnDelete').attr('data-email', edituser.email);
            // Populate fields w/ data
            form.valUserID.value = userid;
            form.email.value = edituser.email;
            form.username.value = edituser.user_name;
            form.firstname.value = edituser.first_name;
            form.lastname.value = edituser.last_name;
            $("#valStatus").val(parseInt(edituser.status));
            if (edituser.status == 0) {
                $("#status").prop('checked', true);
                $('#lblStatus').removeClass('text-danger');
                $('#lblStatus').addClass('text-success');
                $('#lblStatus').html('Account is active');
            } else {
                $("#status").prop('checked', false);
                $('#lblStatus').removeClass('text-success');
                $('#lblStatus').addClass('text-danger');
                $('#lblStatus').html('Account is disabled');
            }
            //form.inputPassword.value = edituser.password;
            //form.inputPassword2.value = edituser.password;
            form.selectRole.options['role_'+edituser.role].selected = true;
            // Set internal variables
            hasEditedDisplayName = true; // no auto suggestions on edit

            // Display form for edit
            $('#userModal').modal('show');
        });
        $('#status').on('click change', (e) => {
            let elem = e.currentTarget;
            let status = $(elem).prop("checked");    
            console.log("Status of checkbox: " + status);
            if (status == true) {
                $("#valStatus").val(0);
                $('#lblStatus').removeClass('text-danger');
                $('#lblStatus').addClass('text-success');
                $('#lblStatus').html('Account is active');
            } else {
                $("#valStatus").val(1);
                $('#lblStatus').removeClass('text-success');
                $('#lblStatus').addClass('text-danger');
                $('#lblStatus').html('Account is disabled');
            }
        });
        $('.toggle-status').on('click', (e) => {
            let elem = e.currentTarget;
            let id = elem.getAttribute("data-id");    
            let status = elem.getAttribute("data-status");    
            let email = elem.getAttribute("data-email");
            // status = 1 is disabled, 0 is enabled
            let doit = confirm(`Are you sure you wish to ${status == 1 ? 'ENABLE':'DISABLE'} ${email}'s account?`);
            if (doit == true) {
                let val = parseInt(status) == 0 ? 1 : 0; // toggle
                postUpdateStatus(id, val);
            }
        });
        $('#btnDelete').on('click', (e) => {
            let elem = e.currentTarget;
            let id = elem.getAttribute("data-userid");        
            let email = elem.getAttribute("data-email");
            let doit = confirm(`Are you sure you wish to DELETE ${email}'s account? \r\n ${id}`);
            if (doit == true) {
                postDelete(id);
            }
        });
        // Form events
        $('#username').on('change', (e) => {
            if (e.currentTarget.value.length > 0) {
                hasEditedDisplayName = true;
                console.log('changed the username');
            } else {
                hasEditedDisplayName = false;
                if ($('#firstname').val().length > 0 && $('#lastname').val().length > 0) {
                    e.currentTarget.value = $('#firstname').val()[0]+$('#lastname').val();
                }
            }
        });
        $('#email').on('blur', (e) => {
            let isValid = e.currentTarget.checkValidity();
            if (isValid) {
                e.currentTarget.classList.remove('error');
                e.currentTarget.setCustomValidity("");
            }
        });
        $('#inputPassword2').on('blur', (e) => {
            let field = e.currentTarget;
            let pwfield = document.getElementById("inputPassword");
            if (field.value.length > 0) {
                if (field.value !== pwfield.value) {
                    alert("Passwords must match!");
                    pwfield.select();
                }
            }
        });
        $('#email').on('change', (e) => {
            $.ajax({
                type:'POST',
                data:{
                    'action': 'check-dupe-email',
                    'email': e.currentTarget.value
                },
                url:'manage-admins-ajax.php',
                success:function(data) {
                    console.log('Duplicate Check: ' + data);
                    if (data == "true") { 
                        e.currentTarget.setCustomValidity("This email address is already in use! Try another one.");
                        e.currentTarget.reportValidity();
                    } else {
                        e.currentTarget.setCustomValidity("");
                        e.currentTarget.classList.remove('error');
                    }
                }
            });
        });
        $('#firstname').on('blur', (e) => {
            let autoUserName = !hasEditedDisplayName && e.currentTarget.value.length > 0 && $('#lastname').val().length > 0 && $('#username').val().length <= 0;
            if (autoUserName) {
                let uname = e.currentTarget.value[0]+$('#lastname').val();            
                $('#username').val(uname.toLowerCase());
            }
        });
        $('#lastname').on('blur', (e) => {
            let autoUserName = !hasEditedDisplayName && e.currentTarget.value.length > 0 && $('#firstname').val().length > 0 && $('#username').val().length <= 0;
            if (autoUserName)    {
                let uname = $('#firstname').val()[0] + e.currentTarget.value;
                $('#username').val(uname.toLowerCase());
            }
        });
        
        $('#btnShowPassword').on('click', (e)=> {
            let pwfield = document.getElementById("inputPassword");
            let pwconfirmfield = document.getElementById("inputPassword2");
            if (pwfield.getAttribute("type") == "password") {
                pwfield.setAttribute("type", "text");
                pwconfirmfield.setAttribute("type", "text");
                $(e.currentTarget).html(`<i class="fas fa-eye text-info"></i>`);
            } else {
                pwfield.setAttribute("type", "password"); 
                pwconfirmfield.setAttribute("type", "password"); 
                $(e.currentTarget).html(`<i class="fas fa-eye-slash"></i>`);               
            }
            
        });
        $('#btnShowPassword2').on('click', (e)=> {
            let pwfield = document.getElementById("pass1");
            let pwconfirmfield = document.getElementById("pass2");
            if (pwfield.getAttribute("type") == "password") {
                pwfield.setAttribute("type", "text");
                pwconfirmfield.setAttribute("type", "text");
                $(e.currentTarget).html(`<i class="fas fa-eye text-info"></i>`);
            } else {
                pwfield.setAttribute("type", "password"); 
                pwconfirmfield.setAttribute("type", "password"); 
                $(e.currentTarget).html(`<i class="fas fa-eye-slash"></i>`);               
            }
            
        });
        $('#btnGeneratePassword').on('click', (e) => {
            let hash = generateString(13);
            $('#inputPassword').val(hash);
            $('#inputPassword2').val(hash);
        });
        $('#btnChangePassword').on('click', (e)=> {
            let form = $('#passwordForm')[0];  
            let userid = $(e.currentTarget).attr('data-userid');  
            let edituser = getAdminByID(userid);

            let pwfield = document.getElementById("pass1");
            let pwconfirmfield = document.getElementById("pass2");

            // Clear all form input fields
            $('#passwordModal :input').val('');
            // Set the form controls
            $('#txtPWUser').html(edituser.email);
            $('#valAction2').val('change-password');
            $('#valUserID2').val(userid);
            
            pwfield.setAttribute("type", "password"); 
            pwconfirmfield.setAttribute("type", "password"); 
            $('#btnShowPassword2').html(`<i class="fas fa-eye-slash"></i>`);

            $('#userModal').modal('hide');
            $('#passwordModal').modal('show');
        });
        $('#passwordForm').on('submit', (e) => {
            e.preventDefault();
            let isSame = $('#pass1').val() == $('#pass2').val();
            if (isSame) {
                e.currentTarget.submit();
            } else {
                alert('Confirm: Password fields do not match!');
            }
        });
    });
</script>
<script type="text/javascript">
    // Gen/Pop
    var generateAdminTableRows = (alist) => {
        if (alist.length <= 0) {
            return;
        }
        let elem = $('#adminRowContainer');
        elem.html(''); // clear out
        for (let r = 0; r < alist.length; r++) {
            let auser = alist[r];
            let row2Insert = ``;
            row2Insert+=`
                <tr>
                    <th scope="row" class="text-muted">${auser.id}</th>                    
            `;
            if (parseInt(auser.id) == 1) {
                row2Insert+=`<td></td>`;
            } else {
                let strCSS = parseInt(auser.status) == 0 ? 'success' : 'danger';
                let currentStatus = parseInt(auser.status) == 0 ? 'Enabled' : 'Disabled';
                let strClickTo = parseInt(auser.status) == 0 ? 'Disable' : 'Enable';     

                if (parseInt(currentSession.login_role >= 1)) {
                    row2Insert+=`
                        <td></td>
                    `;
                } else {
                    row2Insert+=`
                        <td>
                            <a id="btnStatus_${auser.id}" data-id="${parseInt(auser.id)}" data-status="${parseInt(auser.status)}" data-email="${auser.email}" class="toggle-status" href="javascript:void(0);">
                                <i class="fas fa-power-off text-${strCSS}" title="Account ${currentStatus}. Click to ${strClickTo}"></i>
                            </a>
                        </td>
                    `;
                }
            }
            row2Insert+=`  
                    <td>${auser.user_name}</td>
                    <td>${auser.email}</td>
                    <td>${getAdminRoleName(auser.role)}</td>
                    <td>${auser.latest_login}</td>
                    <td>
                        <a id="btnEdit_${auser.id}" data-userid="${auser.id}" class="edit-user" href="javascript:void(0);"><i class="fas fa-edit text-primary" title="Edit"></i></a>&nbsp;
                    </td>
                </tr>
            `;
            elem.append(row2Insert);
        }
    };

    var populateRolesList = (rlist) => {
        for (let r = 0; r < listAdminRoles.length; r++) {
            let arole = listAdminRoles[r];
            $('#selectRole').append(`
                <option id="role_${arole.value}" value="${arole.value}">${arole.name}</option>
            `);
        }
    }
    
    // Helper functions
    var getAdminByID = (adminID) => {
        if (listAdmins.length > 0) {
            for (let a = 0; a < listAdmins.length; a++) {
                let admin = listAdmins[a];
                if (admin.id == adminID) {
                    return admin;
                }
            }
            return null;
        }
        return null;
    };
    var getAdminRoleName = (roleid) => {
        if (listAdminRoles.length > 0) {
            for (let ar = 0; ar < listAdminRoles.length; ar++) {
                let arole = listAdminRoles[ar];
                if (arole.value == roleid) {
                    return arole.name
                }
            }
        }
    };
    var generateString = (length) => {
        return Array.apply(null, {'length': length}).map(function () {
            var result;
            var _pattern = /[a-zA-Z0-9_\-\+\.]/;
            while (true) {
                result = String.fromCharCode(this._getRandomByte());
                if (_pattern.test(result)) {
                    return result;
                }
            }        
        }, this).join('');  
    } 
    var _getRandomByte = () => {
        if (window.crypto && window.crypto.getRandomValues) {
            var result = new Uint8Array(1);
            window.crypto.getRandomValues(result);
            return result[0];
        } else 
        if (window.msCrypto && window.msCrypto.getRandomValues) {
            var result = new Uint8Array(1);
            window.msCrypto.getRandomValues(result);
            return result[0];
        } else {
            return Math.floor(Math.random() * 256);
        }
    };
    var postUpdateStatus = (id, val) => {
        console.log("here");
        let newForm = $('<form>', {
            'action': 'manage-admins.php',
            'method': 'POST',
            'target': '_top'
        }).append($('<input>', {
            'name': 'action',
            'value': 'update-status',
            'type': 'hidden'
        })).append($('<input>', {
            'name': 'id',
            'value': id,
            'type': 'hidden'
        })).append($('<input>', {
            'name': 'status',
            'value': val,
            'type': 'hidden'
        })).appendTo("body");        
        newForm.submit();
    };    
    var postDelete = (id) => {
        console.log("here");
        let newForm = $('<form>', {
            'action': 'manage-admins.php',
            'method': 'POST',
            'target': '_top'
        }).append($('<input>', {
            'name': 'action',
            'value': 'delete',
            'type': 'hidden'
        })).append($('<input>', {
            'name': 'id',
            'value': id,
            'type': 'hidden'
        })).appendTo("body");        
        newForm.submit();
    };    
      
</script>

<?php include("footer-k.php"); ?>