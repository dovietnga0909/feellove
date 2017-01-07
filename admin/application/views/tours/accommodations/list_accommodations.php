<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_accommodations')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/create_accommodation/'.$tour['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_accommodation_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th width="40%"><?=lang('tour_accommodations_field_name')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($accommodations)):?>
				<?php foreach ($accommodations as $k => $accommodation):?>
				<tr>
					<td class="v-middle col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($accommodation, $max_pos, $min_pos, MODULE_ACCOMMODATIONS, '&t_id='.$tour['id'], $accommodations)?>
					</td>
					<td class="v-middle">
						<a href="<?=site_url('/tours/edit_accommodation/'.$accommodation['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$accommodation['name']?>
						</a>
					</td>
					<td><?=get_last_update($accommodation['date_modified'])?> <?=lang('by')?> <?=$accommodation['last_modified_by']?></td>
					<td class="col-action v-middle" nowrap="nowrap">
						<a href="<?=site_url('/tours/edit_accommodation/'.$accommodation['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/tours/delete_accommodation/'.$accommodation['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_accommodations_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_accommodations_found')?></div>
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