<form class="form-horizontal" method="POST" name="frm" role="form">

	<p>
			<b>
				<a href="/admin/customers/edit/<?=$customer_booking['customer_id']?>" target="blank_">
					<?php if(isset($c_titles[$customer_booking['title']])):?>
						<?=(lang($c_titles[$customer_booking['title']]))?>
					<?php endif;?>
					<?=$customer_booking['customer_name']?>
				</a>
			</b>
			
			(Phone: <a href="tel:<?=$customer_booking['phone']?>"><?=$customer_booking['phone']?></a>, Email: <a href="mailto:<?=$customer_booking['email']?>"><?=$customer_booking['email']?></a>,
			IP Address: <?=$customer_booking['ip_address']?>)
			
			<a href="<?=site_url('bookings/create-sr/'.$customer_booking['id'])?>" type="button" class="btn btn-primary pull-right btn-xs"><?=lang('create_service_reservation')?></a>
	</p>
	
	<p>
		<b>Booking Time: </b> <?=bpv_format_date($customer_booking['date_created'], DATE_TIME_FORMAT)?>
		&nbsp; &nbsp;
		<b><?=lang('start_date')?>:</b> <?=bpv_format_date($customer_booking['start_date'], DATE_FORMAT)?> 
		&nbsp; &nbsp;
		<b><?=lang('end_date')?>:</b> <?=bpv_format_date($customer_booking['end_date'], DATE_FORMAT)?>
		
		<?php if(!empty($customer_booking['vnisc_booking_code'])):?>
			&nbsp; &nbsp;
			<b><?=lang('vnisc_booking_code')?>:</b> <?=$customer_booking['vnisc_booking_code']?>
		<?php endif;?> 
	</p>
	
	<div>
		<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
		<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
	</div>	
	
	<hr>
	
	<div class="row">
		<div class="col-xs-6">
			<div class="form-group">
				<label class="col-xs-4 control-label" for="customer_type"><?=lang('customer_type')?>: <?=mark_required()?></label>
				<div class="col-xs-8">
					<select id="customer_type" name="customer_type" class="form-control input-sm">
									
						<option value="">----------</option>
										
						<?php foreach ($customer_types as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('customer_type', $key, $customer_booking['customer_type'] == $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
			
					<?=form_error('customer_type')?>
				</div>		
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label"><?=lang('request_type')?>: <?=mark_required()?></label>
				<div class="col-xs-8">
					<select name="request_type" class="form-control input-sm">
								
						<option value="">----------</option>
										
						<?php foreach ($request_types as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('request_type', $key, $customer_booking['request_type'] == $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>
		
					<?=form_error('request_type')?>
				</div>				
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="request_date"><?=lang('request_date')?>: <?=mark_required()?></label>
				<div class="col-xs-5"> 
					<div class="input-group">
					  	<input type="text" class="form-control input-sm" name="request_date" id="request_date" value="<?=set_value('request_date', bpv_format_date($customer_booking['request_date'], DATE_FORMAT))?>">
					  	<span class="input-group-addon" id="cal_request_date"><span class="fa fa-calendar"></span></span>
					</div>
					<?=form_error('request_date')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="booking_date"><?=lang('booking_date')?>:</label>
				<div class="col-xs-5"> 
					<div class="input-group">
					  	<input type="text" class="form-control input-sm" name="booking_date" id="booking_date" value="<?=set_value('booking_date', bpv_format_date($customer_booking['booking_date'], DATE_FORMAT))?>">  	
					  	<span class="input-group-addon" id="cal_booking_date"><span class="fa fa-calendar"></span></span>
					</div>
		
					<?=form_error('booking_date')?>		
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="payment_method">Pay.Method:</label>
				<div class="col-xs-8"> 
					<select name="payment_method" class="form-control input-sm">
						<option value="">---</option>
						<?php foreach ($payment_methods as $key => $value):?>
							<option value="<?=$key?>" <?=set_select('payment_method', $key, $key==$customer_booking['payment_method'])?>><?=$value?></option>
						<?php endforeach;?>
					</select>
					<?=form_error('payment_method')?>	
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="cb_status"><?=lang('status')?>:</label>
				<div class="col-xs-8"> 
					<select id="cb_status" name="status" onchange="cb_status_change(this)" class="form-control input-sm">				
						<?php foreach ($status as $key => $value) :?>
																				
							<option value="<?=$key?>" <?=set_select('status', $key, $customer_booking['status'] == $key? TRUE: FALSE)?>><?=lang($value)?></option>
						
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
																						
							<option value="<?=$key?>" <?=set_select('close_reason', $key, $customer_booking['close_reason'] == $key? TRUE: FALSE)?>><?=lang($value)?></option>
						
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
								
								<?php if($value['status'] == STATUS_ACTIVE || $customer_booking['user_id'] == $value['id']):?>														
									<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'], $customer_booking['user_id'] == $value['id']? true: false)?>><?=$value['username']?></option>
								<?php endif;?>
							<?php endforeach ;?>				
						</select>
					<?php else:?>
						<label class="control-label"><?=$customer_booking['username']?></label>
						<input type="hidden" name="sale" value="<?=$customer_booking['user_id']?>">
					<?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-8 col-xs-offset-4">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="send_review" value="1" <?=set_checkbox('send_review',1, $customer_booking['send_review'] == 1)?>>
							&nbsp;<?=lang('send_review')?>
						</label>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="description"><?=lang('description')?>:</label>
				<div class="col-xs-8">
					<textarea class="form-control" rows="5" name="description"><?=set_value('description', $customer_booking['description'])?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="note"><?=lang('note')?>:</label>
				<div class="col-xs-8">
					<textarea class="form-control input-sm"  rows="8" name="note"><?=set_value('note', $customer_booking['note'])?></textarea>	
					<?=form_error('note')?>
				</div>
			</div>
			
			<?php if($customer_booking['lasted_invoice'] !== FALSE):?>
				<div class="form-group">
					<div class="col-xs-8 col-xs-offset-4">
						<label>Invoice Status: <?=get_invoice_status_text($customer_booking['lasted_invoice']['status'])?></label>
						<br>
						
						<?php 
							$invoice_link = get_invoice_link($customer_booking['lasted_invoice']['invoice_reference']);							
						?>
						Invoice Link: <a target="blank" href="<?=$invoice_link?>"><?=$invoice_link?></a>
					</div>
				</div>			
			<?php endif;?>
			
			
		
		</div>
		<div class="col-xs-6">
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="adults"><?=lang('adults')?>:</label>
				<div class="col-xs-4"> 
					<select name="adults" id="adults" class="form-control input-sm"">
						<option value="">---</option>
						<?php for ($i = 1; $i <= ADULT_LIMIT; ++$i) :?>
	                    	<option value="<?=$i?>" <?=set_select('adults', $i, $customer_booking['adults'] == $i? true: false)?>><?=$i?></option>
	                    <?php endfor;?>
		           	</select>
		            <?=form_error('adults')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="children"><?=lang('children')?>:</label>
				<div class="col-xs-4"> 
					<select name="children" id="children" class="form-control input-sm">								
						<?php for ($i = 0; $i <= CHILDREN_LIMIT; ++$i) :?>
	                  	  <option value="<?=$i?>" <?=set_select('children', $i, $customer_booking['children'] == $i? true: false)?>><?=$i?></option>
	                    <?php endfor;?>
	                </select>
		            </select>
		            <?=form_error('children')?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-xs-4 control-label" for="infants"><?=lang('infants')?>:</label>
				<div class="col-xs-4"> 
					<select name="infants" id="infants" class="form-control input-sm">								
						<?php for ($i = 0; $i <= CHILDREN_LIMIT; ++$i) :?>
	                    	<option value="<?=$i?>" <?=set_select('infants', $i, $customer_booking['infants'] == $i? true: false)?>><?=$i?></option>
	                    <?php endfor;?>
		            </select>
		                
		            <?=form_error('infants')?>
				</div>
			</div>
			
			<hr>
			
			<div class="form-group">
				<div class="col-xs-1"><label><?=lang('net_price')?>:</label></div>
				<div class="col-xs-3">
					<?=number_format($customer_booking['net_price'])?>
				</div>
				<label class="col-xs-2"><?=lang('selling_price')?>:</label>	
				<div class="col-xs-3">
					<?=number_format($customer_booking['selling_price'])?>	
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-xs-4">
					<label for="onepay"><?=lang('onepay')?>:</label> 
					<input type="text" class="form-control input-sm price-cell" name="onepay" id="onepay" value="<?=set_value('onepay', number_format($customer_booking['onepay']))?>">
					<?=form_error('onepay')?>
				</div>
				
				<div class="col-xs-4">
					<label for="cash"><?=lang('cash')?>:</label> 
					<input type="text" class="form-control input-sm price-cell" name="cash" id="cash" value="<?=set_value('cash', number_format($customer_booking['cash']))?>">
					<?=form_error('cash')?>
				</div>
				
				<div class="col-xs-4">
					<label for="pos"><?=lang('pos')?>:</label> 
					<input type="text" class="form-control input-sm price-cell" name="pos" id="pos" value="<?=set_value('pos', number_format($customer_booking['pos']))?>">
					<?=form_error('pos')?>
				</div>
			</div>
		
			
			<?php if (is_administrator() || is_accounting()) :?>
				<div class="form-group">
					<label class="col-xs-4 control-label" for="approve_status"><?=lang('approve_status')?>:</label>
					<div class="col-xs-4">
						
						<select name="approve_status" class="form-control input-sm">				
							<?php foreach ($approve_status as $key => $value) :?>
																							
								<option value="<?=$key?>" <?=set_select('approve_status', $key, $customer_booking['approve_status'] == $key? true: false)?>><?=lang($value)?></option>
							
							<?php endforeach ;?>				
						</select>
					</div>	
				</div>	
				
				<div class="form-group">
					<label class="col-xs-4 control-label" for="approve_note"><?=lang('approve_note')?>:</label>
					<div class="col-xs-8">				
						<input type="text" class="form-control input-sm" name="approve_note" value="<?=set_value('approve_note', $customer_booking['approve_note'])?>">
					</div>
				</div>		
			<?php endif;?>
			
			<hr>
			
			<div class="form-group">
			
				
				<div class="col-xs-4">
					<label><?=lang('booking_site')?>: <?=mark_required()?></label>
					<select name="booking_site" class="form-control input-sm">
										
						<option value="">----------</option>
										
						<?php foreach ($booking_sites as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('booking_site', $key, $customer_booking['booking_site'] == $key)?>><?=$value?></option>
						
						<?php endforeach ;?>				
					</select>
					
					<?=form_error('booking_site')?>			
				</div>
				
				<div class="col-xs-4">
					<label><?=lang('source')?>:</label>
					<select name="source" class="form-control input-sm">
										
						<option value="">----------</option>
										
						<?php foreach ($booking_sources as $key=>$value) :?>
																						
							<option value="<?=$value['id']?>" <?=set_select('source', $value['id'], $customer_booking['booking_source_id'] == $value['id'])?>><?=$value['name']?></option>
						
						<?php endforeach ;?>				
					</select>			
				</div>
				
				<div class="col-xs-4">
					<label><?=lang('medium')?>:</label>
									
					<select name="medium" class="form-control input-sm">
						
						<option value="">----------</option>
										
						<?php foreach ($mediums as $key=>$value) :?>
																						
							<option value="<?=$key?>" <?=set_select('medium', $key, $customer_booking['medium'] == $key)?>><?=lang($value)?></option>
						
						<?php endforeach ;?>				
					</select>			
				</div>
			</div>
			
			
			<?php if(is_administrator()):?>
			<div class="form-group">
				<div class="col-xs-4"><?=lang('visit_type')?>:</div>
				<div class="col-xs-8">
					<?php if($customer_booking['times_visited'] > 1):?>
						<?=lang('return_visitor')?>
					<?php elseif($customer_booking['times_visited'] > 0):?>
						<?=lang('new_visitor')?>
					<?php endif;?>
				</div>
			</div>
			
			
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('keyword')?>:</div>
				<div class="col-xs-8"><?=$customer_booking['keyword']?></div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('landing_page')?>:</div>
				<div class="col-xs-8"><?=$customer_booking['landing_page']?></div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('campaign')?>:</div>
				<div class="col-xs-8"><?=$customer_booking['campaign']?></div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('ad_content')?>:</div>
				<div class="col-xs-8"><?=$customer_booking['ad_content']?></div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('date_first_visit')?>:</div>
				<div class="col-xs-8">
					<?php if(isset($customer_booking['date_first_visit'])):?>
							
						<?=date('d-m-Y H:i',strtotime($customer_booking['date_first_visit']))?>
					
					<?php endif;?>	
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('date_previous_visit')?>:</div>
				<div class="col-xs-8">
					<?php if(isset($customer_booking['date_previous_visit'])):?>
							
						<?=date('d-m-Y H:i',strtotime($customer_booking['date_previous_visit']))?>
					
					<?php endif;?>		
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('date_current_visit')?>:</div>
				<div class="col-xs-8">
					<?php if(isset($customer_booking['date_current_visit'])):?>
							
						<?=date('d-m-Y H:i',strtotime($customer_booking['date_current_visit']))?>
					
					<?php endif;?>		
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('times_visited')?>:</div>
				<div class="col-xs-8">
					<?php if($customer_booking['times_visited'] > 0):?>	
							
						<?=$customer_booking['times_visited']?>					
					
					<?php endif;?>	
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-xs-4"><?=lang('page_views')?>:</div>
				<div class="col-xs-8">
					<?php if($customer_booking['current_pages_viewed'] > 0):?>	
							
						<?=$customer_booking['current_pages_viewed']?> (current visited)					
					
					<?php endif;?>		
				</div>
			</div>
			
			<?php endif;?>
		
		</div>
	</div>
	
	<!-- 

	<div class="row">
		<div class="col-xs-6">
			
					
		</div>
		
		
		<div class="col-xs-6">
			<?php if(!empty($customer_booking['flight_users'])):?>
			<label>Flight Passengers:</label><br>
		
			
				
				<?php foreach ($customer_booking['flight_users'] as $key => $f_user):?>
							
					<?php 
						$a_txt = $f_user['type'] == 1? 'adult' : ($f_user['type'] == 2? 'child' : 'infant');
					?>
					
					<?=($key + 1)?>. <b><?=$f_user['first_name']?> <?=(!empty($f_user['middle_name']) ? $f_user['middle_name'] : '')?>, <?=$f_user['last_name']?></b>
					
					(
						<?=$a_txt?>, <?=$f_user['gender'] == 1? 'male':'female'?><?=is_null($f_user['birth_day'])? '' : ', '.date(DATE_FORMAT, strtotime($f_user['birth_day']))?>
						<?=!empty($f_user['nationality'])? ', QT: '.$f_user['nationality'] : ''?>
						<?=!empty($f_user['passport'])? ', Passort: '.$f_user['passport'] : ''?>
						<?=!empty($f_user['passportexp'])? ', Passport-Expired: '.date(DATE_FORMAT, strtotime($f_user['passportexp'])) : ''?>
					)
					
					<br>
					
				<?php endforeach;?>					
				
					
			<br>
			<?php endif;?>		
			
		</div>
		
		
	</div>
	
	-->
	<hr>
	<div>
		<button type="submit" name="action"  value="<?=ACTION_SAVE?>" class="btn btn-primary btn-lg"><?=lang('btn_save')?></button>
		<button type="submit" name="action"  value="<?=ACTION_CANCEL?>" class="btn btn-default"><?=lang('btn_cancel')?></button>
	</div>	
	
</form>

<script type="text/javascript">
$(document).ready(function() {

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

	$('.price-cell').mask('000,000,000,000,000', {reverse: true});
});
</script>
