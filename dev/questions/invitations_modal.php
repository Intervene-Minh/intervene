<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invitations</h4>
            </div>
            <div class="modal-body">
                <section id ="contact">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="header-section text-center">
                                <h2>Invite Your Teachers</h2>
                                <p>You can either copy and paste the URL below and send your own invite<br>or just insert your teachers' emails and we'll send it!<br>Here's the link your teachers need: <a style="color:blue">http://www.intervene.io/questions/signup.php</a></p>
                                <hr class="bottom-line">
                            </div>

                            <form id="form-invitation" method="post" action="email-invite.php">
                                <div id="mailboxes" class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                </div>

                                <p>Please select registered grades to share:</p>
                                <?php
                                if (count($registered_folders) > 0) {
                                    echo "<div class='row'>
                                        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;padding: 0px;margin: 0px -4px 0px 3px;'>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                         <b>SmartPrep</b>
                                        </div>";
                                    foreach ($registered_folders as $key => $val) {
                                        echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
						<input type='checkbox' class='folders' name='folders[]' value='{$key}' /> {$val}
						</div>";
                                    }
                                    echo "</div>";
                                    
                                ?>
                                <?php
                            $school_data_dash_res = mysql_query('SELECT * FROM school_permissions WHERE school_id = \'' . $_SESSION['schools_id'] . '\' ');
                            if (mysql_num_rows($school_data_dash_res) > 0) {
                                echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;margin-left: 10px;padding: 0;width:48%;'>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                    <b>Data Dash</b>
                                </div>";
                                while ($school_permission = mysql_fetch_assoc($school_data_dash_res)) {
                                    ?>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                <input type="checkbox"  name="teacher_invite_permission[]" value="<?php print $school_permission['grade_level_id'] ?>"  /><?php print $school_permission['grade_level_name'] ?>
                                </div>
                                <?php }?>
                                
                                 <?php  if($ses_allowed=='yes'){  ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-info1">
                                        <input  name="sessions_access_allow"
                                                value="yes" type="checkbox">Tutor session access 
                                </div> <?php }?>
                                <?php
                                echo "</div>";
                            }
                            echo "</div><br>";
                            echo '<input type="submit" name="invite" value="Invite" class="btn btn-primary" >';
                                } else {
                                    echo "<p>Please purchase grades to share!</p>";
                                }
                            ?>
                            </form>
                        </div>
                    </div>
                </section>
            </div>	<!-- /Invite -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>