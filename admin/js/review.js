function init_review() {
	set_customer_autocomplete();

	//set_up_review_for_autocomplete();

	setScoreTypesDefault();

	totalScore();
}

function set_up_review_for_autocomplete(){
	
	// suggestion hotels and tours
	var hotels = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {url: '/admin/reviews/suggest-hotels/?query=%QUERY',},
	});
	var tours = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {url: '/admin/reviews/suggest-tours/?query=%QUERY',},
	});
	tours.initialize();
	hotels.initialize();

	// instantiate the typeahead UI
	$( '#review_for' ).typeahead({
		minLength: 2,
		highlight : true,
		hint : false 
	}, {
		displayKey: 'name',
		source : hotels.ttAdapter(),
		templates: {
			header: '<span class="lb-header">Khách Sạn</span>'
		}
	}, {
		displayKey: 'name',
		source : tours.ttAdapter(),
		templates: {
			header: '<span class="lb-header">Tour</span>'
		}
	}).on("typeahead:selected", function($e, datum){
		
		$('#review_for').val(datum['name']);
		// Save object id
		if(datum['type'] == 1) {
			$('#tour_id').val('');
			$('#hotel_id').val(datum['id']);
			displayScoreTypes(datum['type']);
		} else {
			$('#hotel_id').val('');
			$('#tour_id').val(datum['id']);
			displayScoreTypes(datum['type']);
		}
		
		// store selected value
		$('#review_for').data("selected_value", $.trim($('#review_for').val()));
	});
	
	$("#review_for").change(function() {
		if($.trim($("#review_for").val()) != $('#review_for').data("selected_value")) {
			$('#hotel_id').val('');
			$('#tour_id').val('');
		}
	})
}

function totalScore()
{
	var total = 0;
	
	for (var i = 0; i < scoreTypes.length; i++)
	{
		var score = 0;
		
		if ( $("#" + scoreTypes[i]).val() != '_' && $("#" + scoreTypes[i]).val() != '')
		{
			score = parseFloat($("#" + scoreTypes[i]).val());

			score = parseFloat(score);
		}
		
		total = total + score;

	}

	total = total/scoreTypes.length;

	if (total != 0){
		$("#total_score").val(total);
	} else {
		$("#total_score").val('');
	}
}