<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('tour_category_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="is_hot" class="col-xs-2 control-label"><?=lang('tour_category_field_is_hot')?>: </label>
	<div class="col-xs-6">
		<input type="checkbox" id="is_hot" name="is_hot" value="1" <?=set_checkbox('is_hot', 1)?>>
		<?=form_error('is_hot')?>
	</div>
</div>
<div class="form-group">
	<label for="link" class="col-xs-2 control-label"><?=lang('tour_category_field_link')?>:</label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="link" name="link" value="<?=set_value('link')?>">
		<?=form_error('link')?>
	</div>
</div>
<div class="form-group">
	<label for="picture" class="col-xs-2 control-label"><?=lang('tour_category_field_picture')?>:</label>
	<div class="col-xs-6">
		<input type="file" name="picture" size="30" />
		<?=$upload_error;?>
	</div>
</div>
<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('tour_category_field_description')?>:</label>
	<div class="col-xs-10">
		<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('categories')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script type="text/javascript">
	init_text_editor();
</script>