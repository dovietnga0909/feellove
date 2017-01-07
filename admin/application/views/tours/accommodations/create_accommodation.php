<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="tour_id" value="<?=$tour['id']?>">
<div class="form-group">
	<label for="name" class="col-xs-3 control-label"><?=lang('tour_accommodations_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="name" value="<?=set_value('name')?>">
	<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="cruise_cabin_id" class="col-xs-3 control-label"><?=lang('tour_accommodations_field_cabin')?>:</label>
	<div class="col-xs-3">
		<select class="form-control" name="cruise_cabin_id">
			<option value=""><?=lang('tours_empty_select')?></option>
			<?php foreach ($cruise_cabins as $cabin):?>
			<option value="<?=$cabin['id']?>" <?=set_select('cruise_cabin_id', $cabin['id'])?>><?=$cabin['name']?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('cruise_cabin_id')?>
	</div>
</div>
<div class="form-group">
	<label for="content" class="col-xs-3 control-label"><?=lang('tours_field_description')?>:</label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="content"><?=set_value('content')?></textarea>
		<?=form_error('content')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-3 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('tours/accommodations/'.$tour['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>
</form>
<script type="text/javascript">
init_text_editor();
</script>