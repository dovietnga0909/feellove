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
			<div class="col-xs-6">
				<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $facility['name'])?>">
				<?=form_error('name')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-2 control-label" for="group_id"><?=lang('facilities_field_group')?>:</label>
			<div class="col-xs-6">
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
			<label class="col-xs-2 control-label" for="is_important"><?=lang('facilities_field_is_important')?>:</label>
			<div class="col-xs-6">
				<input type="checkbox" id="is_important" name="is_important" value="1" <?=set_checkbox('is_important', 1, $facility['is_important'] == 1 ? true : false)?>>
				<?=form_error('is_important')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-2 col-xs-6">
				<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('hotels/facilities/'.$hotel['id'])?>" role="button"><?=lang('btn_cancel')?></a>
				<a class="btn btn-danger mg-left-10" onclick="return confirm_delete('<?=lang('confirm_delete')?>')"
					href="<?=site_url('hotels/delete_facility/'.$facility['id'])?>" role="button"><?=lang('btn_delete')?></a>
			</div>
		</div>
	</form>
<?php endif;?>