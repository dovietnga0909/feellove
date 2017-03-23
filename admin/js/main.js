/**
 * Confirm Delete Item
 */

function confirm_delete(message) {
	if (confirm(message)) {
		return true;
	}
	return false;
}

function init_text_editor() {
	tinymce.init({
   		selector: "textarea.rich-text",
   		menubar: false,
   		theme: "modern",
   		skin : 'yui',
	    height: 300,
   	    plugins: [
   	        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
   	        "searchreplace wordcount visualblocks visualchars code fullscreen",
   	        "insertdatetime media nonbreaking save table contextmenu directionality",
   	        "emoticons template paste textcolor"
   	    ],
   	    toolbar: "bold italic bullist numlist alignleft aligncenter alignright link image preview code fullscreen",
   	    //image_advtab: true,
	});
}

function getSizes(img, selection) {
	$('#crop_photo').data('imgAreaSelect', selection);
	
	var box_width = parseInt( $('#crop_photo').width(), 10 );
	var box_height = parseInt( $('#crop_photo').height(), 10 );
	var actual_width = parseInt( $('#crop_photo').attr('data-photo-width') ,10 );
	var actual_height = parseInt( $('#crop_photo').attr('data-photo-height') ,10);
	
	var crop_width = Math.round( (selection.width * actual_width) / box_width );
	
	var crop_height = Math.round( (selection.height * actual_height) / box_height );
	
	if(isNaN(crop_width)) {
		crop_width = 0;
	}
	if(isNaN(crop_height)) {
		crop_height = 0;
	}
	
	$('#crop_width').html('Width: '+crop_width+'px');
	$('#crop_height').html('Height: '+crop_height+'px');
	//$('#crop_x1').html('x1: '+selection.x1);
	//$('#crop_y1').html('y1: '+selection.y1);
	//$('#crop_x2').html('x2: '+selection.x2);
	//$('#crop_y2').html('y2: '+selection.y2);
	$('#crop_photo').attr('data-crop-width', crop_width);
	$('#crop_photo').attr('data-crop-height', crop_height);
}

function crop_image(crop_url) {
	
	var obj = $('#crop_photo').data('imgAreaSelect');
	
	var x_axis = obj.x1;
	var x2_axis = obj.x2;
	var y_axis = obj.y1;
	var y2_axis = obj.y2;
	var thumb_width = obj.width;
	var thumb_height = obj.height;
	var photo_id = $("#crop_photo").attr('data-photo-id');
	var crop_width = $('#crop_photo').attr('data-crop-width');
	var crop_height = $('#crop_photo').attr('data-crop-height');
	
	var box_width = parseInt( $('#crop_photo').width(), 10 );
	var box_height = parseInt( $('#crop_photo').height(), 10 );
	var actual_width = parseInt( $('#crop_photo').attr('data-photo-width') ,10 );
	var actual_height = parseInt( $('#crop_photo').attr('data-photo-height') ,10);
	
	x_axis = Math.round( (x_axis * actual_width) / box_width );
	y_axis = Math.round( (y_axis * actual_height) / box_height );
	
	if (thumb_width > 0) {
		if (confirm("Do you want to save image!"))
		{
			$.ajax({
				type : 'POST',
				url : crop_url,
				data : {
					img_id: photo_id, w: thumb_width, h: thumb_height, x1: x_axis, y1: y_axis, crop_width: crop_width, crop_height: crop_height
				},
				beforeSend : function() {
					$('#thumbs_msg').html('Photo Croping...');
				},
				success : function(data) {
					$("#thumbs_msg").html(data);
					$('#cropModal').modal('hide');
					location.reload();
				},
				error : function() {
					// failed request; give feedback to user
					$('#thumbs_msg').html('<p class="error"><strong>Oops!</strong> Try that again in a few moments.</p>');
				}
			});
		}
	} else {
		alert("Please select portion!");
	}
}