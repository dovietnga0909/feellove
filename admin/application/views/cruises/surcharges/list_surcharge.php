<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_surcharges')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('cruises/surcharges/'.$cruise_id.'/create')?>/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_surcharge')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('sur_field_name')?></th>
				<th><?=lang('sur_field_start_date')?></th>
				<th><?=lang('sur_field_end_date')?></th>				
				<th><?=lang('sur_field_charge_type')?></th>
				<th><?=lang('sur_field_adult_amount')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($surcharges)):?>
			
				<tr>
					<td colspan="9" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_surcharge_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_surcharge_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($surcharges as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('cruises/surcharges/'.$cruise_id.'/edit/'.$value['id'].'/')?>"><?=$value['name']?></a></td>
						<td><?=date(DATE_FORMAT, strtotime($value['start_date']))?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['end_date']))?></td>
						<td><?=$charge_types[$value['charge_type']]?></td>
						<td>
							<?=number_format($value['adult_amount'])?> 
							<?php if($value['charge_type'] == 1):?>
							<span><?=lang('vnd')?></span>
							<?php else:?>
							<span>%</span>
							<?php endif;?>
						</td>
						<td><?=get_last_update($value['date_modified'], $value['last_modified_by'])?></td>					
						<td>
							<a href="<?=site_url('cruises/surcharges/'.$cruise_id.'/edit/'.$value['id'].'/')?>">
								<span class="fa fa-edit"></span>
							</a>
							<a href="<?=site_url('cruises/surcharges/'.$cruise_id.'/delete/'.$value['id'].'/')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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