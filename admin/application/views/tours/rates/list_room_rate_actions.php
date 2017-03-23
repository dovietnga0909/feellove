<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_hotel_rate_actions')?></b>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/room-rate-action/'.$tour_id.'/create')?>/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_room_rate_action')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('rra_field_start_date')?></th>
				<th><?=lang('rra_field_end_date')?></th>				
				<th><?=lang('rra_field_week_day')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($tour_rate_actions)):?>
			
				<tr>
					<td colspan="7" align="center">
						<div class="alert alert-warning"><?=lang('no_rra_created')?></div>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($tour_rate_actions as $key=>$value):?>
				
					<tr>
						<td><?=$key + 1?></td>
						<td><a href="<?=site_url('tours/room-rate-action/'.$tour_id.'/edit/'.$value['id'].'/')?>"><?=date(DATE_FORMAT, strtotime($value['start_date']))?></a></td>
						<td><a href="<?=site_url('tours/room-rate-action/'.$tour_id.'/edit/'.$value['id'].'/')?>"><?=date(DATE_FORMAT, strtotime($value['end_date']))?></a></td>
						<td>
							<?php foreach($week_days as $k=>$wd):?>
								<?php if(is_bit_value_contain($value['week_day'], $k)):?>
									<?=lang($wd)?>,
								<?php endif;?>
							<?php endforeach;?>
						</td>
						<td><?=get_last_update($value['date_modified'])?> <?=lang('by')?> <?=$value['last_modified_by']?></td>						
						<td>
							<a href="<?=site_url('tours/room-rate-action/'.$tour_id.'/edit/'.$value['id'].'/')?>">
								<span class="fa fa-edit"></span>
							</a>
							<a href="<?=site_url('tours/room-rate-action/'.$tour_id.'/delete/'.$value['id'].'/')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
								<span class="fa fa-times"></span>
							</a>
						</td>
					</tr>
				<?php endforeach;?>
				
			<?php endif;?>
			
		</tbody>
	</table>
</div>