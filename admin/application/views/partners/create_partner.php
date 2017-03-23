<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('partners_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="service_type" class="col-xs-2 text-right"><?=lang('partner_fields_service_types')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<?php foreach ($partner_types as $k => $config):?>
		<div class="col-xs-2 <?= ($k==1) ? 'pd-left-0':''?>">
		<input type="checkbox" name="service_type[]" value="<?=$k?>" <?=set_checkbox('service_type', $k)?>> <?=lang($config)?>
		</div>
		<?php endforeach;?>
		<?=form_error('service_type')?>
	</div>
</div>
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('partners_field_joining_date')?>: <?=mark_required()?></label>
	<div class="col-xs-2" id="joining_date">
		<div class="input-append date input-group">
		<input type="text" class="form-control" name="joining_date" value="<?=set_value('joining_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		</div>
		<?=form_error('joining_date')?>
	</div>
</div>
<div class="form-group">
	<label for="phone" class="col-xs-2 control-label"><?=lang('partners_field_phone')?>: <?=mark_required()?></label>
	<div class="col-xs-3">
		<input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>">
		<?=form_error('phone')?>
	</div>
</div>
<div class="form-group">
	<label for="fax" class="col-xs-2 control-label"><?=lang('partners_field_fax')?>: <?=mark_required()?></label>
	<div class="col-xs-3">
		<input type="text" class="form-control" id="fax" name="fax" value="<?=set_value('fax')?>">
		<?=form_error('fax')?>
	</div>
</div>
<div class="form-group">
	<label for="email" class="col-xs-2 control-label"><?=lang('partners_field_email')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>">
		<?=form_error('email')?>
	</div>
</div>
<div class="form-group">
	<label for="website" class="col-xs-2 control-label"><?=lang('partners_field_website')?>:</label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="website" name="website" value="<?=set_value('website')?>">
		<?=form_error('website')?>
	</div>
</div>
<div class="form-group">
	<label for="address" class="col-xs-2 control-label"><?=lang('partners_field_address')?>: <?=mark_required()?></label>
	<div class="col-xs-8">
		<textarea class="form-control" rows="5" name="address"><?=set_value('address')?></textarea>
		<?=form_error('address')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('partners')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>

<script>
	$('#joining_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
	});
</script>