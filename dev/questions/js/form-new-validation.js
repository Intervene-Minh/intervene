// JavaScript Document
$(document).ready(function() {

$('#question_submit').on('click', function(event) 
{
		var valid 					= true;
		var question_name 			= $('#question_name').val();
		var question_name_spanish 	= $('#question_name_spanish').val();
		var question_question 		= tinyMCE.get('question_question').getContent();
		var question_category 		= $('select#question_category option:selected').val();
		var question_type			= $('select#question_type option:selected').val();
		var response_answer 		= tinyMCE.get('response_answer').getContent();
		
		if(question_name=='')
		{
			alert('Please Enter Name of Question:');
			$('#question_name').focus();
			valid = false;
		
		}
		else if (question_name_spanish=='')
		{
			alert('Please Enter Name of Question in Spanish:');
			$('#question_name_spanish').focus();
			valid = false;
			
		}

		else if (question_question=='')
		{
			alert('Please Enter Actual Question:');
				$('#ac_qus').focus();
			valid = false;
		}
		else if (question_category==''){
			alert('Please Select Organize Questions:');
			$('#question_category').focus();
			valid = false;
		}
		
		else if( $('#form_question .suggest:checked').length == 0 )
		{
			alert('Please Select Objective Field');
			$('#search_object').focus();
			valid = false;
			
		}
		else  if(question_type==1)
		{
			if($('input[type=radio][name=correct]:checked').length == 0)
			{
				if(question_type==1)
				{
					alert("Please select atleast one Actual Question Answer");
					$('.radio_check').focus();
					valid = false;
				}

			}
			else
			{
				for (i=0; i < $('.answers_no_spanis').length; i++)
				{
					var content = $('.no_spanis_'+i).val();
					if( content == '' ) 
					{
						alert('Please enter your answer!');
						valid = false;

					}
				}

			}			
					
		}
		else if(question_type==2)
		{
			if(response_answer == '' ) 
			{
				alert('Please enter your answer!');
				valid = false;
			}
		}
		
		return valid ;
});


/*validation end*/

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

		console.log('submit_dimension+Edit option'+item_height);
		
		$(item_width).val(image_width);
		$(item_height).val(image_height);
		
		$('#demension_dialog').dialog('close');
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
