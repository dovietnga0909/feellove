<?php if(empty($cruise)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('cruises')?>" role="button">
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
	<form class="form-horizontal" role="form" method="post">
		<div class="row">
			<div class="col-xs-8">
				<div class="form-group">
					<label for="name" class="col-xs-3 control-label"><?=lang('cruises_field_name')?>: <?=mark_required()?></label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $cruise['name'])?>">
						<?=form_error('name')?>
					</div>
				</div>
				<div class="form-group">
					<label for="address" class="col-xs-3 control-label"><?=lang('cruises_field_address')?>: <?=mark_required()?></label>
					<div class="col-xs-9">
						<textarea class="form-control" rows="2" name="address"><?=set_value('address', $cruise['address'])?></textarea>
						<?=form_error('address')?>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="pull-right">
					<?php if(!empty($cruise['picture'])):?>
					<img width="175" src="<?=get_static_resources('/images/cruises/uploads/'.$cruise['picture'])?>">
					<?php endif;?>
					<div class="text-center">
						<a href="<?=site_url('cruises/photos/'.$cruise['id'])?>"><?=lang('update_cruise_photo')?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="destination_id" class="col-xs-2 control-label"><?=lang('cruises_field_cruise_type')?>: <?=mark_required()?></label>
			<div class="col-xs-6">
				<?php foreach ($cruise_type as $key => $value):?>
				<div class="col-xs-4">
				<input type="checkbox" name="cruise_type[]" value="<?=$key?>" <?=set_checkbox('cruise_type', $key, is_bit_value_contain($cruise['cruise_type'], $key))?>> <?=lang($value)?>
				</div>
				<?php endforeach;?>
				<?=form_error('cruise_type')?>
			</div>
		</div>
		<div class="form-group">
			<label for="star" class="col-xs-2 control-label"><?=lang('field_status')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, $key==$cruise['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('status')?>
			</div>
		</div>
		<div class="form-group">
			<label for="star" class="col-xs-2 control-label"><?=lang('cruises_field_star')?>: <?=mark_required()?></label>
			<div class="col-xs-2">
				<select class="form-control" name="star">
					<option value=""><?=lang('cruises_select_star')?></option>
					<?php foreach ($cruise_star as $star):?>
					<option value="<?=$star?>" <?=set_select('star', $star, $star==$cruise['star'] ? TRUE : FALSE)?>><?=$star?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('star')?>
			</div>
		</div>
		<?php if(is_admin()):?>
		<div class="form-group">
			<label for="partner_id" class="col-xs-2 control-label"><?=lang('cruises_field_partner')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<select class="form-control" name="partner_id">
					<option value=""><?=lang('cruises_select_partner')?></option>
					<?php foreach ($partners as $partner):?>
					<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], $partner['id']==$cruise['partner_id'] ? TRUE : FALSE)?>><?=$partner['name']?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('partner_id')?>
			</div>
		</div>
		<?php endif;?>
		<div class="form-group">
			<label for="description" class="col-xs-2 control-label"><?=lang('cruises_field_description')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description', $cruise['description'])?></textarea>
				<?=form_error('description')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-2 col-xs-8">
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
<?php endif;?>