<ul class="nav nav-tabs mg-bottom-20">
	<li class="active"><a href="<?=site_url('/hotels/photos/'.$hotel['id'])?>"><?=lang('hotel_mnu_photo_editing')?></a></li>
	<li><a href="<?=site_url('/hotels/photo_upload/'.$hotel['id'])?>"><?=lang('hotel_mnu_photo_upload')?></a></li>
</ul>

<div class="row">
	<div class="col-xs-12 form-group">
		<ul style="padding-left: 20px;" class="note">
			<li><?=lang('hotel_photo_note_1')?></li>
			<li><?=lang('hotel_photo_note_2')?></li>
			<li><?=lang('hotel_photo_note_3')?></li>
		</ul>
	</div>
</div>

<?php if(validation_errors() != ''):?>
<div class="mg-bottom-20 bp-error">
	<?php echo validation_errors(); ?>
</div>
<?php endif;?>

<div class="row">
	<div class="col-xs-12 form-group">
		<ul class="nav nav-pills">
		   	<li <?php if($room_id == 0 && $photo_type != 1) echo 'class="active"'?>>
		   		<a href="<?=site_url('/hotels/photos/'.$hotel['id'])?>">All Photos
		   		<span class="badge"><?=$photo_count[0]?></span>
		   		</a>
		   	</li>
		   	<li <?php if($photo_type == 1) echo 'class="active"'?>>
		   		<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?type=1')?>">Gallery
		   		<span class="badge"><?=$photo_count[1]?></span>
		   		</a>
		   	</li>
		   	<?php foreach ($room_types as $k => $room_type):?>
		    <li <?php if($room_id == $room_type['id']) echo 'class="active"'?>>
		    	<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?r_id='.$room_type['id'])?>"><?=$room_type['name']?>
		    	<span class="badge"><?=$photo_count[$k + 2]?></span>
		   		</a>
		    </li>
		    <?php endforeach;?>
		</ul>
	</div>
</div>

<?php if(!empty($photos)):?>
<form role="form" method="post">

