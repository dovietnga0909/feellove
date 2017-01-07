<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_promotions')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('hotels/promotions/'.$hotel_id.'/create')?>/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_promotion')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('pro_field_name')?></th>
				
								
				<th><?=lang('pro_field_stay_date_from')?></th>
				<th><?=lang('pro_field_stay_date_to')?></th>
				<th><?=lang('pro_field_type')?></th>
				
				<th><?=lang('pro_field_room_type')?></th>
				
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($promotions)):?>
			
				<tr>
					<td colspan="9" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_promotion_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_promotion_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($promotions as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('hotels/promotions/'.$hotel_id.'/edit/'.$value['id'].'/')?>"><?=$value['name']?></a></td>
												
						<td><?=date(DATE_FORMAT, strtotime($value['stay_date_from']))?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['stay_date_to']))?></td>
						<td><?=$promotion_types[$value['promotion_type']]?></td>
						
						<td><?=$pro_room_types[$value['room_type']]?></td>
						
						<td><?=get_last_update($value['date_modified'], $value['last_modified_by'])?></td>						
						<td>
							<a href="<?=site_url('hotels/promotions/'.$hotel_id.'/view/'.$value['id'].'/')?>">
								<span class="fa fa-list-alt"></span>
							</a>
							
							<a href="<?=site_url('hotels/promotions/'.$hotel_id.'/edit/'.$value['id'].'/')?>" class="mg-left-10">
								<span class="fa fa-edit"></span>
							</a>
							<a href="<?=site_url('hotels/promotions/'.$hotel_id.'/delete/'.$value['id'].'/')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
								<span class="fa fa-times"></span>
							</a>
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