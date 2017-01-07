<ul class="nav nav-tabs mg-bottom-20">
	<li><a href="<?=site_url('/cruises/photos/'.$cruise['id'])?>"><?=lang('cruise_mnu_photo_editing')?></a></li>
	<li class="active"><a href="<?=site_url('/cruises/photo_upload/'.$cruise['id'])?>"><?=lang('cruise_mnu_photo_upload')?></a></li>
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
			<div class="thumbnail"><img class="h-photo" src="<?=get_static_resources('/images/cruises/uploads/'.$photo['name'])?>"></div>
			<div class="form-group">
				<select class="form-control input-sm input-roomtype" name="type_<?=$key?>" id="type_<?=$key?>">
					<?php foreach ($cruise_photo_type as $k => $type):?>
					<option value="<?=$k?>" <?=set_select('type_'.$key, $k)?>><?=$type?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control input-sm" name="caption_<?=$key?>" 
					value="<?=set_value('caption_'.$key, $photo['caption'])?>" placeholder="Caption">
				<?=form_error('caption_'.$key)?>
			</div>
			<div class="form-group text-center note link-roomtype hide" id="link-roomtype-<?=$key?>">
				<span id="numb_roomtypes_<?=$key?>">0</span>
				<a href="javascript:select_room_types(<?=$key?>)"><?=strtolower(lang('cruise_photo_field_cabins'))?></a> <?=lang('cruise_photo_field_selected')?>
				<input type="hidden" name="room_type_<?=$key?>" value="">
				<input type="hidden" name="room_type_main_photo_<?=$key?>" value="">
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
			<a class="btn btn-default mg-left-10" href="<?=site_url('cruises/photo_upload/'.$cruise['id'].'?action=cancel')?>" role="button"><?=lang('btn_cancel')?></a>
		</div>
	</div>
</div>
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?=lang('cruise_photo_select_cabins')?></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="modal_roomtypes">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th><?=lang('cruise_photo_cabin_name')?></th>
							<th><?=lang('cruise_photo_is_main_photo')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($cabins as $k => $cabin):?>
							<tr>
								<td><?=$k+1?></td>
								<td><input type="checkbox" name="room_type" value="<?=$cabin['id']?>"> <?=$cabin['name']?></td>
								<td>
									<input type="checkbox" name="room_type_main_photo" value="<?=$cabin['id']?>">
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('btn_cancel')?></button>
				<button type="button" class="btn btn-primary" onclick="save_cabins()"><?=lang('btn_save_changes')?></button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		init_cruise_photo();
	});

    $(".btn-save-photo").click(function(event)
    {
    	validation_photo(event);
    });
</script>