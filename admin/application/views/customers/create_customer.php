<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">

<div class="form-group">
	<label for="gender" class="col-xs-2 control-label"><?=lang('customers_field_gender')?>: <?=mark_required()?></label>
	<div class="col-xs-2">
		<select class="form-control" name="gender">
			<option value=""><?=lang('select_gender')?></option>
			<?php foreach ($genders as $k => $val):?>
			<option value="<?=$k?>" <?=set_select('gender', $k)?>>
			<?=lang($val)?>
			</option>
			<?php endforeach;?>
		</select>
		<?=form_error('gender')?>
	</div>
</div>

<div class="form-group">
	<label for="full_name" class="col-xs-2 control-label"><?=lang('customers_field_full_name')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<input type="text" class="form-control" name="full_name" value="<?=set_value('full_name')?>">
		<?=form_error('full_name')?>
	</div>
</div>

<div class="form-group">
	<label for="phone" class="col-xs-2 control-label"><?=lang('customers_field_phone')?>: <?=mark_required()?></label>
	<div class="col-xs-3">
		<input type="text" class="form-control" name="phone" value="<?=set_value('phone')?>">
		<?=form_error('phone')?>
	</div>
</div>
<div class="form-group">
	<label for="email" class="col-xs-2 control-label"><?=lang('customers_field_email')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<input type="text" class="form-control" name="email" value="<?=set_value('email')?>">
		<?=form_error('email')?>
	</div>
</div>


<div class="form-group">
	<label for="destination_id" class="col-xs-2 control-label"><?=lang('customers_field_destination')?>:</label>
	<div class="col-xs-3">
	<select class="form-control" name="destination_id">
		<option value=""><?=lang('select_destination')?></option>
		<?php foreach ($destinations as $des):?>
		<option value="<?=$des['id']?>" <?=set_select('destination_id', $des['id'])?>><?=$des['name']?></option>
		<?php endforeach;?>
	</select>
	<?=form_error('destination_id')?>
	</div>
</div>

<div class="form-group">
	<label for="address" class="col-xs-2 control-label"><?=lang('customers_field_address')?>:</label>
	<div class="col-xs-6">
		<textarea class="form-control" rows="3" name="address"><?=set_value('address')?></textarea>
		<?=form_error('address')?>
	</div>
</div>

<div class="form-group">
	<label for="happy_or_not" class="col-xs-2 text-right"><?=lang('customers_field_happy_or_not')?>:</label>
	<div class="col-xs-6">
		<input type="checkbox" name="happy_or_not" value="1" <?=set_checkbox('happy_or_not', 1)?>>
		<?=form_error('happy_or_not')?>
	</div>
</div>

<div class="form-group">
	<label for="birthday" class="col-xs-2 control-label"><?=lang('customers_field_birthday')?>: <?=mark_required()?></label>
	<div class="col-xs-2" id="birthday">
		<div class="input-append date input-group">
		<input type="text" class="form-control" name="birthday" value="<?=set_value('birthday')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		</div>
		<?=form_error('birthday')?>
	</div>
</div>

<div class="form-group">
	<label for="customer_budget" class="col-xs-2 control-label"><?=lang('customers_field_budget')?>:</label>
	<div class="col-xs-2">
		<select class="form-control" name="customer_budget">
			<option value=""><?=lang('please_select')?></option>
			<?php foreach ($customer_budget as $k => $val):?>
			<option value="<?=$k?>" <?=set_select('customer_budget', $k)?>>
			<?=lang($val)?>
			</option>
			<?php endforeach;?>
		</select>
		<?=form_error('customer_budget')?>
	</div>
</div>
<div class="form-group">
	<label for="travel_types" class="col-xs-2 control-label"><?=lang('customers_field_travel_types')?>:</label>
	<div class="col-xs-2">
		<select class="form-control" name="travel_types">
			<option value=""><?=lang('please_select')?></option>
			<?php foreach ($travel_types as $k => $val):?>
			<option value="<?=$k?>" <?=set_select('travel_types', $k)?>>
			<?=lang($val)?>
			</option>
			<?php endforeach;?>
		</select>
		<?=form_error('travel_types')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('customers')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script>
	$('#birthday .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
	});
</script>