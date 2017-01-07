<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_activity')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('destinations/create_activity/'.$destination['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_activity_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('activity_field_name')?></th>
				<th><?=lang('activity_field_description')?></th>
				<th><?=lang('activity_status')?></th>
				<th><?=lang('field_last_modified')?></th>
			
				<th class="text-center" width="5%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
		
			<?php if(!empty($activities)):?>
				
				<?php foreach ($activities as $k => $activity):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$k + 1;?>
					</td>
					<td>
						<a href="<?=site_url('destinations/edit_activity/'.$activity['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$activity['name']?>
						</a>
					</td>
					<td><?=$activity['description']?></td>
					<td><?php if($activity['status'] == STATUS_ACTIVE ):?>
						<?=lang('active'); ?>
						<?php else :?>
						<?=lang('inactive'); ?>
						<?php endif;?>
					</td>
					<td><?=get_last_update($activity['date_modified'])?> <?=lang('by')?> <?=$activity['last_modified_by']?></td>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/destinations/edit_activity/'.$activity['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/destinations/delete_activity/'.$activity['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_activity_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_activity_found')?></div>
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