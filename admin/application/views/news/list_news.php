<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_news')?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('news/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_news_create_btn')?>
	  	</a>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<th><?=lang('news_field_name')?></th>
				<th><?=lang('news_field_type')?></th>
				<th><?=lang('news_field_start_date')?></th>
				<th><?=lang('news_field_end_date')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($lst_news)):?>
				<?php foreach ($lst_news as $k => $news):?>
				<tr>
					<td class="col-action" nowrap="nowrap">
						<?=$offset + $k + 1?>
						<?=get_order_arrow($news, $max_pos, $min_pos, MODULE_NEWS)?>
					</td>
					<td>
						<a href="<?=site_url('/news/edit/'.$news['id'])?>" title="<?=lang('ico_edit')?>">
						<?=$news['name']?>
						</a>
					</td>
					<td><?=$news['type_name']?></td>
					<td><?=date(DATE_FORMAT, strtotime($news['start_date']));?></td>
					<td><?=date(DATE_FORMAT, strtotime($news['end_date']));?></td>
					
					<td class="text-center"><?php echo $news['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
					
					<td><?=get_last_update($news['date_modified'])?> <?=lang('by')?> <?=$news['last_modified_by']?></td>
					
					<?php $privilege = get_right(DATA_NEWS, $news['user_created_id'])?>
					
					<td class="text-center col-action">
						<a href="<?=site_url('/news/edit/'.$news['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/news/photos/'.$news['id'])?>" title="<?=lang('ico_photo')?>">
							<span class="fa fa-photo"></span>
						</a>
						<?php if($privilege == FULL_PRIVILEGE || is_marketing_team()):?>
						<a href="<?=site_url('/news/delete/'.$news['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="8" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_news_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_news_found')?></div>
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