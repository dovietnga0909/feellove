<?php if(empty($tour)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('tours')?>" role="button">
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
		<div class="row">
			<div class="col-xs-8">
				<div class="form-group">
					<label for="name" class="col-xs-3 control-label"><?=lang('tours_field_name')?>: <?=mark_required()?></label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $tour['name'])?>">
						<?=form_error('name')?>
					</div>
				</div>
				<div class="form-group">
					<label for="short_name" class="col-xs-3 control-label"><?=lang('tours_field_short_name')?>: </label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="short_name" name="short_name" value="<?=set_value('short_name', $tour['short_name'])?>">
						<?=form_error('short_name')?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="code" class="col-xs-3 control-label"><?=lang('tours_field_code')?>: </label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="code" name="code" value="<?=set_value('code', $tour['code'])?>">
						<?=form_error('code')?>
					</div>
				</div>

				<div class="form-group">
					<label for="departure_type" class="col-xs-3 control-label"><?=lang('tours_field_departure_type')?>: <?=mark_required()?></label>
					<div class="col-xs-8">
					<select class="form-control" name="departure_type" id="departure_type">
						<option value=""><?=lang('tours_empty_select')?></option>
						<?php foreach ($departure_type as $key => $value):?>
						<option value="<?=$key?>" <?=set_select('departure_type', $key, $key==$tour['departure_type'] ? TRUE : FALSE)?>><?=lang($value)?></option>
						<?php endforeach;?>
					</select>
					<?=form_error('departure_type')?>
					</div>
				</div>
				<div class="form-group">
					<label for="cruise_id" class="col-xs-3 control-label"><?=lang('tours_field_cruise')?>: </label>
					<div class="col-xs-8">
					<select class="form-control" name="cruise_id">
						<option value=""><?=lang('tours_empty_select')?></option>
						<?php foreach ($cruises as $cruise):?>
						<option value="<?=$cruise['id']?>" <?=set_select('cruise_id', $cruise['id'], $cruise['id']==$tour['cruise_id'] ? TRUE : FALSE)?>><?=$cruise['name']?></option>
						<?php endforeach;?>
					</select>
					<?=form_error('cruise_id')?>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="pull-right">
					<?php if(!empty($tour['picture'])):?>
					<img width="175" src="<?=get_static_resources('/images/tours/uploads/'.$tour['picture'])?>">
					<?php endif;?>
					<div class="text-center">
						<a href="<?=site_url('tours/photos/'.$tour['id'])?>"><?=lang('update_tour_photo')?></a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="routes" class="col-xs-2 control-label"><?=lang('tours_field_route')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
			    
			    <input type="text" class="form-control" id="search_destination" name="search_destination" placeholder="<?=lang('suggestion_destination_placeholder')?>">
			    
    			<select class="form-control" name="routes" id="routes" multiple="multiple" style="height:245px;">
    				<?php foreach ($tour_destinations as $value):?>
    				<optgroup label="<?=$value['name']?>">
    					<option value="<?=$value['id']?>"><?=$value['name']?></option>
    					<?php foreach ($value['destinations'] as $des):?>
    					<option value="<?=$des['id']?>"><?=$des['name']?></option>
    					<?php endforeach;?>
    				</optgroup>
    				<?php endforeach;?>
    			</select>
    			<?=form_error('route_ids')?>
			</div>
			<div class="col-xs-1" style="margin-top: 70px">
				<button type="button" class="btn btn-primary" onclick="add_tour_route()"><?=lang('btn_add')?></button>
			</div>
			<div class="col-xs-5">
				<input type="hidden" name="route_ids" id="route_ids" value="<?=set_value('route_ids', $tour['route_ids'])?>">
				<input type="hidden" name="route_hidden_ids" id="route_hidden_ids" value="<?=set_value('route_hidden_ids', $tour['route_hidden_ids'])?>">
				<input type="hidden" name="land_tour_ids" id="land_tour_ids" value="<?=set_value('land_tour_ids', $tour['land_tour_ids'])?>">
				<div style="max-height: 260px; overflow: auto; border: 1px solid #ccc">
				<table class="table">
					<tr>
						<th width="5%">#</th>
						<th><?=lang('destination_name')?></th>
						<th><?=lang('is_hidden')?></th>
						<th><?=lang('is_land_tour')?></th>
						<th class="text-center"><?=lang('field_action')?></th>
					</tr>
					<tbody id="tour_routes">
						
					    <?php if(!empty($tour['tour_destinations'])):?>
					    
					         <?php foreach ($tour['tour_destinations'] as $k => $des):?>
					         <tr id="row_<?=$k+1?>">
    					         <td nowrap="nowrap">
    					              <span class="glyphicon glyphicon-arrow-up btn_up_des" style="cursor: pointer;"></span><span class="glyphicon glyphicon-arrow-down btn_down_des" style="cursor: pointer;"></span>
    					         </td>
    					         <td><?=$des['name']?></td>
    					         <td class="text-center">
    					              <input type="checkbox" class="ck_hidden" name="ck_hidden_<?=$des['destination_id']?>" id="ck_hidden_<?=$des['destination_id']?>" 
    					              value="<?=$des['destination_id']?>" 
    					              <?=set_checkbox('ck_hidden_'.$des['destination_id'], $des['destination_id'], $des['hidden'] == 1 ? true:false)?>>
    					         </td>
    					         <td class="text-center">
    					              <input type="checkbox" class="ck_land_tour" id="ck_land_tour_<?=$des['destination_id']?>" 
    					              value="<?=$des['destination_id']?>" 
    					              <?=set_checkbox('ck_land_tour_'.$des['destination_id'], $des['destination_id'], $des['is_land_tour'] == 1 ? true:false)?>>
    					         </td>
    					         <td class="text-center">
    					              <a des_id="<?=$des['destination_id']?>" href="javascript:void(0)"><span class="glyphicon glyphicon-remove rm_des"></span></a>
    					         </td>
					         </tr>
					         <?php endforeach;?>
					    <?php else:?>
					    <tr class="route_empty">
						<td colspan="5" align="center"><?=lang('no_destination_added')?></td>
						</tr>
						<?php endif;?>
						
					</tbody>
				</table>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="duration" class="col-xs-2 control-label"><?=lang('tours_field_duration')?>: <?=mark_required()?></label>
			<div class="col-xs-2">
			<input type="text" class="form-control" id="duration" name="duration" value="<?=set_value('duration', $tour['duration'])?>">
			</div>
			<div class="col-xs-1" style="padding: 0">
			<label class="control-label" style="font-weight: normal;"><?=lang('unit_day')?></label>
			</div>
			
			<div class="col-xs-2">
			<input type="text" class="form-control" id="night" name="night" value="<?=set_value('night', $tour['night'])?>">
			</div>
			<div class="col-xs-1" style="padding: 0">
			<label class="control-label" style="font-weight: normal;"><?=lang('unit_night')?></label>
			</div>
			<?=form_error('duration')?>
			<?=form_error('night')?>
		</div>
		<div class="form-group">
			<label for="star" class="col-xs-2 control-label"><?=lang('field_status')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, $key==$tour['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('status')?>
			</div>
		</div>
		<?php if(is_admin()):?>
		<div class="form-group">
			<label for="partner_id" class="col-xs-2 control-label"><?=lang('tours_field_partner')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<select class="form-control" name="partner_id">
					<option value=""><?=lang('tours_select_partner')?></option>
					<?php foreach ($partners as $partner):?>
					<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], $partner['id']==$tour['partner_id'] ? TRUE : FALSE)?>><?=$partner['name']?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('partner_id')?>
			</div>
		</div>
		<?php endif;?>
		<div class="form-group">
			<label for="description" class="col-xs-2 control-label"><?=lang('tours_field_description')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description', $tour['description'])?></textarea>
				<?=form_error('description')?>
			</div>
		</div>
		<div class="form-group">
			<label for="tour_highlight" class="col-xs-2 control-label"><?=lang('tours_field_tour_highlight')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control" rows="10" name="tour_highlight"><?=set_value('tour_highlight', $tour['tour_highlight'])?></textarea>
				<?=form_error('tour_highlight')?>
			</div>
		</div>
		<div class="form-group">
			<label for="service_includes" class="col-xs-2 control-label"><?=lang('tours_field_service_includes')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control rich-text" rows="10" name="service_includes"><?=set_value('service_includes', $tour['service_includes'])?></textarea>
				<?=form_error('service_includes')?>
			</div>
		</div>
		<div class="form-group">
			<label for="service_excludes" class="col-xs-2 control-label"><?=lang('tours_field_service_excludes')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control rich-text" rows="10" name="service_excludes"><?=set_value('service_excludes', $tour['service_excludes'])?></textarea>
				<?=form_error('service_excludes')?>
			</div>
		</div>
		<div class="form-group">
			<label for="notes" class="col-xs-2 control-label"><?=lang('tours_field_notes')?>:</label>
			<div class="col-xs-10">
				<textarea class="form-control" rows="10" name="notes"><?=set_value('notes', $tour['notes'])?></textarea>
				<?=form_error('notes')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-2 col-xs-8">
		    	<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('tours')?>" role="button"><?=lang('btn_cancel')?></a>
		    </div>
		</div>
	</form>
	
	<script type="text/javascript">
		init_text_editor();
		
		init_tour_routes();
	</script>
<?php endif;?>