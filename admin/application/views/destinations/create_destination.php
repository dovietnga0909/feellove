<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('destinations_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="marketing_title" class="col-xs-2 control-label"><?=lang('marketing_title')?>:</label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="marketing_title" name="marketing_title" value="<?=set_value('marketing_title')?>">
		<?=form_error('marketing_title')?>
	</div>
</div>
<div class="form-group">
	<label for="type" class="col-xs-2 control-label"><?=lang('destinations_field_type')?>: <?=mark_required()?></label>
	<div class="col-xs-3">
		<select class="form-control" name="type">
			<option value=""><?=lang('destinations_empty_select')?></option>
			<?php foreach ($destination_types as $type):?>
				<?php if(is_array($type['value'])):?>
					<optgroup label="<?=lang($type['label'])?>"><?=lang($type['label'])?>
					<?php foreach ($type['value'] as $key => $value):?>	
						<option value="<?=$key?>" <?=set_select('type', $key)?>><?=lang($value)?></option>
					<?php endforeach;?>
					</optgroup>
				<?php else:?>
					<option value="<?=$type['value']?>" <?=set_select('type', $type['value'])?>>
					<?=lang($type['label'])?>
					</option>
				<?php endif;?>
			<?php endforeach;?>
		</select>
		<?=form_error('type')?>
	</div>
</div>
<div class="form-group">
	<label for="parent_id" class="col-xs-2 control-label"><?=lang('destinations_field_parent_destination')?>:</label>
	<div class="col-xs-3">
		<select class="form-control" name="parent_id">
			<option value=""><?=lang('destinations_empty_select')?></option>
			<?php foreach ($parent_destinations as $des):?>
			<option value="<?=$des['id']?>" <?=set_select('parent_id', $des['id'])?>><?=$des['name']?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('parent_id')?>
	</div>
</div>
<div class="form-group">
	<label for="is_flight_destination" class="col-xs-2 text-right">
		<?=lang('destinations_field_is_hotel_top')?>
	</label>
	<div class="col-xs-6">
		<input type="checkbox" name="is_top_hotel"
			value="1" <?=set_checkbox('is_top_hotel', 1)?>>
	</div>
</div>
<div class="form-group">
	<label for="description_short" class="col-xs-2 control-label"><?=lang('description_short')?>:</label>
	<div class="col-xs-8">
		<textarea class="form-control rich-text" rows="10" name="description_short"><?=set_value('description_short')?></textarea>
		<?=form_error('description_short')?>
	</div>
</div>
<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('destinations_full')?>:</label>
	<div class="col-xs-8">
		<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>
<div class="form-group">
	<label for="latitude" class="col-xs-2 control-label"><?=lang('destinations_field_latitude')?>: <?=mark_required()?></label>
	<div class="col-xs-2">
		<input type="text" class="form-control" id="latitude" name="latitude" value="<?=set_value('latitude')?>">
		<?=form_error('latitude')?>
	</div>
</div>
<div class="form-group">
	<label for="longitude" class="col-xs-2 control-label"><?=lang('destinations_field_longitude')?>: <?=mark_required()?></label>
	<div class="col-xs-2">
		<input type="text" class="form-control" id="longitude" name="longitude" value="<?=set_value('longitude')?>">
		<?=form_error('longitude')?>
	</div>
</div>
<div class="col-xs-offset-2 col-xs-8" style="padding: 0 0 10px 5px">
	<div id="destination_map"></div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('destinations')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script type="text/javascript">
	init_text_editor();

	var latitude = 21.016121;
	var longitude = 105.850833;

    var myOptions = {
    	zoom: 8,
        center: new google.maps.LatLng(latitude, longitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP
   	};

    var map = new google.maps.Map(document.getElementById("destination_map"), myOptions);

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