<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'partners/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('partners_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_partner_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="payment_type"><?=lang('partners_field_payment_type')?>:</label>
			<br>
			<select class="form-control input-sm" name="payment_type">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($payment_types as $k => $value):?>
				<option value="<?=$k?>" <?=set_select('payment_type', $k, isset($search_criteria['payment_type']) && $search_criteria['payment_type'] == $k)?>><?=lang($value)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="service_type"><?=lang('partner_fields_service_types')?>:</label>
			<br>
			<select class="form-control input-sm" name="service_type">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($partner_types as $k => $config):?>
				<option value="<?=$k?>" <?=set_select('service_type', $k, isset($search_criteria['service_type']) && $search_criteria['service_type'] == $k)?>><?=lang($config)?></option>
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
