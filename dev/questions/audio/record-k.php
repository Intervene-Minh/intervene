<style type="text/css">#recordingslist{ list-style: none }
#recordingslist li { padding: 10px}
</style>
<div class="RecordCls">
	<div id="controls" style="text-align: center;">
	<?php
	$str="SELECT audio_file FROM `telpas_student_course_audios` WHERE `course_id` = '".$couserID."' AND `intervene_student_id`='".$_SESSION['student_id']."' AND `question_id`='".$instanceID."'";
	$result=mysql_query($str);
	$audioListTotal=mysql_num_rows($result);
	if($audioListTotal > 1 ){ ?>
		<ol id="recordingsList" style="width: 100%;list-style: none">
			<?php
			while($audioList = mysql_fetch_assoc($result)) { ?>
				<li style="padding: 5px;"><audio controls="" src="<?php echo $audioList['audio_file']?>"></audio></li>
				<?php } ?>
				</ol>
                <?php } else { ?>
                        <span id="meter" style="display: none;">
                            <canvas id="canvas" width="20" height="40" style="background-color: grey"></canvas>
                            <br>
                            <span id="strLevel"></span>                        
                        </span>
                        <br/>
                        <p class="msg"><p>
						<button onclick="startRecording(this);" class="btn btn-success" id="recBtn">Record</button>
						<button onclick="stopRecording(this);" disabled class="btn btn-danger"
						 id="strBtn"
						>Stop</button>
                        <ul id="recordingslist"></ul>
                        <!-- Pop up -->
                        
						<!-- ------- -->                   
                        <pre id="log" class="hide"></pre>
                        
						<script src="audio/record_event-k.js"></script>
						<script type="text/javascript"> var datstr ='<?php echo $dataString?>';</script>
						<script src="audio/recordmp3.js"></script>
						<?php } ?>
					</div>