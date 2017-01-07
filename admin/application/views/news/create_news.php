<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
<div class="form-group">
	<label class="col-xs-2 control-label" for="name"><?=lang('news_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label" for="category"><?=lang('news_field_category')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<?php foreach ($categories as $key => $cat):?>
		<div class="col-xs-3 pd-left-0">
		    <div class="checkbox">
		        <label>
		        <input type="checkbox" name="category[]" value="<?=$key?>" <?=set_checkbox('category', $key)?>> <?=lang($cat)?>
		        </label>
		    </div>
		</div>
		<?php endforeach;?>
		<div class="col-xs-12 pd-left-0">
		<?=form_error('category')?>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label" for="type"><?=lang('news_field_type')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<select class="form-control" name="type" id="type">
			<option value=""><?=lang('news_empty_select')?></option>
			<?php foreach ($news_types as $k => $type):?>
			<option value="<?=$k?>" <?=set_select('type', $k)?>>
			<?=lang($type)?>
			</option>
			<?php endforeach;?>
		</select>
		<?=form_error('type')?>
	</div>
</div>
<div class="form-group">
	<label for="start_date" class="col-xs-2 control-label"><?=lang('news_field_start_date')?>: <?=mark_required()?></label>
	<div class="col-xs-2" id="group_start_date">
		<div class="input-append date input-group">
		<input type="text" class="form-control" id="start_date" name="start_date" value="<?=set_value('start_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
	<div class="col-xs-4">
	<?=form_error('start_date')?>
	</div>
</div>
<div class="form-group">
	<label for="end_date" class="col-xs-2 control-label"><?=lang('news_field_end_date')?>: <?=mark_required()?></label>
	<div class="col-xs-2" id="group_end_date">
		<div class="input-append date input-group">
		<input type="text" class="form-control" id="end_date" name="end_date" value="<?=set_value('end_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
	<div class="col-xs-4">
		<?=form_error('end_date')?>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label" for="link"><?=lang('news_field_link')?>:</label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="link" name="link" value="<?=set_value('link')?>">
		<?=form_error('link')?>
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label" for="source"><?=lang('news_field_source')?>:</label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="source" name="source" value="<?=set_value('source')?>">
		<?=form_error('source')?>
	</div>
</div>
<div class="form-group">
	<label for="short_description" class="col-xs-2 control-label"><?=lang('news_field_short_description')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control" rows="5" name="short_description" id="short_description"><?=set_value('short_description')?></textarea>
		<?=form_error('short_description')?>
	</div>
</div>
<div class="form-group">
	<label for="content" class="col-xs-2 control-label"><?=lang('news_field_content')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control rich-text" rows="15" name="content" id="content"><?=set_value('content')?></textarea>
		<?=form_error('content')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('news')?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>
</form>
<script>
$('#group_start_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});

$('#group_end_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});

init_text_editor();
</script>