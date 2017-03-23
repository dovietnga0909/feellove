<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_partners')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('partners/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_partner_create_btn')?>
	  	</a>
	  </div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th><?=lang('partners_field_name')?></th>
					<th><?=lang('partners_field_joining_date')?></th>
					<th><?=lang('partners_field_phone')?></th>
					<th><?=lang('partners_field_email')?></th>
					<th><?=lang('field_last_modified')?></th>
					<th class="text-center" width="5%"><?=lang('field_action')?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($partners)):?>
					<?php foreach ($partners as $k => $partner):?>
					<tr>
						<td><?=$offset + $k + 1?></td>
						<td>
							<a href="<?=site_url('/partners/edit/'.$partner['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$partner['name']?>
							</a>
						</td>
						<td><?=date(DATE_FORMAT, strtotime($partner['joining_date']))?></td>
						<td><?=$partner['phone']?></td>
						<td><?=$partner['email']?></td>
						<td><?=get_last_update($partner['date_modified'])?> <?=lang('by')?> <?=$partner['last_modified_by']?></td>
						
						<?php $privilege = get_right(DATA_PARTNER, $partner['user_created_id'])?>
						
						<td class="col-action" nowrap="nowrap">
							<a href="<?=site_url('/partners/edit/'.$partner['id'])?>" title="<?=lang('ico_edit')?>">
								<span class="fa fa-edit"></span>
							</a>
							<a href="<?=site_url('/partners/payment/'.$partner['id'])?>" title="<?=lang('ico_payment')?>">
								<span class="fa fa-credit-card"></span>
							</a>
							<a href="<?=site_url('/partners/contacts/'.$partner['id'])?>" title="<?=lang('ico_contact')?>">
								<span class="fa fa-user"></span>
							</a>
							<?php if($privilege == FULL_PRIVILEGE):?>
							<a href="<?=site_url('/partners/delete/'.$partner['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
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
								<div class="alert alert-warning"><?=lang('no_partner_created')?></div>
							<?php else:?>
								<div class="alert alert-warning"><?=lang('no_partner_found')?></div>
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