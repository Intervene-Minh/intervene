// JavaScript Document

$(document).ready(function() {
	/* Sub list on sidebar */
	$('#sidebar .fa-angle-right').on('click', function() {
		if( $(this).nextAll('ul.subitem').length == 0 )
			return;
		$(this).parents('ul.listing').find('.subitem').slideUp();
		$(this).parents('ul.listing').find('.fa-angle-down').hide();
		$(this).parents('ul.listing').find('.fa-angle-right').css('display', 'inline-block');
		$(this).siblings('ul.subitem').slideDown();
		$(this).siblings('.fa-angle-down').css('display', 'inline-block');
		$(this).hide();
	});
	$('#sidebar .fa-angle-down').on('click', function() {
		$(this).siblings('.subitem').slideUp();
		$(this).siblings('.fa-angle-right').css('display', 'inline-block');
		$(this).hide();
	});
	
	/* Toggle Question Detail */
	$('.toggle-detail').on('click', function() {
		$(this).siblings('.item-detail').slideToggle();
	});
	
	/* Check edit question and redirect to single-question.php */
	$('#edit-question').on('click', function() {
		// Init an @array to store checked data
		var items = [];
		// Get data
		$(this).parents('.content_wrap').find('input.edit_items:checked').each(function() {
			items.push( $(this).val() );
		});
		// Return error if no item or more than one item was checked!
		var Error = (items.length == 0) ? 'Please select one item!' : ((items.length > 1) ? 'You can only edit one specific item!' : false);
		if( Error ) {
			alert(Error);
			return;
		}
		window.location.assign('single-question.php?question=' + items[0]);
	});
	
	/* Check edit passage and redirect to single-question.php */
	$('#edit-passage').on('click', function() {
		// Init an @array to store checked data
		var items = [];
		// Get data
		$(this).parents('.content_wrap').find('input.edit_items:checked').each(function() {
			items.push( $(this).val() );
		});
		// Return error if no item or more than one item was checked!
		var Error = (items.length == 0) ? 'Please select one item!' : ((items.length > 1) ? 'You can only edit one specific item!' : false);
		if( Error ) {
			alert(Error);
			return;
		}
		window.location.assign('single-passage.php?passage=' + items[0]);
	});
	
		/* Check edit passage and redirect to single-question.php */
	$('#edit-strategy').on('click', function() {
		// Init an @array to store checked data
		var items = [];
		// Get data
		$(this).parents('.content_wrap').find('input.edit_items:checked').each(function() {
			items.push( $(this).val() );
		});
		// Return error if no item or more than one item was checked!
		var Error = (items.length == 0) ? 'Please select one item!' : ((items.length > 1) ? 'You can only edit one specific item!' : false);
		if( Error ) {
			alert(Error);
			return;
		}
		window.location.assign('single-strategy.php?strategy_id=' + items[0]);
	});
	
	/* Prop check distrator item */
	$('#distrator_wrap a.item_name').on('click', function() {
		var item = $(this).prev('input.edit_items');
		if( $(item).is(':checked') ) 
			$(item).prop('checked', false);
		else
			$(item).prop('checked', true);
	});
	
	/* Popup Forms */
	$('.popup_form').on('click', function() {
		var target	= $(this).data('target');
		var titles	= $(this).attr('title');
		// Reset form
		$(target).find('input.textfield').val('');
		$(target).find('input.hidden_id').val('');
		$(target).find('input.submit_button').val('Create');
		
		$(target).dialog({
			title		: titles,
			width		: '600',
			modal		: true,
			resizable	: false,
		});
	});
	
	/* Popup Edit */
	$('.popup_edit').on('click', function() {
		// Init an @array to store checked data
		var items = [];
		// Get data
		$(this).parents('.content_wrap').find('input.edit_items:checked').each(function() {
			items.push({
				'id'	: $(this).val(),
				'title'	: $(this).attr('title'),
				'data'	: $(this).next('a.item_name').text(),
				'desc'	: $(this).data('desc'),
				'parent': $(this).data('parent'),
			});
		});
		// Return error if no item or more than one item was checked!
		var Error = (items.length == 0) ? 'Please select one item!' : ((items.length > 1) ? 'You can only edit one specific item!' : false);
		if( Error ) {
			alert(Error);
			return;
		}
		// Setup data
		var target = $(this).data('target');
		$(target).find('input.submit_button').val('Save');
		$(target).find('input.hidden_id').val(items[0]['id']);
		$(target).find('input.field_data').val(items[0]['data']);
		$(target).find('textarea.field_desc').val(items[0]['desc']);
		// Check if is sub-item
		if( typeof items[0]['parent'] != 'undefined' ) {
			if( items[0]['parent'] == 0 ) {		// is level 1
				$(target).find('input.level_field[value=0]').prop('checked', true);
				// Disable current item on dropdown
				$(target).find('select.child_of option[value='+items[0]['id']+']').prop('disabled', true).siblings().prop('disabled', false);
			} else {							// is sub-item
				$(target).find('input.level_field[value=1]').prop('checked', true);
				$(target).find('select.child_of option[value='+items[0]['parent']+']').prop('selected', true);
			}
		}
		
		// Popup dialog
		$(target).dialog({
			title		: items[0]['title'],
			width		: '600',
			modal		: true,
			resizable	: false,
		});
	});
	
	
	
	/* Popup Report An Error */
	$('.popup_report_error').on('click', function() {
		// Setup data
		var target = $(this).data('target');
		var q_id = $(this).val();
		$(target).find('input.hidden_id').val(q_id);

		// Popup dialog
		$(target).dialog({
			title		: 'Report An Error',
			width		: '600',
			modal		: true,
			resizable	: false,
		});
	});
	

	/* Manager Users Edit */
	$('.edit-user').on('click', function() {
		var count = $('.table-manager-user').find('input.checkbox:checked').length;
		var Error = (count == 0) ? 'Please select one item!' : ((count > 1) ? 'You can only edit one specific item!' : false);
		if(Error){
			alert(Error);
			return;
		}
		
		var $id = $('.table-manager-user').find('input.checkbox:checked').val();
		window.location.href='profile.php?id='+$id;
	});
	
	/* Close Forms */
	$('.form_dialog .reset_button').on('click', function() {
		$(this).parents('.form_dialog').dialog('close');
	});
	
	/* Submit Folder Form */
	$('#submit_folder').on('click', function(event) {
		event.preventDefault();
		
		// Validate
		if( $.trim( $('#folder_name').val() ) == '' ) {
			$('#folder_name').css('border-color', 'red');
			$('#folder_name').focus();
			return false;
		} else {
			$('#folder_name').css('border-color', 'inherit');
		}
		if( ! $('input[name=folder_level]').is(':checked') ) {
			alert('Please choose folder level!');
			return false;
		} else if( $('input[name=folder_level]:checked').val() != 0 && $('#child_of_folder').val() == '' ) {
			$('#child_of_folder').css('border-color', 'red');
			$('#child_of_folder').focus();
			return false;
		} else {
			$('#child_of_folder').css('border-color', 'inherit');
		}
		
		// Loading...
		$(this).parent('.button_wrap').prepend('<img id="loading" alt="..." src="images/loading.gif" />');
		
		var popup = $(this).parents('.form_dialog');
		var value = $.trim( $('#folder_name').val() );
		var hidId = $(this).parents('.form_dialog').find('input.hidden_id').val();
		var level = ( $('input[name=folder_level]:checked').val() == 0 ) ? 0 : $('#child_of_folder').val();
		
		var data = {
			action	: 'query_folder',
			hidId	: hidId,
			value	: value,
			level	: level,
		};
		
		$.ajax({
			type	: 'POST',
			url		: 'inc/functions.php',
			data	: data,
			dataType: 'json',
			success	: function(response) {
				$('#loading').remove();
				alert(response.msg);
				if(response.stt)
					$(popup).dialog('close');
				if(response.stt && response.sql == 'update')
					location.reload();
			}
		});
	});
	
	/* Submit Objective Form */
	$('#submit_objective').on('click', function(event) {
		event.preventDefault();
		
		// Validate
		if( $.trim( $('#objective_name').val() ) == '' ) {
			$('#objective_name').css('border-color', 'red');
			$('#objective_name').focus();
			return false;
		} else {
			$('#objective_name').css('border-color', 'inherit');
		}
		if( ! $('input[name=objective_level]').is(':checked') ) {
			alert('Please choose folder level!');
			return false;
		} else if( $('input[name=objective_level]:checked').val() != 0 && $('#child_of_object').val() == '' ) {
			$('#child_of_object').css('border-color', 'red');
			$('#child_of_object').focus();
			return false;
		} else {
			$('#child_of_object').css('border-color', 'inherit');
		}
		
		// Loading...
		$(this).parent('.button_wrap').prepend('<img id="loading" alt="..." src="images/loading.gif" />');
		
		var popup = $(this).parents('.form_dialog');
		var value = $.trim( $('#objective_name').val() );
		var descr = $.trim( $('#objective_desc').val() );
		var hidId = $(this).parents('.form_dialog').find('input.hidden_id').val();
		var level = ( $('input[name=objective_level]:checked').val() == 0 ) ? 0 : $('#child_of_object').val();
		
		var data = {
			action	: 'query_objective',
			hidId	: hidId,
			value	: value,
			descr	: descr,
			level	: level,
		};
		
		$.ajax({
			type	: 'POST',
			url		: 'inc/functions.php',
			data	: data,
			dataType: 'json',
			success	: function(response) {
				$('#loading').remove();
				alert(response.msg);
				if( response.stt )
					$(popup).dialog('close');
				if(response.stt && response.sql == 'update')
					location.reload();
			}
		});
	});
	
	/* Submit Distrator Form */
	$('#submit_distrator').on('click', function(event) {
		event.preventDefault();
		
		var popup = $(this).parents('.form_dialog');
		var value = $.trim( $('#distrator_field').val() );
		var hidId = $(this).parents('.form_dialog').find('input.hidden_id').val();
		
		if( value == '' ) {
			$('#distrator_field').css('border-color', 'red');
			$('#distrator_field').focus();
			return false;
		} else {
			$('#distrator_field').css('border-color', 'inherit');
		}
		
		// Loading...
		$(this).parent('.button_wrap').prepend('<img id="loading" alt="..." src="images/loading.gif" />');
		
		var data = {
			action	: 'query_distrator',
			hidId	: hidId,
			value	: value,
		}
		
		$.ajax({
			type	: 'POST',
			url		: 'inc/functions.php',
			data	: data,
			dataType: 'json',
			success	: function(response){
				$('#loading').remove();
				alert(response.msg);
				if(response.stt)
					$(popup).dialog('close');
				if(response.sql == 'update')
					location.reload();
			}
		});
	});
	
	/* Delete Terms */
	$('.remove_items').on('click', function() {
		// Init an @array to store checked data
		var items = [];
		// Get data
		$(this).parents('.content_wrap').find('input.edit_items:checked').each(function() {
			items.push( $(this).val() );
		});
                
		// Return error if no item or more than one item was checked!
		if( items.length == 0 ) {
			alert('Please select at least one item!');
			return false;
		}
		
		var type = $(this).data('type');
		var conf = "Are you sure to remove these items?";
		conf += (type == 'category' || type == 'objective') ? "\n  (All children will be also removed)" : "";
		var check = confirm(conf);
		if(!check )
			return false;
		
		var data = {
			action	: 'remove_items',
			type	: type,
			items	: items,
		};
		
		$.ajax({
			type	: 'POST',
			url		: 'inc/functions.php',
			data	: data,
			dataType: 'json',
			success	: function(response) {
				alert(response.msg);
				if(response.stt)
					location.reload();
			}
		});
	});
	
	
	$('#question_passage').on('change',function(){
		if($(this).val()!=""){
			$('.upload_button').css('display','none');
		}else{
			$('.upload_button').css('display','inline');
		}
		
	});
	
});