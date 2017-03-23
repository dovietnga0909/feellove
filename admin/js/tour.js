/* ========================================================================
 * TOUR PHOTO
 * ======================================================================== */
function init_tour_photo() {
	
	// onChange Room Types
	$('.input-roomtype').change(function() { 
		var id = $(this).attr('id').split('_')[1];
		//_change_room_type(id);
		
		//var photo_type = $('#type_'+id+' :selected').val();
		//if(photo_type == 3) {
			//select_room_types(id);
		//}
		
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
	
	$('.h-photo').click(function() {
		$('#crop_photo').attr('data-photo-width', $(this).attr('data-photo-width'));
		$('#crop_photo').attr('data-photo-height', $(this).attr('data-photo-height'));
		$('#crop_photo').attr('data-photo-id', $(this).attr('data-photo-id'));
		$('#crop_photo').attr('src', $(this).attr('src'));
		$('#crop_width').html('');
		$('#crop_height').html('');
		
		if(typeof $('img#crop_photo').data('imgAreaSelect') === "undefined") {
			
			var ias = $('img#crop_photo').imgAreaSelect({
				aspectRatio : "16:9",
				onSelectEnd : getSizes,
				onSelectChange : getSizes,
				instance: true,
				handles: true
			});
		}

		$('#cropModal').modal();
	});
}

function add_tour_route() {
	
	var route_ids = update_routes();
	route_ids = route_ids.split('-');
    
	$('select#routes :selected').each(function(i, selected){ 
        
		// check whether destination exist or not
		if( (route_ids.length > 0 && $.inArray($(selected).val(), route_ids) == -1) || route_ids.length == 0 ) {
			
			add_route_row(selected);
	      
		}
    });
	
	$('.route_empty').hide();
}

function add_route_row(selected, routeCount) {
	var rm = $('<a>').attr('des_id', $(selected).val()).attr('href', 'javascript:void(0)').html('<span class="glyphicon glyphicon-remove rm_des"></span>');
	var hidden = '<input type="checkbox" class="ck_hidden" id="ck_hidden_'+$(selected).val()+'" value="'+$(selected).val()+'">';
	var land_tour = '<input type="checkbox" class="ck_land_tour" id="ck_land_tour_'+$(selected).val()+'" value="'+$(selected).val()+'">';
	
	var rowCount = $('#tour_routes tr').length;
	
	var btn_up = $('<span>').addClass('glyphicon glyphicon-arrow-up btn_up_des').css('cursor', 'pointer');
	var btn_down = $('<span>').addClass('glyphicon glyphicon-arrow-down btn_down_des').css('cursor', 'pointer');
	
	var tr;
	
	//console.log(rowCount+' - '+routeCount);
	
	tr = $('<tr>').attr('id', 'row_' + [rowCount+1] ).append($('<td nowrap="nowrap">').append(btn_up).append(btn_down));
	
	tr = tr.append($('<td>').text($(selected).text()))
			.append($('<td class="text-center">').append(hidden))
			.append($('<td class="text-center">').append(land_tour))
			.append($('<td class="text-center">').append(rm));
    $('#tour_routes').append(tr);
    
    // update route_ids
    update_routes();
    
    // bind action
    bind_action();
}

function bind_action() {
	
	var rowCount = $('#tour_routes tr').length;

	// remove destination
    $('.rm_des').click(function() {
    	
    	$(this).closest("tr").remove();
    	
    	if ( update_routes() == '' ) {
    		$('.route_empty').show();
    	}
    });
    
    // remove destination
    $('.ck_hidden').click(function() {
    	update_hidden_routes();
    });
    
    $('.ck_land_tour').click(function() {
    	update_land_tour();
    });
    
    // re-order
    $('.btn_up_des').click(function() {
    	var thisRow = $(this).closest('tr');
        var prevRow = thisRow.prev();
        if (prevRow.length) {
            prevRow.before(thisRow);
        }
    	update_routes();
    });
    
    $('.btn_down_des').click(function() {
    	var thisRow = $(this).closest('tr');
        var nextRow = thisRow.next();
        if (nextRow.length) {
            nextRow.after(thisRow);
        }
    	update_routes();
    });
}

function update_routes() {
	var route_ids = '';
	
	$('#tour_routes').find('a').each(function() { 
		route_ids += $(this).attr('des_id') + '-';
	});
	
	if (route_ids.length > 1) {
		route_ids = route_ids.substring(0, route_ids.length - 1);
	}
	
	//console.log('route_ids: '+route_ids);
	$('#route_ids').val(route_ids);
	
	return route_ids;
}

function update_hidden_routes() {
	
	// update hidden field
	var route_hidden_ids = '';
	$('.ck_hidden:checked').each(function() {
		route_hidden_ids += $(this).val() + '-';
	});
	
	if (route_hidden_ids.length > 1) {
		route_hidden_ids = route_hidden_ids.substring(0, route_hidden_ids.length - 1);
	}
	
	$('#route_hidden_ids').val(route_hidden_ids);
}

function update_land_tour() {
	
	// update land tour id field
	var land_tour_ids = '';
	$('.ck_land_tour:checked').each(function() {
		land_tour_ids += $(this).val() + '-';
	});
	
	if (land_tour_ids.length > 1) {
		land_tour_ids = land_tour_ids.substring(0, land_tour_ids.length - 1);
	}
	
	$('#land_tour_ids').val(land_tour_ids);
}

function init_tour_routes() {
	
	// load data
	if( $('#route_ids').val() != '' ) {
			
		bind_action();
		
		/*
		var route_ids = $('#route_ids').val().split('-');
		
		$('select#routes option').each(function() { 
			
			var routeCount = route_ids.length;
			
			if ($.inArray($(this).val(), route_ids) != -1) {
				add_route_row($(this), routeCount);
			}
		});
		
		var route_hidden_ids = $('#route_hidden_ids').val().split('-');
		
		for(i=0; i < route_hidden_ids.length; i++ ) {
			$('#ck_hidden_' + route_hidden_ids[i]).prop('checked', true);
		}
		
		var land_tour_ids = $('#land_tour_ids').val().split('-');
		
		for(i=0; i < land_tour_ids.length; i++ ) {
			$('#ck_land_tour_' + land_tour_ids[i]).prop('checked', true);
		}
		
		$('.route_empty').hide();
		*/
	}
	
	// init auto suggestion for destinations
	$("#search_destination").bind('keypress', function(e) {
		
	    var code = (e.keyCode ? e.keyCode : e.which);

	    //Enter keycode
	    if(code == 13) 
		{
    	    var query = $(this).val();
	 		
	 		if(query != '')
	 		{
	 			$.ajax({
	 			    type: "POST",
	 			    url: "/admin/tours/auto-suggestion/",
	 			    data: {
	 					'query': query
	 				},
	 			    cache: false,
	 			    success: function(html)
	 			    {
	 			        $("#routes").html(html);
	 			    }
	 			});
	 		}

	 		return false;
	     }
	});
}