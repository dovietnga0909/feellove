<table class="no_border" width="100%">
	
		<tr>		
			<td>
				<input type="text" size="12" name="name_advanced" value="<?=set_value('name_advanced', isset($search_criteria['name'])? $search_criteria['name']: '')?>">
				&nbsp;&nbsp;
				<?=lang('sale')?>:	
				<select name="sale_advanced" style="width: 60px">
					<option value=""><?=lang('common_select_all')?></option>				
					<?php foreach ($sales as $value) :?>
																					
						<option value="<?=$value['id']?>" <?=set_select('sale_advanced', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['user_name']?></option>
					
					<?php endforeach ;?>				
				</select>
				
				&nbsp;&nbsp;<?=lang('partner')?>:
				<select name="partner" style="width: 60px">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['partner_id']) &&  $search_criteria['partner_id'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>
					<?php 
						$results = get_option_group_partner($partners);
						$partner_types = $results['partner_types'];
						$partner_ops = $results['partners'];
					?>
					<?php foreach ($partner_types as $key => $partner_t):?>
						<optgroup label="<?=translate_text($partner_t)?>">
							<?php foreach ($partner_ops[translate_text($partner_t)] as $value):?>
								<option value="<?=$value['id']?>" <?=set_select('partner', $value['id'],isset($search_criteria['partner_id']) &&  $search_criteria['partner_id'] == $value['id']? true: false)?>><?=$value['short_name']?></option>
							<?php endforeach ;?>
						</optgroup>
					<?php endforeach ;?>
				</select>
				
				&nbsp;Country:
				<select name="country" style="width: 60px">
					<option value=""><?=lang('common_select_all')?></option>						
					<?php foreach ($countries as $key=>$value) :?>
						
						<?php if(in_array($key, $booked_countries)):?>
																					
						<option value="<?=$key?>" <?=set_select('country', $key,isset($search_criteria['country']) &&  strval($search_criteria['country']) == strval($key)? true: false)?>><?=$value[0]?></option>
						
						<?php endif;?>
						
					<?php endforeach ;?>			
				</select>
				
			</td>
			
			<td><b><?=lang('s-date')?>:</b></td>
			
			<td>
				<input tabindex="1" type="text" size="15" name="start_date" id="start_date" value="<?=set_value('start_date', isset($search_criteria['start_date'])? $this->timedate->format($search_criteria['start_date'], DATE_FORMAT) : "")?>">
				
			</td>
			<td>
				
				Des:										
				<select name="destination"  style="width: 60px;">
					<option value=""><?=lang('common_select_all')?></option>
					
					<option value="0" <?php if(isset($search_criteria['destination_id']) &&  $search_criteria['destination_id'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>
										
					<?php foreach ($destinations as $key => $value):?>
						<option value="<?=$value['id']?>" <?=set_select('destination', $value['id'], isset($search_criteria['destination_id']) && $search_criteria['destination_id'] == $value['id'])?>><?=$value['name']?></option>
					<?php endforeach ;?>
				</select>
				
				&nbsp;
				Res.Type:										
				<select name="reservation_type"  style="width: 60px;">
					<option value=""><?=lang('common_select_all')?></option>					
					<?php foreach ($reservation_type as $key => $value):?>
						<option value="<?=$key?>" <?=set_select('reservation_type', $key, isset($search_criteria['reservation_type']) && $search_criteria['reservation_type'] == $key)?>><?=translate_text($value)?></option>
					<?php endforeach ;?>
				</select>
				
				&nbsp;<?=lang('booking_type')?>:
				<select name="booking_type" style="width: 60px">
					<option value=""><?=lang('common_select_all')?></option>						
					<option value="1" <?=set_select('booking_type', 1,isset($search_criteria['booking_type']) &&  $search_criteria['booking_type'] == 1)?>><?=lang('book_seperate')?></option>
					<option value="2" <?=set_select('booking_type', 2,isset($search_criteria['booking_type']) &&  $search_criteria['booking_type'] == 2)?>><?=lang('in_package')?></option>			
				</select>
			</td>	
			<td align="right">
				R.S: <b><?=$total_sr?></b> - Review: <b><?=$total_review?></b> - Pax: <b><?=$total_pax?></b>(<?=$total_adults?> a, <?=$total_children?> c, <?=$total_infants?> i)
				
				&nbsp;
			</td>		
			
		</tr>
		
		<tr>
						
			<td style="font-size: 11px;">
				
				<?php foreach ($reservation_status as $key => $value) :?>
																					
					<input type="checkbox" name="arr_reservation_status[]" value="<?=$key?>" <?=set_checkbox('arr_reservation_status', $key, isset($search_criteria['arr_reservation_status']) && in_array($key, $search_criteria['arr_reservation_status'])?TRUE:FALSE)?>><?=translate_text($value)?>&nbsp;
				
				<?php endforeach ;?>	
				
			</td>	
			
			<td><b><?=lang('e-date')?>:</b></td>
			
			<td>
				<input tabindex="2" type="text" size="15" name="end_date" id="end_date" value="<?=set_value('end_date', isset($search_criteria['end_date'])? $this->timedate->format($search_criteria['end_date'], DATE_FORMAT) : "")?>">
				
			</td>		
			<td style="font-size: 11px;">				
				
				<input type="checkbox" name="date_field[]" value="7" <?=set_checkbox('date_field', 7, isset($search_criteria['date_field']) && in_array(7, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('b-date')?>&nbsp;
				
				<input type="checkbox" name="date_field[]" value="1" <?=set_checkbox('date_field', 1, isset($search_criteria['date_field']) && in_array(1, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('s-date')?>&nbsp;
				
				<input type="checkbox" name="date_field[]" value="2" <?=set_checkbox('date_field', 2, isset($search_criteria['date_field']) && in_array(2, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('e-date')?>&nbsp;
							
				<input type="checkbox" name="date_field[]" value="3" <?=set_checkbox('date_field', 3, isset($search_criteria['date_field']) && in_array(3, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('1-p-due')?>&nbsp;
				
				<input type="checkbox" name="date_field[]" value="4" <?=set_checkbox('date_field', 4, isset($search_criteria['date_field']) && in_array(4, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('1-p-date')?>&nbsp;
				
				<input type="checkbox" name="date_field[]" value="5" <?=set_checkbox('date_field', 5, isset($search_criteria['date_field']) && in_array(5, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('2-p-due')?>&nbsp;
				
				<input type="checkbox" name="date_field[]" value="6" <?=set_checkbox('date_field', 6, isset($search_criteria['date_field']) && in_array(6, $search_criteria['date_field'])?TRUE:FALSE)?>><?=lang('2-p-date')?>&nbsp;
			
			</td>
			
			<td align="right">
				<input type="submit" onclick="advanced_search();" value="<?=lang('common_button_search')?>" name="btnSave" class="button">
				<input type="button" onclick="resetForm();" value="<?=lang('common_button_reset')?>" name="btnReset" class="button">				
				<i><a href="javascript: void(0)" onclick="hide_advanced_search()"><?=lang('hide_advanced_search')?></a></i>
			</td>	
		</tr>
		
		<tr>
			<td colspan="5">
				
				<div id="booking_source_data" <?php if(!isset($search_criteria['show_bs']) || $search_criteria['show_bs'] == "hide"):?>style="display: none;" <?php endif;?>>
				
				<?php if(is_administrator() || is_dev_team()):?>
				<select name="booking_site" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['booking_site']) &&  $search_criteria['booking_site'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($booking_sites as $key=>$value) :?>						
						<option value="<?=$key?>" <?=set_select('booking_site', $key,isset($search_criteria['booking_site']) &&  $search_criteria['booking_site'] == $key? true: false)?>><?=$value?></option>
					<?php endforeach ;?>			
				</select>
				&nbsp;&nbsp;
				Cus.Type:
				<select name="customer_type" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['customer_type']) &&  $search_criteria['customer_type'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($customer_types as $key=>$value) :?>						
						<option value="<?=$key?>" <?=set_select('customer_type', $key,isset($search_criteria['customer_type']) &&  $search_criteria['customer_type'] == $key? true: false)?>><?=translate_text($value)?></option>
					<?php endforeach ;?>			
				</select>
				
				&nbsp;&nbsp;
				Req.Type:
				<select name="request_type" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['request_type']) &&  $search_criteria['request_type'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($request_types as $key=>$value) :?>						
						<option value="<?=$key?>" <?=set_select('request_type', $key,isset($search_criteria['request_type']) &&  $search_criteria['request_type'] == $key? true: false)?>><?=translate_text($value)?></option>
					<?php endforeach ;?>			
				</select>
				
				&nbsp;&nbsp;
				<?=lang('source')?>:
				<select name="source" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['source']) &&  $search_criteria['source'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($booking_sources as $value) :?>						
						<option value="<?=$value['id']?>" <?=set_select('source', $value['id'],isset($search_criteria['source']) &&  $search_criteria['source'] == $value['id']? true: false)?>><?=$value['name']?></option>
					<?php endforeach ;?>			
				</select>	
				
				
				&nbsp;&nbsp;
				<?=lang('medium')?>:
				<select name="medium" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['medium']) &&  $search_criteria['medium'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($mediums as $key=>$value) :?>						
						<option value="<?=$key?>" <?=set_select('medium', $key,isset($search_criteria['medium']) &&  $search_criteria['medium'] == $key? true: false)?>><?=translate_text($value)?></option>
					<?php endforeach ;?>			
				</select>
				
				
				&nbsp;&nbsp;
				<?=lang('keyword')?>:				
				<input type="text" size="15" name="keyword" value="<?=isset($search_criteria['keyword']) ? $search_criteria['keyword']: ''?>">
				
				&nbsp;&nbsp;
				L.page			
				<input type="text" size="15" name="landing_page" value="<?=isset($search_criteria['landing_page']) ? $search_criteria['landing_page']: ''?>">
				
				&nbsp;&nbsp;
				<?=lang('campaign')?>:				
				
				<select name="campaign" style="width:90px;">
					<option value=""><?=lang('common_select_all')?></option>
					<option value="0" <?php if(isset($search_criteria['campaign']) &&  $search_criteria['campaign'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>						
					<?php foreach ($campaigns as $value) :?>						
						<option value="<?=$value['id']?>" <?=set_select('campaign', $key,isset($search_criteria['campaign']) &&  $search_criteria['campaign'] == $value['id']? true: false)?>><?=$value['name']?></option>
					<?php endforeach ;?>			
				</select>
				
				<br>
				<br>
				
				<?php endif;?>
								
				<b>B.Status:</b>				
				<?php foreach ($booking_status as $key => $value) :?>
																					
					<input type="checkbox" name="booking_status[]" value="<?=$key?>" <?=set_checkbox('booking_status', $key, isset($search_criteria['booking_status']) && in_array($key, $search_criteria['booking_status'])?TRUE:FALSE)?>><?=translate_text($value)?>&nbsp;&nbsp;
				
				<?php endforeach ;?>
				
				&nbsp;&nbsp;
				<b>Multi Partner:</b>&nbsp;
					
					<input type="text" size="20" value="" name="multi_partner" id="multi_partner">
					
					<?php if(isset($search_criteria['multi_partners'])):?>
						<?php foreach ($search_criteria['multi_partners'] as $id):?>					
						<?php foreach ($partners as $partner):?>
							<?php if($id == $partner['id']):?>											
							<span style="margin-left:5px" id="selected_partner_<?=$partner['id']?>">
								<?=$partner['name']?>			
								<img width="10" height="10" src="<?=base_url() .'media/Delete.png'?>" style="cursor:pointer" onclick="delete_selected_partner(<?=$partner['id']?>)">
								<input type="hidden" class="multi_partners" name="multi_partners[]" value="<?=$partner['id']?>"> 
							</span>
							<?php endif;?>
						<?php endforeach;?>
						<?php endforeach;?>
					<?php endif;?>
					
				</div>
				
				<?php if(isset($search_criteria['show_bs']) && $search_criteria['show_bs'] == "show"):?>
				
					&nbsp;&nbsp;<a href="javascript:void(0)" onclick="showHideBSource(this)" rel="show">Less &laquo;</a>
				
				<?php else:?>
					
					&nbsp;&nbsp;<a href="javascript:void(0)" onclick="showHideBSource(this)" rel="hide">More &raquo;</a>
					
				<?php endif;?>
				
			</td>
		</tr>
		
						
</table>

<script language="javascript">

	function set_multi_partner_autocomplete(){
	
		 $("#multi_partner").autocomplete({
			 
			 source: function( request, response ) {
				 
				 $.ajax({
					 url: "<?=site_url('customer/service_reservation').'/search_partner/'?>",
					 dataType: "json",
					 type: "POST",
					 data: {							 
						 partner_name: request.term,
						 selected_ids: get_selected_ids()
					 },
					 success: function( data ) {
						 response( $.map( data, function( item ) {
							 return {
								 label: item.name,
								 value: '',
								 id: item.id,
								 name: item.name
						 	}
				 		}));
	
				 	}
				 });
			 
			 },
			 minLength: 2,
			 select: function( event, ui ) {					 
				 add_partner(ui.item.id, ui.item.name);
			 },
			 open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			 },
			 close: function() {
			 	$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			 }
		});
		
	}

		
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
			url: "<?=site_url('customer/service_reservation/').'/showHideBsSource'?>",
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

	function advanced_search() {
		
		document.frm.action_type.value = "advanced_search";
		
		document.frm.submit();
	}
	
	function hide_advanced_search() {
		/*
		document.frm.action_type.value = "";
		
		document.frm.submit();
		*/
		$("#advanced_search").hide();
		$("#search").show();
	}

	function delete_selected_partner(id){
		$('#selected_partner_' + id).remove();
	}

	function add_partner(id, name){
		
		var item  = '<span style="margin-left:5px" id="selected_partner_' + id + '">' 
			+ name +			
			'<img width="10" height="10" src="<?=base_url() .'media/Delete.png'?>" style="cursor:pointer" onclick="delete_selected_partner(' + id + ')">'
			+ '<input type="hidden" class="multi_partners" name="multi_partners[]" value="' + id + '">' + 
		'</span>';
		
		$('#booking_source_data').append(item);
	}

	function get_selected_ids(){
		var ids = '';	
		$('.multi_partners').each(function(){
			ids = ids + $(this).val() + ',';
		});

		if (ids.length > 0){
			ids = ids.substring(0, ids.length - 1);
		}			
		
		return ids;
	}
	
	$(document).ready(function() {
		
		$("#start_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		$("#end_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		set_multi_partner_autocomplete();
	});
</script>