function set_customer_autocomplete(){
    	
	// for partner aucomplete
	var customers = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('full_name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: '/admin/bookings/search-customers/%QUERY/'
	});
		 
	customers.initialize();
	
	$('#customer_name').typeahead(
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
		$('#customer_id').val(datum['id']);
		
		// store selected value
		$('#customer_name').data("selected_value", $.trim($('#customer_name').val()));
	});
	
	$("#customer_name").change(function() {
		if($.trim($("#customer_name").val()) != $('#customer_name').data("selected_value")) {
			$('#customer_id').val('');
		}
	})
	
}