<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('cruises_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="address" class="col-xs-2 control-label"><?=lang('cruises_field_address')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control" rows="2" name="address"><?=set_value('address')?></textarea>
		<?=form_error('address')?>
	</div>
</div>
<div class="form-group">
	<label for="cruise_type" class="col-xs-2 control-label"><?=lang('cruises_field_cruise_type')?>: <?=mark_required()?></label>
		<?php foreach ($cruise_type as $key => $value):?>
		<div class="col-xs-1">
		<input type="checkbox" name="cruise_type[]" value="<?=$key?>"> <?=lang($value)?>
		</div>
		<?php endforeach;?>
		<?=form_error('cruise_type')?>
	
</div>
<div class="form-group">
	<label for="star" class="col-xs-2 control-label"><?=lang('cruises_field_star')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<select class="form-control" name="star">
			<option value=""><?=lang('cruises_select_star')?></option>
			<?php foreach ($cruise_star as $star):?>
			<option value="<?=$star?>" <?=set_select('star', $star)?>><?=$star?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('star')?>
	</div>
</div>
<div class="form-group">
	<label for="partner_id" class="col-xs-2 control-label"><?=lang('cruises_field_partner')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<select class="form-control" name="partner_id">
			<option value=""><?=lang('cruises_select_partner')?></option>
			<?php foreach ($partners as $partner):?>
			<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'])?>><?=$partner['name']?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('partner_id')?>
	</div>
</div>
<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('cruises_field_description')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('cruises')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script type="text/javascript">
init_text_editor();
</script>