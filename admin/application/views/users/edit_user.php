<?php if(empty($user)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('users')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>
	<form class="form-horizontal" role="form" method="post">
		<div class="form-group">
			<label for="full_name" class="col-xs-3 control-label"><?=lang('users_field_full_name')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="full_name" name="full_name" value="<?=set_value('full_name', $user['full_name'])?>">
				<?=form_error('full_name')?>
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-xs-3 control-label"><?=lang('users_field_email')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $user['email'])?>">
				<?=form_error('email')?>
			</div>
		</div>
		<?php if(is_administrator()):?>
		<div class="form-group">
			<label for=status class="col-xs-3 control-label"><?=lang('field_status')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, $key==$user['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('status')?>
			</div>
		</div>
		<?php endif;?>
		
		<?php if(is_administrator()):?>
		<div class="form-group">
			<label for="allow_assign_request" class="col-xs-3 control-label"><?=lang('allow_assign_request')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="allow_assign_request">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($allow_assign_request_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('allow_assign_request', $key, $key==$user['allow_assign_request'] ? TRUE : FALSE)?>>
						<?=lang($value)?>
					</option>
					<?php endforeach;?>
				</select>
				<?=form_error('allow_assign_request')?>
			</div>
		</div>
		<?php endif;?>
		
		<div class="form-group">
			<label for="partner_id" class="col-xs-3 control-label"><?=lang('users_field_partner')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<select class="form-control" name="partner_id">
					<option value=""><?=lang('users_select_partner')?></option>
					<?php foreach ($partners as $partner):?>
					<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], $partner['id']==$user['partner_id'] ? TRUE : FALSE)?>><?=$partner['name']?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('partner_id')?>
			</div>
		</div>
		<div class="form-group">
			<label for="roles" class="col-xs-3 control-label"><?=lang('users_field_roles')?>:</label>
			<div class="col-xs-8">
				<?php foreach ($roles as $role):?>
				<div class="col-xs-3">
					<input type="checkbox" name="roles[]" value="<?=$role['id']?>" 
						<?=set_checkbox('bed_config', $role['id'], in_array($role['id'], $user['roles']) ? true:false)?>> <?=$role['name']?>
				</div>
				<?php endforeach;?>
				<?=form_error('roles')?>
			</div>
		</div>
		<div class="form-group">
			<label for="signature" class="col-xs-3 control-label"><?=lang('users_field_signature')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="5" name="signature"><?=set_value('signature', $user['signature'])?></textarea>
				<?=form_error('signature')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-3 col-xs-6">
		    	<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('users')?>" role="button"><?=lang('btn_cancel')?></a>
		    </div>
		</div>
	</form>
<?php endif;?>