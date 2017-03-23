<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_hotels')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('hotels/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_hotel_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('hotels_field_id')?></th>
				<th><?=lang('hotels_field_name')?></th>
				<th><?=lang('hotels_field_destination')?></th>
				<th><?=lang('hotels_field_star')?></th>
				<th><?=lang('hotels_field_partner')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($hotels)):?>
				<?php foreach ($hotels as $k => $hotel):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($hotel, $max_pos, $min_pos, MODULE_HOTEL)?>
					</td>
					<td><?=$hotel['id']?></td>
					<td>
						<a href="<?=site_url('/hotels/profiles/'.$hotel['id'])?>" id="hotel_detail_<?=$hotel['id']?>">
							<?=$hotel['name']?>
							<?=_get_partner_info($hotel)?>
						</a>
					</td>
					<td><?=$hotel['destination_name']?></td>
					<td><?=$hotel['star']?></td>
					<td><?=$hotel['partner_name']?></td>
					<td class="text-center"><?php echo $hotel['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?=get_last_update($hotel['date_modified'], $hotel['last_modified_by'])?></td>
					
					<?php $privilege = get_right(DATA_HOTEL, $hotel['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/hotels/profiles/'.$hotel['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/hotels/photos/'.$hotel['id'])?>" title="<?=lang('ico_photo')?>">
							<span class="fa fa-photo"></span>
						</a>
						<a href="<?=site_url('/hotels/rooms/'.$hotel['id'])?>" title="<?=lang('ico_rooms')?>">
							<span class="fa fa-home"></span>
						</a>
						<a href="<?=site_url('/hotels/contracts/'.$hotel['id'])?>" title="<?=lang('ico_contracts')?>">
							<span class="fa fa-floppy-o"></span>
						</a>
						<a href="<?=site_url('/hotels/rates/'.$hotel['id'])?>" title="<?=lang('ico_rates')?>">
							<span class="fa fa-dollar"></span>
						</a>
						<a href="<?=site_url('/hotels/promotions/'.$hotel['id'])?>" title="<?=lang('ico_promotion')?>">
							<span class="fa fa-tag"></span>
						</a>
						<a href="<?=site_url('/hotels/surcharges/'.$hotel['id'])?>" title="<?=lang('ico_surcharge')?>">
							<span class="fa fa-cogs"></span>
						</a>
						<a href="<?=site_url('/hotels/reviews/'.$hotel['id'])?>" title="<?=lang('ico_reviews')?>">
							<span class="fa fa-comments-o"></span>
						</a>
						<a href="<?=site_url('/hotels/clear-cache/'.$hotel['id'])?>" onclick="return confirm_delete('<?=lang('confirm_clear_cache')?>')" title="<?=lang('ico_empty_cache')?>">
							<span class="fa fa-clock-o"></span>
						</a>
						
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/hotels/delete/'.$hotel['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="8" class="text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_hotel_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_hotel_found')?></div>
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