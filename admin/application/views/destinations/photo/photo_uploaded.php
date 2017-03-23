<ul class="nav nav-tabs mg-bottom-20">
	<li><a href="<?=site_url('/destinations/photos/'.$destination['id'])?>"><?=lang('destination_photo_title')?></a></li>
	<li class="active"><a href="<?=site_url('/destinations/photo_upload/'.$destination['id'])?>"><?=lang('destination_photo_upload_title')?></a></li>
</ul>

<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>
<?php echo validation_errors(); ?>

<form role="form" method="post">
<ul class="sortable grid">
	<?php foreach ($photos as $key => $photo):?>
	<li>
		<div class="form-group thumb" id="thumb_<?=$key?>">
			<div class="thumbnail"><img class="h-photo" src="<?=get_static_resources('/images/destinations/uploads/'.$photo['name'])?>"></div>
			<div class="form-group">
				<select class="form-control input-sm input-roomtype" name="type_<?=$key?>" id="type_<?=$key?>">
					<?php foreach ($destination_photo_type as $k => $type):?>
					<option value="<?=$k?>" <?=set_select('type_'.$key, $k)?>><?=$type?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control input-sm" name="caption_<?=$key?>" 
					value="<?=set_value('caption_'.$key, $photo['caption'])?>" placeholder="Caption">
				<?=form_error('caption_'.$key)?>
			</div>
		</div>
	</li>
	<?php endforeach;?>
</ul>
<div class="row">
	<div class="col-xs-8">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-save-photo">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<a class="btn btn-default mg-left-10" href="<?=site_url('destinations/photo_upload/'.$destination['id'].'?action=cancel')?>" role="button"><?=lang('btn_cancel')?></a>
		</div>
	</div>
</div>
</form>

<script>
	$(function() {
		init_tour_photo();
	});
</script>