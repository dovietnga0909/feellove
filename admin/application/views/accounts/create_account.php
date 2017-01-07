<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_create_account')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label for="email" class="col-xs-2 control-label"><?=lang('account_field_email')?>: <?=mark_required()?></label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="email" name="email" value="">
			<?=form_error('email')?>
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-xs-2 control-label"><?=lang('account_field_username')?>: </label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="username" name="username" value="">
			<?=form_error('username')?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="phone" class="col-xs-2 control-label"><?=lang('account_field_phone')?>:</label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="phone" name='phone' value="">
			<?=form_error('phone')?>
		</div>
	</div>
	<div class="form-group">
	    <div class="col-xs-offset-3 col-xs-6">
	    	<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<a class="btn btn-default mg-left-10" href="<?=site_url('accounts')?>" role="button"><?=lang('btn_cancel')?></a>
	    </div>
	</div>
</form>