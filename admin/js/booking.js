
function search(action){
	
	document.frm.action.value = action;
	
	document.frm.submit();
	
}

function show_advanced_search(){		
	$("#advanced_search").show();
	$("#search").hide();
}

function hide_advanced_search() {
	$("#advanced_search").hide();
	$("#search").show();
}

function cb_status_change(obj){
	var status = $(obj).val();

	if (status == 5 || status == 7){ // cancel & close lost
		
		$('#close_reason').show();
		
	} else {
		
		$('#close_reason').hide();
	}
	
}


/**
 *  Functions in Serivce Reservation Page
 */

function show_hide_sr_fields_by_type(){
	$('.res_cruise').hide();
	$('.res_visa').hide();
	$('.res_flight').hide();

	var type = $("#reservation_type").val();

	if (type == 1){ // cruise tour
		$('.res_cruise').show();
	}

	if (type == 7){ // visa
		$('.res_visa').show();
		//$('#service_name').val('Vietnam Visa On Arrival');
	}
	
	if (type == 8){ // flight
		$('.res_flight').show();
		$('.res_un_flight').hide();
	} else {
		$('.res_un_flight').show();
	}
}

function set_service_name_autocomplete(){
	var type = $("#reservation_type").val();
	
	if(type == 2) // for hotel
	{
		set_hotel_autocomplete();
		
	} else {
		$('#service_name').typeahead('destroy');
	}
	
	show_hide_sr_fields_by_type();
}

function set_customer_autocomplete(){
	
	// for partner aucomplete
	var customers = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('full_name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: 'search-customers/%QUERY/'
	});
		 
	customers.initialize();
	
	$('#cus_autocomplete').typeahead(
		{
			minLength: 2,
			highlight : true,
			hint : true 
		}, 
		{
			name: 'customers',
			displayKey: 'full_name',
			source: customers.ttAdapter(),
			templates: {
				 empty: [
				 '<div class="error">',
				 	'No Customer Found!',
				 '</div>'
				 ].join('\n'),
				 suggestion: Handlebars.compile('<p><strong>{{full_name}}</strong> - {{email}} - {{phone}}</p>')
			 }
		}
	).on("typeahead:selected", function($e, datum){
		$('#customer').val(datum['id']);
		
		// store selected value
		$('#cus_autocomplete').data("selected_value", $.trim($('#cus_autocomplete').val()));
	});
	
	$("#cus_autocomplete").change(function() {
		if( $.trim($("#cus_autocomplete" ).val()) != $('#cus_autocomplete').data("selected_value") ) {
			$('#customer').val('');
		}
	})
	
}


function set_hotel_autocomplete(){

	// for partner aucomplete
	var hotels = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '../search-hotels/%QUERY/'
	});
		 
	hotels.initialize();
	
	$('#service_name').typeahead(
		{
			minLength: 2,
			highlight : true,
			hint : true 
		}, 
		{
			name: 'hotels',
			displayKey: 'name',
			source: hotels.ttAdapter(),
			templates: {
				 empty: [
				 '<div class="error">',
				 	'No Hotel Found!',
				 '</div>'
				 ].join('\n'),
				 suggestion: Handlebars.compile('<p><strong>{{name}}</strong> - {{des_name}}</p>')
			 }
		}
	).on("typeahead:selected", function($e, datum){
		$('#partner_autocomplete').val(datum['partner_name']);
		$('#partner').val(datum['partner_id']);
		
		$('#des_autocomplete').val(datum['des_name']);
		$('#destination').val(datum['des_id']);
		
		$('#service_id').val(datum['id']);
	});
}

function set_sr_autocomplete(){
	// for partner aucomplete
	var partners = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '../search-partners/%QUERY/'
	});
		 
	partners.initialize();
	
	$('#partner_autocomplete').typeahead(
		{
			minLength: 2,
			highlight : true,
			hint : true 
		}, 
		{
			name: 'partners',
			displayKey: 'name',
			source: partners.ttAdapter()
		}
	).on("typeahead:selected", function($e, datum){
		
		$('#partner').val(datum['id']);
	
		// store selected value
		$('#partner_autocomplete').data("selected_value", $.trim($('#partner_autocomplete').val()));
	});
	
	$("#partner_autocomplete").change(function() {
		if( $.trim($("#partner_autocomplete" ).val()) != $('#partner_autocomplete').data("selected_value") ) {
			$('#partner').val('');
		}
	})
	
	
	// for destination aucomplete
	var destinations = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '../search-destinations/%QUERY/'
	});
		 
	destinations.initialize();
	
	$('#des_autocomplete').typeahead(
		{
			minLength: 2,
			highlight : true,
			hint : true 
		}, 
		{
			name: 'destinations',
			displayKey: 'name',
			source: destinations.ttAdapter(),
			templates: {
				 empty: [
				 '<div class="error">',
				 	'No Destination Found!',
				 '</div>'
				 ].join('\n'),
				 suggestion: Handlebars.compile('<p><strong>{{name}}</strong> - {{parent_name}}</p>')
			 }
		}
	).on("typeahead:selected", function($e, datum){
		
		$('#destination').val(datum['id']);
	
		// store selected value
		$('#des_autocomplete').data("selected_value", $.trim($('#des_autocomplete').val()));
	});
	
	$("#des_autocomplete").change(function() {
		if( $.trim($("#des_autocomplete" ).val()) != $('#des_autocomplete').data("selected_value") ) {
			$('#destination').val('');
		}
	})
}
