<div class="cb-adv-search row">
	<div class="col-xs-5">
		<div class="form-group">
			<input type="text" class="form-control input-sm" name="name_advanced" value="<?=set_value('name_advanced', isset($search_criteria['name'])? $search_criteria['name']: '')?>">
			
		</div>
		&nbsp;
		<select name="sale_advanced" style="width: 70px">
			<option value=""><?=lang('all')?></option>				
			<?php foreach ($sales as $value) :?>
																			
				<option value="<?=$value['id']?>" <?=set_select('sale_advanced', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['username']?></option>
			
			<?php endforeach ;?>				
		</select>
		&nbsp;
		
		Approve:
		<select name="approve_status_advanced" style="width: 70px" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>						
			<?php foreach ($approve_status as $key=>$value) :?>
																			
				<option value="<?=$key?>" <?=set_select('approve_status_advanced', $key,isset($search_criteria['approve_status']) &&  strval($search_criteria['approve_status']) == strval($key)? true: false)?>><?=lang($value)?></option>
				
			<?php endforeach ;?>			
		</select>
		&nbsp;
		
		<?=lang('partner')?>:
		<select name="partner" style="width: 70px" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['partner_id']) &&  $search_criteria['partner_id'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>
			<?php 
				$results = get_option_group_partner($partners);
				$partner_types = $results['partner_types'];
				$partner_ops = $results['partners'];
			?>
			<?php foreach ($partner_types as $key => $partner_t):?>
				<optgroup label="<?=lang($partner_t)?>">
					<?php foreach ($partner_ops[lang($partner_t)] as $value):?>
						<option value="<?=$value['id']?>" <?=set_select('partner', $value['id'],isset($search_criteria['partner_id']) &&  $search_criteria['partner_id'] == $value['id']? true: false)?>><?=$value['short_name']?></option>
					<?php endforeach ;?>
				</optgroup>
			<?php endforeach ;?>
		</select>
	
	</div>
	
	
	<div class="col-xs-2">
	
		<label for="start_date"><?=lang('s-date')?>:</label>
		<div class="form-group">
			<input tabindex="1" type="text" class="form-control input-sm" name="start_date" id="start_date" value="<?=set_value('start_date', isset($search_criteria['start_date'])? date(DATE_FORMAT, strtotime($search_criteria['start_date'])) : "")?>">
		</div>
		
	</div>
	
	<div class="col-xs-5">
	
		<div class="checkbox">
			 <label>
			<input type="checkbox" value="1" name="duplicated_cb" <?=set_checkbox('duplicated_cb', 1, isset($search_criteria['duplicated_cb']) && $search_criteria['duplicated_cb'] == 1)?>>
			<?=lang('duplicated_cb')?>
			</label>
		</div>
		&nbsp;
		
		Cus.City:
		<select name="city_advanced" style="width:70px" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>						
			<?php foreach ($booked_cities as $key=>$value) :?>
				
				<option value="<?=$value['id']?>" <?=set_select('city_advanced', $key,isset($search_criteria['city']) &&  strval($search_criteria['city']) == strval($key)? true: false)?>><?=$value['name']?></option>
				
			<?php endforeach ;?>			
		</select>
		&nbsp;
		

		<span style="float: right;">
		Cus.Booking: <b><?=$total_cb?></b> - Review: <b><?=$total_review?></b> - Pax: <b><?=$total_pax?></b>(<?=$total_adults?> a, <?=$total_children?> c, <?=$total_infants?> i)
		
		&nbsp;
		</span>

	
	</div>
</div>

<div class="cb-adv-search row">
	<div class="col-xs-5">
		<?php foreach ($status as $key => $value) :?>	
			<div class="checkbox">
			    <label>
			      <input type="checkbox" name="booking_status[]" value="<?=$key?>" <?=set_checkbox('booking_status', $key, isset($search_criteria['booking_status']) && in_array($key, $search_criteria['booking_status'])?TRUE:FALSE)?>>
			      <?=lang($value)?>
			    </label>
			    &nbsp;
			  </div>	
	
		<?php endforeach ;?>
	</div>
	<div class="col-xs-2">
		<label for="end_date"><?=lang('e-date')?>:</label>
		<div class="form-group">
			<input tabindex="2" type="text" class="form-control input-sm" name="end_date" id="end_date" value="<?=set_value('end_date', isset($search_criteria['end_date'])? date(DATE_FORMAT, strtotime($search_criteria['end_date'])) : "")?>">
		</div>
	</div>
	
	<div class="col-xs-5">
		<div class="checkbox">
			<label>
			<input type="checkbox" name="date_field[]" value="1" <?=set_checkbox('date_field', 1, isset($search_criteria['date_field']) && in_array(1, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('r-date')?>
			</label>
			&nbsp;
		</div>
		
		<div class="checkbox">
			<label>		
			<input type="checkbox" name="date_field[]" value="6" <?=set_checkbox('date_field', 6, isset($search_criteria['date_field']) && in_array(6, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('b-date')?>
			</label>
			&nbsp;
		</div>
		
		<div class="checkbox">
			<label>	
			<input type="checkbox" name="date_field[]" value="2" <?=set_checkbox('date_field', 2, isset($search_criteria['date_field']) && in_array(2, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('s-date')?>
			</label>
		</div>
		
		<div class="checkbox">
			<label>	
			<input type="checkbox" name="date_field[]" value="3" <?=set_checkbox('date_field', 3, isset($search_criteria['date_field']) && in_array(3, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('e-date')?>
			</label>
			&nbsp;
		</div>
		
		<div class="checkbox">
			<label>	
			<input type="checkbox" name="date_field[]" value="4" <?=set_checkbox('date_field', 4, isset($search_criteria['date_field']) && in_array(4, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('m-date')?>
			</label>
			&nbsp;
		</div>
		
		<div class="checkbox">
			<label>	
			<input type="checkbox" name="date_field[]" value="5" <?=set_checkbox('date_field', 5, isset($search_criteria['date_field']) && in_array(5, $search_criteria['date_field'])?TRUE:FALSE)?>>
			<?=lang('p-due')?>
			</label>
			&nbsp;
		</div>
		
		<button type="button" onclick="search('<?=ACTION_ADVANCED_SEARCH?>')" class="btn btn-primary"><?=lang('btn_search')?></button>
		<button type="button" onclick="search('<?=ACTION_RESET?>')" class="btn btn-default"><?=lang('btn_reset')?></button>
	</div>					
</div>

<?php if(is_administrator() || is_dev_team() || is_marketing_team()):?>
	
	<div class="cb-adv-search" id="booking_source_data" <?php if(!isset($search_criteria['show_bs']) || $search_criteria['show_bs'] == "hide"):?>style="display: none;" <?php endif;?>>
		
		<div>
		Booking Site:
		<select name="booking_site" style="width:150px;" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['booking_site']) &&  $search_criteria['booking_site'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
			<?php foreach ($booking_sites as $key=>$value) :?>						
				<option value="<?=$key?>" <?=set_select('booking_site', $key,isset($search_criteria['booking_site']) &&  $search_criteria['booking_site'] == $key? true: false)?>><?=$value?></option>
			<?php endforeach ;?>			
		</select>
		&nbsp;&nbsp;
		Cus.Type:
		<select name="customer_type" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['customer_type']) &&  $search_criteria['customer_type'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
			<?php foreach ($customer_types as $key=>$value) :?>						
				<option value="<?=$key?>" <?=set_select('customer_type', $key,isset($search_criteria['customer_type']) &&  $search_criteria['customer_type'] == $key? true: false)?>><?=lang($value)?></option>
			<?php endforeach ;?>			
		</select>
		
		&nbsp;&nbsp;
		Req.Type:
		<select name="request_type" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['request_type']) &&  $search_criteria['request_type'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
			<?php foreach ($request_types as $key=>$value) :?>						
				<option value="<?=$key?>" <?=set_select('request_type', $key,isset($search_criteria['request_type']) &&  $search_criteria['request_type'] == $key? true: false)?>><?=lang($value)?></option>
			<?php endforeach ;?>			
		</select>
		
		&nbsp;&nbsp;
		<?=lang('source')?>:
		<select name="source" style="width: 100px;" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['source']) &&  $search_criteria['source'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
			<?php foreach ($booking_sources as $value) :?>						
				<option value="<?=$value['id']?>" <?=set_select('source', $value['id'],isset($search_criteria['source']) &&  $search_criteria['source'] == $value['id']? true: false)?>><?=$value['name']?></option>
			<?php endforeach ;?>			
		</select>	
		
		
		&nbsp;&nbsp;
		<?=lang('medium')?>:
		<select name="medium" class="form-control input-sm">
			<option value=""><?=lang('all')?></option>
			<option value="0" <?php if(isset($search_criteria['medium']) &&  $search_criteria['medium'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
			<?php foreach ($mediums as $key=>$value) :?>						
				<option value="<?=$key?>" <?=set_select('medium', $key,isset($search_criteria['medium']) &&  $search_criteria['medium'] == $key? true: false)?>><?=lang($value)?></option>
			<?php endforeach ;?>			
		</select>
		
		
		&nbsp;&nbsp;
		<?=lang('keyword')?>:
		<div class="form-group">			
			<input type="text" class="form-control input-sm" name="keyword" value="<?=isset($search_criteria['keyword']) ? $search_criteria['keyword']: ''?>">
		</div>	
		</div>
		
		<div style="margin-top:10px;">
			L.page
			<div class="form-group">			
				<input type="text" class="form-control input-sm" name="landing_page" value="<?=isset($search_criteria['landing_page']) ? $search_criteria['landing_page']: ''?>">
			</div>
			&nbsp;&nbsp;
			<?=lang('campaign')?>:				
			
			<select name="campaign" class="form-control input-sm">
				<option value=""><?=lang('common_select_all')?></option>
				<option value="0" <?php if(isset($search_criteria['campaign']) &&  $search_criteria['campaign'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
				<?php foreach ($campaigns as $value) :?>						
					<option value="<?=$value['id']?>" <?=set_select('campaign', $key,isset($search_criteria['campaign']) &&  $search_criteria['campaign'] == $value['id']? true: false)?>><?=$value['name']?></option>
				<?php endforeach ;?>			
			</select>
			&nbsp;&nbsp;
			Payment Method:
			<select name="payment_method" style="width:150px;" class="form-control input-sm">
				<option value=""><?=lang('all')?></option>
				<option value="0" <?php if(isset($search_criteria['payment_method']) &&  $search_criteria['payment_method'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
				<?php foreach ($payment_methods as $key=>$value) :?>						
					<option value="<?=$key?>" <?=set_select('payment_method', $key,isset($search_criteria['payment_method']) &&  $search_criteria['payment_method'] == $key? true: false)?>><?=$value?></option>
				<?php endforeach ;?>			
			</select>
		</div>

	</div>
	
	<div>
		<a href="javascript: void(0)" class="pull-right" style="text-decoration:underline" onclick="hide_advanced_search()"><?=lang('hide_advanced_search')?></a>
	
	
	<?php if(isset($search_criteria['show_bs']) && $search_criteria['show_bs'] == "show"):?>
		
		<a href="javascript:void(0)" onclick="showHideBSource(this)" rel="show">Less &laquo;</a>
	
	<?php else:?>
		
		<a href="javascript:void(0)" onclick="showHideBSource(this)" rel="hide">More &raquo;</a>
		
	<?php endif;?>
	</div>	
<?php endif;?>
						

<script language="javascript">

	function showHideBSource(obj){
	
		$src = $(obj).attr("rel");

		var display = "hide";
				
		if ($src == "hide"){

			$('#booking_source_data').show();	

			$(obj).html("Less &laquo;");

			$(obj).attr("rel","show");

			display = "show";
			
		} else {
			
			$('#booking_source_data').hide();
			
			$(obj).html("More &raquo;");

			$(obj).attr("rel","hide");

		}

		$.ajax({
			url: "<?=site_url('customer/customer_booking/').'/showHideActualSellProfit'?>",
			type: "POST",
			data: {	
				td_column: "show_bs",
				td_show: display								
			},
			success:function(value){									
				//alert(value);
			}
		});
		
	}
	
	$(document).ready(function() {
		$('#start_date').datepicker({
		    format: "<?=DATE_FORMAT_CALLENDAR?>",
		    autoclose: true,
		    todayHighlight: true,
	    });

		$('#end_date').datepicker({
		    format: "<?=DATE_FORMAT_CALLENDAR?>",
		    autoclose: true,
		    todayHighlight: true
	    });
	});
</script>