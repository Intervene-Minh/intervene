// JavaScript Document
$(document).ready(function() {
	/* Search Objective */
        $('#datadash').toggleClass( "open" );
        $('#datadash a').click(function(){
            $('.group_1').slideToggle();
            $('#datadash').toggleClass( "open" );
            if($('#datadash').hasClass('open')) {
                $('#datadash').removeClass('close-but');
            }else{
                $('#datadash').addClass('close-but');
            }
        }); 
         $('#smartprep').toggleClass("open");
        $('#smartprep a').click(function(){
            $('.group_2').slideToggle();
             $('#smartprep').toggleClass("open");
              if($('#smartprep').hasClass('open')) {
                $('#smartprep').removeClass('close-but');
            }else{
                $('#smartprep').addClass('close-but');
            }
        });
	$('#search_object').on('keyup', function() {
		var s_field = $(this);
		var keyword = $.trim( $(this).val() );
		var question= $(this).data('question');
		
		var objects = [0];
		$('#form_question .suggest:checked').each(function() {
			objects.push( $(this).val() );
		});
		
		if( keyword == '' ) {
			$('#suggestion').hide();
		} else {
			$('#suggestion').show();
		}
		
		$(this).siblings('.fa').hide();
		$(this).siblings('.searching').show();
		var data = {
			action	: 'search_object',
			keyword	: keyword,
			objects	: objects,
			question: question,
		};
		$.ajax({
			type	: 'POST',
			url		: 'inc/form.php',
			data	: data,
			success	: function(response) {
				$('#defaultObj').hide();
				$('#suggestion').html(response);
				$(s_field).siblings('.fa').show();
				$(s_field).siblings('.searching').hide();
			}
		});
	});
	
	$('#form_question .suggest:checked').on('click', function() {
		if( !$(this).is(':checked') )
			$(this).removeAttr('checked');
	});
	
	/* Change answer type */
	$('#question_type').on('change', function() {
		var type = $(this).val();
		if( type == 1 ) {
			$('.lv_label_for_multiple_choice').show();
			$('#multiple_choice').show();
			$('#multiple_choice_spanish').show();
			$('#open_response').hide();
		} else {
			$('.lv_label_for_multiple_choice').hide();
			$('#multiple_choice').hide();
			$('#multiple_choice_spanish').hide();
			$('#open_response').show();
		}
	});
	
	/* Correct button */
	$('#form_question .fa-check').on('click', function() {
		$('#form_question .fa-check').removeClass('fa-check-square-o');
		$('#form_question .fa-check').addClass('fa-square-o');
		$(this).removeClass('fa-square-o');
		$(this).addClass('fa-check-square-o');
		$(this).siblings('input[name=correct]').prop('checked', true);
	});
	
	/* Upload images */
	$('#form_question .uploads').on('change', function() {
		var field = $(this);
		var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(theFile) { // set image data as background of div
                $(field).siblings('.upload_button').html('<img src="' + this.result + '" height="40" />');
                $(field).siblings('.update_image').show();
				
				var image = new Image();
				image.src = theFile.target.result;

				image.onload = function() {
					$(field).siblings('.hidden_wid').val(this.width);
					$(field).siblings('.hidden_hig').val(this.height);
				};

            }
        }
	});
	$('#form_question .upload_button').on('click', function() {
		$(this).siblings('.uploads').trigger('click');
	});
	
	/* Popup Dialog Update Image Dimension */
	$('#form_question .update_image').on('click', function() {
		// Reset form
		var hidden_wid = $(this).siblings('.hidden_wid').val();
		var hidden_hig = $(this).siblings('.hidden_hig').val();
		$('#demension_dialog #image_width').val(hidden_wid);
		$('#demension_dialog #image_height').val(hidden_hig);
		
		var item_width = $(this).siblings('.hidden_wid').attr('id');
		var item_height = $(this).siblings('.hidden_hig').attr('id');
		$('#demension_dialog #item_width').val('#form_question #' + item_width);
		$('#demension_dialog #item_height').val('#form_question #' + item_height);
		
		$('#demension_dialog').dialog({
			title		: 'Update image dimension',
			width		: '600',
			modal		: true,
			resizable	: false,
		});
	});
	
	/* Submit Update Image Dimension */
	$('#submit_dimension').on('click', function(event) {
		event.preventDefault();
		
		var image_width = $.trim($('#demension_dialog #image_width').val());
		var image_height = $.trim($('#demension_dialog #image_height').val());
		var item_width = $('#demension_dialog #item_width').val();
		var item_height = $('#demension_dialog #item_height').val();
		
		$(item_width).val(image_width);
		$(item_height).val(image_height);
		
		$('#demension_dialog').dialog('close');
	});
	
	/* Validate form */
	$('#question_submit').on('click', function(event) {
		var valid = true;
		
		// Validate question name and organize
		$('#form_question .required').each(function() {
			if( $(this).val() == '' ) {
				$(this).css('border-color', 'red');
				$(this).focus();
				valid = false;
				return false;
			} else {
				$(this).css('border-color', '#cccccc');
			}
		});
		
		if( !valid )
			return false;
		
		// Validate question details
		var question = tinyMCE.get('question_question').getContent();
		if( question == '' ) {
			alert('Please enter your question!');
			valid = false;
			return false;
		}
		
		// Validate objective
		if( $('#form_question .suggest:checked').length == 0 ) {
			$('#form_question #search_object').css('border-color', 'red');
			$('#form_question #search_object').focus();
			alert('Please choose at least one objective!');
			valid = false;
			return false;
		} else {
			$('#form_question #search_object').css('border-color', '#cccccc');
		}
		
		/* // Validate list answers
		$('#form_question ul#multiple_choice input.answers').each(function() {
			if( $(this).val() == '' && $(this).parents('ul#multiple_choice').is(':visible') ) {
				$(this).css('border-color', 'red');
				$(this).focus();
				valid = false;
				return false;
			} else {
				$(this).css('border-color', '#cccccc');
			}
		}); */
		for (i=0; i < tinyMCE.editors.length; i++){
			if(tinyMCE.editors[i].targetElm.className == 'answers' && $('#multiple_choice').is(':visible')){
				var content = tinyMCE.editors[i].getContent();
				
				if( content == '' ) {
					console.log(tinyMCE.editors[i]);
					alert('Please enter your answer!');
					valid = false;
					return false;
				}
			}
			
		}
		
		if( !valid )
			return false;
		
		// Validate radio correct button
		if( $('#form_question #multiple_choice').is(':visible') && !$('#form_question input[name=correct]').is(':checked') ) {
			valid = false;
			alert('Please choose the correct answer!');
			return false;
		}
		
		var answer = tinyMCE.get('response_answer').getContent();
		if( $('#form_question #open_response').is(':visible') && answer == '' ) {
			alert('Please enter your answer!');
			valid = false;
			return false;
		}
		
		if( !valid )
			return false;
	});
	
	$('#passage_submit').on('click',function(event){
		// event.preventDefault();
		var title = $('#passage_title').val();
		var content = tinyMCE.get('passage_content').getContent();
		if(title==''){
			$('#passage_title').css('border-color', 'red');
			$('#passage_title').focus();
			return false;
		}
		if(content==''){
			alert('Please enter your content!');
			return false;
		}
	});
	
	$('#lesson_submit').on('click', function(event){
		var title = $('#lesson_name').val();
		var distrator = $('#distrator_id').val();
		var content = tinyMCE.get('lesson_desc').getContent();
		if(title==''){
			$('#lesson_name').css('border-color', 'red');
			$('#lesson_name').focus();
			return false;
		} else {
			$('#lesson_name').css('border-color', 'inherit');
		}
		if(distrator==0){
			alert('Please select your distrator!');
			return false;
		}
		if(content==''){
			alert('Please enter your content!');
			return false;
		}
	});
});

function open_asses(url, val) {
    document.location.href=url+val;
}
