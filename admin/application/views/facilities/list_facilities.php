<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_facilities')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('facilities/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_facility_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('facilities_field_name')?></th>
				<th><?=lang('facilities_field_type')?></th>
				<th><?=lang('facilities_field_group')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th class="text-center"><?=lang('facilities_field_is_important')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($facilities)):?>
				<?php foreach ($facilities as $k => $facility):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($facility, $max_pos, $min_pos, MODULE_FACILITY)?>
					</td>
					<td>
						<a href="<?=site_url('/facilities/edit/'.$facility['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$facility['name']?>
						</a>
					</td>
					<td><?=$facility['type_name']?></td>
					<td><?=$facility['group_name']?></td>
					<td class="text-center"><?php echo $facility['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					
					<td class="text-center">
						<?php echo $facility['is_important'] == 1 ? '<span class="fa fa-check"></span>' : ''?>		
					</td>
					<td><?=get_last_update($facility['date_modified'])?> <?=lang('by')?> <?=$facility['last_modified_by']?></td>
					
					<?php $privilege = get_right(DATA_FACILITY, $facility['user_created_id'])?>
					
					<td class="text-center col-action">
						<a href="<?=site_url('/facilities/edit/'.$facility['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/facilities/delete/'.$facility['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="8" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_facility_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_facility_found')?></div>
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