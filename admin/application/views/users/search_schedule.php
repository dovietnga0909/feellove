<div class="well well-sm">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'users/schedules/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('users_field_username')?></label>
	  		<br>
			
			<select class="form-control input-sm" id="user_id" name="user_id">
				<option value=""><?=lang('all')?></option>
				
				<?php foreach ($hotline_users as $user):?>
				
					<option value="<?=$user['id']?>" <?=set_select('user_id', $user['id'], isset($search_criteria['user_id']) && $search_criteria['user_id'] == $user['id'])?>><?=$user['username']?></option>
				
				<?php endforeach;?>
				
			</select>
			
		</div>
		
		
		<div class="form-group mg-left-10">
		    <label for="status"><?=lang('field_status')?></label>
		    <br>
			<select class="form-control input-sm" id="status" name="status">
				<option value=""><?=lang('all')?></option>
				<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == STATUS_ACTIVE)?>><?=lang('active')?></option>
			  	<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == ''.STATUS_INACTIVE)?>><?=lang('inactive')?></option>
			</select>
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