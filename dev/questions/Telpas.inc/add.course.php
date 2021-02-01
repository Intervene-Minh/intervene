<?php
error_reporting(-1);
ini_set('display_errors', 1);
$curr_time= date('Y-m-d H:i:s');
/////////////

// include database and object files
include_once 'config/database.php';
include_once 'libraries/Telpas_quiz.php';
include_once 'libraries/Product.php';
 // echo  'Add Telpas course'; die ;

// get database connection
$database = new Database();
$db = $database->getConnection();
///////Global///////////////


///Add Coursee
$TelpasCourse=new Telpas_quiz($db);
$TelpasCourse->course_id=21;
$TelpasCourse->telpas_uuid=52;
$TelpasCourse->intervene_uuid=13501;
$TelpasCourse->created_at=$curr_time;
 $Result=$TelpasCourse->create();
 print_r($Result);
 echo 'Record added ::';
die; 



///////////////////

$Telpas_course= new Telpas_quiz($db);
$Results=$Telpas_course->listAll();
//$html='List of Telpas_course::';

$toal_products=$Telpas_course->countAll();
echo  $toal_products , 'toal_products=='; 

$sql_insert = "INSERT INTO Telpas_course_users(course_id,telpas_uuid)
    VALUES(" . $db->quote($dept_name) .")";

//die; 



?>
<?php
/*
@Views:products

**/
// Show list of product 
while ($row = $Results->fetch(PDO::FETCH_ASSOC)){
	print_r($row); die; 
        @extract($row);  die; 
?>
<p style="border:1px solid red;">
	Id::<?php print($row['id'])?>, 
	Product:<?php print($row['product_name'])?> 
 : Code:<?php print($row['sku_code'])?>
 <br/> </p>

<?php }?>
