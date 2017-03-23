<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_tours')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_tour_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('tours_field_code')?></th>
				<th><?=lang('tours_field_name')?></th>
				<th><?=lang('tours_field_partner')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($tours)):?>
				<?php foreach ($tours as $k => $tour):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($tour, $max_pos, $min_pos, MODULE_TOURS, '', $tours)?>
					</td>
					
					<td>
						<?php if(!empty($tour['code'])):?>
							<a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>">
								<?=$tour['code']?>
							</a>
						<?php endif;?>
					</td>
					
					<td>
						<a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$tour['name']?>
						</a>
					</td>
					<td><?=$tour['partner_name']?></td>
					
					<td class="text-center"><?php echo $tour['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?=get_last_update($tour['date_modified'], $tour['last_modified_by'])?></td>
					
					<?php $privilege = get_right(DATA_TOUR, $tour['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/tours/photos/'.$tour['id'])?>" title="<?=lang('ico_photo')?>">
							<span class="fa fa-photo"></span>
						</a>
						<a href="<?=site_url('/tours/accommodations/'.$tour['id'])?>" title="<?=lang('ico_accommodation')?>">
							<span class="fa fa-home"></span>
						</a>
						<a href="<?=site_url('/tours/itinerary/'.$tour['id'])?>" title="<?=lang('ico_itinerary')?>">
							<span class="fa fa-file-text-o"></span>
						</a>
						<a href="<?=site_url('/tours/contracts/'.$tour['id'])?>" title="<?=lang('ico_contracts')?>">
							<span class="fa fa-floppy-o"></span>
						</a>
						<a href="<?=site_url('/tours/rates/'.$tour['id'])?>" title="<?=lang('ico_rates')?>">
							<span class="fa fa-dollar"></span>
						</a>
						<a href="<?=site_url('/tours/reviews/'.$tour['id'])?>" title="<?=lang('ico_reviews')?>">
							<span class="fa fa-comments-o"></span>
						</a>
						<a href="<?=site_url('/tours/clear-cache/'.$tour['id'])?>" onclick="return confirm_delete('<?=lang('confirm_clear_cache')?>')" title="<?=lang('ico_empty_cache')?>">
							<span class="fa fa-clock-o"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/tours/delete/'.$tour['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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
							<div class="alert alert-warning"><?=lang('no_tour_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_tour_found')?></div>
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