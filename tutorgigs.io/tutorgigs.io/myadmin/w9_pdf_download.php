<?php
session_start();
include('inc/connection.php'); 
include('inc/public_inc.php'); 

require_once $_SERVER['DOCUMENT_ROOT'].'/myadmin/dompdf/autoload.inc.php'; 

// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
// Load HTML content 
$tid = $_GET['tid'];
$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$tid));
$get_state_arr=($tutor_det['all_state']=='no')?$tutor_det['all_state_url']:'home.php'; 

ob_start(); 
if($get_state_arr == 'legal_stuff.php')
   include "w9_pdf_legal_content.php";
else
    include "w9_pdf_content.php";
    
$html = ob_get_clean();
//echo $html; exit;
$dompdf->loadHtml($html); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'landscape'); 
 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF to Browser 
$dompdf->stream();

exit;
?>
