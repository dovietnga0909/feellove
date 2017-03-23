<form role="form" name="frm" method="post">
<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label for="latitude"><?=lang('cruises_field_latitude')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" id="latitude" name="latitude" value="<?=set_value('latitude', $cruise['latitude'])?>">
			<?=form_error('latitude')?>
		</div>
		<div class="form-group">
			<label for="longitude"><?=lang('cruises_field_longitude')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" id="longitude" name="longitude" value="<?=set_value('longitude', $cruise['longitude'])?>">
			<?=form_error('longitude')?>
		</div>
		<div class="form-group note">
			<?=lang('cruise_map_note')?>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
		</div>
	</div>
	<div class="col-md-9">
		<div id="hotel_map"></div>
	</div>
</div>
</form>
<script type="text/javascript"> 
	var latitude = $('#latitude').val();
	var longitude = $('#longitude').val();
	var zoomOpt = 16;

	if(latitude == 0.000000 && longitude == 0.000000) {
		latitude = 21.016121;
		longitude = 105.850833;
		zoomOpt = 8;
	}

    var myOptions = {
    	zoom: zoomOpt,
        center: new google.maps.LatLng(latitude, longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("hotel_map"), myOptions);

    var myLatlng = new google.maps.LatLng(latitude, longitude);

    var marker = new google.maps.Marker({
    	position: myLatlng,
    	map: map,
    	draggable:true,
   	});

	// To add the marker to the map, call setMap();
    marker.setMap(map);

    google.maps.event.addListener(marker, 'dragend', function(evt){
    	// get lat and lng
    	$('#latitude').val(evt.latLng.lat().toFixed(6));
    	$('#longitude').val(evt.latLng.lng().toFixed(6));

    	// we'll center our marker and display it on the map
    	map.setCenter(marker.position);
    	marker.setMap(map);
    });
</script>
