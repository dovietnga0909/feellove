<?php if(empty($accommodation)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('tours/')?>" role="button">
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

<form role="form" name="frm" method="post">
<input type="hidden" name="tour_id" value="<?=$tour['id']?>">
<input type="hidden" name="accommodation_id" value="<?=$accommodation['id']?>">
<div class="row">
	<div class="col-xs-6">
		<div class="form-group">
			<label for="name"><?=lang('tour_accommodations_field_name')?>: <?=mark_required()?></label>
			<input type="text" class="form-control" name="name" value="<?=set_value('name', $accommodation['name'])?>">
			<?=form_error('name')?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-3">
		<div class="form-group">
			<label for="cruise_cabin_id"><?=lang('tour_accommodations_field_cabin')?>:</label>
			<select class="form-control" name="cruise_cabin_id">
				<option value=""><?=lang('tours_empty_select')?></option>
				<?php foreach ($cruise_cabins as $cabin):?>
				<option value="<?=$cabin['id']?>" <?=set_select('cruise_cabin_id', $cabin['id'], $accommodation['cruise_cabin_id'] == $cabin['id'] ? true : false)?>><?=$cabin['name']?></option>
				<?php endforeach;?>
			</select>
			<?=form_error('cruise_cabin_id')?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-9">
		<div class="form-group">
			<label for="content"><?=lang('tours_field_description')?>:</label>
			<textarea class="form-control rich-text" rows="8" name="content"><?=set_value('content', $accommodation['content'])?></textarea>
			<?=form_error('content')?>
		</div>
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('tours/accommodations/'.$accommodation['tour_id'])?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>
</form>
<script type="text/javascript">
init_text_editor();
</script>
<?php endif;?>