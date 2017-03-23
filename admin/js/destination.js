/* ========================================================================
 * DESTINATION PHOTO
 * ======================================================================== */
function init_destination_photo() {
	
	$('.h-photo').click(function() {
		$('#crop_photo').attr('data-photo-width', $(this).attr('data-photo-width'));
		$('#crop_photo').attr('data-photo-height', $(this).attr('data-photo-height'));
		$('#crop_photo').attr('data-photo-id', $(this).attr('data-photo-id'));
		$('#crop_photo').attr('src', $(this).attr('src'));
		$('#crop_width').html('');
		$('#crop_height').html('');
		
		if(typeof $('img#crop_photo').data('imgAreaSelect') === "undefined") {
			
			var ias = $('img#crop_photo').imgAreaSelect({
				aspectRatio : "4:3",
				onSelectEnd : getSizes,
				instance: true,
				handles: true,
			});
		}
		
		$('#cropModal').modal();
	});
	
	// onChange Room Types
	$('.input-roomtype').change(function() { 
		var id = $(this).attr('id').split('_')[1];
		
		// destination main photo selected
		if($(this).find(":selected").val() == 2) {
			// move old main photo to gallery
			$(".input-roomtype").each(function() {
				var cid = $(this).attr('id').split('_')[1];
				if($(this).find(":selected").val() == 2 && cid != id) {
					$(this).val(1);
				}
			});
		}
    });
}