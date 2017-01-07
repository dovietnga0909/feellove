<div class="panel panel-default">
	 <!-- Default panel contents -->
  	<div class="panel-heading">
  		<?=lang('list_of_newsletters')?>
  		<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('newsletters/create')?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_newsletter_create_btn')?>
	  	</a>
  	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('newsletters')?></th>
				<th><?=lang('templates')?></th>
				<th><?=lang('status')?></th>
				<th><?=lang('user_created')?></th>
				<th><?=lang('user_last_modified')?></th>
				<th><?=lang('newsletter_log')?></th>
				<th width="10%"><?=lang('function')?></th>
			</tr>
		</thead>
		<tbody>
			
			<?php if(!empty($newsletters)):?>
				<?php foreach ($newsletters as $k => $newsletter):?>
				<tr>
					<td><?=$offset + $k + 1?></td>
					<td><a href="<?=site_url('/newsletters/edit/'.$newsletter['id'])?>" class="show_newsletter" title="<?=$newsletter['name']?>"> <?=$newsletter['name']?></a></td>
					<td><?=get_lang_config('template_type', $newsletter['template_type'])?></td>
					<td>
						<?=get_lang_config('newsletter_status', $newsletter['status'])?>
						<?php if($newsletter['status'] != 0 && isset($newsletter['nr_send_success']) && isset($newsletter['nr_total_email'])){echo '('.$newsletter['nr_send_success'].'/'.$newsletter['nr_total_email'].')';}?>
					</td>
					
					<td><?php if(isset($newsletter['user_created_id'])){ echo lang('by'); echo $newsletter['created_newsletter_by'];}else{echo lang('by'); echo lang('system');}?></td>
					<td><?php if(isset($newsletter['user_modified_id'])){ echo lang('by'); echo $newsletter['last_modified_by'];}else{echo lang('by'); echo lang('system');}?></td>
					
					<td>
						<?php if($newsletter['status'] != 0):?>
						<?php
							$log_email = '';
							if(isset($newsletter['nr_total_email'])){ 
								$log_email	 =	'Total number of email in newsletters <b>'.$newsletter['nr_total_email'].'</b><br>';
							}
							if(isset($newsletter['nr_send_success'])){ 
								$log_email	.=	'Total number of emails sent successfully in newsletters <b>'.$newsletter['nr_send_success'].'</b><br>';
							}
							if(isset($newsletter['nr_send_false'])){ 
								$log_email	.=	'Total number of emails sent error in newsletters <b>'.$newsletter['nr_send_false'].'</b><br>';
							}
						?>
						
						<a href="javascript:void(0)" type="button" class="btn" data-html="true" data-container="body" data-toggle="popover" data-placement="top" data-original-title="Log send email newsletter" data-content="<?=$log_email?>">
						  	<span class="fa fa-file-text"></span>
						</a>
						<?php endif;?>
					</td>
					<td class="col-action" nowrap="nowrap">
						<a href="javascript:void(0)" onclick="review_newsletter(<?=$newsletter['id']?>)" title="<?=lang('ico_reviews')?>">
							<span class="fa fa fa-eye" data-toggle="modal" data-target="#myModal"></span>
						</a>
						<a href="<?=site_url('/newsletters/edit/'.$newsletter['id'])?>" title="<?=lang('ico_edit')?>">
							<span class="fa fa-edit"></span>
						</a>
						<a href="<?=site_url('/newsletters/photos/'.$newsletter['id'])?>" title="<?=lang('ico_photo')?>">
							<span class="fa fa-photo"></span>
						</a>
						<a href="<?=site_url('/newsletters/delete/'.$newsletter['id'])?>" title="<?=lang('delete')?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="10" class="text-center">
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<script>
	
	$(function () {
	 	$('[data-toggle="popover"]').popover();
	})
	
	function review_newsletter(newsletter_id){
		
		$.ajax({
			url: "newsletters/review/",
			type: "POST",
			data: {
				"id": newsletter_id,
			},
			success:function(value){
				$('#myModal').html(value);
			},
			error:function(var1, var2, var3){
				// do nothing
			}
		});
	}
</script>
