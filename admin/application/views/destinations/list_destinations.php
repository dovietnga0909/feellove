<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_destinations')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('destinations/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_destination_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('destinations_field_id')?></th>
				<th><?=lang('destinations_field_name')?></th>
				<th><?=lang('destinations_field_parent_destination')?></th>
				<th><?=lang('destinations_field_type')?></th>
				
				<th><?=lang('nr_tour')?></th>
				<th><?=lang('nr_hotel')?></th>
				
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="5%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($destinations)):?>
				<?php foreach ($destinations as $k => $destination):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($destination, $max_pos, $min_pos, MODULE_DESTINATION, '', $destinations, $pos_name)?>
					</td>
					<td>
						<?=$destination['id']?>
					</td>
					<td>
						<a href="<?=site_url('/destinations/edit/'.$destination['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$destination['name']?>
							<?php if(!empty($destination['destination_code'])):?>
								(<?=$destination['destination_code']?>)
							<?php endif;?>
						</a>
					</td>
					<td><?=$destination['parent_name']?></td>
					<td><?=$destination['type_name']?></td>
					
					<td><?=($destination['nr_tour_domistic'] + $destination['nr_tour_outbound'])?></td>
					<td><?=$destination['number_of_hotels']?></td>
					
					<td><?=get_last_update($destination['date_modified'])?> <?=lang('by')?> <?=$destination['last_modified_by']?></td>
					
					<?php $privilege = get_right(DATA_DESTINATION, $destination['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/destinations/edit/'.$destination['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/destinations/map/'.$destination['id'])?>" title="<?=lang('ico_map')?>">
							<span class="fa fa-map-marker"></span>
						</a>
						<a href="<?=site_url('/destinations/clear-cache/'.$destination['id'])?>" onclick="return confirm_delete('<?=lang('confirm_clear_cache')?>')" title="<?=lang('ico_empty_cache')?>">
							<span class="fa fa-clock-o"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/destinations/delete/'.$destination['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_destination_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_destination_found')?></div>
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