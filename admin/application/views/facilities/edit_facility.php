<?php if(empty($facility)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('facilities')?>" role="button">
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
			<label class="col-xs-2 control-label" for="name"><?=lang('facilities_field_name')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $facility['name'])?>">
				<?=form_error('name')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 control-label" for="type_id"><?=lang('facilities_field_type')?>: <?=mark_required()?></label>
			<div class="col-xs-6">
				<?php foreach ($facility_types as $key => $type):?>
				<div class="col-xs-3 <?=($key==1) ? 'pd-left-0':''?>">
					<input type="checkbox" name="type_id[]" value="<?=$key?>" <?=set_checkbox('type_id', $key, is_bit_value_contain($facility['type_id'], $key))?>> <?=lang($type)?>
				</div>
				<?php endforeach;?>
				<div class="col-xs-12 pd-left-0">
				<?=form_error('type_id')?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 control-label" for="is_important"><?=lang('facilities_field_is_important')?>:</label>
			<div class="col-xs-4">
				<div class="col-xs-4 pd-left-0">
					<input type="checkbox" id="is_important" name="is_important" value="1" <?=set_checkbox('is_important', 1, $facility['is_important'] == 1 ? true : false)?>>
				</div>
				<div class="col-xs-12 pd-left-0">
				<?=form_error('is_important')?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 control-label" for="name"><?=lang('field_status')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, $key==$facility['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('status')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 control-label" for="group_id"><?=lang('facilities_field_group')?>:</label>
			<div class="col-xs-3">
				<select class="form-control" name="group_id">
					<option value=""><?=lang('facilities_select_group')?></option>
					<?php foreach ($facility_groups as $k => $group):?>
					<option value="<?=$k?>" <?=set_select('group_id', $k, $k == $facility['group_id'] ? TRUE : FALSE)?>><?=lang($group)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('group_id')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-2 col-xs-6">
				<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('facilities')?>" role="button"><?=lang('btn_cancel')?></a>
			</div>
		</div>
	</form>
<?php endif;?>