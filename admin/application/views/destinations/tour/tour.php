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
			<label for="is_tour_highlight_destination" class="col-xs-12">
				<div class="col-xs-3 text-right">
					<?=lang('destinations_field_is_tour_highlight_destination')?>
				</div>
				<div class="col-xs-6">
					<input type="checkbox" name="is_tour_highlight_destination" id="is_tour_highlight_destination"
						value="1" <?=set_checkbox('is_tour_highlight_destination', 1, 1==$destination['is_tour_highlight_destination'] ? TRUE : FALSE)?>>
				</div>
			</label>
		</div>
		
		
		<div class="form-group">
			<label for="is_tour_destination_group" class="col-xs-12">
				<div class="col-xs-3 text-right">
					<?=lang('destinations_field_is_tour_destination_group')?>
				</div>
				<div class="col-xs-6">
					<input type="checkbox" name="is_tour_destination_group" id="is_tour_destination_group"
					value="1" <?=set_checkbox('is_tour_destination_group', 1, 1==$destination['is_tour_destination_group'] ? TRUE : FALSE)?>>
				</div>
			</label>
		</div>
		<div class="form-group">
			<label for="is_tour_includes_all_children_destination" class="col-xs-12">
				<div class="col-xs-3 text-right">
					<?=lang('destinations_field_is_tour_includes_all_children_destination')?>
				</div>
				<div class="col-xs-6">
					<input type="checkbox" name="is_tour_includes_all_children_destination" id="is_tour_includes_all_children_destination" 
					value="1" <?=set_checkbox('is_tour_includes_all_children_destination', 1, 1==$destination['is_tour_includes_all_children_destination'] ? TRUE : FALSE)?>>
				</div>
			</label>
		</div>
		<div class="form-group">
			<label for="is_tour_departure_destination" class="col-xs-12">
				<div class="col-xs-3 text-right">
					<?=lang('destinations_field_is_tour_departure_destination')?>
				</div>
				<div class="col-xs-6">
					<input type="checkbox" name="is_tour_departure_destination" id="is_tour_departure_destination" 
					value="1" <?=set_checkbox('is_tour_departure_destination', 1, 1==$destination['is_tour_departure_destination'] ? TRUE : FALSE)?>>
				</div>
			</label>
		</div>
		<div class="form-group">
			<label for="travel_tip" class="col-xs-3 control-label"><?=lang('travel_tip')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="8" name="travel_tip"><?=set_value('travel_tip', $destination['travel_tip'])?></textarea>
				<?=form_error('travel_tip')?>
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
<?php endif;?>
