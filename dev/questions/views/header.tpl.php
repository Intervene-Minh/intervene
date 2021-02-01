
    <div id="header" class="clear fullwidth" style="height:auto;">
    <div class="container">
      <div class="row">
        <div class="logo">
          <h1>Intervene | Question Database</h1>
          <img alt="Less Test Prep" src="<?php print $base_url.'uploads/schoollogo/'.$logo; ?>" height="100"  />
        </div>    <!-- /.logo -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-main"> <!-- button menu mobile --><!-- data-target -->
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php 
                    // get current file name
                    $file_name = basename($_SERVER['PHP_SELF']);
                    $first_part = explode('.', $file_name);
                    // create array file name of menu
                    $directories = array("folder");
                    // create class active for menu
                    foreach ($directories as $folder){
                        $active[$folder] = ($first_part[0] == $folder)? "current-item-menu":"";
                    }
                ?>
                
        <div class="users">
          <p>
            <?php if( isset($_SESSION['login_user']) || $_SESSION['student_id'] ) : ?>
              <a href="profile.php" class="welcome">Welcome <?php if($_SESSION['student_id'] !=''){echo $_SESSION['student_name']; }else{ echo $_SESSION['login_user'];}?>!</a>
              <a href="logout.php" class="links"><span class="glyphicon glyphicon-arrow-right"></span> Logout</a>
            <?php elseif( !isset($_SESSION['schools_id']) ) : ?>
              <a href="login.php" class="links no_padding">Login</a>/
              <a href="signup.php" class="links no_padding">Sign up</a>
            <?php endif;?>
          </p>
        </div>    <!-- /.users -->
        <div class="clearnone">&nbsp;</div>
      </div>
    </div>
  </div>  
