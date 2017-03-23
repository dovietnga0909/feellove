<form class="form-horizontal" role="form" method="post">
	<h4><?=lang('cruise_settings_useful_information')?></h4>
	<div class="form-group">
		<label for="check_in" class="col-xs-3 control-label"><?=lang('cruises_field_check_in')?>: <?=mark_required()?></label>
		<div class="col-xs-6">
			<textarea class="form-control" rows="2" id="check_in" name="check_in"><?=set_value('check_in', $cruise['check_in'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('check_in')?>
		</div>
	</div>
	<div class="form-group">
		<label for="check_out" class="col-xs-3 control-label"><?=lang('cruises_field_check_out')?>: <?=mark_required()?></label>
		<div class="col-xs-6">
			<textarea class="form-control" rows="2" id="check_out" name="check_out"><?=set_value('check_out', $cruise['check_out'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('check_out')?>
		</div>
	</div>
	<div class="form-group">
		<label for="shuttle_bus" class="col-xs-3 control-label"><?=lang('cruises_field_shuttle_bus')?>: </label>
		<div class="col-xs-6">
			<textarea class="form-control" rows="2" id="shuttle_bus" name="shuttle_bus"><?=set_value('shuttle_bus', $cruise['shuttle_bus'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('shuttle_bus')?>
		</div>
	</div>
	<div class="form-group">
		<label for="guide" class="col-xs-3 control-label"><?=lang('cruises_field_guide')?>: </label>
		<div class="col-xs-6">
			<textarea class="form-control" rows="2" id="guide" name="guide"><?=set_value('guide', $cruise['guide'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('guide')?>
		</div>
	</div>
	<h4><?=lang('cruise_settings_cancellation')?></h4>
	<div class="form-group">
		<label for="default_cancellation" class="col-xs-3 control-label"><?=lang('cruises_field_default_cancellcation')?>: <?=mark_required()?></label>
		<div class="col-xs-6">
			<select class="form-control" name="default_cancellation" id="default_cancellation">
				<option value=""><?=lang('cruises_empty_select')?></option>
				<?php foreach ($cancellations as $cancellation):?>
				<option value="<?=$cancellation['id']?>" <?=set_select('default_cancellation', $cancellation['id'], $cancellation['id']==$cruise['cancellation_id'] ? TRUE : FALSE)?>><?=$cancellation['name']?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('default_cancellation')?>
		</div>
	</div>
	<h4><?=lang('cruise_settings_age_policy')?></h4>
	<div class="form-group">
		<label for="infant_age_util" class="col-xs-3 control-label"><?=lang('cruises_field_infant_age_util')?>: <?=mark_required()?></label>
		<div class="col-xs-2">
			<select class="form-control" name="infant_age_util" id="infant_age_util">
				<option value=""><?=lang('cruises_empty_select')?></option>
				<?php for($i=1; $i<=$max_infant_age; $i++):?>
				<option value="<?=$i?>" <?=set_select('infant_age_util', $i, $i==$cruise['infant_age_util'] ? TRUE : FALSE)?>><?=$i?></option>
				<?php endfor;?>
			</select>
		</div>
		<span class="help-block"><?=lang('cruises_field_years_old')?>: <?=mark_required()?></span>
		<div class="col-xs-offset-3 col-xs-6">
		<?=form_error('infant_age_util')?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-xs-3 control-label"><?=lang('cruises_field_children_age_from')?>: <?=mark_required()?></label>
		<label class="col-xs-2 control-label" id="children_age_from"><?=$cruise['infant_age_util']+1?></label>
		<label for="children_age_to" class="col-xs-1 control-label"><?=lang('cruises_field_util')?></label>
		<div class="col-xs-2">
			<select class="form-control" name="children_age_to" id="children_age_to">
				<option value=""><?=lang('cruises_empty_select')?></option>
				<?php for($i=1; $i<=$max_children_age; $i++):?>
				<option value="<?=$i?>" <?=set_select('children_age_to', $i, $i==$cruise['children_age_to'] ? TRUE : FALSE)?>><?=$i?></option>
				<?php endfor;?>
			</select>
		</div>
		<span class="help-block"><?=lang('cruises_field_years_old')?></span>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('children_age_to')?>
		</div>
	</div>
	<div class="form-group">
		<label for="extra_bed_requires_from" class="col-xs-3 control-label"><?=lang('cruises_field_extra_bed_requires_from')?>:</label>
		<div class="col-xs-2">
			<select class="form-control" name="extra_bed_requires_from">
				<option value=""><?=lang('cruises_empty_select')?></option>
				<?php for($i=1; $i<=$max_children_age; $i++):?>
				<option value="<?=$i?>" <?=set_select('extra_bed_requires_from', $i, $i==$cruise['extra_bed_requires_from'] ? TRUE : FALSE)?>><?=$i?></option>
				<?php endfor;?>
			</select>
		</div>
		<span class="help-block"><?=lang('cruises_field_years_old')?></span>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('extra_bed_requires_from')?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-xs-offset-3 col-xs-6">
			<div class="checkbox">
				<label>
				<input type="checkbox" name="children_stay_free" value="1" <?=set_checkbox('children_stay_free', 1, 1==$cruise['children_stay_free'] ? TRUE : FALSE)?>>
				<?=lang('cruise_settings_children_stay_free')?>
				</label>
			</div>
		</div>
	</div>

	<h4><?=lang('cruise_settings_children_extra_beds')?></h4>
	<div class="form-group">
		<label for="infants_policy" class="col-xs-3 control-label"><?=lang('cruises_field_infants')?>: <?=mark_required()?></label>
		<div class="col-xs-9">
			<textarea class="form-control" rows="3" name="infants_policy"><?=set_value('infants_policy', $cruise['infants_policy'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('infants_policy')?>
		</div>
	</div>
	<div class="form-group">
		<label for="children_policy" class="col-xs-3 control-label"><?=lang('cruises_field_children')?>: <?=mark_required()?></label>
		<div class="col-xs-9">
			<textarea class="form-control" rows="3" name="children_policy"><?=set_value('children_policy', $cruise['children_policy'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('children_policy')?>
		</div>
	</div>
	<div class="form-group">
		<label for="extra_cancellation" class="col-xs-3 control-label"><?=lang('cruises_field_extra_cancellation')?>:</label>
		<div class="col-xs-9">
			<textarea class="form-control rich-text" rows="5" name="extra_cancellation"><?=set_value('extra_cancellation', $cruise['extra_cancellation'])?></textarea>
		</div>
		<div class="col-xs-offset-3 col-xs-6">
			<?=form_error('extra_cancellation')?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-xs-offset-3 col-xs-6">
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
		</div>
	</div>
</form>
<script>
$('#infant_age_util').change(function() { 
	var infant = $(this).val();
	if(infant == '') infant = 0;
	infant = parseInt(infant) + 1;
	
	$('#children_age_from').html(infant);
	var children_age = $('#children_age_to').val();
	if(children_age <= infant) {
		$('#children_age_to').val(infant + 1);
	}
});

init_text_editor();
</script>