<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
<div class="form-group">
	<label class="col-xs-2 control-label" for="name"><?=lang('roles_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('roles')?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>
</form>