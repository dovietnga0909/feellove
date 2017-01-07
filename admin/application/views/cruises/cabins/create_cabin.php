<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="cruise_id" value="<?=$cruise['id']?>">
<div class="form-group">
	<label for="name" class="col-xs-3 control-label"><?=lang('cruise_cabins_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="name" value="<?=set_value('name')?>">
	<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="description" class="col-xs-3 control-label"><?=lang('cruises_field_description')?>:</label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>
<div class="form-group">
	<label for="cabin_size" class="col-xs-3 control-label"><?=lang('cruise_cabins_field_cabin_size')?>:</label>
	<div class="col-xs-2">
		<input type="text" class="form-control" name="cabin_size" value="<?=set_value('cabin_size')?>">
		<?=form_error('cabin_size')?>
	</div>
</div>
<div class="form-group">
	<label for="bed_config" class="col-xs-3 control-label"><?=lang('cruise_cabins_field_bed_config')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<?php foreach ($bed_configuration as $k => $config):?>
		<div class="col-xs-4">
			<input type="checkbox" name="bed_config[]" value="<?=$k?>" <?=set_checkbox('bed_config', $k)?>> <?=lang($config)?>
		</div>
		<?php endforeach;?>
		<?=form_error('bed_config')?>
	</div>
</div>
<div class="form-group">
	<label for="facilities" class="col-xs-3 control-label"><?=lang('cruise_cabins_field_facilities')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<?php foreach ($facilities as $facility):?>
		<div class="col-xs-4">
		<input type="checkbox" name="facilities[]" value="<?=$facility['id']?>" <?=set_checkbox('facilities', $facility['id'])?>> <?=$facility['name']?>
		</div>
		<?php endforeach;?>
		<?=form_error('facilities')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-3 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('cruises/cabins/'.$cruise['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script type="text/javascript">
init_text_editor();
</script>