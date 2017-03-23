<div class="row">
	<div class="col-xs-2">
		<label><?=lang('1_payment')?>:</label>
		<input type="text" name="1_payment" class="form-control input-sm price-cell" value="<?=set_value('1_payment', number_format($sr['1_payment']))?>"/>				
		<?=form_error('1_payment')?>
	</div>
	
	<div class="col-xs-2">
		<label><?=lang('1_payment_due')?>:</label>
		<div class="input-group">
			<input type="text" name="1_payment_due" id="1_payment_due" class="form-control input-sm" value="<?=set_value('1_payment_due', bpv_format_date($sr['1_payment_due']))?>"/>
			<span class="input-group-addon" id="cal_1_payment_due"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
	
	<div class="col-xs-2">
		<label><?=lang('1_payment_date')?>:</label>
		<div class="input-group">
			<input type="text" name="1_payment_date" id="1_payment_date" class="form-control input-sm" value="<?=set_value('1_payment_date', bpv_format_date($sr['1_payment_date']))?>"/>
			<span class="input-group-addon" id="cal_1_payment_date"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
</div>

<div class="row">
	
	<div class="col-xs-2">
		<label><?=lang('2_payment')?>:</label>
		<input type="text" name="2_payment" class="form-control input-sm price-cell" value="<?=set_value('2_payment', number_format($sr['2_payment']))?>"/>				
		<?=form_error('2_payment')?>
	</div>
	
	<div class="col-xs-2">
		<label><?=lang('2_payment_due')?>:</label>
		<div class="input-group">
			<input type="text" name="2_payment_due" id="2_payment_due" class="form-control input-sm" value="<?=set_value('2_payment_due', bpv_format_date($sr['2_payment_due']))?>"/>
			<span class="input-group-addon" id="cal_2_payment_due"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
	
	<div class="col-xs-2">
		<label><?=lang('2_payment_date')?>:</label>
		<div class="input-group">
			<input type="text" name="2_payment_date" id="2_payment_date" class="form-control input-sm" value="<?=set_value('2_payment_date', bpv_format_date($sr['2_payment_date']))?>"/>
			<span class="input-group-addon" id="cal_2_payment_date"><span class="fa fa-calendar"></span></span>
		</div>
	</div>

</div>