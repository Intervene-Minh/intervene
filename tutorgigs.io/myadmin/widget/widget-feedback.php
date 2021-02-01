<h4 class="widget-title"><i class="fa fa-reply"></i><a href="javascript: void(0);" class="popup_feedback" data-target="#feedback_dialog" title="Feedback">Feedback</a></h4>

<!-- Form Feedback -->
<div id="feedback_dialog" class="form_dialog">
	<div class="clear fullwidth">
		<form name="feedback_form" id="feedback_form" class="form_data" method="post" action="">
			<div class="form_wrap clear fullwith">
				<label for="message">Message-XX:</label>
				<textarea name="message" id="message" class="field_data" cols="50" rows="5"></textarea>
			</div>
			<div class="button_wrap clear fullwith">
				<input type="submit" name="submit_feedback" id="submit_feedback" class="form_button submit_button" value="Send" />
				<input type="reset" name="reset_distrator" id="reset_distrator" class="form_button reset_button" value="Cancel" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	/* Popup Form Feedback */
	$('.popup_feedback').on('click', function() {
		var target	= $(this).data('target');
		var titles	= $(this).attr('title');
		// Reset form
		$(target).find('#message').val('');
		
		$(target).dialog({
			title		: titles,
			width		: '600',
			modal		: true,
			resizable	: false,
		});
	});
	
	/* Submit Form Feedback */
	$('#submit_feedback').on('click', function(event){
		event.preventDefault();
		
		// Validate
		if( $.trim( $('#feedback_dialog #message').val() ) == '' ) {
			$('#feedback_dialog #message').css('border-color', 'red');
			$('#feedback_dialog #message').focus();
			return false;
		} else {
			$('#feedback_dialog #message').css('border-color', 'inherit');
		}
		
		// Loading...
		$(this).parent('.button_wrap').prepend('<img id="loading" alt="..." src="images/loading.gif" />');
		
		var popup = $(this).parents('.form_dialog');
		var value = $.trim( $('#feedback_dialog #message').val() );
		var data = {
			action	: 'send_feedback',
			message	: value,
		};
		
		$.ajax({
			type	: 'POST',
			url		: 'inc/function-feedback.php',
			data	: data,
			// dataType: 'json',
			success	: function(response) {
				$('#loading').remove();
				if(response == 'ok') {
					alert('Thanks for your feedback!');
				} else {
					alert('Can not send feedback. Please try again later!');
				}
				$(popup).dialog('close');
			}
		});
	});
});
</script>