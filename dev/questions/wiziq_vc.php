<?php

  include("student_header.php");

  if (!$_SESSION['student_id']) {

    //print_r($_SESSION);
   header('Location: login.php');
    exit;
  }
//echo date("Y-m-d H:i"); exit;
  include("student_inc.php");
  //include("ses_live_inc.php");# Tutor sesion Live 
  $page_name='Student Welcome page';

     $loginStudentId=$_SESSION['student_id'];

      $curr_ses_id=$_SESSION['live']['live_ses_id'];
      
      // TITU START //
      $ses_det= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_schools_x_sessions_log` WHERE id = " .$curr_ses_id));
      
       $student_det= mysql_fetch_assoc(mysql_query("SELECT * FROM `students` WHERE id = " .$loginStudentId));
      
      $wiziq = mysql_fetch_assoc(mysql_query("select *from int_slots_x_student_teacher WHERE slot_id='$curr_ses_id' AND student_id = '".$loginStudentId."'"));
 
      if(empty($wiziq['wiziq_class_url']))
      {
     $access_key="J6qZvO44ueg=";
		$secretAcessKey="drKW+GfWR4O3icWrPBm8RQ==";
		$webServiceUrl="http://classapi.wiziqxt.com/apimanager.ashx";

                
                require_once("AuthBase.php");
		$authBase = new AuthBase($secretAcessKey,$access_key);
                $XMLAttendee ="<attendee_list>";
                $XMLAttendee .="
			<attendee>
			  <attendee_id>".$student_det['id']."</attendee_id>
			  <screen_name>".$student_det['first_name']." ".$student_det['last_name']."</screen_name>
                          <language_culture_name>es-ES</language_culture_name>
			</attendee>";
                
     
      $XMLAttendee .="</attendee_list>";
      
   
		$method = "add_attendees";
		$requestParameters["signature"]=$authBase->GenerateSignature($method,$requestParameters);
		$requestParameters["class_id"] = $ses_det['wiziq_class_id'];//required
		$requestParameters["attendee_list"]=$XMLAttendee;
		$httpRequest=new HttpRequest();
		try
		{
                   
			$XMLReturn=$httpRequest->wiziq_do_post_request($webServiceUrl.'?method=add_attendees',http_build_query($requestParameters, '', '&')); 
		}
		catch(Exception $e)
		{	
	  		echo $e->getMessage();
		}
 		if(!empty($XMLReturn))
 		{
 			try
			{
			  $objDOM = new DOMDocument();
			  $objDOM->loadXML($XMLReturn);
			}
			catch(Exception $e)
			{
			  echo $e->getMessage();
			}
			$status=$objDOM->getElementsByTagName("rsp")->item(0);
    		$attribNode = $status->getAttribute("status");
			if($attribNode=="ok")
			{
				
				$attendeeTag=$objDOM->getElementsByTagName("attendee");
				$length=$attendeeTag->length;
				for($i=0;$i<$length;$i++)
				{
					$attendee_idTag=$objDOM->getElementsByTagName("attendee_id");
					$attendee_id=$attendee_idTag->item($i)->nodeValue;
					
					$attendee_urlTag=$objDOM->getElementsByTagName("attendee_url");
					$attendee_url=$attendee_urlTag->item($i)->nodeValue;
                                        
                                     mysql_query("UPDATE int_slots_x_student_teacher SET attendence_id='".$attendee_id."',"
                                            . "wiziq_class_url='".$attendee_url."' WHERE slot_id='$curr_ses_id' AND student_id = '".$attendee_id."'"); 
                                            $error="You Accepted this session";
				}
 			}
			else if($attribNode=="fail")
			{
				$error=$objDOM->getElementsByTagName("error")->item(0);
				echo "<br>errorcode=".$errorcode = $error->getAttribute("code");	
				echo "<br>errormsg=".$errormsg = $error->getAttribute("msg");	
			}
	 	}
           
      }
      else
         $attendee_url= $wiziq['wiziq_class_url'];
  
//    echo "<pre>";
//    print_r($st_arr);2189692
    
     
     

?>

<!-- TITU START --> 
        <style>
    
ul.count-date {
    display: flex;
    align-items: center;
    margin-top: 35px;
    justify-content: space-between;
    max-width: 350px;
    padding-left:35%
}

ul.count-date li {
    width: 105px;
    height: 85px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 36px;
    flex-wrap: wrap;
    flex-direction: column;
    font-weight: 600;
    line-height: 1;
    flex-shrink: 0;
    margin-right: 10px;
}

ul.count-date li span {
    display: block;
    font-weight: 400;
    font-size: 12px;
}

.counting-content h3 {
    font-size: 24px;
    line-height: 1.3;
    font-weight: bold;
    margin-bottom: 10px;
}

</style>
        <!-- TITU END --> 
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div id="items_id" style="display: none;">
         CHecking sesion
      </div>
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
                
            </div>
           <!--  NExt col -->
           <div class="align-center col-md-12">
           <!-- StudentWelcome -->
        

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Session Time Left</h3>
  </div>
  <div class="panel-body">
   <div class="counting-date" style="text-align: left;margin-bottom: 50px">
<!--                            <h2 style="padding-left: 35%;color: green" >Session will be over by</h3>-->
              <?php if(strtotime($ses_det['ses_end_time']) > time()) {  ?>
                            <ul class="count-date" style="margin-top: 0px">
               <li id="timer_days" style="color:blueviolet"></li>
              <li id="timer_hours" style="color:blueviolet"></li>
              <li id="timer_mins" style="color:blueviolet"></li>
              <li id="timer_secs" style="color:blueviolet"></li>
              </ul>
              <?php } ?>
            </div> <!-- /.counting-date -->
            
            <a href="<?php echo $attendee_url;?>" id="myanchor" target="_blank" class="btn btn-primary btn-lg" style="text-align: center">GO TO WHITEBOARD</a>
  </div>
</div>
           
   
           
     
         


           <!--  Student Quziz pendings -->
            <?php # include("inc/pendnig_quiz_inc.php");?>
 
                <!--  Student Assessment -->
                
          
            <?php #  include("inc/pending_assessment_inc.php");?>
            <!--  Student Quziz pendings -->
                      
            </div>
            

           <!-- C0ntent -->
          



        </div>
    </div>
</div>

<script>
//   $(document).ready(function(){
//    $("#myanchor").click(function() {
//        alert('Item selected');
//        window.open($(this).attr("href"), 'display');
//       
//    });
//
//    $("#myanchor").click();
//});


   
    // Set the date we're counting down to
    // 1. JavaScript
    // var countDownDate = new Date("Sep 5, 2018 15:37:25").getTime();
    // 2. PHP
    var countDownDate = <?php echo strtotime($ses_det['ses_end_time']) ?> * 1000;
    var now = <?php echo time() ?> * 1000;

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        // 1. JavaScript
        // var now = new Date().getTime();
        // 2. PHP
        now = now + 1000;

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

       if(days) { $('#timer_days').html(days+' <span >Days</span>');} else {$('#timer_days').html('0 <span >Days</span>');}
       if(hours){  $('#timer_hours').html(hours+ ' <span >Hours</span>'); } else {$('#timer_hours').html('0 <span >Hours</span>');}
       if(minutes) { $('#timer_mins').html(minutes +' <span >Mins</span>');} else {$('#timer_mins').html('0 <span >Mins</span>');}
       if(seconds) {  $('#timer_secs').html(seconds + '<span >Secs</span>');} else {$('#timer_secs').html('0 <span >Secs</span>');}
       
//alert(distance)
        // If the count down is over, write some text 
        if (distance < 0) { 
            $('.counting-date').hide('slow');
           window.location.href = "student_quiz.php?id=<?php echo $ses_det['id'];?>";
        }
    }, 1000);
    </script>
    
     <!-- TITU END --> 

<!--<iframe  allow="microphone *; camera *; speakers *; usermedia *; autoplay*;"  allowfullscreen src="<?php echo $Board->data->url ;?>"  height="100%" width="100%"></iframe>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>



