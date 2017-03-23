

/* ========================================================================
 * CRUISE PHOTO
 * ======================================================================== */
function init_cruise_photo() {
	// get photo room types
	$(".input-roomtype").each(function() {
		var id = $(this).attr('id').split('_')[1];
		_change_room_type(id);
    });
	
	// onChange Room Types
	$('.input-roomtype').change(function() { 
		var id = $(this).attr('id').split('_')[1];
		_change_room_type(id);
		
		var photo_type = $('#type_'+id+' :selected').val();
		if(photo_type == 3) {
			select_room_types(id);
		}
		
		// hotel main photo selected
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
	
	// onChange Room Types
	$("#myModal input[name='room_type']").change(function() {
		var value = $(this).val();
		if($(this).is(':checked')) {
			$("#myModal input[name='room_type_main_photo'][value="+value+"]").removeAttr( "disabled" );
		} else {
			$("#myModal input[name='room_type_main_photo'][value="+value+"]").attr( "disabled", "disabled" );
		}
		$("#myModal input[name='room_type_main_photo'][value="+value+"]").prop('checked', false);
	});
	
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
}

function _change_room_type(id) {
	
	var photo_type = $('#type_'+id+' :selected').val();
	
	// room type photo selected
	if(photo_type == 3) {
		
		$('#link-roomtype-'+id).removeClass('hide');
		
		_display_selected_room_type(id);
	} else {
		$('#link-roomtype-'+id).addClass('hide');
		$('#room_type_'+id).val('');
		$('#room_type_main_photo_'+id).val('');
	}
}

function _display_selected_room_type(id) {

	var selected = $("input[name='room_type_"+id+"']").val();

	var leng = 0;

	if(selected != '') {
		var chkArray = selected.split('-');
		leng = chkArray.length;
	}
	
	$('#numb_roomtypes_'+id).html(leng);
}

function validation_photo(event) {
	var status = 0;
    
	$(".input-roomtype").each(function() {
		var id = $(this).attr('id').split('_')[1]; 

		$('#thumb_'+id).parent().removeClass('input-error');

		if($('#type_'+id+' :selected').val() == 3 && !$("input[name='room_type_"+id+"']").val()) {
			
			$('#thumb_'+id).parent().addClass('input-error');
    		status = 1;
    		
    	}
    });

    if(status == 1) {
    	event.preventDefault(); // cancel default behavior
    	alert('Please select at least one room type!');
        return false;
    }
}

function select_room_types(id) {
	// set selected room types
	_set_selected_chk(id, 'myModal', 'room_type');
	_set_selected_chk(id, 'myModal', 'room_type_main_photo');
    
	$('#modal_roomtypes').val(id);
	$('#myModal').modal();
}

function save_cabins() {

	var id = $('#modal_roomtypes').val();

    /* save  selected room types */
	var selected = _get_selected_chk('myModal', 'room_type');
	$("input[name='room_type_"+id+"']").val(selected);
	
	// get room main photo
    var mainSelected = _get_selected_chk('myModal', 'room_type_main_photo');
	$("input[name='room_type_main_photo_"+id+"']").val(mainSelected);
	
	// Each roomtype has only one main photo
	$( "input[name^='room_type_main_photo_']" ).each(function() {
		var mid = $(this).attr('name').split('_')[4];
		if(mid != id) {
			var main_photo_ids = $(this).val().split('-');
			var chkArray = mainSelected.split('-');
			var new_ids = [];
			
			for (index = 0; index < chkArray.length; ++index) {
				for (i = 0; i < main_photo_ids.length; ++i) {
					if(main_photo_ids[i] != chkArray[index]) {
						new_ids.push(main_photo_ids[i]);
					}
				}
			}
			$(this).val(new_ids.join('-'));
		}
    });
	

	// display number of selected room types 
	_display_selected_room_type(id);

	$('#myModal').modal('hide');
}

function _get_selected_chk(box_id, name) {
	var chkArray = [];
    
    $("#"+box_id+" input[name='"+name+"']:checked").each(function() {
        chkArray.push($(this).val());
    });
     
    /* we join the array separated by the hyphen */
    var selected;
    selected = chkArray.join('-');
    
    return selected;
}

function _set_selected_chk(id, box_id, name) {
	var selected = $("input[name='"+name+"_"+id+"']").val();
	
	if (typeof selected !== "undefined") {
		var chkArray = selected.split('-');

		$("#"+box_id+" input[name='"+name+"']").prop('checked', false);

		$("#"+box_id+" input[name='"+name+"']").each(function() {
			for (index = 0; index < chkArray.length; ++index) {
				if($(this).val() == chkArray[index]) {
					$(this).prop('checked', true);
				}
			}
	    });
		
		// enable room main photo if it's selected
		if (name == 'room_type_main_photo') {
			 $("#"+box_id+" input[name='room_type']:checked").each(function() {
				 var value = $(this).val();
				 $("#"+box_id+" input[name='room_type_main_photo'][value="+value+"]").removeAttr( "disabled" );
			 });
		}
	}
}