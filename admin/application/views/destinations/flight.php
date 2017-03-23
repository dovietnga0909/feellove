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

	<form class="form-horizontal" role="form" method="post">
		<div class="form-group">
			<label class="col-xs-12" style="padding: 0">
			    <div class="col-xs-3 text-right">
			        <?=lang('destinations_field_is_flight_destination')?>
			    </div>
				
				<div class="col-xs-6">
    				<input type="checkbox" name="is_flight_destination" 
    					value="1" <?=set_checkbox('is_flight_destination', 1, 1==$destination['is_flight_destination'] ? TRUE : FALSE)?>>
    			</div>
			</label>
		</div>
		<div class="form-group">
			<label for="name" class="col-xs-3 control-label"><?=lang('destinations_field_code')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" name="destination_code" value="<?=set_value('destination_code', $destination['destination_code'])?>">
				<?=form_error('destination_code')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12" style="padding: 0">
			     <div class="col-xs-3 text-right">
			         <?=lang('destinations_field_is_flight_group')?>
			     </div>
    			 <div class="col-xs-6">
    				<input type="checkbox" name="is_flight_group" value="1" <?=set_checkbox('is_flight_group', 1, 1==$destination['is_flight_group'] ? TRUE : FALSE)?>>
    			 </div>	
			</label>
		</div>
		<div class="form-group">
			<label for="description" class="col-xs-3 control-label"><?=lang('destinations_field_flight_tips')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control rich-text" rows="10" name="flight_tips"><?=set_value('flight_tips', $destination['flight_tips'])?></textarea>
				<?=form_error('flight_tips')?>
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
	</form>
	<script type="text/javascript">
		init_text_editor();
	</script>
<?php endif;?>
