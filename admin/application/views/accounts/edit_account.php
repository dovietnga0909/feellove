<?php if(empty($account)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('accounts')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>
	<form class="form-horizontal" role="form" method="post">
		<div class="form-group">
			<label for="email" class="col-xs-2 control-label"><?=lang('account_field_email')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $account['email'])?>">
				<?=form_error('email')?>
			</div>
		</div>
		<div class="form-group">
			<label for="username" class="col-xs-2 control-label"><?=lang('account_field_username')?>: </label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="username" name="username" value="<?=set_value('email', $account['username'])?>">
				<?=form_error('username')?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="phone" class="col-xs-2 control-label"><?=lang('account_field_phone')?>:</label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="phone" name='phone' value="<?=set_value('phone', $account['phone'])?>">
				<?=form_error('phone')?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="active" class="col-xs-2 control-label"><?=lang('account_field_active')?>:</label>
			<div class="col-xs-4">
				<select class="form-control" id="active" name="active">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('active', $key, $key==$account['active'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('active')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-2 col-xs-6">
		    	<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('accounts')?>" role="button"><?=lang('btn_cancel')?></a>
		    </div>
		</div>
	</form>
<?php endif;?>