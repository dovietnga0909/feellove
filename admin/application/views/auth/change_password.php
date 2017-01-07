<div class="row">
	<div class="col-md-4">
		<form role="form" name="frm" method="post" action="<?=site_url().'change_password/'?>">
			<input type="hidden" name="action" value="">
			<div class="form-group">
				<label for="old_password"><?=lang('change_password_old_password_label')?>: <?=mark_required()?></label>
				<input type="password" class="form-control" id="old_password" name="old_password" 
					value="<?=set_value('old_password')?>"
					maxlength="20" autocomplete="off">
				<?=form_error('old_password')?>
			</div>
			<div class="form-group">
				<label for="new_password"><?php echo lang('change_password_new_password_label').sprintf(lang('change_password_min_length'), $min_password_length);?>: <?=mark_required()?></label>
				<input type="password" class="form-control" id="new_password" name="new_password" 
					value="<?=set_value('new_password')?>"
					maxlength="20" autocomplete="off">
				<?=form_error('new_password')?>
			</div>
			<div class="form-group">
				<label for="new_password_confirm"><?=lang('change_password_new_password_confirm_label')?>: <?=mark_required()?></label>
				<input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" 
					value="<?=set_value('new_password_confirm')?>"
					maxlength="20" autocomplete="off">
				<?=form_error('new_password_confirm')?>
			</div>
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save_changes')?>
			</button>
		</form>
	</div>
</div>