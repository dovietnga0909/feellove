<div class="panel panel-default">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<?=lang('list_of_tours')?>
		<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/create?cruise_id='.$cruise['id'])?>" role="button"> <span
			class="fa fa-arrow-circle-right"></span> <?=lang('create_tour_create_btn')?>
		</a>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?=lang('tours_field_code')?></th>
				<th><?=lang('tours_field_name')?></th>
				<th><?=lang('tours_field_partner')?></th>
				<th class="text-center"><?=lang('field_status')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($tours)):?>
			<?php foreach ($tours as $k => $tour):?>
			<tr>
				<td>
					<?php if(!empty($tour['code'])):?>
						<a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$tour['code']?>
						</a>
					<?php endif;?>
				</td>
				<td><a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>"> <?=$tour['name']?></a></td>
				<td><?=$tour['partner_name']?></td>
				<td class="text-center"><?php echo $tour['status'] == STATUS_ACTIVE ? lang('active') : lang('inactive')?></td>
				<td><?=get_last_update($tour['date_modified'])?> <?=lang('by')?> <?=$tour['last_modified_by']?></td>
				<td class="col-action" nowrap="nowrap">
					<a href="<?=site_url('/tours/profiles/'.$tour['id'])?>" title="<?=lang('ico_edit')?>">
						<span class="fa fa-edit"></span>
					</a> 
					<a href="<?=site_url('/tours/photos/'.$tour['id'])?>" title="<?=lang('ico_photo')?>">
						<span class="fa fa-photo"></span>
					</a> 
					<a href="<?=site_url('/tours/accommodations/'.$tour['id'])?>">
						<span class="fa fa-home"></span>
					</a> 
					<a href="<?=site_url('/tours/itinerary/'.$tour['id'])?>">
						<span class="fa fa-file-text-o"></span>
					</a> 
					<a href="<?=site_url('/tours/delete/'.$tour['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
						<span class="fa fa-times"></span>
					</a>
				</td>
			</tr>
			<?php endforeach;?>
			<?php else:?>
			<tr>
				<td colspan="8" class="text-center">
					<div class="alert alert-warning">
						<?=lang('no_tour_created')?>
					</div>
				</td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
