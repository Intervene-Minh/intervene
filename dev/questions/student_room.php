<?php
include("student_header_for_room.php");

     

//echo $Board->data->url;


//print_r($Board);
?>

<!--  HTML IFRAME -->

<!-- TITU START --> 
        
        <!-- TITU END --> 

 <div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
      <div id="items_id" style="display: none;">
         CHecking sesion
      </div>
        <div class="row">
            
           <!--  NExt col -->
           <div class="align-center col-md-12">
           <!-- StudentWelcome -->
           
            <?php if($ses_det['ios_newrow'] == 1) { ?>  
              

           <div class="jumbotron" style="padding:20px !important;background-color: #fff;margin-top: 5%">
 
  <p><div class="row"  style="text-align: center"> 
           <div class="col-md-3">&nbsp;</div>
            <div class="col-md-6">
   
            </div>
             <div class="col-md-2">&nbsp;</div>
           </div>
           </p>
           
           <label style="font-size:15px">To join the newrow whiteboard please click on the below button</p>
  <p> <a href="<?php echo $Board->data->url;?>" id="refresh_link" target="_blank" class="btn btn-primary btn-lg">GO TO WHITEBOARD</a></p>
           
</div>           

 <?php } else { ?>
        <!-- /.counting-date -->

           

 <?php }  ?>

 
 
                     <!-- TITU START --> 
<script>
   
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
       

        // If the count down is over, write some text 
        if (distance < 0) { 
            $('.counting-date').hide('slow');
           window.location.href = "student_quiz.php?id=<?php echo $ses_det['id'];?>";
        }
    }, 1000);
    </script>
    
     <!-- TITU END --> 
 

  
    
 
</div>
           
   
           
     
         


           <!--  Student Quziz pendings -->
            <?php # include("inc/pendnig_quiz_inc.php");?>
 
                <!--  Student Assessment -->
                
          
            <?php #  include("inc/pending_assessment_inc.php");?>
            <!--  Student Quziz pendings -->
                      
            </div>
            

           <!-- C0ntent -->
          



        </div>
      <?php if($ses_det['ios_newrow'] != 1) { ?>       
   <iframe  allow="microphone *; camera *; speakers *; usermedia *; autoplay*;"  allowfullscreen src="<?php echo $Board->data->url ;?>"  style="width: 100%;height: 90vh !important"></iframe>


     <?php } ?>
    </div>
        
        

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



<script type="text/javascript">

is_quizz_move();

  function is_quizz_move(){

        var url_1="student_live_ses_ajax.php";
        $.ajax({ 
                     type: 'GET', 
                     url: url_1, 
                     data: { get_param: 'student_actvity',from_page: 'student_room' }, 
                     dataType: 'json',
                     success: function (data) { 
                     // alert('Test');
                     var str=' Test info='+data.status;
                     console.log('===status='+data.status);
                      // quiz_url
                       var quiz_url='';
                       // $quiz_link="student_quiz.php?id=".$row['id'];# 'quiz active'; 
                      var move_url='';
                      //console.log('sent_from='+data.sent_from);
                      move_url=data.content.quiz_url;
                      if(data.cur_board!='newrow'){
                           var groupword_board='https://intervene.io/questions/student_board_2.php';
                           console.log('Board have changed Re-direct to board2');
                           window.location.href =groupword_board;
                      }
                      if(data.status=='ok'){
                         window.location.href =move_url;
                      }
                        $("#items_id").html(str);
               
                 }
             });

       setTimeout(function(){ is_quizz_move();},3000);
   
   }

</script>

<script type="text/javascript">

$(document).ready(function(){



    $("#refresh_link").click(function(){

  //  window.location.href='student_room.php';
setTimeout(function(){ window.location.href='student_room.php'; }, 500);


    });

});
</script>

