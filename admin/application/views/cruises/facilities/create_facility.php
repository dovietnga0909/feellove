<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form role="form" name="frm" method="post">
<div class="row">
	<div class="col-md-8">
		<input type="hidden" name="action" value="">
		<div class="form-group">
			<label for="name"><?=lang('facilities_field_name')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
			<?=form_error('name')?>
		</div>
		<div class="form-group">
			<label class="control-label" for="is_important"><?=lang('facilities_field_is_important')?>:</label>
			<input type="checkbox" id="is_important" name="is_important" value="1" <?=set_checkbox('is_important', 1)?>>
			<?=form_error('is_important')?>
		</div>
		<div class="form-group">
			<label for="group_id"><?=lang('facilities_field_group')?>:</label>
			<select class="form-control" name="group_id">
				<option value=""><?=lang('facilities_select_group')?></option>
				<?php foreach ($facility_groups as $k => $group):?>
				<option value="<?=$k?>" <?=set_select('group_id', $k)?>><?=lang($group)?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('group_id')?>
		</div>
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
	</div>
</div>
</form>