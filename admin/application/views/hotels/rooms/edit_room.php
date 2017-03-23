<?php if(empty($room_type)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('hotels/')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

	<?php if(isset($save_status) && $save_status === FALSE):?>
		<div class="alert alert-danger">
			<?=lang('fail_to_save')?>
		</div>
	<?php endif;?>

<form role="form" name="frm" method="post">
<input type="hidden" name="hotel_id" value="<?=$hotel['id']?>">
<input type="hidden" name="room_id" value="<?=$room_type['id']?>">
<div class="row">
	<div class="col-xs-9">
		<div class="form-group">
			<label for="name"><?=lang('hotel_rooms_field_name')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" name="name" value="<?=set_value('name', $room_type['name'])?>">
			<?=form_error('name')?>
		</div>
		<div class="form-group">
			<label for="description"><?=lang('hotels_field_description')?>:</label>
			<textarea class="form-control rich-text" rows="8" name="description"><?=set_value('description', $room_type['description'])?></textarea>
			<?=form_error('description')?>
		</div>
	</div>
	<div class="col-xs-3">
		<div class="pull-right">
		<?php if(!empty($room_type['picture'])):?>
		<img width="175" src="<?=get_static_resources('/images/hotels/uploads/'.$room_type['picture'])?>">
		<?php endif;?>
		<div class="text-center">
			<a href="<?=site_url('hotels/photos/'.$room_type['hotel_id'])?>"><?=lang('update_room_type_photo')?></a>
		</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-3">
		<div class="form-group">
			<label for="room_size"><?=lang('hotel_rooms_field_room_size')?>:</label>
			<input type="text" class="form-control" name="room_size" value="<?=set_value('room_size', $room_type['minimum_room_size'])?>">
			<?=form_error('room_size')?>
		</div>
		<div class="form-group">
			<label for="view_id"><?=lang('hotel_rooms_field_view')?>:</label>
			<select class="form-control" name="view_id">
				<?php foreach ($room_views as $k => $view):?>
				<option value="<?=$k?>" <?=set_select('view_id', $k, $k==$room_type['view_id'] ? true:false)?>><?=lang($view)?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('view_id')?>
		</div>
		<div class="form-group">
			<label for="room_size"><?=lang('hotel_rooms_field_status')?>:</label><br>
			<select class="form-control" name="status">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($status_config as $key => $value):?>
				<option value="<?=$key?>" <?=set_select('status', $key, $key==$room_type['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('status')?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-8">
		<div class="form-group">
			<label for="bed_config"><?=lang('hotel_rooms_field_bed_config')?>: <?=mark_required()?></label>
			<div class="row">
				<?php foreach ($bed_configuration as $k => $config):?>
				<div class="col-xs-4">
				<input type="checkbox" name="bed_config[]" value="<?=$k?>" <?=set_checkbox('bed_config', $k, is_bit_value_contain($room_type['bed_config'], $k))?>> <?=lang($config)?>
				</div>
				<?php endforeach;?>
			</div>
			<?=form_error('bed_config')?>
		</div>
		<div class="form-group">
			<label for="facilities"><?=lang('hotel_rooms_field_facilities')?>: <?=mark_required()?></label>
			<div class="row">
			<?php if(!empty($facilities)):?>
				<?php $r_facilities = explode('-', $room_type['facilities'])?>
				<?php foreach ($facilities as $facility):?>
				<div class="col-xs-4">
				<input type="checkbox" name="facilities[]" value="<?=$facility['id']?>" 
					<?=set_checkbox('facilities', $facility['id'], in_array($facility['id'], $r_facilities) ? true:false)?>> <?=$facility['name']?>
				</div>
				<?php endforeach;?>
			<?php else:?>
				<div class="error col-xs-12"><?=lang('no_search_results')?></div>
			<?php endif;?>
			</div>
			<?=form_error('facilities')?>
		</div>
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('hotels/rooms/'.$room_type['hotel_id'])?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>
</form>
<script type="text/javascript">
init_text_editor();
</script>
<?php endif;?>