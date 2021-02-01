<h4 class="widget-title">
	<i class="fa fa-youtube-play"></i><a href="javascript: void(0);" class="popup_howitworks" data-target="#howitworks_dialog" title="How it works">How it works</a>
</h4>

<!-- Form Feedback -->
<div id="howitworks_dialog" class="form_dialog">
	<div class="clear fullwidth">
		<form class="form_data" method="post" action="">
			<div class="form_wrap clear fullwith">
				<video id="InterveneVideo" controls width="640" height="360">
					<source src="../images/Intervene2.mp4" type="video/mp4">
					Your browser does not support HTML5 video.
				</video>
			</div>
			<div class="button_wrap clear fullwith">
				<input type="reset" class="form_button reset_button" value="Skip" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	// Play video when dialog is opened
	$('#howitworks_dialog').on('dialogopen', function( event, ui ) {
		var myVideo = document.getElementById("InterveneVideo");
		myVideo.play();
	});
	
	// Pause video when dialog is closed
	$('#howitworks_dialog').on('dialogclose', function( event, ui ) {
		var myVideo = document.getElementById("InterveneVideo");
		myVideo.pause();
	});
	
	/* Popup Form Feedback */
	$('.popup_howitworks').on('click', function() {
		var target	= $(this).data('target');
		var titles	= $(this).attr('title');
		
		$(target).dialog({
			title		: titles,
			width		: '680',
			modal		: true,
			resizable	: false,
		});
	});
	
	<?php
	if( isset($_SESSION['firstlogin']) && $_SESSION['firstlogin'] ) {
		unset($_SESSION['firstlogin']);
		echo "$('.popup_howitworks').trigger('click');";
	}
	?>
});
</script>