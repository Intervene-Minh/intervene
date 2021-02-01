<?php

/*  check user is login  or not*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ellpractice.com/intervene/moodle/check-login.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
$res=curl_exec($ch);




print_r($res);

$resData= json_decode($res);

print_r($resData);
echo $checkLogin=$resData->statusLog;

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

$(document).ready(function(){
$.ajax({
  method: "POST",
  url: "https://ellpractice.com/intervene/moodle/check-login.php",
  data: { data:1}
})
  .done(function( data ) {
    alert( "Data Saved: " + data );
  });

});
</script>