<?php
    require_once("../student_header.php");
    if (!$_SESSION['student_id']) {
        header('Location: ../login.php');
        exit;
    }

    //require_once("../student_inc.php");
	//require_once("../MoodleWebServices/get_courseListOnly.php");
    //require_once("../telpas_query_page.php");
?>

<div>
   <div class="container">
      <p> Zoho Desk Testing </p>   
   </div>
</div>
<script type="text/javascript" > 
    window.ZohoHCAsap=window.ZohoHCAsap || function(a,b) {
        ZohoHCAsap[a]=b;
    };
    (function(){
        var d=document; 
        var s=d.createElement("script");
        s.type="text/javascript";
        s.defer=true; 
        s.src="https://desk.zoho.com/portal/api/web/inapp/470696000000196001?orgId=706230762"; 
        d.getElementsByTagName("head")[0].appendChild(s); 
    })(); 
</script>


