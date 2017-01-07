<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_customers')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('customers/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_customer_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('customers_field_full_name')?></th>
				<th><?=lang('customers_field_email')?></th>
				<th><?=lang('customers_field_phone')?></th>
				<th><?=lang('customers_field_destination')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="5%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($customers)):?>
				<?php foreach ($customers as $k => $customer):?>
				<tr>
					<td><?=$offset + $k + 1?></td>
					<td>
						<a href="<?=site_url('/customers/edit/'.$customer['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$customer['full_name']?>
						</a>
					</td>
					<td><?=$customer['email']?></td>
					<td><?=$customer['phone']?></td>
					<td><?=$customer['destination_name']?></td>
					<td><?=get_last_update($customer['date_modified'])?></td>
					
					<?php $privilege = get_right(DATA_CUSTOMER, $customer['user_created_id'])?>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/customers/edit/'.$customer['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE):?>
						<a href="<?=site_url('/customers/delete/'.$customer['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="7" class="text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_customer_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_customer_found')?></div>
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

<button class="btn btn-primary" onclick="location.href='<?=site_url('bookings/export_customer') ?>'" role="button">
  	Export Customer List
</button>