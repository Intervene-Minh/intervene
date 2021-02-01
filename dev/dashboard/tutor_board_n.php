<?php 
 session_start();
 ob_start();
 $home_url="https://tutorgigs.io/"; 
include('inc/connection.php'); 
include('inc/public_inc.php'); 
require_once('inc/check-role.php');

echo '======================';
?>

<!-- Access Control- <a href="tutor_board.php?ac=toggle_board"   class="btn btn-success btn-xs">
                              Switch / toggle to Groupworld</a> |&nbsp; &nbsp;
                              <a href="tutor_board_live.php"   class="btn btn-success btn-xs">
                              Media Devices are Unavailable , Click here </a>



                              <hr/>
                              <br/> -->







<!DOCTYPE html>
<html>
<title>Board options</title>
<body>
Access Control- <a href="tutor_board.php?ac=toggle_board"   class="btn btn-success btn-xs">
                              Switch / toggle to Groupworld</a> |&nbsp; &nbsp;
                              <a href="tutor_board_live.php"   class="btn btn-success btn-xs">
                              Media Devices are Unavailable , Click here </a>



                              <hr/>
                              <br/>
<!-- <iframe src="https://tutorgigs.io/dashboard/newrow_code.php?autoplay=1&loop=1&autopause=0" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe> -->

<!-- <iframe src="https://tutorgigs.io/dashboard/newrow_code.php"  allow="microphone; camera"  height="100%" width="100%">
</iframe> -->

<!-- <iframe src="https://tutorgigs.io/dashboard/newrow_code.php"  height="100%" width="100%" ></iframe> -->

<iframe src="https://smart.newrow.com/#/room/AAA-111" allow="microphone; camera" width="1000" height="640"></iframe>

</body>
</html>
