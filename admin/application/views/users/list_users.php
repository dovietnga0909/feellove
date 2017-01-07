<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_users')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('users/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_user_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('users_field_full_name')?></th>
				<!-- 
				<th><?=lang('users_field_partner')?></th>
				 -->
				<th><?=lang('users_field_username')?></th>
				<th><?=lang('users_field_email')?></th>
				<th><?=lang('display_on')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th width="5%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($users)):?>
				<?php foreach ($users as $k => $user):?>
				<tr>
					<td><?=$offset + $k + 1?></td>
					<td>
						<a href="<?=site_url('/users/edit/'.$user['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$user['full_name']?>
						</a>
					</td>
					<!-- 
					<td><?=$user['partner_name']?></td>
					 -->
					<td><?=$user['username']?></td>
					<td><?=$user['email']?></td>
					<td><?=display_user($user);?></td>
					<td class="text-center"><?php echo $user['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					<td><?=get_last_update($user['date_modified'])?> <?=lang('by')?> <?=$user['last_modified_by']?></td>
					<td class="col-action" nowrap="nowrap">
						<a href="<?=site_url('/users/edit/'.$user['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<?php if($user['id'] != 1):?>
						<a href="<?=site_url('/users/delete/'.$user['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
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