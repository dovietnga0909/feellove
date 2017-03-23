<div class="well well-sm">
	<form role="form" name="sfrm" method="post" action="<?=site_url().'destinations/'?>">
		<div class="row">
			<div class="col-xs-2">
				<label for="search_name"><?=lang('destinations_field_name')?></label>	
				<input type="text" placeholder="<?=lang('list_destination_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
			</div>
			<div class="col-xs-2">
				<label for="type"><?=lang('destinations_field_type')?>:</label>
				<select class="form-control input-sm" name="type">
					<option value=""><?=lang('destinations_empty_select')?></option>
					<?php foreach ($destination_types as $type):?>
						<?php if(is_array($type['value'])):?>
							<optgroup label="<?=lang($type['label'])?>"><?=lang($type['label'])?>
							<?php foreach ($type['value'] as $key => $value):?>	
								<option value="<?=$key?>" <?=set_select('type', $key, isset($search_criteria['type']) && $search_criteria['type'] == $key)?>><?=lang($value)?></option>
							<?php endforeach;?>
							</optgroup>
						<?php else:?>
							<option value="<?=$type['value']?>" <?=set_select('type', $type['value'], isset($search_criteria['type']) && $search_criteria['type'] == $type['value'])?>>
							<?=lang($type['label'])?>
							</option>
						<?php endif;?>
					<?php endforeach;?>
				</select>
			</div>
			<div class="col-xs-2">
				<label for="parent_destination"><?=lang('destinations_field_parent_destination')?>:</label>
				<select class="form-control input-sm" name="parent_id">
					<option value=""><?=lang('destinations_empty_select')?></option>
					<?php foreach ($parent_destinations as $des):?>
					<option value="<?=$des['id']?>" <?=set_select('parent_id', $des['id'], isset($search_criteria['parent_id']) && $search_criteria['parent_id'] == $des['id'])?>><?=$des['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="col-xs-2">
				<div class="checkbox" style="margin-top:30px">
					<label>
						<input type="checkbox" name="is_top_hotel" 
						value="1" <?=set_checkbox('is_top_hotel', 1, isset($search_criteria['is_top_hotel']) && $search_criteria['is_top_hotel'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_hotel_top')?>
					</label>
				</div>
			</div>
			
			<div class="col-xs-2">
				<div class="checkbox" style="margin-top:30px">
					<label>
						<input type="checkbox" name="is_flight_destination" 
							value="1" <?=set_checkbox('is_flight_destination', 1, isset($search_criteria['is_flight_destination']) && $search_criteria['is_flight_destination'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_flight_destination')?>
					</label>
				</div>
			</div>
			
			<div class="col-xs-2">
				<div class="checkbox" style="margin-top:30px">
					<label>
						<input type="checkbox" name="is_flight_group" 
							value="1" <?=set_checkbox('is_flight_group', 1, isset($search_criteria['is_flight_group']) && $search_criteria['is_flight_group'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_flight_group')?>
					</label>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-2">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="is_tour_departure" 
							value="1" <?=set_checkbox('is_tour_departure', 1, isset($search_criteria['is_tour_departure']) && $search_criteria['is_tour_departure'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_tour_departure_destination')?>
					</label>
				</div>
			</div>
			
			<div class="col-xs-2">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="is_tour_des_group" 
							value="1" <?=set_checkbox('is_tour_des_group', 1, isset($search_criteria['is_tour_des_group']) && $search_criteria['is_tour_des_group'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_tour_destination_group')?>
					</label>
				</div>
			</div>

			<div class="col-xs-2">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="is_tour_highlight" 
							value="1" <?=set_checkbox('is_tour_highlight', 1, isset($search_criteria['is_tour_highlight']) && $search_criteria['is_tour_highlight'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_tour_highlight_destination')?>
					</label>
				</div>
			</div>
			
			<div class="col-xs-2">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="is_include_all_children" 
							value="1" <?=set_checkbox('is_include_all_children', 1, isset($search_criteria['is_include_all_children']) && $search_criteria['is_include_all_children'] == 1 ? TRUE : FALSE)?>>
						<?=lang('destinations_field_is_tour_includes_all_children_destination')?>
					</label>
				</div>
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-xs-2 col-xs-offset-10">
				<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>" style="margin-right:20px">
					<span class="fa fa-search"></span>
					<?=lang('btn_search')?>
				</button>
				
				<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
			  		<span class="fa fa-refresh"></span>
			  		<?=lang('btn_reset')?>
			  	</button>
			</div>
		</div>
	</form>
</div>