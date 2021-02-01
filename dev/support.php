<?php require_once('header.php');?>

<html lang="en">
<head>
<title>Login to Support</title>
<meta charset="UTF-8">





<script type="text/javascript">
// PASSWORD PROTECTION SCRIPT

function TheLogin() {

var password = 'tutor';

if (this.document.login.pass.value == password) {
  top.location.href="/support/index2.html";
  window.alert("Please read the following instructions:  When the new page opens, you will see at the bottom right corner of page, a chat button.  Click the chat button.  Then enter your Name and email and choose Department Student and Proctor Support.  Please start a conversation with your name and school name. Let us know that students are logging in.  If you have any issues during the session, you can contact us through this chatbox. We will also respond with instructions and updates to help you manage and issues you encounter with our tutoring program.  Thanks!")
}
else {
  window.alert("Incorrect password, please try again or email us at support@tutorgigs.io for the correct password. You can also find this information in the Proctor Training Powerpoint emailed to you.");
  }
}

</script>





</head>

<body>

<br><br><br><br>





<div style="text-align: center; margin: 0 auto; color: #0000FF; font: normal 13px arial, sans-serif;">
Enter your password:<br>
<form name="login" style="margin: 5px 0px 0px 0px;">
<input type="text" name="pass" size="17" onkeydown="if(event.keyCode==13) return false;" style="width: 130px;"><br>
<input type="button" value="Click to Login" style="width: 134px; margin: 4px auto 4px auto;" onclick="javascript:TheLogin(this.form)">
</form>



</div>





<br><br><br>

</body>
</html>
<?php require_once('footer.php');?>