<?php 
/*
After login :Landing page
**/
include("header.php"); 

//print_r($_SESSION);
$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$_SESSION['ses_teacher_id']));

//Signup State/////////////
$get_state_arr=unserialize($tutor_det['signup_state']);
 // print_r($tutor_det);

////////Acces website-All step completed by Tutor///
if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}

?>

<div id="main" class="clear fullwidth">
  <div class="container">
    <div class="row">
      <div id="sidebar" class="col-md-4">
        <?php include("sidebar.php"); ?>
      </div>    <!-- /#sidebar -->
      <div id="content" class="col-md-8">
        <div class="content_wrap">
          <div class="ct_heading clear">
            <h3>Home</h3>
          </div>    <!-- /.ct_heading -->
          <div class="ct_display clear">
            <div class="item-listing clear">
              <h3 class="notfound align-center">
                <a href="#">Welcome <?= $_SESSION['login_user']?>.</a>
              </h3>
            </div>
          </div>
        </div>
      </div>    <!-- /#content -->
      <div class="clearnone">&nbsp;</div>
    </div>
  </div>
</div>    <!-- /#main -->
<?php  if(isset($_SESSION['is_applicant_tutor'])&&$_SESSION['is_applicant_tutor']==1){ 

require("create-newrowAccount.php"); 
  ?>
<script type="text/javascript">
$(window).load(function()
{
    $('#myModal').modal('show');
});
</script>
<?php unset($_SESSION['is_applicant_tutor']); } //else echo 'no settttttt';?>
<script type="text/javascript">

// $(window).load(function()
// {
//     $('#myModal').modal('show');
// });


  //loadme();
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
            $("#contentBody").html('Congrulation ,you are tutor now!');
            $("#myModal").modal('show'); 

        }
    });  


}
</script>


<!-- Trigger the modal with a button -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Welcome&nbsp;<?= $_SESSION['login_user']?></h4>
      </div>
      <div class="modal-body" id="contentBody">
     <p class="text-primary"><strong>Congratulations you are now a Tutor !</strong>  </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok!</button>
      </div>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>