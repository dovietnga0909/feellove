<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_roles')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('roles/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_role_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('roles_field_name')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($roles)):?>
				<?php foreach ($roles as $k => $role):?>
				<tr>
					<td><?=$offset + $k + 1?></td>
					<td>
						<a href="<?=site_url('/roles/edit/'.$role['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$role['name']?>
						</a>
					</td>
					<td><?=get_last_update($role['date_modified'])?> <?=lang('by')?> <?=$role['last_modified_by']?></td>
					<td class="text-center col-action">
						<a href="<?=site_url('/roles/edit/'.$role['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/roles/delete/'.$role['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_role_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_role_found')?></div>
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