<ul class="sortable grid">
<?php foreach ($photos as $key => $photo):?>
	<?php 
		$err_class = form_error('type_'.$key) ? ' class="input-error"' : '';
	?>
	<li <?=$err_class?>>
		<div class="thumb" id="thumb_<?=$key?>">
			<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?action=remove&p_id='.$photo['id'])?>" 
				class="box_btn_delete" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
			&times;
			</a>
			<input type="hidden" value="<?=$photo['id']?>" name="identity_<?=$key?>">
			<div class="thumbnail">
				<img class="lazy h-photo" data-photo-id="<?=$photo['id']?>"
				    data-photo-width="<?=$photo['width']?>" data-photo-height="<?=$photo['height']?>"
					src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"
					data-original="<?=get_static_resources('/images/hotels/uploads/'.$photo['name'])?>">
			</div>
			<div class="form-group text-center note" style="position: relative;">
				<?=$photo['width'].' * '.$photo['height'].' px'?>
				
				<?php if($key == 0):?>
				<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?action=re_order&type='.GO_DOWN.'&p_id='.$photo['id'])?>" 
					class="box_btn_up">
					<span class="fa fa-chevron-right"></span>
				</a>
				<?php elseif ($key == count($photos) - 1):?>
				<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?action=re_order&type='.GO_UP.'&p_id='.$photo['id'])?>" 
					class="box_btn_down">
					<span class="fa fa-chevron-left"></span>
				</a>
				<?php else:?>
				<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?action=re_order&type='.GO_UP.'&p_id='.$photo['id'])?>" 
					class="box_btn_down">
					<span class="fa fa-chevron-left"></span>
				</a>
				<a href="<?=site_url('/hotels/photos/'.$hotel['id'].'?action=re_order&type='.GO_DOWN.'&p_id='.$photo['id'])?>" 
					class="box_btn_up">
					<span class="fa fa-chevron-right"></span>
				</a>
				<?php endif;?>
			</div>
			<div class="form-group">
				<select class="form-control input-sm input-roomtype" name="type_<?=$key?>" id="type_<?=$key?>">
					<?php foreach ($hotel_photo_type as $k => $type):?>
					<option value="<?=$k?>" <?=set_select('type_'.$key, $k, $photo['type'] == $k ? true : false)?>><?=$type?></option>
					<?php endforeach;?>
				</select>
			</div>
			<?php $cap_err_class = (form_error('caption_'.$key) != '') ? ' input-error' : '';?>
			<input type="text" class="form-control input-sm<?=$cap_err_class?>" name="caption_<?=$key?>" 
				value="<?=set_value('caption_'.$key, $photo['caption'])?>" placeholder="Caption">
			<div class="form-group text-center note link-roomtype hide" id="link-roomtype-<?=$key?>">
				<span id="numb_roomtypes_<?=$key?>">0</span>
				<a href="javascript:select_room_types(<?=$key?>)"><?=strtolower(lang('hotel_photo_field_room_types'))?></a> <?=lang('hotel_photo_field_selected')?>
				<input type="hidden" name="room_type_<?=$key?>" value="<?=set_value('room_type_'.$key, $photo['room_ids'])?>">
				<input type="hidden" name="room_type_main_photo_<?=$key?>" value="<?=set_value('room_type_main_photo_'.$key, $photo['room_main_photo_ids'])?>">
				<input type="hidden" name="position_<?=$key?>" value="<?=set_value('position_'.$key, $photo['position'])?>" class="input-position">
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
				<?=lang('btn_save_changes')?>
			</button>
			
			<a class="btn btn-default mg-left-10" href="<?=site_url('hotels/photos/'.$hotel['id'])?>" role="button"><?=lang('btn_cancel')?></a>
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
				<h4 class="modal-title" id="myModalLabel"><?=lang('hotel_photo_select_room_types')?></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="modal_roomtypes">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th><?=lang('hotel_photo_room_type_name')?></th>
							<th><?=lang('hotel_photo_is_main_photo')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($room_types as $k => $room_type):?>
							<tr>
								<td><?=$k+1?></td>
								<td>
									<input type="checkbox"
										name="room_type" value="<?=$room_type['id']?>"> <?=$room_type['name']?>
								</td>
								<td>
									<input type="checkbox"
										name="room_type_main_photo" value="<?=$room_type['id']?>" disabled="disabled">
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('btn_cancel')?></button>
				<button type="button" class="btn btn-primary" onclick="save_room_types()"><?=lang('btn_select_room_type')?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog crop-photo-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<img src="" id="crop_photo" data-photo-id="" class="crop-photo">
				<input type="hidden" name="image_id" value="">
			</div>
			<div class="modal-footer">
				<span id="thumbs_msg" class="pull-left bp-error"></span>
				<span id="crop_width" class="pull-left mg-left-10"></span>
				<span id="crop_height" class="pull-left mg-left-10"></span>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('btn_cancel')?></button>
				<button type="button" class="btn btn-primary" onclick="crop_image('/admin/crop_image/')"><?=lang('btn_crop_photo')?></button>
			</div>
		</div>
	</div>
</div>
<?php else:?>
	<?=lang('hotel_photo_please_upload')?>
<?php endif;?>

<script>
	$(function() {
		$("img.lazy").lazyload({
		    effect : "fadeIn"
		});
		
		init_hotel_photo();
	});

  	//Triggered when the user stopped sorting and the DOM position has changed.
    $('.sortable').sortable({
    	handle:'.h-photo'
    }).bind('sortupdate', function() {  
    	var posArray = [];
        $(".input-position").each(function() {
        	posArray.push($(this).val());
 	    });
        posArray.sort();
        
        $(".input-position").each(function(index) {
        	$(this).val(posArray[index]);
        });
    });

    $(".btn-save-photo").click(function(event)
    {
    	//validation_photo(event);
    });

</script>