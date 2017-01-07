
<table class="no_border" width="100%">
		<tr>
			<td colspan="4">
				<input type="button" onclick="save();" value="<?=lang('common_button_save')?>" name="btnSave" class="button">
				<input type="button" onclick="cancel();" value="<?=lang('common_button_cancel')?>" name="btnCancel" class="button">
			</td>
		</tr>
		<tr><td colspan="4"><hr size="1"></hr></td></tr>
					
		<tr>
			<td width="10%"><b><?=lang('customer')?>:</b></td>
			
			<td width="28%">
				
				<?php foreach ($customers as $value) :?>
					<?php if(isset($search_criteria['customer_id']) && $search_criteria['customer_id'] == $value['id']):?>
						<b><?=$value['full_name']?></b>						
					<?php endif;?>
				<?php endforeach;?>
				
				<input type="hidden" value="<?=set_value('customer_booking_id', $search_criteria['customer_booking_id'])?>" name="customer_booking_id">
				
				&nbsp;&nbsp;&nbsp;
				
				<input type="checkbox" value="1" name="reviewed" <?=set_checkbox('reviewed',1)?>> <?=lang('reviewed')?>

			</td>
			
			<td width="10%"><?=lang('net_price')?>:<?=mark_required()?></td>
			
			<td>
				<input type="text" name="net_price" size="40" value="<?=set_value('net_price')?>"/>
				<?=form_error('net_price')?>
				
			</td>
		</tr>
		
		<tr>
			<td><?=lang('origin')?>:</td>
			<td>
				<?php if(isset($origins)):?>
				
				<select name="origin" id="origin">
					<option value="">---------</option>
					<?php foreach ($origins as $value):?>
						<option value="<?=$value['id']?>" <?=set_select('origin', $value['id'])?>><?=$value['service_name']?></option>
					<?php endforeach;?>				
				</select>
				
				<?php endif;?>
			</td>	
			
			<td><?=lang('selling_price')?>:<?=mark_required()?></td>			
			<td>
				<input type="text" name="selling_price" size="40" value="<?=set_value('selling_price')?>"/>
				<?=form_error('selling_price')?>
			</td>
						
		</tr>
		
		<tr>
			
			<td><?=lang('reservation_type')?>:</td>		
										
			<td>
				<select name="reservation_type" id="reservation_type" onchange="changeReservationType()">
								
					<?php foreach ($reservation_type as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_type', $key)?>><?=translate_text($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
			</td>
			
			
			
			
			<td><?=lang('reservation_status')?>:</td>		
										
			<td>
				<select name="reservation_status">				
					<?php foreach ($reservation_status as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_status', $key)?>><?=translate_text($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=lang('reserved_date')?>: <input type="text" name="reserved_date" id="reserved_date" size="15" value="<?=set_value('reserved_date')?>"/>
			</td>	
		</tr>
		
		<tr>
			
			<td><?=lang('service_name')?>:<?=mark_required()?></td>
			
			<td>
				<input type="text" name="service_name" id="service_name" size="40" value="<?=set_value('service_name')?>"/>
				
				<input type="hidden" name="service_id" id="service_id" value="<?=set_value('service_id')?>"/>
				
				<?=form_error('service_name')?>
			</td>
			
			
			
			<td><?=lang('1_payment')?>:</td>									
			<td>
				<input type="text" name="1_payment" size="15" value="<?=set_value('1_payment')?>"/>				
				
				<?=lang('1_payment_due')?>:&nbsp;<input type="text" name="1_payment_due" id="1_payment_due" size="15" value="<?=set_value('1_payment_due')?>"/>&nbsp;
				<?=lang('1_payment_date')?>:&nbsp;<input type="text" name="1_payment_date" id="1_payment_date" size="15" value="<?=set_value('1_payment_date')?>"/>	
				<?=form_error('1_payment')?>
			</td>
		</tr>
		
		<tr>
			
			<td><?=lang('start_date')?>:<?=mark_required()?></td>
			
			<td>
				<input type="text" name="start_date" id="start_date" size="40" value="<?=set_value('start_date')?>"/>
				<label class="small_text"><?=DATE_FORMAT_LBL?></label>
				<?=form_error('start_date')?>
			</td>
			
			
			
			<td><?=lang('2_payment')?>:</td>									
			<td>
				<input type="text" name="2_payment" size="15" value="<?=set_value('2_payment')?>"/>				
				
				
				<?=lang('2_payment_due')?>:&nbsp;<input type="text" name="2_payment_due" id="2_payment_due" size="15" value="<?=set_value('2_payment_due')?>"/>&nbsp;
				<?=lang('2_payment_date')?>:&nbsp;<input type="text" name="2_payment_date" id="2_payment_date" size="15" value="<?=set_value('2_payment_date')?>"/>	
				<?=form_error('2_payment')?>
			</td>
		
		</tr>
		
		<tr>
			
			<td><?=lang('end_date')?>:<?=mark_required()?></td>
			
			<td>
				<input type="text" name="end_date" id="end_date" size="40" value="<?=set_value('end_date')?>"/>				
				<?=form_error('end_date')?>
			</td>
			
			
			
			<td class="res_cruise"><?=lang('cabin_booked')?>:</td>
			<td class="res_cruise">
				<select name="cabin_booked">
					<option value="">----</option>				
					<?php foreach ($cabin_booked as $value) :?>
																
						<option value="<?=$value?>" <?=set_select('cabin_booked', $value)?>><?=$value?></option>		
					
					<?php endforeach ;?>				
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?=lang('cabin_incentive')?>:&nbsp;
				<select name="cabin_incentive">
					<option value="">----</option>				
					<?php foreach ($cabin_booked as $value) :?>
																
						<option value="<?=$value?>" <?=set_select('cabin_incentive', $value)?>><?=$value?></option>		
					
					<?php endforeach ;?>				
				</select>
				
				<?=form_error('cabin_booked')?>
			</td>
				
		</tr>
		
		<tr>
			
			<td><?=lang('partner')?>:<?=mark_required()?></td>		
										
			<td>
				<select name="partner" id="partner" style="width: 270px;">
					<option value=""> ---------- </option>
					<?php 
						$results = get_option_group_partner($partners);
						$partner_types = $results['partner_types'];
						$partner_ops = $results['partners'];
					?>
					<?php foreach ($partner_types as $key => $partner_t):?>
						<optgroup label="<?=translate_text($partner_t)?>">
							<?php foreach ($partner_ops[translate_text($partner_t)] as $value):?>
								<option value="<?=$value['id']?>" <?=set_select('partner', $value['id'])?>><?=translate_text($value['short_name'])?></option>
							<?php endforeach ;?>
						</optgroup>
					<?php endforeach ;?>
				</select>
				
				<?=form_error('partner')?>
			</td>
			
			
			
			<td class="res_visa"><?=lang('type_of_visa')?>:<?=mark_required()?></td>
			<td class="res_visa">
				<select name="type_of_visa">
					<option value="">----</option>				
					<?php foreach ($visa_types as $key=>$value) :?>
																
						<option value="<?=$key?>" <?=set_select('type_of_visa', $key)?>><?=translate_text($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
				
				<?=form_error('type_of_visa')?>
			</td>
		
		</tr>
		
		<tr>
			<td><?=lang('destination')?>:<?=mark_required()?></td>		
										
			<td>
				<select name="destination" id="destination" style="width: 270px;">
					<option value=""> ---------- </option>
					
					<?php foreach ($destinations as $key => $value):?>
						<option value="<?=$value['id']?>" <?=set_select('destination', $value['id'])?>><?=$value['name']?></option>
					<?php endforeach ;?>
				</select>
				
				<?=form_error('destination')?>
			</td>
			
			<td class="res_visa"><?=lang('processing_time')?>:<?=mark_required()?></td>
			<td class="res_visa">
				<select name="processing_time">
					<option value="">----</option>				
					<?php foreach ($visa_processing_times as $key=>$value) :?>
																
						<option value="<?=$key?>" <?=set_select('processing_time', $key)?>><?=translate_text($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
				
				<?=form_error('processing_time')?>
			</td>
		</tr>
		
		<tr>
			
			<td><?=lang('customer_request')?>:</td>			
			<td>
				<textarea rows="10" cols="40" name="description"><?=set_value('description')?></textarea>
			</td>
			
			<td><?=lang('detail_reservation')?>:</td>			
			<td>
				<textarea rows="10" cols="40" name="detail_reservation"><?=set_value('detail_reservation')?></textarea>
			</td>

		</tr>								
			
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr><td colspan="4"><?=note_required()?></td></tr>
		
		<tr>
			<td colspan="4">
				<input type="button" onclick="save();" value="<?=lang('common_button_save')?>" name="btnSave" class="button">
				<input type="button" onclick="cancel();" value="<?=lang('common_button_cancel')?>" name="btnCancel" class="button">
			</td>
		</tr>
					
</table>

<script language="javascript">

	function show_hide_fields_by_type(){
		$('.res_cruise').hide();
		$('.res_visa').hide();

		var type = $("#reservation_type option:selected").val();

		if (type == 1){ // cruise tour
			$('.res_cruise').show();
		}

		if (type == 7){ // visa
			$('.res_visa').show();
			$('#service_name').val('Vietnam Visa On Arrival');
		}
	}	

	function save() {

		var type = $("#reservation_type option:selected").val();
		

		var service_id = $('#service_id').val();

		if ((type == 1 || type == 4) && service_id == ''){

			alert('Please select the right service by using autocomplete function');
			
			return false;
			
		}
		
		document.frm.action_type.value = "save_create";
		
		document.frm.submit();
	}
	
	function cancel() {
		
		window.location = '<?=site_url('customer/service_reservation/index/'.$this->uri->segment(4))?>';
		
	}

	function setAutocomplete(source){
		
		var data = new Array();
		
		if (source == 'cruises'){					
			for (var i = 0; i < cruises.length; i++){				
				data[i] = cruises[i].name;				
			}
		} else if (source == 'hotels'){
			for (var i = 0; i < hotels.length; i++){				
				data[i] = hotels[i].name;				
			}
		} else if (source == 'tours'){
			for (var i = 0; i < tours.length; i++){				
				data[i] = tours[i].name;				
			}
		} else if (source == 'cars'){
			for (var i = 0; i < cars.length; i++){
				data[i] = cars[i].name;		
			}
		}

		$("#service_name").autocomplete({			 
			source: data,
			autoFocus: true,
			select: function(event, ui) {
				selectItem(ui.item.value);	
			}
		});
	}

	function get_selected_object(value){

		var type = $("#reservation_type option:selected").val();
		
		var obj = '';
		
		if (type == 1){
			for (var i = 0; i < cruises.length; i++){
								
				if (value == cruises[i].name){
					
					obj = cruises[i];

					break;		
				}
			}
		} else if (type == 2){
			for (var i = 0; i < hotels.length; i++){
				
				if (value == hotels[i].name){
					
					obj = hotels[i];

					break;		
				}
			}
		} else if (type == 3){
			for (var i = 0; i < cars.length; i++){
				
				if (value == cars[i].name){
					
					obj = cars[i];

					break;		
				}
			}
		} else if (type == 4){
			for (var i = 0; i < tours.length; i++){
				
				if (value == tours[i].name){
					
					obj = tours[i];

					break;		
				}
			}
		}

		return obj;
	}

	function selectItem(value){

		var obj = get_selected_object(value);
		
		if (obj != ''){
			$('#service_id').val(obj.id);
			
			$("#partner option[value=" + obj.partner_id + "]").attr('selected', 'selected');

			$("#destination option[value=" + obj.destination_id + "]").attr('selected', 'selected');
		}
	}

	function changeReservationType(){

		//$("#service_name").val("");
				
		var type = $("#reservation_type option:selected").val();
		//$("#service_name").val('');
		if (type == 1){
			
			setAutocomplete('cruises');
			
		} else if (type == 2){
			
			setAutocomplete('hotels');
			
		}else if (type == 3){
			
			setAutocomplete('cars');
			
		}else if (type == 4){
			
			setAutocomplete('tours');
			
		} else {
			//$("#service_name").val('');
		}

		show_hide_fields_by_type();
	}

	$(document).ready(function() {

		show_hide_fields_by_type();
		
		$("#start_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		$("#end_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		$("#1_payment_due").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
		
		$("#1_payment_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		$("#2_payment_due").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
		
		$("#2_payment_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});

		$("#reserved_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
		
		setAutocomplete('cruises');
	});
	
</script>