<?php if(empty($cabin)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('cruises/cabins/'.$cabin['cruise_id'])?>" role="button">
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

<form class="form-horizontal" role="form" name="frm" method="post">
<div class="form-group">
	<label for="number_of_cabins" class="col-xs-3 control-label"><?=lang('cabin_settings_field_numb_of_cabins')?>:</label>
	<div class="col-xs-2">
		<input type="text" class="form-control" name="number_of_cabins" value="<?=set_value('number_of_cabins', $cabin['number_of_cabins'])?>">
		<?=form_error('number_of_cabins')?>
	</div>
</div>
<div class="form-group">
	<label for="max_occupancy" class="col-xs-3 control-label"><?=lang('cabin_settings_field_max_occupancy')?>:</label>
	<div class="col-xs-2">
		<select class="form-control" name="max_occupancy">
			<option value=""><?=lang('cruises_empty_select')?></option>
			<?php for($i=0; $i<=$max_occupancy; $i++):?>
			<option value="<?=$i?>" <?=set_select('max_occupancy', $i, $i==$cabin['max_occupancy'] ? TRUE : FALSE)?>><?=$i?></option>
			<?php endfor;?>
		</select>
		<?=form_error('max_occupancy')?>
	</div>
</div>
<div class="form-group">
	<label for="max_extra_beds" class="col-xs-3 control-label"><?=lang('cabin_settings_field_max_extra_beds')?>:</label>
	<div class="col-xs-2">
		<select class="form-control" name="max_extra_beds">
			<option value=""><?=lang('cruises_empty_select')?></option>
			<?php for($i=0; $i<=$max_extra_beds; $i++):?>
			<option value="<?=$i?>" <?=set_select('max_extra_beds', $i, $i==$cabin['max_extra_beds'] ? TRUE : FALSE)?>><?=$i?></option>
			<?php endfor;?>
		</select>
		<?=form_error('max_extra_beds')?>
	</div>
</div>
<div class="form-group">
	<label for="max_children" class="col-xs-3 control-label"><?=lang('cabin_settings_field_max_children')?>:</label>
	<div class="col-xs-2">
		<select class="form-control" name="max_children">
			<option value=""><?=lang('cruises_empty_select')?></option>
			<?php for($i=0; $i<=$max_children; $i++):?>
			<option value="<?=$i?>" <?=set_select('max_children', $i, $i==$cabin['max_children'] ? TRUE : FALSE)?>><?=$i?></option>
			<?php endfor;?>
		</select>
		<?=form_error('max_children')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-3 col-xs-4">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('cruises/cabins/'.$cabin['cruise_id'])?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<?php endif;?>