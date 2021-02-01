<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Board</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<?php $boradUrl='https://intervene.io/questions/lti-hit-page-student.php'?>
<div class="container">

		<h1>Student Board</h1>
	<div class="embed-responsive embed-responsive-16by9">
    <iframe class="embed-responsive-item" scrolling="no" frameborder="0"  src="<?php echo $boradUrl?>" allow="microphone ; camera ; speakers ; usermedia ; autoplay*;"allowfullscreen   height="100%" width="100%"></iframe>
  </div>
</div>
<div class="countdown"></div>
</body>
</html>
