<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_promotions')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url()?>marketings/create-pro/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_promotion')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('field_name')?></th>
				<th><?=lang('pro_field_code')?></th>
				<th><?=lang('pro_field_status')?></th>
				<th><?=lang('field_expired_date')?></th>
				<th><?=lang('pro_field_current_booked')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($promotions)):?>
			
				<tr>
					<td colspan="7" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_pro_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_pro_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($promotions as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('marketings/edit-pro/'.$value['id'])?>"><?=$value['name']?></a></td>
						<td><?=$value['code']?></td>
						<td><?=$value['status'] == STATUS_ACTIVE? lang('active'):lang('inactive')?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['expired_date']))?></td>
						<td><?=$value['current_nr_booked']?></td>
						<td><?=date(DATE_TIME_FORMAT, strtotime($value['date_modified']))?></td>	
						
						<?php $privilege = get_right(DATA_MARKETING, $value['user_created_id'])?>
											
						<td>
							<a href="<?=site_url('marketings/edit-pro/'.$value['id'])?>">
								<span class="fa fa-edit"></span>
							</a>
							<?php if($privilege == FULL_PRIVILEGE):?>
							<a href="<?=site_url('marketings/delete-pro/'.$value['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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