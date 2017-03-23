<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label for="full_name" class="col-xs-3 control-label"><?=lang('users_field_full_name')?>: <?=mark_required()?></label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="full_name" name="full_name" value="<?=set_value('full_name')?>">
			<?=form_error('full_name')?>
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-xs-3 control-label"><?=lang('users_field_email')?>: <?=mark_required()?></label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>">
			<?=form_error('email')?>
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-xs-3 control-label"><?=lang('users_field_username')?>: <?=mark_required()?></label>
		<div class="col-xs-3">
			<input type="text" class="form-control" id="username" name="username" autocomplete="off" value="<?=set_value('username')?>">
			<?=form_error('username')?>
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-xs-3 control-label"><?=lang('users_field_password')?>: <?=mark_required()?></label>
		<div class="col-xs-3">
			<input type="password" class="form-control" id="password" name="password" autocomplete="off">
			<?=form_error('password')?>
		</div>
	</div>
	<div class="form-group">
		<label for="passconf" class="col-xs-3 control-label"><?=lang('users_field_passconf')?>: <?=mark_required()?></label>
		<div class="col-xs-3">
			<input type="password" class="form-control" id="passconf" name="passconf">
			<?=form_error('passconf')?>
		</div>
	</div>
	<div class="form-group">
		<label for="partner_id" class="col-xs-3 control-label"><?=lang('users_field_partner')?>: <?=mark_required()?></label>
		<div class="col-xs-4">
			<select class="form-control" name="partner_id">
				<option value=""><?=lang('users_select_partner')?></option>
				<?php foreach ($partners as $partner):?>
				<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'])?>><?=$partner['name']?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('partner_id')?>
		</div>
	</div>
	<div class="form-group">
		<label for="signature" class="col-xs-3 control-label"><?=lang('users_field_signature')?>:</label>
		<div class="col-xs-8">
			<textarea class="form-control" rows="5" name="signature"><?=set_value('signature')?></textarea>
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