<?php
extract($_REQUEST);
$token = '14432b87ba8ea3a4896dc7707d10e71d';

$functionname = 'core_course_get_courses_by_field';
$Directorypath=$_SERVER["DOCUMENT_ROOT"].'/questions/MoodleWebServices/';
require_once($Directorypath.'cur-moodlel.php');
$domainname = $webServiceURl;
$curl = new curl;
$restformat = 'json'; 

$params = array('field'=>'category','value'=>$category);

//print_r($params);
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

$resp = $curl->post($serverurl . $restformat, $params);

$responsCourse = json_decode($resp,true);
if(!empty($responsCourse))
{?>
<option value="0">Select Assignment</option>
<?php
foreach ($responsCourse['courses'] as  $value) 
{?>
<option value="<?php echo $value['id']?>"><?php echo $value['fullname']?></option>
<?php }}
?>

           