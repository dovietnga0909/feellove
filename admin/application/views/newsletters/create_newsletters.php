<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_create_newsletter')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
	<div class="form-group">
		<label class="col-xs-2 control-label" for="name"><?=lang('newsletters')?> : <?=mark_required()?></label>
		<div class="col-xs-6">
			<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
			<?=form_error('name')?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-xs-2 control-label" for="template_id"><?=lang('templates')?> : <?=mark_required()?></label>
		<div class="col-xs-6">
			<select class="form-control" name="template_id" id="template_id">
				<option value=""><?=lang('select_templates')?></option>
				<?php foreach ($templates as $k => $temp):?>
				<option value="<?=$temp['id']?>" > <?=$temp['name']?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('template_id')?>
		</div>
	</div>
	<div class="form-group">
		<label for="start_date" class="col-xs-2 control-label"><?=lang('start_date')?> : <?=mark_required()?></label>
		<div class="col-xs-2" id="group_start_date">
			<div class="input-append date input-group">
				<input type="text" class="form-control" id="start_date" name="start_date" value="<?=set_value('start_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
			<?=form_error('start_date')?>
		</div>
		
		<label for="end_date" class="col-xs-2 control-label"><?=lang('end_date')?> : <?=mark_required()?></label>
		<div class="col-xs-2" id="group_end_date">
			<div class="input-append date input-group">
			<input type="text" class="form-control" id="end_date" name="end_date" value="<?=set_value('end_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
			<?=form_error('end_date')?>
		</div>
	</div>
	
	<div class="form-group">
	    <label for="send_on[]" class="col-xs-2 control-label"><?=lang('send_on')?> : <?=mark_required()?></label>
	    <div class="col-xs-6">
	      	<?php foreach ($send_on as $key=>$value):?>
	    	<label class="checkbox-inline">
	  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('send_on[]', $key)?> name="send_on[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('send_on[]')?>
	    </div>
	</div>
	
	<div class="form-group">
		<label class="col-xs-2 control-label" for="send_hour"> <?=lang('hour')?> :</label>
		<div class="col-xs-2">
			<select class="form-control" name="send_hour" id="send_hour">
				<?php for($i = 0; $i <= NR_HOUR; $i++):?>
				<option value="<?=$i?>"> <?=$i?> </option>
				<?php endfor;?>
			</select>
			<?=form_error('send_hour')?>
		</div>
		
		<label class="col-xs-2 control-label" for="send_minute"> <?=lang('minute')?> :</label>
		<div class="col-xs-2">
			<select class="form-control" name="send_minute" id="send_minute">
				<?php foreach($send_minute as $key => $value):?>
				<option value="<?=$value?>"> <?=$value?> </option>
				<?php endforeach;?>
			</select>
			<?=form_error('send_minute')?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-xs-2 control-label" ><?=lang('customer_gender')?> :</label>
		<div class="col-xs-6">
	        <?php foreach ($customer_gender as $key => $value):?>
	    	<label class="checkbox-inline">
	  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('customer_gender[]', $key)?> name="customer_gender[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('customer_gender[]')?>
        </div>
	</div>
	
	<div class="form-group">
		<label class="col-xs-2 control-label"><?=lang('customer_type')?> :</label>
		<div class="col-xs-6">
	        <?php foreach ($customer_type as $key => $value):?>
	    	<label class="checkbox-inline">
	  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('customer_type[]', $key)?> name="customer_type[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('customer_type[]')?>
        </div>
	</div>
	
	<div class="form-group">
	    <div class="col-xs-offset-2 col-xs-6">
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<a class="btn btn-default mg-left-10" href="<?=site_url('newsletters')?>" role="button"><?=lang('btn_cancel')?></a>
		</div>
	</div>
</form>

<script>

$('#group_start_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});

$("#group_end_date .input-append.date.input-group").datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});

init_text_editor();
</script>

