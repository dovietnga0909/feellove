<style>
	.tt-dropdown-menu{width:300px;}
</style>
<div class="well well-sm">
	<form role="form" name="sfrm" method="post" action="<?=site_url().'tours/'?>">
		<div class="row">
		
			<div class="col-xs-3">
				<label for="search_name"><?=lang('tours_field_name')?></label>
				<input type="text" placeholder="<?=lang('list_tour_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
			</div>
			
			<div class="col-xs-3">
				<label for="status"><?=lang('field_status')?>:</label>
				<select class="form-control input-sm" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, isset($search_criteria['status']) && $search_criteria['status'] == $key)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="col-xs-3">
				<label for="partner_id"><?=lang('tours_field_partner')?>:</label>
			
				<select class="form-control input-sm" name="partner_id">
					<option value=""><?=lang('tours_select_partner')?></option>
					<?php foreach ($partners as $partner):?>
					<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], isset($search_criteria['partner_id']) && $search_criteria['partner_id'] == $partner['id'])?>><?=$partner['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="col-xs-3">
				<label for="cruise_id"><?=lang('tours_field_cruise')?>:</label>
				<select class="form-control input-sm" name="cruise_id">
					<option value=""><?=lang('tours_empty_select')?></option>
					<?php foreach ($cruises as $cruise):?>
					<option value="<?=$cruise['id']?>" <?=set_select('cruise_id', $cruise['id'], isset($search_criteria['cruise_id']) && $search_criteria['cruise_id'] == $cruise['id'])?>><?=$cruise['name']?></option>
					<?php endforeach;?>
				</select>
			</div>

		</div>
		
		<div class="row" style="margin-top:15px">
			
			<div class="col-xs-3">
				<label for="cruise_id"><?=lang('tours_field_destination')?>:</label>
				<input type="text" placeholder="Input <?=lang('tours_field_destination')?>..." class="form-control input-sm" name="des_autocomplete" id="des_autocomplete" value="<?=set_value('des_autocomplete', isset($search_criteria['des_autocomplete']) ? $search_criteria['des_autocomplete'] : '')?>">
				<input type="hidden" id="des_id" name="des_id" value="<?=set_value('des_id', isset($search_criteria['des_id']) ? $search_criteria['des_id'] : '')?>">
			</div>
			
			<div class="col-xs-3">
				<label for="is_outbound"><?=lang('domistic_outbound')?>:</label>
				<select class="form-control input-sm" name="is_outbound">
					<option value=""><?=lang('tours_empty_select')?></option>
					<?php foreach ($domistic_outbound as $key=>$value):?>
						<option value="<?=$key?>" <?=set_select('is_outbound', $key, isset($search_criteria['is_outbound']) && $search_criteria['is_outbound'] == $key)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
			</div>
		
			<div class="col-xs-3">
				<label for="cruise_id"><?=lang('tour_category_title')?>:</label>
				<select class="form-control input-sm" name="category_id">
					<option value=""><?=lang('tours_empty_select')?></option>
					<?php foreach ($tour_categories as $cat):?>
					<option value="<?=$cat['id']?>" <?=set_select('category_id', $cat['id'], isset($search_criteria['category_id']) && $search_criteria['category_id'] == $cat['id'])?>><?=$cat['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			<div class="col-xs-3">
				<label for="cruise_id"><?=lang('tours_field_departure_type')?>:</label>
				<select class="form-control input-sm" name="departure_type">
					<option value=""><?=lang('tours_empty_select')?></option>
					<?php foreach ($departure_types as $key=>$value):?>
						<option value="<?=$key?>" <?=set_select('departure_type', $key, isset($search_criteria['departure_type']) && $search_criteria['departure_type'] == $key)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
			</div>
			
			
			
			
		
		</div>
		
		<div class="row" style="margin-top:15px">
		
		    <div class="col-xs-3">
                <div class="checkbox" style="margin-top: 0">
					<label><input type="checkbox" name="is_cruise_tour" value="1" <?=set_checkbox('is_cruise_tour', 1, isset($search_criteria['is_cruise_tour']) && $search_criteria['is_cruise_tour'] == 1)?>><?=lang('is_halong_cruise_tour')?></label>
				</div>
		    </div>
			
			<div class="col-xs-9 text-right">
			
				<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>" style="margin-right:15px">
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

<script type="text/javascript">

	function set_des_autocomplete(){
		
		// for partner aucomplete
		var customers = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: 'search-destinations/%QUERY/'
		});
			 
		customers.initialize();
		
		$('#des_autocomplete').typeahead(
			{
				minLength: 2,
				highlight : true,
				hint : true 
			}, 
			{
				name: 'destinations',
				displayKey: 'name',
				source: customers.ttAdapter(),
				templates: {
					 empty: [
					 '<div class="error">',
					 	'No Destination Found!',
					 '</div>'
					 ].join('\n'),
					 suggestion: Handlebars.compile('<p><strong>{{name}}</strong> - {{parent_name}}</p>')
				 }
			}
		).on("typeahead:selected", function($e, datum){
			$('#des_id').val(datum['id']);
			
			// store selected value
			$('#des_autocomplete').data("selected_value", $.trim($('#des_autocomplete').val()));
		});
		
		$("#des_autocomplete").change(function() {
			if( $.trim($("#des_autocomplete" ).val()) != $('#des_autocomplete').data("selected_value") ) {
				$('#des_id').val('');
			}
		})
		
	}
	
	set_des_autocomplete();

</script>