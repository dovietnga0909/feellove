<div class="panel panel-default">
	 <!-- Default panel contents -->
  	<div class="panel-heading">
  		<?=lang('list_of_accounts')?>
  		<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('accounts/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_account_create_btn')?>
	  	</a>
  	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('users_field_email')?></th>
				<th><?=lang('users_field_username')?></th>
				<th><?=lang('phone_of_accounts')?></th>
				<th><?=lang('register_account')?></th> 
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('creat_account')?></th>
				<th><?=lang('date_create')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th width="5%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($accounts)):?>
				<?php foreach ($accounts as $k => $account):?>
				<tr>
					<td><?=$offset + $k + 1?></td>
					<td><a href="<?=site_url('/accounts/edit/'.$account['id'])?>"> <?=$account['email']?></a></td>
					<td><?=$account['username']?></td>
					<td><?=$account['phone']?></td>
					<td><?=name_register($account['register'])?></td>
					<td class="text-center"><?php echo $account['active'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?php if(isset($account['created_account_by'])){ echo lang('by'); echo $account['created_account_by'];}else{echo lang('by'); echo lang('system');}?></td>
					<td><?=$account['date_created']?></td>
					<td><?=get_last_update($account['date_modified'])?> <?php if(isset($account['last_modified_by'])) echo lang('by');?> <?=$account['last_modified_by']?></td>
					
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/accounts/edit/'.$account['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/accounts/delete/'.$account['id'])?>" title="<?=lang('delete')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php if($account['register'] == 1) :?>
						<?php else :?>
						<a href="<?=site_url('/accounts/reset/'.$account['id'])?>" title="<?=lang('reset_password')?>" onclick="return confirm_delete('<?=lang('confirm_reset_password')?>')"><span class="fa fa-refresh"></span></a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="8" class="text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_user_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_user_found')?></div>
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