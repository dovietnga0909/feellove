<?php if(empty($destination)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('destinations')?>" role="button">
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

	<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="col-xs-10">
				<div class="form-group">
					<label for="name" class="col-xs-3 control-label"><?=lang('destinations_field_name')?>: <?=mark_required()?></label>
					<div class="col-xs-6">
						<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $destination['name'])?>">
						<?=form_error('name')?>
					</div>
				</div>
				<div class="form-group">
					<label for="marketing_title" class="col-xs-3 control-label"><?=lang('marketing_title')?>:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="marketing_title" name="marketing_title" value="<?=set_value('marketing_title', $destination['marketing_title'])?>">
						<?=form_error('marketing_title')?>
					</div>
				</div>
				<?php if(is_admin()):?>
        		<div class="form-group">
        			<label for="keywords" class="col-xs-3 control-label"><?=lang('destinations_field_keywords')?>: <?=mark_required()?></label>
        			<div class="col-xs-8">
        			    <textarea class="form-control" rows="1" name="keywords"><?=set_value('keywords', $destination['keywords'])?></textarea>
        				<?=form_error('keywords')?>
        			</div>
        		</div>
        		<?php endif;?>
				<div class="form-group">
					<label for="type" class="col-xs-3 control-label"><?=lang('destinations_field_type')?>: <?=mark_required()?></label>
					<div class="col-xs-4">
						<select class="form-control" name="type">
							<option value=""><?=lang('destinations_empty_select')?></option>
							<?php foreach ($destination_types as $type):?>
								<?php if(is_array($type['value'])):?>
									<optgroup label="<?=lang($type['label'])?>"><?=lang($type['label'])?>
									<?php foreach ($type['value'] as $key => $value):?>	
										<option value="<?=$key?>" <?=set_select('type', $key, $destination['type'] == $key ? TRUE : FALSE)?>><?=lang($value)?></option>
									<?php endforeach;?>
									</optgroup>
								<?php else:?>
									<option value="<?=$type['value']?>" <?=set_select('type', $type['value'], $destination['type'] == $type['value'] ? TRUE : FALSE)?>>
									<?=lang($type['label'])?>
									</option>
								<?php endif;?>
							<?php endforeach;?>
						</select>
						<?=form_error('type')?>
					</div>
				</div>
				<div class="form-group">
					<label for="is_top_hotel" class="col-xs-3 text-right">
						<?=lang('destinations_field_is_hotel_top')?>
					</label>
					<div class="col-xs-6">
						<input type="checkbox" name="is_top_hotel" 
							value="1" <?=set_checkbox('is_top_hotel', 1, 1==$destination['is_top_hotel'] ? TRUE : FALSE)?>>
					</div>
				</div>
				<div class="form-group">
					<label for="parent_id" class="col-xs-3 control-label"><?=lang('destinations_field_parent_destination')?>:</label>
					<div class="col-xs-6">
						<select class="form-control" name="parent_id">
							<option value=""><?=lang('destinations_empty_select')?></option>
							<?php foreach ($parent_destinations as $des):?>
							<option value="<?=$des['id']?>" <?=set_select('parent_destination', $des['id'], $des['id'] == $destination['parent_id'] ? TRUE : FALSE)?>>
							<?=$des['name']?>
							</option>
							<?php endforeach;?>
						</select>
						<?=form_error('parent_id')?>
					</div>
				</div>
				<div class="form-group">
					<label for="description_short" class="col-xs-3 control-label"><?=lang('description_short')?>:</label>
					<div class="col-xs-9">
						<textarea class="form-control rich-text" rows="6" name="description_short"><?=set_value('description_short', $destination['description_short'])?></textarea>
						<?=form_error('description_short')?>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-xs-3 control-label"><?=lang('destinations_full')?>:</label>
					<div class="col-xs-9">
						<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description', $destination['description'])?></textarea>
						<?=form_error('description')?>
					</div>
				</div>
				<div class="form-group">
				    <div class="col-xs-offset-3 col-xs-6">
				    	<button type="submit" class="btn btn-primary">
							<span class="fa fa-download"></span>
							<?=lang('btn_save')?>
						</button>
						<a class="btn btn-default mg-left-10" href="<?=site_url('destinations')?>" role="button"><?=lang('btn_cancel')?></a>
				    </div>
				</div>
			</div>
			<div class="col-xs-2">
				<div class="pull-right">
					<?php if(!empty($destination['picture'])):?>
					<a href="<?=site_url("destinations/photos/".$destination["id"]) ?>" ><img width="150" src="<?=get_static_resources('/images/destinations/uploads/'.$destination['picture'])?>"></a>
					<?php endif;?>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		init_text_editor();
	</script>
<?php endif;?>