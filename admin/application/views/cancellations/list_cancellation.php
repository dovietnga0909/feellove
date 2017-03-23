<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_cancellations')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url()?>cancellations/create/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_cancellation')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('can_field_name')?></th>
				<th><?=lang('can_field_fit')?></th>
				<th><?=lang('can_field_fit_cutoff')?></th>
				<th><?=lang('can_field_git_cutoff')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($cancellations)):?>
			
				<tr>
					<td colspan="7" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_cancellation_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_cancellation_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($cancellations as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('cancellations/edit/'.$value['id'])?>"><?=$value['name']?></a></td>
						<td><?=$value['fit']?></td>
						<td><?=$value['fit_cutoff']?></td>
						<td><?=$value['git_cutoff']?></td>
						<td><?=date(DATE_TIME_FORMAT, strtotime($value['date_modified']))?></td>	
						
						<?php $privilege = get_right(DATA_CANCELLATIONS, $value['user_created_id'])?>
											
						<td>
							<a href="<?=site_url('cancellations/edit/'.$value['id'])?>">
								<span class="fa fa-edit"></span>
							</a>
							<?php if($privilege == FULL_PRIVILEGE):?>
							<a href="<?=site_url('cancellations/delete/'.$value['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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