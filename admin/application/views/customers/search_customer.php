<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'customers/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('customers_field_search_text')?>:</label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_customer_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="destination_id"><?=lang('customers_field_destination')?>:</label>
			<br>
			<select class="form-control input-sm" name="destination_id">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($customer_destinations as $destination):?>
				<option value="<?=$destination['id']?>" <?=set_select('destination_id', $destination['id'], isset($search_criteria['destination_id']) && $search_criteria['destination_id'] == $destination['id'])?>><?=$destination['name']?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="budget"><?=lang('customers_field_budget')?>:</label>
			<br>
			<select class="form-control input-sm" name="budget">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($customer_budget as $k => $val):?>
				<option value="<?=$k?>" <?=set_select('budget', $k, isset($search_criteria['budget']) && $search_criteria['budget'] == $k)?>><?=lang($val)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="travel_type"><?=lang('customers_field_travel_types')?>:</label>
			<br>
			<select class="form-control input-sm" name="travel_type">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($travel_types as $k => $val):?>
				<option value="<?=$k?>" <?=set_select('travel_type', $k, isset($search_criteria['travel_type']) && $search_criteria['travel_type'] == $k)?>><?=lang($val)?></option>
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