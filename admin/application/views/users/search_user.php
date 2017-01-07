<div class="well well-sm">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'users/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('users_field_username')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_user_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="display_on"><?=lang('display_on')?></label>
	  		<br>
			<select name="display_on" class="form-control input-sm">
				<option value=""><?=lang('all')?></option>
				<option value="<?=HOTEL?>" <?=set_select('display_on', HOTEL, isset($search_criteria['display_on']) && $search_criteria['display_on'] == HOTEL)?>><?=lang('hotel')?></option>
				<option value="<?=FLIGHT?>" <?=set_select('display_on', FLIGHT, isset($search_criteria['display_on']) && $search_criteria['display_on'] == FLIGHT)?>><?=lang('flight')?></option>
				<option value="<?=CRUISE?>" <?=set_select('display_on', CRUISE, isset($search_criteria['display_on']) && $search_criteria['display_on'] == CRUISE)?>><?=lang('cruise')?></option>
				<option value="<?=TOUR?>" <?=set_select('display_on', TOUR, isset($search_criteria['display_on']) && $search_criteria['display_on'] == TOUR)?>><?=lang('tour')?></option>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="status"><?=lang('field_status')?></label>
	  		<br>
			<select name="status" class="form-control input-sm">
				<option value=""><?=lang('all')?></option>
				<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE)?>><?=lang('active')?></option>
				<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE)?>><?=lang('inactive')?></option>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="allow_assign_request"><?=lang('allow_assign_request')?></label>
	  		<br>
			<select name="allow_assign_request" class="form-control input-sm">
				<option value=""><?=lang('all')?></option>
				<option value="<?=YES?>" <?=set_select('allow_assign_request', YES)?>><?=lang('yes')?></option>
				<option value="<?=NO?>" <?=set_select('allow_assign_request', NO)?>><?=lang('no')?></option>
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