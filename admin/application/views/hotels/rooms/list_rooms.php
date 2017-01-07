<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_rooms')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('hotels/create_room/'.$hotel['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_room_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('hotel_rooms_field_photo')?></th>
				<th width="40%"><?=lang('hotel_rooms_field_name')?></th>
				<th class="text-center"><?=lang('hotel_rooms_field_status')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($room_types)):?>
				<?php foreach ($room_types as $k => $room_type):?>
				<tr>
					<td class="v-middle col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($room_type, $max_pos, $min_pos, MODULE_ROOM_TYPES, '&h_id='.$hotel['id'])?>
					</td>
					<td>
						<?php if(!empty($room_type['picture'])):?>
							<a href="<?=site_url('/hotels/edit_room/'.$room_type['id'])?>" title="<?=lang('ico_edit')?>">
							<img width="135" src="<?=get_static_resources('/images/hotels/uploads/'.$room_type['picture'])?>">
							</a>
						<?php endif;?>
					</td>
					<td class="v-middle">
						<a href="<?=site_url('/hotels/edit_room/'.$room_type['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$room_type['name']?>
						</a>
					</td>
					<td class="text-center v-middle"><?php echo $room_type['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td class="col-action v-middle" nowrap="nowrap">
						<a href="<?=site_url('/hotels/edit_room/'.$room_type['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/hotels/settings_room/'.$room_type['id'])?>" title="<?=lang('ico_settings')?>">
							<span class="fa fa-cogs"></span>
						</a>
						<a href="<?=site_url('/hotels/delete_room/'.$room_type['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_room_types_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_room_types_found')?></div>
						<?php endif;?>
					</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
<div class="clearfix">
	<?=$paging_info['paging_links']?>
	<p class="paging-txt pull-right">
		<?=$paging_info['paging_text']?>
	</p>
</div>