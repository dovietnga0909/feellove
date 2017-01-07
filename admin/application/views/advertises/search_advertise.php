<div class="well well-sm">
	<form class="form-inline" role="form" method="post">
	  
	  <div class="form-group">
	  	<label for="name"><?=lang('ad_field_ad_name')?></label>
	  	<br>
	    <input type="text" class="form-control input-sm" placeholder="<?=lang('ad_field_name')?>..." id="name" name="name" 
	    	value="<?=set_value('name', !empty($search_criteria['name'])?$search_criteria['name']:'')?>">   
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="data_type"><?=lang('ad_field_data_type')?></label>
	    <br>
		<select class="form-control input-sm" id="data_type" name="data_type">
			<option value=""><?=lang('all')?></option>
			<?php foreach ($data_types as $key=>$value):?>
				<option value="<?=$key?>" <?=set_select('data_type', $key, !empty($search_criteria['data_type']) && $search_criteria['data_type'] == $key)?>><?=$value?></option>
			<?php endforeach;?>
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="display_on"><?=lang('ad_field_display_on')?></label>
	    <br>
		<select class="form-control input-sm" id="display_on" name="display_on">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?=set_select('display_on', '0', isset($search_criteria['display_on']) && $search_criteria['display_on'] == '0')?>><?=lang('blank')?></option>
			<?php foreach ($ad_pages as $key=>$value):?>
				<option value="<?=$key?>" <?=set_select('display_on', $key, !empty($search_criteria['display_on']) && $search_criteria['display_on'] == $key)?>><?=$value?></option>
			<?php endforeach;?>
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="status"><?=lang('ad_field_status')?></label>
	    <br>
		<select class="form-control input-sm" id="status" name="status">
			<option value=""><?=lang('all')?></option>
			<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == STATUS_ACTIVE)?>><?=lang('active')?></option>
		  	<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == ''.STATUS_INACTIVE)?>><?=lang('inactive')?></option>
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