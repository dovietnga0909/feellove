<div class="panel panel-default">
	 <!-- Default panel contents -->
	  <div class="panel-heading">
	  	<?=lang('list_of_reviews')?>
	  	<?php if( isset($hotel) ):?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('reviews/create_review?hotel_id='.$hotel['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_review_create_btn')?>
	  	</a>
	  	<?php elseif( isset($tour) ):?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('reviews/create_review?tour_id='.$tour['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_review_create_btn')?>
	  	</a>
	  	<?php elseif( isset($cruise) ):?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('reviews/create_review?cruise_id='.$cruise['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_review_create_btn')?>
	  	</a>
	  	<?php endif;?>
	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center" width="5%">#</th>
				<?php if( isset($cruise) ):?>
				<th width="40%"><?=lang('reviews_field_for')?></th>
				<?php endif;?>
				<th nowrap="nowrap"><?=lang('reviews_field_cus_name')?></th>
				
				<th class="text-center" nowrap="nowrap"><?=lang('reviews_field_score')?></th>
				<th class="text-center"><?=lang('reviews_field_date')?></th>
				<th><?=lang('field_last_modified')?></th>
				<th class="text-center" width="10%"><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($reviews)):?>
				<?php foreach ($reviews as $k => $review):?>
				<tr>
					<td class="v-middle col-action" align="center">
						<?=$offset + $k + 1?>
					</td>
					<?php if( isset($cruise) ):?>
					<td class="v-middle">
						<a href="<?=site_url('/reviews/edit_review/'.$review['id'])?>">
						<?php if(!empty($review['hotel_id'])):?>
							<?=$review['review_for_hotel']?>
						<?php else:?>
							<?=$review['review_for_tour']?>
						<?php endif;?>
						</a>
					</td>
					<?php endif;?>
					<td class="v-middle" align="left">
						<?=$review['customer_name']?>
					</td>
					<td class="v-middle" align="center">
						<?=$review['total_score']?>
					</td>
					<td class="v-middle" align="center"><?=date(DATE_FORMAT, strtotime($review['review_date']))?></td>
					<td><?=get_last_update($review['date_modified'], $review['last_modified_by'])?></td>
					<td class="col-action v-middle" nowrap="nowrap">
						<a href="<?=site_url('/reviews/edit_review/'.$review['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/reviews/delete_review/'.$review['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="8" class="error text-center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_review_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_review_found')?></div>
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