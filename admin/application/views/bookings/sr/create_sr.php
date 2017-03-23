<form class="form-horizontal" method="POST" name="frm" role="form">
<p>
	<b><?=($customer_booking['title']==1? 'Mr.':'Ms.')?>
		<?=$customer_booking['customer_name']?>
	</b>
	(Phone: <a href="tel:<?=$customer_booking['phone']?>"><?=$customer_booking['phone']?></a>, Email: <a href="mailto:<?=$customer_booking['email']?>"><?=$customer_booking['email']?></a>)
</p>
<hr>

<div class="row">
	<div class="col-xs-6">
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('reservation_type')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<select name="reservation_type" id="reservation_type" onchange="set_service_name_autocomplete()" class="form-control input-sm">
					<option value="">---------</option>					
					<?php foreach ($reservation_type as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_type', $key)?>><?=lang($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('service_name')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" class="form-control input-sm" name="service_name" id="service_name" value="<?=set_value('service_name')?>"/>
						
				<input type="hidden" name="service_id" id="service_id" value="<?=set_value('service_id')?>"/>
				
				<?=form_error('service_name')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('start_date')?>:<?=mark_required()?></label>
			<div class="col-xs-5">
				<div class="input-group">
					<input type="text" name="start_date" id="start_date" class="form-control input-sm" value="<?=set_value('start_date')?>"/>
					<span class="input-group-addon" id="cal_start_date"><span class="fa fa-calendar"></span></span>
				</div>
				<?=form_error('start_date')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('end_date')?>:<?=mark_required()?></label>
			<div class="col-xs-5">
				<div class="input-group">
					<input type="text" name="end_date" id="end_date" class="form-control input-sm" value="<?=set_value('end_date')?>"/>
					<span class="input-group-addon" id="cal_end_date"><span class="fa fa-calendar"></span></span>
				</div>
				
				<?=form_error('end_date')?>
			</div>
		</div>
		
		<div class="form-group res_un_flight">
			<label class="col-xs-4 control-label"><?=lang('destination')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" name="des_autocomplete" id="des_autocomplete" class="form-control input-sm" placeholder="Input Destination" value="<?=set_value('des_autocomplete')?>"/>
				<input type="hidden" name="destination" id="destination" value="<?=set_value('destination')?>"/>
				<?=form_error('destination')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('description')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="7" name="description"><?=set_value('description')?></textarea>
				<?=form_error('description')?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('detail_reservation')?>:</label>
			<div class="col-xs-8">
				<textarea class="form-control" rows="7" name="detail_reservation"><?=set_value('detail_reservation')?></textarea>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-xs-8 col-xs-offset-4">
				<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
				<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
			</div>
		</div>
		
	</div>
	<div class="col-xs-6">
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('net_price')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<input class="form-control input-sm price-cell" type="text" name="net_price" value="<?=set_value('net_price')?>"/>
				<?=form_error('net_price')?>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('selling_price')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
				<input class="form-control input-sm price-cell" type="text" name="selling_price" value="<?=set_value('selling_price')?>"/>
				<?=form_error('selling_price')?>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('reservation_status')?>: <?=mark_required()?></label>
			<div class="col-xs-5">
				<select name="reservation_status" class="form-control input-sm">				
					<?php foreach ($reservation_status as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_status', $key)?>><?=lang($value)?></option>		
					
					<?php endforeach ;?>				
				</select>
			</div>		
		</div>
		
		<div class="form-group res_un_flight">
			<label class="col-xs-4 control-label"><?=lang('reserved_date')?>:</label>
			<div class="col-xs-5">
				<div class="input-group">
				  <input type="text" class="form-control input-sm" name="reserved_date" id="reserved_date" value="<?=set_value('reserved_date')?>"/>
				  <span class="input-group-addon" id="cal_reserved_date"><span class="fa fa-calendar"></span></span>
				</div>
			</div>		
		</div>
		
		<div class="form-group">
			<label class="col-xs-4 control-label"><?=lang('partner')?>:<?=mark_required()?></label>
			<div class="col-xs-8">
				<input type="text" name="partner_autocomplete" id="partner_autocomplete" class="form-control input-sm" placeholder="Input Partner" value="<?=set_value('partner_autocomplete')?>"/>
				<input type="hidden" name="partner" id="partner" value="<?=set_value('partner')?>"/>
				
				<?=form_error('partner')?>
			</div>
		</div>
		
		<div class="res_cruise">
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('cabin_booked')?>:<?=mark_required()?></label>
				<div class="col-xs-5">
					<select name="cabin_booked" class="form-control input-sm">
						<option value="">----</option>				
						<?php foreach ($cabin_booked as $value) :?>
																	
							<option value="<?=$value?>" <?=set_select('cabin_booked', $value)?>><?=$value?></option>		
						
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
																	
							<option value="<?=$value?>" <?=set_select('cabin_booked', $value)?>><?=$value?></option>		
						
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
																	
							<option value="<?=$key?>" <?=set_select('type_of_visa', $key)?>><?=lang($value)?></option>		
						
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
																	
							<option value="<?=$key?>" <?=set_select('processing_time', $key)?>><?=lang($value)?></option>		
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('processing_time')?>
				</div>
			</div>
		</div>
		
		<div class="form-group res_flight">
			<label class="col-xs-4 control-label"><?=lang('pnr')?>:</label>
			<div class="col-xs-8">
				<input type="text" class="form-control input-sm" name="flight_pnr" id="flight_pnr" value="<?=set_value('flight_pnr')?>"/>
				<?=form_error('flight_pnr')?>
			</div>
		</div>
		
	</div>
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


	$('.price-cell').mask('000,000,000,000,000', {reverse: true});

	set_service_name_autocomplete();
    
</script>
