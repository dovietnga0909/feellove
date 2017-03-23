<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_tour_category')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('categories/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_tour_category_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('tour_category_field_name')?></th>
				<th class="text-center"><?=lang('tour_category_field_is_hot')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th nowrap="nowrap"><?=lang('tour_category_field_link')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($categories)):?>
				<?php foreach ($categories as $k => $category):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($category, $max_pos, $min_pos, MODULE_TOUR_CATEGORY, '', $categories)?>
					</td>
					<td>
						<a href="<?=site_url('/categories/edit/'.$category['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$category['name']?>
						</a>
					</td>
					<td class="text-center">
					   <?php if($category['is_hot'] == STATUS_ACTIVE):?>
					       <span class="fa fa-check"></span>
					   <?php endif;?>
					</td>
					<td class="text-center"><?php echo $category['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?=$category['link']?></td>
					<td><?=get_last_update($category['date_modified'], $category['last_modified_by'])?></td>
					
					<?php $privilege = get_right(DATA_TOUR, $category['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/categories/edit/'.$category['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/categories/clear-cache/'.$category['id'])?>" onclick="return confirm_delete('<?=lang('confirm_clear_cache')?>')" title="<?=lang('ico_empty_cache')?>">
							<span class="fa fa-clock-o"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/categories/delete/'.$category['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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
							<div class="alert alert-warning"><?=lang('no_tour_category_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_tour_category_found')?></div>
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