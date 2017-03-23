<form class="form-horizontal" method="POST" name="frm" role="form">
	
	<div>
		<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
		<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
	</div>
	<hr>
	
	<div class="row">
		
		<div class="col-xs-6">
		
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('customer')?>: <?=mark_required()?></label>
				<div class="col-xs-8">
					<input type="text" class="form-control input-sm" name="cus_autocomplete" id="cus_autocomplete" value="<?=set_value('cus_autocomplete')?>">
					<input type="hidden" id="customer" name="customer" value="<?=set_value('customer')?>">
					<?=form_error('customer')?>
				</div>		
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="customer_type"><?=lang('customer_type')?>: <?=mark_required()?></label>
				<div class="col-xs-8">
					<select id="customer_type" name="customer_type" class="form-control input-sm" style="width:50%">
										
						<option value="">----------</option>
										
						<?php foreach ($customer_types as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('customer_type', $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('customer_type')?>
				</div>		
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('request_type')?>: <?=mark_required()?></label>
				<div class="col-xs-8">
					<select name="request_type" class="form-control input-sm" style="width:50%">
										
						<option value="">----------</option>
										
						<?php foreach ($request_types as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('request_type', $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
		
					<?=form_error('request_type')?>
				</div>		
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="request_date"><?=lang('request_date')?>: <?=mark_required()?></label>
				<div class="col-xs-8"> 
					<div class="input-group" style="width:50%">
					  	<input type="text" class="form-control input-sm" name="request_date" id="request_date" value="<?=set_value('request_date')?>">
					  	<span class="input-group-addon" id="cal_request_date"><span class="fa fa-calendar"></span></span>
					</div>
					<?=form_error('request_date')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="booking_date"><?=lang('booking_date')?>:</label>
				<div class="col-xs-8"> 
					<div class="input-group" style="width:50%">
					  	<input type="text" class="form-control input-sm" name="booking_date" id="booking_date" value="<?=set_value('booking_date')?>">  	
					  	<span class="input-group-addon" id="cal_booking_date"><span class="fa fa-calendar"></span></span>
					</div>
		
					<?=form_error('booking_date')?>	
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="payment_method"><?=lang('payment_method')?>:</label>
				<div class="col-xs-8"> 
					<select name="payment_method" class="form-control input-sm" style="width:50%">
						<option value="">---</option>
						<?php foreach ($payment_methods as $key => $value):?>
							<option value="<?=$key?>" <?=set_select('payment_method', $key)?>><?=$value?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="cb_status"><?=lang('status')?>:</label>
				<div class="col-xs-8"> 
					<select id="cb_status" name="status" onchange="cb_status_change(this)" class="form-control input-sm">				
						<?php foreach ($status as $key => $value) :?>
																						
							<option value="<?=$key?>" <?=set_select('status', $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>	
				</div>
			</div>
			
			<div id="close_reason" class="form-group">
				<label class="col-xs-4 control-label" for="close_reason">				
					<?=lang('close_reason')?>: <?=mark_required()?>
				</label>
				
				<div class="col-xs-8"> 
					<select name="close_reason" class="form-control input-sm">
						<option value="">---------</option>				
						<?php foreach ($close_reason as $key => $value) :?>
																						
							<option value="<?=$key?>" <?=set_select('close_reason', $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
				
					<?=form_error('close_reason')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="sale"><?=lang('sale')?>:</label>
				<div class="col-xs-8"> 
					<?php if(is_administrator() || is_sale_manager()):?>
						<select name="sale" class="form-control input-sm">				
							<?php foreach ($sales as $value) :?>
								<?php if($value['status'] == STATUS_ACTIVE):?>														
									<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'])?>><?=$value['username']?></option>
								<?php endif;?>
							<?php endforeach ;?>				
						</select>
					<?php else:?>
						<label class="control-label"><?=get_username()?></label>
						<input type="hidden" name="sale" value="<?=get_user_id()?>">
					<?php endif;?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="description"><?=lang('description')?>:</label>
				<div class="col-xs-8">
					<textarea class="form-control" rows="3" name="description"><?=set_value('description')?></textarea>
					<?=form_error('close_reason')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="note"><?=lang('note')?>:</label>
				<div class="col-xs-8">
					<textarea class="form-control input-sm"  rows="3" name="note"><?=set_value('note')?></textarea>	
					<?=form_error('note')?>
				</div>
			</div>
		
		</div>
		
		<div class="col-xs-6">
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="adults"><?=lang('adults')?>:</label>
				<div class="col-xs-8"> 
					<select name="adults" id="adults" class="form-control input-sm" style="width:50%">
						<option value="">---</option>
						<?php for ($i = 1; $i <= ADULT_LIMIT; ++$i) :?>
			                    	<option value="<?=$i?>" <?=set_select('adults', $i)?>><?=$i?></option>
		                    <?php endfor;?>
		           	</select>
		            <?=form_error('adults')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="children"><?=lang('children')?>:</label>
				<div class="col-xs-8"> 
					<select name="children" id="children" class="form-control input-sm" style="width:50%">								
						<?php for ($i = 0; $i <= CHILDREN_LIMIT; ++$i) :?>
		                    <option value="<?=$i?>" <?=set_select('children', $i)?>><?=$i?></option>
		                    <?php endfor;?>
		            </select>
		            <?=form_error('children')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="infants"><?=lang('infants')?>:</label>
				<div class="col-xs-8"> 
					<select name="infants" id="infants" class="form-control input-sm" style="width:50%">								
						<?php for ($i = 0; $i <= CHILDREN_LIMIT; ++$i) :?>
		                    	<option value="<?=$i?>" <?=set_select('infants', $i)?>><?=$i?></option>
		                    <?php endfor;?>
		            </select>
		                
		            <?=form_error('infants')?>
				</div>
			</div>
			
			
			<div class="form-group">
				<input class="form-control input-sm" style="visibility:hidden;">
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('booking_site')?>: <?=mark_required()?></label>
				
				<div class="col-xs-8">
					<select name="booking_site" class="form-control input-sm" style="width:50%">
										
						<option value="">----------</option>
										
						<?php foreach ($booking_sites as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('booking_site', $key)?>><?=$value?></option>
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('booking_site')?>
				</div>			
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('source')?>:</label>
				<div class="col-xs-8">
					<select name="source" class="form-control input-sm" style="width:50%">
										
						<option value="">----------</option>
										
						<?php foreach ($booking_sources as $key=>$value) :?>
																						
							<option value="<?=$value['id']?>" <?=set_select('source', $value['id'])?>><?=$value['name']?></option>
						
						<?php endforeach ;?>				
					</select>
				</div>			
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('medium')?>:</label>
				<div class="col-xs-8">				
					<select name="medium" class="form-control input-sm" style="width:50%">
						
						<option value="">----------</option>
										
						<?php foreach ($mediums as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('medium', $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
				</div>			
			</div>

		</div>
		
	</div>
	
	<hr>
	<div>
		<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
		<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
	</div>
	
</form>

<script type="text/javascript">
$(document).ready(function() {

	set_customer_autocomplete();

	cb_status_change($('#cb_status'));

	$('#request_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#cal_request_date').click(function(){$('#request_date').focus()});

	$('#booking_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#cal_booking_date').click(function(){$('#booking_date').focus()});

	
});
</script>
