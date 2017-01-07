<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'cruises/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('cruises_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_cruise_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="star"><?=lang('cruises_field_star')?>:</label>
			<br>
			<select class="form-control input-sm" name="star">
				<option value=""><?=lang('cruises_select_star')?></option>
				<?php foreach ($cruise_star as $val):?>
				<option value="<?=$val?>" <?=set_select('star', $val, isset($search_criteria['star']) && $search_criteria['star'] == $val)?>><?=$val?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="partner_id"><?=lang('cruises_field_partner')?>:</label>
			<br>
			<select class="form-control input-sm" name="partner_id">
				<option value=""><?=lang('cruises_select_partner')?></option>
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
			<label for="destination_id"><?=lang('cruises_field_cruise_type')?>:</label>
			<br>
			<select class="form-control input-sm" name="cruise_type">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($cruise_type as $key => $value):?>
				<option value="<?=$key?>" <?=set_select('cruise_type', $key, isset($search_criteria['cruise_type']) && $search_criteria['cruise_type'] == $key)?>><?=lang($value)?></option>
				<?php endforeach;?>
				
			</optgroup>
			
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