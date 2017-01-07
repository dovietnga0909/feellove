<?php if(empty($news)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('news')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

	<?php if(isset($save_status) && $save_status === FALSE):?>
		<div class="alert alert-danger">
			<?=lang('fail_to_save')?>
		</div>
	<?php endif;?>
	
	<?php if(!empty($uploaded_errors)):?>
		
		<?=$uploaded_errors?>
		
	<?php endif;?>
	
	<form role="form" method="post" enctype="multipart/form-data" class="form-horizontal">

	<div class="form-group">
		<label for="name" class="col-sm-2 control-label"><?=lang('news_field_name')?>: </label>
		<div class="col-sm-6">
			<label id="name" class="control-label"><?=$news['name']?> </label>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-2 control-label"><?=lang('news_field_select_photo')?>: </label>
		<div class="col-sm-6">
			<input type="file" name="photos[]" multiple="multiple" id="photo" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-6">
			<button type="submit" class="btn btn-primary" name="submit_action" value="<?=ACTION_UPLOAD?>">
				<span class="fa fa-upload"></span>
				<?=lang('btn_upload')?>
			</button>
		</div>
	</div>

	<hr>
	
	<?php if(empty($news['photos'])):?>
		<p class="text-info">
			<?=lang('no_photo_uploaded')?>
		</p>
	<?php else:?>
		<p class="text-info">
			<?=lang('assign_photo_to_page')?>:
		</p>
		<div class="row">
			<?php foreach ($news['photos'] as $photo):?>
	
	
			<div class="col-xs-4">
				<div class="thumbnail" style="position: relative;">
					<a href="<?=site_url('/news/delete-photo/'.$news['id'].'/'.$photo['id'])?>/"
						onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="delete_photo_btn"> <span
						class="fa fa-times"></span>
					</a> <img src="<?=get_static_resources("/images/news/".$photo['name'])?>" alt="<?=$photo['name']?>">
					<div>
						<center>
							<?=$photo['width'].' * '.$photo['height'].' px'?>
						</center>
						<a href="<?=get_static_resources("/images/news/".$photo['name'])?>" style="font-size: 12px"><?=get_static_resources("/images/news/".$photo['name'])?></a>
					</div>
					<div class="caption">
	
						<div class="form-group">
							<div class="col-xs-12">
								<select class="form-control" name="status_<?=$photo['id']?>">
									<option value="<?=STATUS_ACTIVE?>"
									<?=set_select('status_'.$photo['id'], STATUS_ACTIVE, STATUS_ACTIVE == $photo['status'])?>>
										<?=lang('active')?>
									</option>
									<option value="<?=STATUS_INACTIVE?>"
									<?=set_select('status_'.$photo['id'], STATUS_INACTIVE, STATUS_INACTIVE == $photo['status'])?>>
										<?=lang('inactive')?>
									</option>
									
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<input class="chk_main" type="checkbox" value="1" name="is_main_photo_<?=$photo['id']?>" <?=set_checkbox('is_main_photo', 1, $photo['is_main_photo'] == 1 ? true:false)?>>
								<?=lang('news_field_is_main_photo')?>
							</div>
						</div>
					</div>
				</div>
			</div>
	
	
			<?php endforeach;?>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<button type="submit" class="btn btn-primary" name="submit_action" value="<?=ACTION_SAVE?>">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('news/photos/'.$news['id'])?>" role="button"><?=lang('btn_cancel')?> </a>
			</div>
		</div>	
		<?php endif;?>
</form>
<script>
	$('.chk_main').click(function() {
		$('.chk_main').not(this).prop('checked', false);  
	});
</script>

<?php endif;?>