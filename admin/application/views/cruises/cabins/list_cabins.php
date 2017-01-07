<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_cabins')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('cruises/create_cabin/'.$cruise['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_cabin_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('cruise_cabins_field_photo')?></th>
				<th width="40%"><?=lang('cruise_cabins_field_name')?></th>
				<th class="text-center"><?=lang('cruise_cabins_field_status')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($cabins)):?>
				<?php foreach ($cabins as $k => $cabin):?>
				<tr>
					<td class="v-middle col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($cabin, $max_pos, $min_pos, MODULE_CABINS, '&c_id='.$cruise['id'])?>
					</td>
					<td>
						<?php if(!empty($cabin['picture'])):?>
							<a href="<?=site_url('/cruises/edit_cabin/'.$cabin['id'])?>" title="<?=lang('ico_edit')?>">
							<img width="135" src="<?=get_static_resources('/images/cruises/uploads/'.$cabin['picture'])?>">
							</a>
						<?php endif;?>
					</td>
					<td class="v-middle">
						<a href="<?=site_url('/cruises/edit_cabin/'.$cabin['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$cabin['name']?>
						</a>
					</td>
					<td class="text-center v-middle"><?php echo $cabin['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td class="col-action v-middle" nowrap="nowrap">
						<a href="<?=site_url('/cruises/edit_cabin/'.$cabin['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/cruises/settings_cabin/'.$cabin['id'])?>" title="<?=lang('ico_settings')?>">
							<span class="fa fa-cogs"></span>
						</a>
						<a href="<?=site_url('/cruises/delete_cabin/'.$cabin['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_cabins_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_cabins_found')?></div>
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