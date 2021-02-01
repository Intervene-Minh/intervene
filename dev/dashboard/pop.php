<?php
/*****
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

4 Steps-Form application
1. first signup  -inital registration ,name email
2. Step-1 application
 3 - step-2 QUIZ
4- step 3- Interview

// 	 echo "<script>alert('".$msg."');location.href='".$step_2_url."';</script>";

****/
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
  loadme();
function loadme(){
    //alert("loadig");

    // $.ajax({url: "notify_refresh_top.php", success: function(result){
    //     //alert("success"+result);
    //       $("#contentBody").html(result);
    //     $("#myModal").modal('show'); 

    // }});


 var url_1="https://tutorgigs.io/dashboard/notify_refresh_top.php";  

      $.ajax({ 
            type: 'GET', 
            url: url_1, 
            data: { get_param: 'value' }, 
            dataType: 'json',
            success: function (data) { 
              //  alert(data);
            //console.log(data);  //exit();    
            var str='';
            //alert(data[0].image);
            for(i=0;i<data.length;i++){
              str+='<p>'+i+'-'+data[i].text+'</p> <br/>';
            console.log(data[i].text+"Data::"+i+"<br/>");
           }
           // Display modal
            $("#contentBody").html(str);
           $("#myModal").modal('show'); 


        
      
    
        // $(".last-updated").append(data.data.updated);
        // $(".aqiStatus").html(data.data.text); 
        }
    });  


}
</script>
</head>
<body>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" onclick="loadme()">Load me</button>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body" id="contentBody">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

</body>
</html>