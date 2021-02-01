// JavaScript Document
$(document).ready(function() {
	/* Search Objective */
        $('#datadash').toggleClass( "open" );
        $('#datadash a').click(function(){
            $('.group_1').slideToggle();
            $('#datadash').toggleClass( "open" );
            if($('#datadash').hasClass('open')) {
                $('#datadash').removeClass('close-but');
                return false;
            }else{
                $('#datadash').addClass('close-but');
                return false;
            }
        }); 

         $('#datadashw').toggleClass("open");
        $('#datadashw a').click(function(){
            $('.group_3').slideToggle();
             $('#datadashw').toggleClass("open");
              if($('#datadashw').hasClass('open')) {
                $('#datadashw').removeClass('close-but');
                return false;
            }else{
                $('#datadashw').addClass('close-but');
                return false;
            }
        });

        //Telpas Pro Menu
        $('#telpas_menu').toggleClass("open");
        $('#telpas_menu a').click(function(){
            $('.group_4').slideToggle();
             $('#telpas_menu').toggleClass("open");
              if($('#telpas_menu').hasClass('open')) {
                $('#telpas_menu').removeClass('close-but');
                return false;
            }else{
                $('#telpas_menu').addClass('close-but');
                return false;
            }
        });
        //Telpas Pro Menu
        
         $('#smartprep').toggleClass("open");
        $('#smartprep a').click(function(){
            $('.group_2').slideToggle();
             $('#smartprep').toggleClass("open");
              if($('#smartprep').hasClass('open')) {
                $('#smartprep').removeClass('close-but');
                return false;
            }else{
                $('#smartprep').addClass('close-but');
                return false;
            }
        });
        
        // TITU START // 
         // Folder Menu
         $('#folder').toggleClass("close-but");
        $('#folder a').click(function(){
            $('.folder_widget').slideToggle();
             $('#folder').toggleClass("open");
              if($('#folder').hasClass('open')) {
                $('#folder').removeClass('close-but');
                return false;
            }else{
                $('#folder').addClass('close-but');
                return false;
            }
        });
        
        
         $('#Distractor').toggleClass("open");
          $('.Distractor_widget').slideToggle();
        $('#Distractor a').click(function(){
            $('.Distractor_widget').slideToggle();
             $('#Distractor').toggleClass("open");
              if($('#Distractor').hasClass('open')) {
                $('#Distractor').removeClass('close-but');
                return false;
            }else{
                $('#Distractor').addClass('close-but');
                return false;
            }
        });
        
        
        
         $('#Passages').toggleClass("open");
          $('.Passages_widget').slideToggle();
        $('#Passages a').click(function(){
            $('.Passages_widget').slideToggle();
             $('#Passages').toggleClass("open");
              if($('#Passages').hasClass('open')) {
                $('#Passages').removeClass('close-but');
                return false;
            }else{
                $('#Passages').addClass('close-but');
                return false;
            }
        });
        
        $('#Questions').toggleClass("open");
          $('.Questions_widget').slideToggle();
        $('#Questions a').click(function(){
            $('.Questions_widget').slideToggle();
             $('#Questions').toggleClass("open");
              if($('#Questions').hasClass('open')) {
                $('#Questions').removeClass('close-but');
                return false;
            }else{
                $('#Questions').addClass('close-but');
                return false;
            }
        });
        
         $('#Distractors').toggleClass("open");
          $('.Distractors_widget').slideToggle();
        $('#Distractors a').click(function(){
            $('.Distractors_widget').slideToggle();
             $('#Distractors').toggleClass("open");
              if($('#Distractors').hasClass('open')) {
                $('#Distractors').removeClass('close-but');
                return false;
            }else{
                $('#Distractors').addClass('close-but');
                return false;
            }
        });
        
         $('#Courses').toggleClass("open");
          $('.Courses_widget').slideToggle();
        $('#Courses a').click(function(){
            $('.Courses_widget').slideToggle();
             $('#Courses').toggleClass("open");
              if($('#Courses').hasClass('open')) {
                $('#Courses').removeClass('close-but');
                return false;
            }else{
                $('#Courses').addClass('close-but');
                return false;
            }
        });
        
         $('#Rosters').toggleClass("open");
          $('.Rosters_widget').slideToggle();
        $('#Rosters a').click(function(){
            $('.Rosters_widget').slideToggle();
             $('#Rosters').toggleClass("open");
              if($('#Rosters').hasClass('open')) {
                $('#Rosters').removeClass('close-but');
                return false;
            }else{
                $('#Rosters').addClass('close-but');
                return false;
            }
        });
        
         $('#Intervention').toggleClass("open");
          $('.Intervention_widget').slideToggle();
        $('#Intervention a').click(function(){
            $('.Intervention_widget').slideToggle();
             $('#Intervention').toggleClass("open");
              if($('#Intervention').hasClass('open')) {
                $('#Intervention').removeClass('close-but');
                return false;
            }else{
                $('#Intervention').addClass('close-but');
                return false;
            }
        });
        
        $('#Quiz').toggleClass("open");
          $('.Quiz_widget').slideToggle();
        $('#Quiz a').click(function(){
            $('.Quiz_widget').slideToggle();
             $('#Quiz').toggleClass("open");
              if($('#Quiz').hasClass('open')) {
                $('#Quiz').removeClass('close-but');
                return false;
            }else{
                $('#Quiz').addClass('close-but');
                return false;
            }
        });
        
         $('#objective').toggleClass("open");
          $('.objective_widget').slideToggle();
        $('#objective a').click(function(){
            $('.objective_widget').slideToggle();
             $('#objective').toggleClass("open");
              if($('#objective').hasClass('open')) {
                $('#objective').removeClass('close-but');
                return false;
            }else{
                $('#objective').addClass('close-but');
                return false;
            }
        });
        
        $('#manager_order').toggleClass("open");
          $('.manager_order_widget').slideToggle();
        $('#manager_order a').click(function(){
            $('.manager_order_widget').slideToggle();
             $('#manager_order').toggleClass("open");
              if($('#manager_order').hasClass('open')) {
                $('#manager_order').removeClass('close-but');
                return false;
            }else{
                $('#manager_order').addClass('close-but');
                return false;
            }
        });
        
        $('#Assesments').toggleClass("open");
          $('.Assesments_widget').slideToggle();
        $('#Assesments a').click(function(){
            $('.Assesments_widget').slideToggle();
             $('#Assesments').toggleClass("open");
              if($('#Assesments').hasClass('open')) {
                $('#Assesments').removeClass('close-but');
                return false;
            }else{
                $('#Assesments').addClass('close-but');
                return false;
            }
        });
        
        // TITU END //
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
