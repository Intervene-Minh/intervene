<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT'].'/myadmin/dompdf/autoload.inc.php'; 

// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
// Load HTML content 
$tid = $_GET['tid'];
ob_start();
include "pdf_content.php";

$html = ob_get_clean();

$dompdf->loadHtml($html); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'landscape'); 
 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF to Browser 
$dompdf->stream();

exit;
?>
