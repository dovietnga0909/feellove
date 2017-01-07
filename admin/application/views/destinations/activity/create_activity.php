<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="destination_id" value="<?=$destination['id']?>">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('activity_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>

<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('activity_field_description')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>

<div class="form-group">
	<label for="activity_photos" class="col-xs-2 control-label"><?=lang('activity_field_photo')?>:</label>
	<div class="col-xs-9">
		<input type="hidden" name="photos" id="activity_photos" value="<?=set_value('photos')?>">
		<a data-toggle="modal" href="<?=site_url('/destinations/activities/photos/'.$destination['id'])?>" data-target="#myModal"><?=lang('photos_activity_field_label')?></a>
	</div>
	<?=form_error('photos')?>
</div>

<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('destinations/activity/'.$destination['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog tour-modal-dialog">
        <div class="modal-content">
           
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

</form>
<script type="text/javascript">
	init_text_editor();

	$('#myModal').on('shown.bs.modal', function (e) {
		init_photos();
	})
</script>