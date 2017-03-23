<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_schedules')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('users/schedules/create')?>/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_schedule')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('users_field_username')?></th>
				<th><?=lang('field_start_date')?></th>
				<th><?=lang('field_end_date')?></th>				
				<th><?=lang('h_week_day')?></th>
				<th><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($schedules)):?>
			
				<tr>
					<td colspan="9" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_schedule_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_schedule_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($schedules as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('users/schedules/edit/'.$value['id'].'/')?>"><?=$value['username']?></a></td>
						<td><?=date(DATE_FORMAT, strtotime($value['start_date']))?></td>
						<td><?=date(DATE_FORMAT, strtotime($value['end_date']))?></td>
						<td>
							<?php foreach ($week_days as $k=>$v):?>
						    	<div class="checkbox-inline">
						    		<?php 
						    			$checked = is_bit_value_contain($value['week_day'], $k);
						    		?>
						    		<input type="checkbox" readonly="readonly" id="check_box_<?=$k?>" value="<?=$k?>" <?=set_checkbox('week_day[]',$k, $checked)?> name="week_day[]" > <?=lang($v)?>
								</div>
								
								<?php if($k == 3):?>
									<br>
								<?php endif;?>
								
							<?php endforeach;?>
			
						</td>
						<td><?=$value['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
						<td><?=date(DATE_TIME_FORMAT, strtotime($value['date_modified']))?></td>						
						<td>
							<a href="<?=site_url('users/schedules/edit/'.$value['id'].'/')?>">
								<span class="fa fa-edit"></span>
							</a>
							<a href="<?=site_url('users/schedules/delete/'.$value['id'].'/')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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