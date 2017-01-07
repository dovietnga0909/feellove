<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'facilities/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('facilities_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_facility_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="type_id"><?=lang('facilities_field_type')?>:</label>
			<br>
			<select class="form-control input-sm" name="type_id" id="type_id">
				<option value=""><?=lang('facilities_select_type')?></option>
				<?php foreach ($facility_types as $k => $type):?>
				<option value="<?=$k?>" <?=set_select('type_id', $k, isset($search_criteria['type_id']) && $search_criteria['type_id'] == $k)?>><?=lang($type)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="group_id"><?=lang('facilities_field_group')?>:</label>
			<br>
			<select class="form-control input-sm" name="group_id" id="group_id">
				<option value=""><?=lang('facilities_select_group')?></option>
				<?php foreach ($facility_groups as $k => $group):?>
				<option value="<?=$k?>" <?=set_select('group_id', $k, isset($search_criteria['group_id']) && $search_criteria['group_id'] == $k)?>><?=lang($group)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="group_id"><?=lang('facilities_field_is_important')?>:</label>
			<br>
			<input type="checkbox" id="is_important" name="is_important" value="1" <?=set_checkbox('is_important', 1, isset($search_criteria['is_important']) && $search_criteria['is_important'] == 1)?>>
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