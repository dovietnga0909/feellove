<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_advertises')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url()?>advertises/create/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_advertise')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('ad_field_name')?></th>
				<th><?=lang('ad_field_data_type')?></th>
				<th><?=lang('field_status')?></th>
				<th><?=lang('field_start_date')?></th>
				<th><?=lang('field_end_date')?></th>
				<th><?=lang('ad_field_display_on')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($advertises)):?>
			
				<tr>
					<td colspan="8" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_advertise_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_advertise_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($advertises as $key=>$value):?>
					<tr>
						<td>
							<?=$offset + $key + 1?>
							<?=get_order_arrow($value, $max_pos, $min_pos, MODULE_ADVERTISES, '', $advertises)?>
						</td>
						<td><a href="<?=site_url('advertises/edit/'.$value['id'])?>"><?=$value['name']?></a></td>
						<td><?=$data_types[$value['data_type']]?></td>
						<td><?=$value['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['start_date']))?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['end_date']))?></td>
						<td><?=$value['display_on_txt']?></td>
						<td>
							<?=get_last_update($value['date_modified'])?> <?=lang('by')?> <?=$value['last_modified_by']?>
							
						</td>	
						
						<?php $privilege = get_right(DATA_ADVERTISES, $value['user_created_id'])?>
											
						<td>
							<a href="<?=site_url('advertises/edit/'.$value['id'])?>">
								<span class="fa fa-edit"></span>
							</a>
							<?php if($privilege == FULL_PRIVILEGE):?>
							<a href="<?=site_url('advertises/delete/'.$value['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
								<span class="fa fa-times"></span>
							</a>
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach;?>
				
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