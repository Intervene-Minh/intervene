// JavaScript Document

function UpdateSubject( subID, subName ) {
	$.ajax({ url: 'adminfSubjects.php',
			 data: {'action': 'edit', 'subID': subID , 'subName': subName },
			 type: 'post',
			 success: function(data) {
				 // Close up edited row
				 ToggleRow(subID);
				 $("#subName"+subID).html( subName );
				 
				 
				 var msg = parseJSON(data); // parse JSON
				 // Add success response message
				 AddMessage(msg.message, true);	
			 },
			 
			 error: function(response) {
				 // Add error response message
				 AddMessage(response, false);

			}
	});
	
}

function ToggleRow( rowID ) {
	var editRow = "#edit" + rowID;
	var row = "#s" + rowID;
	//alert(editRow);
	if ( $(editRow).css("display") == 'none') {
		$(row).hide();
		$(editRow).show();
		
		// re fill the box with original value if user cancelled before
		$('#esName'+rowID).val( $('#subName'+rowID).html() );
		
	} else {
		$(editRow).hide();
		$(row).show();
	}
}

function AddMessage(msg, status) {
	var msgType = status == true ? 'info' : 'error';
	var msgTitle = status == true ? 'Success' : 'Error';
	var message = '';
	message+= '<div class="callout callout-' + msgType + '">';
	message+= '<button class="close" type="button" onClick="HideMessage()">&times;</button>';
	message+= '<h4>' + msgTitle + '!</h4>';
	message+= '<p>' + msg + '</p>';
	message+= '</div>';
	
	$("#msgBox").html( message );
	$("#msgBox").show();
}

function HideMessage() {
	$("#msgBox").html( '' );
	$("#msgBox").hide();
}

function parseJSON( jsonObj ) {
	return eval ( "(" + jsonObj + ")" );
}
