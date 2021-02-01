<script type="text/javascript">
/* get result by category*/
$('#CatName').change(function()
{

    var catID=$(this).val();
    if(catID > 0)
    {

      $('#loaderImg').show();
        $.ajax({
          url:"MoodleWebServices/get_studentCourseResultAjax.php",
          type:"POST",
          data:{category:catID},
          success:function(data)
          {
            $('.tableCls').show();
            $('#loaderImg').hide();
            $('#dataRow99').html(data);

          }
  });
}
});

/* Add Reading Lesting Grade by slect list*/
function _getScore(type,course_id,Telstuid,Instuid) {

  console.log('Course:'+type);
  console.log(course_id);
  console.log(Telstuid);
  $('.'+type+Telstuid).hide(); //Hide request button.
  $('#'+type+Telstuid).text('processing...');

  $.ajax({
          type: 'post',
          url: 'MoodleWebServices/get_student_score_ajax.php',
          timeout: 3000,
          data:{studentID:Telstuid,course_id:course_id,type:type,intervene_student_id:Instuid},
          success: function (data) {
            console.log(data);
             $('#'+type+Telstuid).html(data);
             $('.'+type+Telstuid).hide();
          }
          });
  }
/* open audio modal*/
	function openAudioModal(str,course_id){
    $('.actionUrl').val('submit_speaking_grade.php');
    $.ajax({
      url:"get_audio_modal.php",
        type:"POST",
        data:{InereveneStuID:str,course_id:course_id},
        success:function(data)
        {
          $('#audioModal').modal('show');
          $('.RecordingList').html(data);
        }
    });
}
/* Add Speacking Grade by slect list*/
function _addgrade(str,id,stuid,course_id) {
           
  $.ajax({
          type: 'post',
          url: 'submit_speaking_grade.php',
          data:{action:'addGrade',id:id,score:str,stuid:stuid,course_id:course_id},
          success: function (data) {
          $('.grade').html(data);
          $('#speaking_'+stuid+'_'+course_id).removeClass('bgcolor pending-grading');
          $('#listen'+stuid).html(data);


            }
          });
  }
 /* Add Listening Grade by save button*/
 $(function () {
  $('form').on('submit', function (e) {
          e.preventDefault();
          if($('#action_type').val() == 'writing')
          {
                var actionURl=$('.actionUrl').val();
                var writing_stu_id = $('#writing_stu_id').val();
                var writing_course_id = $('#writing_course_id').val();


                $.ajax({
                  type: 'post',
                  url: actionURl,
                  data: $('form').serialize(),
                  success: function (data) {
                    //alert('score was submitted');
                   // $('.grade').html(data);

                    $('#write'+writing_stu_id).html(data);
                    $('#writing_'+writing_stu_id+'_'+writing_course_id).removeClass('bgcolor pending-grading');
                    $('#writtingModal').modal('hide');
                  }
                });
         }
         else  if($('#action_type').val() == 'speaking')
         {
                var actionURl=$('.actionUrl').val();
                var speaking_stu_id = $('#speaking_stu_id').val();
                var speaking_course_id = $('#speaking_course_id').val();


                $.ajax({
                  type: 'post',
                  url: actionURl,
                  data: $('form').serialize(),
                  success: function (data) {
                    //alert('score was submitted');
                    $('.grade').html(data);



                    $('#listen'+speaking_stu_id).html(data);
                    $('#speaking_'+speaking_stu_id+'_'+speaking_course_id).removeClass('bgcolor pending-grading');
                    $('#audioModal').modal('hide');
                  }
                });
         }

        });

      }); 

/* Add Writting Grade by slect list*/
function _addgradeWrite(str,id,stuid,course_id) {
  $.ajax({
            type: 'post',
            url: 'submit_writing_grade.php',
            data:{action:'addGradeWrite',id:id,score:str,stuid:stuid,course_id:course_id},
            success: function (data) {
              $('.grade').html(data);
              $('#write'+stuid).html(data);
              $('#writing_'+stuid+'_'+course_id).removeClass('bgcolor pending-grading');
            }
          });
  }

/* get question description from moodle server*/
function _get_description(quID){
                 $('.collapse').removeClass('in');
  $.ajax({
            type: 'post',
            url: 'get_question_dec.php',
            data:{instanceID:quID},
            success: function (data) {
              $('.spin').hide();

              $('.data'+quID).html(data);
            }
          });
}
/*open Writting modal*/
  function openWrittingModal(str,course_id){
     $('.actionUrl').val('submit_writing_grade.php');
   $.ajax({
      url:"get_writting_modal.php",
        type:"POST",
        data:{InereveneStuID:str,course_id:course_id},
        success:function(data)
        {
          $('#writtingModal').modal('show');
          $('.WrittingList').html(data);
        }
    });
}


</script>
<!-- this is audio recording list modal-->
 <div class="modal fade" id="audioModal" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Audio List</h4>
        </div>
        <div class="modal-body">
		  <div class="row">
		       <div class="col-md-3">
			        <p class="w-100">
					   Directions for user:<br>
					   1 = Beginner;<br>
					   2 = Intermediate;<br>
					   3 = Advanced;<br>
					   4 = Advanced High</p>
					<p class="w-100">Calculation:<br> Points earned/Point Possible
					</p>
         <a href="javascript:void(0)" class="btn btn-info toggleBtn" data-toggle="collapse" data-target="#buttonRubric"
         >View Speaking PLD Rubric</a>
			   </div>
         <div class="col-md-9">
          <form>
            <input type="hidden" value="submit_speaking_grade.php" id="speaking_url" class="actionUrl">
            <div class="RecordingList"></div></form>
          <div class="row br-2 text-center">
            <div id="buttonRubric" class="collapse">
              <img src="https://intervene.io/questions/uploads/PLD_Speaking_Rubric.png" class="desImg"></div>
            </div>	
			   </div>
		  </div>
        </div>
      </div>
    </div>
  </div>

  <!-- this is writting recording list modal-->
 <div class="modal fade" id="writtingModal" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Writting </h4>
        </div>
        <div class="modal-body">
      <div class="row">
           <div class="col-md-3">
              <p class="w-100">
             Directions for user:<br>
             1 = Beginner;<br>
             2 = Intermediate;<br>
             3 = Advanced;<br>
             4 = Advanced High</p>
          <p class="w-100">Calculation:<br> Points earned/Point Possible
          </p>
         <a href="javascript:void(0)" class="btn btn-info toggleBtn" data-toggle="collapse" data-target="#buttonRubricWritting"
         >View Writing PLD Rubric</a>
         </div>
         <div class="col-md-9">
          <form>
          <input type="hidden" value="submit_writing_grade.php" id="writing_url" class="actionUrl">

            <div class="WrittingList"></div></form>
          <div class="row br-2 text-center">
            <div id="buttonRubricWritting" class="collapse">
              <img src="https://intervene.io/questions/uploads/PLD_Writting_Rubric.png" class="desImg"></div>
            </div>  
         </div>
      </div>
        </div>
      </div>
    </div>
  </div>
