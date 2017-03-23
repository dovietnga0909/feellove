<?php if(empty($activity)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('destinations/')?>" role="button">
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

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="destination_id" value="<?=$activity['destination_id']?>">
<input type="hidden" name="activity_id" value="<?=$activity['id']?>">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('activity_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="name" value="<?=set_value('name', $activity['name'])?>">
		<?=form_error('name')?>
	</div>
</div>
 
<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('activity_field_description')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="description"><?=set_value('description', $activity['description'])?></textarea>
		<?=form_error('content')?>
	</div>
</div>

<div class="form-group">
	<label for="active" class="col-xs-2 control-label"><?=lang('activity_status')?>:</label>
	<div class="col-xs-4">
		<select class="form-control" id="active" name="active">
			<option value=""><?=lang('please_select')?></option>
			<?php foreach ($status_config as $key => $value):?>
			<option value="<?=$key?>" <?=set_select('active', $key, $key == $activity['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('active')?>
	</div>
</div>

<div class="form-group">
	<label for="activity_photos" class="col-xs-2 control-label"><?=lang('activity_field_photo')?>:</label>
	<div class="col-xs-9">
		<input type="hidden" name="photos" id="activity_photos" value="<?=set_value('photos', $activity['photos'])?>">
		<a data-toggle="modal" href="<?=site_url('/destinations/activities/photos/'.$activity['destination_id'])?>" data-target="#myModal"><?=lang('photos_activity_field_label')?></a>
	</div>
	<?=form_error('photos')?>
</div>

<div class="row">
	<div class="col-xs-offset-2 col-xs-6">
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('destinations/activities/'.$activity['destination_id'])?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title"><?=lang('tour_itineraries_select_photos')?></h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
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
<?php endif;?>