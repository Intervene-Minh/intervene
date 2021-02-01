<?php






//$url='http://cybertest-it.co.za/schools';

$url='https://ellpractice.com/intervene/moodle';


//$token="3a173b0e90748a7530cc2cb43b776a9a";
$token="50b6bcb5454768a5992e15fa61fa5a69";
if(isset($_POST['submit'])){

     $imagename = $_FILES["ProfilePicture"]["name"]; 

   $params = array('component' => 'user','filearea' => 'draft', 'itemid' => 0,'filename' => $imagename,'filepath' => '/','filecontent' => base64_encode($imagename), 'contextlevel' => 'user', 'instanceid' =>2);    

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0); 

curl_setopt($ch, CURLOPT_VERBOSE, 0); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_URL, $url . '/webservice/rest/server.php?wstoken='.$token.'&wsfunction=core_files_upload&moodlewsrestformat=json');

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

$response = curl_exec($ch);

$result=json_decode($response, true);


print_r($result);
echo $result['itemid'];

$params2 = array( 'draftitemid' => $result['itemid'],'userid' => 192);

$ch2 = curl_init(); 

curl_setopt($ch2, CURLOPT_HEADER, 0);

curl_setopt($ch2, CURLOPT_VERBOSE, 0); 

curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch2, CURLOPT_URL, $url . '/webservice/rest/server.php?wstoken='.$token.'&wsfunction=core_user_update_picture&moodlewsrestformat=json');

curl_setopt($ch2, CURLOPT_POST, true);

curl_setopt($ch2, CURLOPT_POSTFIELDS, $params2);

$response2 = curl_exec($ch2);

print_r($response2);

 }


?>

<form method="POST" enctype="multipart/form-data" id="StudentForm" action="">
<input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" name="ProfilePicture" />

<input type="submit" name="submit" value="submit">	</form>
