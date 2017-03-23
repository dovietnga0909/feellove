<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_cruises')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('cruises/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_cruise_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('cruises_field_id')?></th>
				<th><?=lang('cruises_field_name')?></th>
				<th class="text-center"><?=lang('cruises_field_cruise_type')?></th>
				<th><?=lang('cruises_field_star')?></th>
				<th><?=lang('cruises_field_partner')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($cruises)):?>
				<?php foreach ($cruises as $k => $cruise):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($cruise, $max_pos, $min_pos, MODULE_CRUISES)?>
					</td>
					<td><?=$cruise['id']?></td>
					<td>
						<a href="<?=site_url('/cruises/profiles/'.$cruise['id'])?>" id="cruise_detail_<?=$cruise['id']?>">
							<?=$cruise['name']?>
							<?=_get_cruise_partner_info($cruise)?>
						</a>
					</td>
					<td class="text-center"><?=$cruise['type_name']?></td>
					<td><?=$cruise['star']?></td>
					<td><?=$cruise['partner_name']?></td>
					<td class="text-center"><?php echo $cruise['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?=get_last_update($cruise['date_modified'], $cruise['last_modified_by'])?></td>
					
					<?php $privilege = get_right(DATA_CRUISE, $cruise['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/cruises/profiles/'.$cruise['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/cruises/photos/'.$cruise['id'])?>" title="<?=lang('ico_photo')?>">
							<span class="fa fa-photo"></span>
						</a>
						<a href="<?=site_url('/cruises/cabins/'.$cruise['id'])?>" title="<?=lang('ico_rooms')?>">
							<span class="fa fa-home"></span>
						</a>
						<a href="<?=site_url('/cruises/contracts/'.$cruise['id'])?>" title="<?=lang('ico_contracts')?>">
							<span class="fa fa-floppy-o"></span>
						</a>
						<a href="<?=site_url('/cruises/promotions/'.$cruise['id'])?>" title="<?=lang('ico_promotion')?>">
							<span class="fa fa-tag"></span>
						</a>
						<a href="<?=site_url('/cruises/surcharges/'.$cruise['id'])?>" title="<?=lang('ico_surcharge')?>">
							<span class="fa fa-cogs"></span>
						</a>
						<a href="<?=site_url('/cruises/reviews/'.$cruise['id'])?>" title="<?=lang('ico_reviews')?>">
							<span class="fa fa-comments-o"></span>
						</a>
						<a href="<?=site_url('/cruises/clear-cache/'.$cruise['id'])?>" onclick="return confirm_delete('<?=lang('confirm_clear_cache')?>')" title="<?=lang('ico_empty_cache')?>">
							<span class="fa fa-clock-o"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/cruises/delete/'.$cruise['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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
							<div class="alert alert-warning"><?=lang('no_cruise_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_cruise_found')?></div>
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