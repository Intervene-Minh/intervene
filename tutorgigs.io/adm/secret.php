<?php
    require("config.php");
    if(empty($_SESSION['user'])) 
    {
        header("Location: index.php");
        die("Redirecting to index.php"); 
    }
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>P2G Admin</title>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="../tutor/js/bootstrap.min.js"></script>    
    <link href="../tutor/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
    
    
    <style type="text/css">
        /*body { background: url(assets/bglight.png); }*/
        .hero-unit { background-color: #fff; }
        .center { display: block; margin: 0 auto; }
    </style>
</head>

<body>

<div class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="navbar-brand"><img class="img-responsive pull-left" src="../tutor/img/logo80x120.png" height="50" width="40"></a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li><a href="#" ><span class="glyphicon glyphicon-user"></span> <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></a></li>
          <li class="divider-vertical"></li>
          <li><a href="logout.php">Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>



</body>
</html>