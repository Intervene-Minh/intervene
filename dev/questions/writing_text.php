<?php
	$str="SELECT id,question_answer FROM `telpas_student_writing_grades` WHERE `intervene_student_id`='".$_SESSION['student_id']."' AND `question_id`='".$instanceID."'";
	$result=mysql_query($str);
	$row=mysql_fetch_assoc($result);

?>
<div class="col-12 content" style="text-align: left;padding: 20px;">
<form>
<div class="form-group">
  <label for="write">Write your answer here:</label>
  <textarea class="form-control" rows="10" id="write" name="writeAns" 
  placeholder="Write your answer here..." required><?php echo $row['question_answer'];?></textarea>
<input type="hidden" name="question_id" value="<?php echo $instanceID?>"/>
<input type="hidden" name="course_id" value="<?php echo $couserID;?>"/>
<input type="hidden" name="writeID" value="<?php echo $row['id'];?>"/>
</div>
<div class="form-group">
	<input type="submit" value="Save" class="btn btn-default" style="padding: 10px 30px; font-size: 20px;color: #fff; background: #4164ba;"></div>
</form>
</div>
<script type="text/javascript">
/* Add submit  write data by save button*/
 $(function () {
  $('form').on('submit', function (e) {
          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'submit_writing_answer.php',
            data: $('form').serialize(),
            success: function (data) {
              if(data > 0){ location.reload();
              }
            }
          });

        });

      });
</script>