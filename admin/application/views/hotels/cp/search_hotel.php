<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'hotels/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('hotels_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_hotel_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="star"><?=lang('hotels_field_star')?>:</label>
			<br>
			<select class="form-control input-sm" name="star">
				<option value=""><?=lang('hotels_select_star')?></option>
				<?php foreach ($hotel_star as $val):?>
				<option value="<?=$val?>" <?=set_select('star', $val, isset($search_criteria['star']) && $search_criteria['star'] == $val)?>><?=$val?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="partner_id"><?=lang('hotels_field_partner')?>:</label>
			<br>
			<select class="form-control input-sm" name="partner_id">
				<option value=""><?=lang('hotels_select_partner')?></option>
				<?php foreach ($partners as $partner):?>
				<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], isset($search_criteria['partner_id']) && $search_criteria['partner_id'] == $partner['id'])?>><?=$partner['name']?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="partner_id"><?=lang('field_status')?>:</label>
			<br>
			<select class="form-control input-sm" name="status">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($status_config as $key => $value):?>
				<option value="<?=$key?>" <?=set_select('status', $key, isset($search_criteria['status']) && $search_criteria['status'] == $key)?>><?=lang($value)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="destination_id"><?=lang('hotels_field_destination')?>:</label>
			<br>
			<select class="form-control input-sm" name="destination_id">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($hotel_destinations as $destination):?>
					
					<optgroup label="<?=$destination['name']?>">
						<option value="<?=$destination['id']?>" <?=set_select('destination_id', $destination['id'], isset($search_criteria['destination_id']) && $search_criteria['destination_id'] == $destination['id'])?>><?=$destination['name']?></option>
						<?php foreach ($destination['children'] as $sub_des):?>
							<option value="<?=$sub_des['id']?>" <?=set_select('destination_id', $sub_des['id'], isset($search_criteria['destination_id']) && $search_criteria['destination_id'] == $sub_des['id'])?>><?=$sub_des['name']?></option>
						<?php endforeach;?>
				
					</optgroup>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label>&nbsp;</label>
		   	<br>
			<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
				<span class="fa fa-search"></span>
				<?=lang('btn_search')?>
			</button>
		</div>
		
		<div class="form-group mg-left-10">
		   	<label>&nbsp;</label>
		   	<br>
		  	<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
		  		<span class="fa fa-refresh"></span>
		  		<?=lang('btn_reset')?>
		  	</button>	  	
		 </div>
	</form>
</div>