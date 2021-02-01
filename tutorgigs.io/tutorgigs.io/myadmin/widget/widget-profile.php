<h4 class="widget-title"><i class="fa fa-user"></i>Profile</h4>
<div  class="widget-content">
<!--	<p class="add_new"><i class="fa fa-pencil-square-o"></i><a href="profile.php">Edit Profile</a></p>-->
	<?php
        if ((int)$_SESSION['login_role'] < 10) {

    ?>
    <p class="list" title="Manage TutorGigs Admins">
		<i class="fa fa-user"></i>
        <a href="manage-admins.php" style="color:green">Manage Admins</a>
	</p>
    <?php 
        } 
    ?>
	<p class="list"><i class="fa fa-sign-out"></i><a href="logout.php">Logout</a></p>
</div>