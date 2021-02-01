<?php
 /**
 https://ellpractice.com/intervene/moodle/admin/phpinfo.php
 @ Single Sign on Authentication with CAS

 *Ref:https://calnetweb.berkeley.edu/calnet-technologists/cas/casifying-your-web-application-or-web-server/cas-code-samples/cas
 ---
@< https://stackoverflow.com/questions/19940801/single-sign-on-authentication-with-cas
 ****/


// Set up some variables for CAS
$casService = 'https://auth-test.berkeley.edu/cas';
$thisService = 'https://<your server>.berkeley.edu' . $_SERVER['PHP_SELF'];
 
/*
* Check to see if there is a ticket in the GET request.
* CAS uses "ticket" for the service ticket. Bad choice of words, but
* it is what CAS uses.
*
* If the ticket exists, validate it with CAS. If not, redirect the user
* to CAS.
*
* Of course, you will want to hook this in with your application's
* session management system, i.e., if the user already has a session,
* you don't want to do either of these two things.
*
*/
if ($_SERVER["REQUEST_METHOD"] && $_GET["ticket"]) {
   if ($response = responseForTicket($_GET["ticket"])) {
      if ($uid = uid($response)) {
         echo "The user is: $uid";
      }
      else {
         echo "Could not get UID from response.";
      }
   }
   else {
      echo "The response was not valid.";
   }
}
else {
   header("Location: $casService/login?service=$thisService");
}
 
 
/*
* Returns the CAS response if the ticket is valid, and false if not.
*/
function responseForTicket($ticket) {
   global $casService, $thisService;
 
   $casGet = "$casService/serviceValidate?ticket=$ticket&service=" . urlencode($thisService);
 
   // See the PHP docs for warnings about using this method:
   // http://us3.php.net/manual/en/function.file-get-contents.php
   $response = file_get_contents($casGet);
 
   if (preg_match('/cas:authenticationSuccess/', $response)) {
      return $response;
   }
   else {
      return false;
   }
}
 
/*
* Returns the UID from the passed in response, or it
* returns false if there is no UID.
*/
function uid($response) {
   // Turn the response into an array
   $responseArray = preg_split("/\n/", $response);
   // Get the line that has the cas:user tag
   $casUserArray = preg_grep("/(\d+)<\/cas:user>/", $responseArray);
   if (is_array($casUserArray)) {
      $uid = trim(strip_tags(implode($casUserArray)));
      if (is_numeric($uid)) {
         return $uid;
      }
   }
   return false;
}
 
?>