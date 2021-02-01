<?php 
include("header.php");

//

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script>
$(document).ready(function(){
  $(".item_name").click(function(){

      $('#results_id').html('Please wait...');
        var user_id= $(this).attr('data-uid');
          // var user_id=5;
          console.log('UID:'+user_id);
    //////////////////////////////
    $.post("https://intervene.io/ajax/user_data.php",
    {
      name: "Donald Duck",
      uid: user_id,
      city: "Duckburg"
    },
    function(data,status){
      //alert("Data: " + data + "\nStatus: " + status);
      // open popp
      if(status=='success'){// #myModal
       $('#results_id').html(data+' UID-'+user_id);
        $("#myModal").modal('show');
      }


    });
  });
});
</script>



<script type="text/javascript">
//
// $(function(){
//   console.log('Tsesing======');
//   var dat_url="http://intervene.io/ajax/user_data.php";
//   var txtbox=55; var hiddenTxt='tse';
//   $.ajax({
//         type: 'post',
//         url: dat_url,
//         dataType: 'json',
//         data: {
//             txt: txtbox,
//             hidden: hiddenTxt
//         },
//         cache: false,
//         success: function (returndata) {
//             alert('Xreturndata');exit();
//         },
//         error: function () {
//             console.error('Failed to process ajax !');
//         }
//     });
  

//   ///////
// });
  
</script>

</head>
<body>

<div class="container">
  <h2>Click popup</h2>


  <!-- Button to Open the Modal -->
 <!--  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Open modal
  </button> -->

   <button type="button" id="viewid" >
    Open 
  </button>
         
         <!-- Contnt -->
         <?php 

          $results = mysql_query(" SELECT * FROM `demo_users` WHERE 1");
         while($row= mysql_fetch_assoc($results) ) {
            # code...
          

         ?>

         <p> <strong>Name:<?=$row['email']?></strong>

          <a class="item_name"

           data-uid="<?=$row['id']?>"  > View<?=$row['id'];?> </a>

         </p>
         <?php  } ?>

           <!-- Contnt -->








  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">User info</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <div id="results_id">
          
        </div>
          Modal body..
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>



</body>
</html>
