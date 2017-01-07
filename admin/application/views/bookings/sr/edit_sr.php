<form class="form-horizontal" method="POST" name="frm" role="form">
<p>
	<b>
		<?php if(isset($c_titles[$sr['title']])):?>
			<?=lang($c_titles[$sr['title']])?>
		<?php endif;?>
		
		<?=$sr['customer_name']?>
	</b>
	(Phone: <a href="tel:<?=$sr['phone']?>"><?=$sr['phone']?></a>, Email: <a href="mailto:<?=$sr['email']?>"><?=$sr['email']?></a>)
</p>

<div>
	<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
	<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
</div>

<hr>

<div class="row">
	<div class="col-xs-6">
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('reservation_type')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<select name="reservation_type" id="reservation_type" onchange="set_service_name_autocomplete()" class="form-control input-sm">
								
					<?php foreach ($reservation_type as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_type', $key, $sr['reservation_type'] == $key?true:false)?>><?=lang($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('service_name')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" class="form-control input-sm" name="service_name" id="service_name" value="<?=set_value('service_name', $sr['service_name'])?>"/>
				
				<input type="hidden" name="service_id" id="service_id" value="<?=set_value('service_id', $sr['service_id'])?>"/>
		
				<?=form_error('service_name')?>
			</div>
		</div>
	
		 
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('start_date')?>:<?=mark_required()?></label>
			<div class="col-xs-5">
				<div class="input-group">
					<input type="text" name="start_date" id="start_date" class="form-control input-sm" value="<?=set_value('start_date', bpv_format_date($sr['start_date']))?>"/>
					<span class="input-group-addon" id="cal_start_date"><span class="fa fa-calendar"></span></span>
				</div>
				<?=form_error('start_date')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('end_date')?>:<?=mark_required()?></label>
			<div class="col-xs-5">
				<div class="input-group">
					<input type="text" name="end_date" id="end_date" class="form-control input-sm" value="<?=set_value('end_date', bpv_format_date($sr['end_date']))?>"/>
					<span class="input-group-addon" id="cal_end_date"><span class="fa fa-calendar"></span></span>
				</div>
				
				<?=form_error('end_date')?>
			</div>
		</div>
		
		<div class="form-group res_un_flight">
			<label class="col-xs-4 control-label"><?=lang('destination')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" name="des_autocomplete" id="des_autocomplete" class="form-control input-sm"  placeholder="Input Destination" value="<?=set_value('des_autocomplete', $sr['des_name'])?>"/>
				<input type="hidden" name="destination" id="destination" value="<?=set_value('destination', $sr['destination_id'])?>"/>
				
				<?=form_error('destination')?>
			</div>
		</div>
		
		<!-- 
		<div class="form-group">
			<div class="col-xs-8 col-xs-offset-4">
				<input type="checkbox" value="1" name="reviewed" <?=set_checkbox('reviewed',1, $sr['reviewed'] == 1)?>> <?=lang('reviewed')?>
			</div>
		</div>
		 -->
		 
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('description')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="7" name="description"><?=set_value('description', $sr['description'])?></textarea>
				<?=form_error('description')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('detail_reservation')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="7" name="detail_reservation"><?=set_value('detail_reservation', $sr['detail_reservation'])?></textarea>
			</div>
		</div>
	
	</div>
	
	<div class="col-xs-6">
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('net_price')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<input class="form-control input-sm price-cell" type="text" name="net_price" value="<?=set_value('net_price', number_format($sr['net_price']))?>"/>
				<?=form_error('net_price')?>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('selling_price')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<input class="form-control input-sm price-cell" type="text" name="selling_price" value="<?=set_value('selling_price',  number_format($sr['selling_price']))?>"/>
				<?=form_error('selling_price')?>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('reservation_status')?>: <?=mark_required()?></label>
			<div class="col-xs-5">
				<select name="reservation_status" class="form-control input-sm">				
					<?php foreach ($reservation_status as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_status', $key, $sr['reservation_status'] == $key? true: false)?>><?=lang($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
			</div>		
		</div>
		
		<div class="form-group res_un_flight">
			<label class="col-xs-4 control-label"><?=lang('reserved_date')?>:</label>
			<div class="col-xs-5">
				<div class="input-group">
					<input type="text" class="form-control input-sm" name="reserved_date" id="reserved_date" value="<?=set_value('reserved_date', bpv_format_date($sr['reserved_date']))?>"/>
		  			<span class="input-group-addon" id="cal_reserved_date"><span class="fa fa-calendar"></span></span>
				</div>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('partner')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" name="partner_autocomplete" id="partner_autocomplete" class="form-control input-sm" placeholder="Input Partner" value="<?=set_value('partner_autocomplete', $sr['partner_name'])?>"/>
				<input type="hidden" name="partner" id="partner" value="<?=set_value('partner', $sr['partner_id'])?>"/>
				
				<?=form_error('partner')?>
			</div>
		</div>
		
		<div class="form-group res_un_flight">
			<div class="col-xs-4">
				<label><?=lang('1_payment')?>:</label>
				<input type="text" name="1_payment" class="form-control input-sm price-cell" value="<?=set_value('1_payment', number_format($sr['1_payment']))?>"/>				
				<?=form_error('1_payment')?>
			</div>
			
			<div class="col-xs-4">
				<label><?=lang('1_payment_due')?>:</label>
				<div class="input-group">
					<input type="text" name="1_payment_due" id="1_payment_due" class="form-control input-sm" value="<?=set_value('1_payment_due', bpv_format_date($sr['1_payment_due']))?>"/>
					<span class="input-group-addon" id="cal_1_payment_due"><span class="fa fa-calendar"></span></span>
				</div>
			</div>
			
			<div class="col-xs-4">
				<label><?=lang('1_payment_date')?>:</label>
				<div class="input-group">
					<input type="text" name="1_payment_date" id="1_payment_date" class="form-control input-sm" value="<?=set_value('1_payment_date', bpv_format_date($sr['1_payment_date']))?>"/>
					<span class="input-group-addon" id="cal_1_payment_date"><span class="fa fa-calendar"></span></span>
				</div>
			</div>
		</div>

		<div class="form-group res_un_flight">
			
			<div class="col-xs-4">
				<label><?=lang('2_payment')?>:</label>
				<input type="text" name="2_payment" class="form-control input-sm price-cell" value="<?=set_value('2_payment', number_format($sr['2_payment']))?>"/>				
				<?=form_error('2_payment')?>
			</div>
			
			<div class="col-xs-4">
				<label><?=lang('2_payment_due')?>:</label>
				<div class="input-group">
					<input type="text" name="2_payment_due" id="2_payment_due" class="form-control input-sm" value="<?=set_value('2_payment_due', bpv_format_date($sr['2_payment_due']))?>"/>
					<span class="input-group-addon" id="cal_2_payment_due"><span class="fa fa-calendar"></span></span>
				</div>
			</div>
			
			<div class="col-xs-4">
				<label><?=lang('2_payment_date')?>:</label>
				<div class="input-group">
					<input type="text" name="2_payment_date" id="2_payment_date" class="form-control input-sm" value="<?=set_value('2_payment_date', bpv_format_date($sr['2_payment_date']))?>"/>
					<span class="input-group-addon" id="cal_2_payment_date"><span class="fa fa-calendar"></span></span>
				</div>
			</div>
		
		</div>
		
		
		
		<div class="res_cruise">
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('cabin_booked')?>:<?=mark_required()?></label>
				<div class="col-xs-5">
					<select name="cabin_booked" class="form-control input-sm">
						<option value="">----</option>				
						<?php foreach ($cabin_booked as $value) :?>
																	
							<option value="<?=$value?>" <?=set_select('cabin_booked', $value, $sr['cabin_booked'] == $value? true: false)?>><?=$value?></option>		
						
						<?php endforeach ;?>				
					</select>
					<?=form_error('cabin_booked')?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('cabin_incentive')?>:</label>
				<div class="col-xs-5">
					<select name="cabin_incentive" class="form-control input-sm">
						<option value="">----</option>				
						<?php foreach ($cabin_booked as $value) :?>
																	
							<option value="<?=$value?>" <?=set_select('cabin_booked', $value, $sr['cabin_incentive'] == $value? true: false)?>><?=$value?></option>		
						
						<?php endforeach ;?>				
					</select>
				</div>
			</div>
		</div>

		<div class="res_visa">
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('type_of_visa')?>:<?=mark_required()?></label>
				<div class="col-xs-5">
					<select name="type_of_visa" class="form-control input-sm">
						<option value="">----</option>				
						<?php foreach ($visa_types as $key=>$value) :?>
																	
							<option value="<?=$key?>" <?=set_select('type_of_visa', $key, $key==$sr['type_of_visa'])?>><?=lang($value)?></option>		
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('type_of_visa')?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('processing_time')?>:<?=mark_required()?></label>
				<div class="col-xs-5">
					<select name="processing_time" class="form-control input-sm">
						<option value="">----</option>				
						<?php foreach ($visa_processing_times as $key=>$value) :?>
																	
							<option value="<?=$key?>" <?=set_select('processing_time', $key, $key==$sr['processing_time'])?>><?=lang($value)?></option>		
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('processing_time')?>
				</div>
			</div>
		
		</div>
		
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('pnr')?>:</label>
			<div class="col-xs-8">
				<input type="text" class="form-control input-sm" name="flight_pnr" id="flight_pnr" value="<?=set_value('flight_pnr', $sr['flight_pnr'])?>"/>
				<?=form_error('flight_pnr')?>
			</div>
		</div>
			
		<?php if(!empty($sr['flight_class'])):?>
	
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('flight_code')?>:</label>
			<div class="col-xs-8">
				<input type="text" class="form-control input-sm" name="flight_code" id="flight_code" value="<?=set_value('flight_code', $sr['flight_code'])?>"/>
				<?=form_error('flight_code')?>
			</div>
		</div>
		
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('departure_time')?>:</label>
			<div class="col-xs-4">
				<input type="text" class="form-control input-sm" name="departure_time" id="departure_time" value="<?=set_value('departure_time', $sr['departure_time'])?>"/>
				<?=form_error('departure_time')?>
			</div>
		</div>
		
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('arrival_time')?>:</label>
			<div class="col-xs-4">
				<input type="text" class="form-control input-sm" name="arrival_time" id="arrival_time" value="<?=set_value('arrival_time', $sr['arrival_time'])?>"/>
				<?=form_error('arrival_time')?>
			</div>
		</div>
		
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('fare_rule_short')?>:</label>
			<div class="col-xs-8">
			
				<?php for($i = 1; $i <=3; $i++):?>
					<div class="radio">
					  <label>
					    <input type="radio" name="fare_rule_short" value="<?=lang('tf_fare_rule_'.$i)?>" <?= set_radio('fare_rule_short', lang('tf_fare_rule_'.$i), select_fare_rule_short($sr, $i)); ?> >
					    <?=lang('tf_fare_rule_'.$i)?>
					  </label>
					</div>
				<?php endfor;?>
				
				<?=form_error('fare_rule_short')?>
			</div>
		</div>
		
		<?php endif;?>
	</div>
</div>
<hr>
<div>
	<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
	<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
</div>
</form>

<script type="text/javascript">

	show_hide_sr_fields_by_type();

	set_sr_autocomplete();

	$('#start_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#cal_start_date').click(function(){$('#start_date').focus()});

	$('#end_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#cal_end_date').click(function(){$('#end_date').focus()});


	$('#reserved_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });
	$('#cal_reserved_date').click(function(){$('#reserved_date').focus()});


	$('#1_payment_due').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });
	$('#cal_1_payment_due').click(function(){$('#1_payment_due').focus()});

	$('#1_payment_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });
	$('#cal_1_payment_date').click(function(){$('#1_payment_date').focus()});


	$('#2_payment_due').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });
	$('#cal_2_payment_due').click(function(){$('#2_payment_due').focus()});

	$('#2_payment_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });
	$('#cal_2_payment_date').click(function(){$('#2_payment_date').focus()});
	

	<?php if($sr['reservation_type'] != 5):?>
		$('.price-cell').mask('000,000,000,000', {reverse: true});
	<?php endif;?>
	
	set_service_name_autocomplete();
    
</script>
