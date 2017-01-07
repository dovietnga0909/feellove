<form role="form" name="frm" method="post">
<div class="row">
	<div class="col-xs-3">
		<div class="form-group">
			<label for="latitude"><?=lang('map_destination_field_des_name')?>:</label>
			<?=$destination['name']?>
		</div>
		<div class="form-group">
			<label for="latitude"><?=lang('destinations_field_latitude')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" id="latitude" name="latitude" value="<?=set_value('latitude', $destination['latitude'])?>">
			<?=form_error('latitude')?>
		</div>
		<div class="form-group">
			<label for="longitude"><?=lang('destinations_field_longitude')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" id="longitude" name="longitude" value="<?=set_value('longitude', $destination['longitude'])?>">
			<?=form_error('longitude')?>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
		</div>
	</div>
	<div class="col-xs-9">
		<div id="destination_map"></div>
	</div>
</div>
</form>
<script type="text/javascript"> 
      var myOptions = {
         zoom: 16,
         center: new google.maps.LatLng($('#latitude').val(), $('#longitude').val()),
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById("destination_map"), myOptions);

      var myLatlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());

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
