
<form method="POST" name="frm" enctype="multipart/form-data">

	<input type="hidden" name="action" value=''>
	
	<table class="no_border" width="100%" style="padding-left: 10px;">
		<tr>
			<?php			
				$send_to = $cb_email_info['customer_email'];				
				if ($is_edit_mode){					
					$send_to = $email['send_to'];
				}		
			?>
			
			<td width="150"><?=lang('send_to')?>: <?=mark_required()?></td>
						
			<td>
				<input type="text" size="50" name="send_to" maxlength="200" value="<?=set_value('send_to', $send_to)?>">
				<?=form_error('send_to')?>
			</td>
		</tr>
		
		<tr>
			<?php 
				 
				$departure = date('d M Y', strtotime($cb_email_info['main_service_start_date']));

				$start_date_txt = $cb_email_info['booking_type'] == RESERVATION_TYPE_HOTEL ? "Check-in" : "Departure";
				
				$subject = "Deposit: ". $cb_email_info['main_service_name']. " - ". $start_date_txt. " ". $departure;
				if ($is_edit_mode){					
					$subject = $email['subject'];
				}
			?>
		
			<td><?=lang('subject')?>: <?=mark_required()?></td>
						
			<td>
				<input type="text" size="92" name="subject" maxlength="250" value="<?=set_value('subject', $subject)?>">
				<?=form_error('subject')?>
			</td>
		</tr>
		
		<tr>
			<?php
				$title = $cb_email_info['title'] == 1 ? 'Mr. ' : 'Ms. ';							
				$customer_name = $title.$cb_email_info['full_name'];
							
				$dear = $customer_name;				
				if ($is_edit_mode){					
					$dear = $email['dear'];
				}		
			?>
			<td><?=lang('dear')?>: <?=mark_required()?></td>
						
			<td>
				<input type="text" size="50" name="dear" maxlength="100" value="<?=set_value('dear', $dear)?>">
				<?=form_error('dear')?>
			</td>
		</tr>
		
		<tr>
			<?php			
				$request = str_replace("<name>", '<b>'.$cb_email_info['sale_name'].'</b>', lang('greeting_content'));
				
				if ($cb_email_info['booking_site'] == 2){ // site halong bay junk boat
					$request = str_replace("<site>", '('.$booking_sites[$cb_email_info['booking_site']].')', $request);	
				} else {
					$request = str_replace("<site>", '', $request);
				}				
				
				if ($cb_email_info['booking_type'] == RESERVATION_TYPE_CRUISE_TOUR){
					
					$str_val = lang('service_checking');
					
					$str_val = str_replace("<cruise>", '<b>'.$cb_email_info['main_service_cruise_name'].'</b>', $str_val);
					
					$departure = date('d M Y', strtotime($cb_email_info['main_service_start_date']));
					
					$str_val = str_replace("<depart>", '<b>'.$departure.'</b>', $str_val);
					
					$request = $request. $str_val;
					
					//$request = $request. lang('greeting_end');
				}
				
				$request = $request . lang('would_like_confirm');
				
				if ($is_edit_mode){					
					$request = $email['request'];
				}		
			?>
			<td colspan="2">
				<?=lang('greeting')?>: <?=mark_required()?>	
				<textarea rows="5" cols="90" id="request" name="request"  maxlength="1000"><?=set_value('request', $request)?></textarea>
				<?=form_error('request')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">				
				<table class="border" cellpadding="0" cellspacing="0">
				
					<tr>
						
						<?php 
							$name_label = $cb_email_info['booking_type'] == RESERVATION_TYPE_HOTEL ? lang('hotel_name') : lang('tour_name');
						?>
						
						<td width="150"><b><?=$name_label?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="tour_name" maxlength="250" value="<?=set_value('tour_name', $is_edit_mode ? $email['tour_name'] : $cb_email_info['main_service_name'])?>">
							<?=form_error('tour_name')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$title = $cb_email_info['title'] == 1 ? 'Mr.' : 'Ms.';
							
							$guest_name = $title.$cb_email_info['full_name'];
							
							if ($is_edit_mode){
								$guest_name = $email['guest_name'];
							}
						?>
						<td><b><?=lang('guest_name')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="guest_name" maxlength="250" value="<?=set_value('guest_name', $guest_name)?>">
							<?=form_error('guest_name')?>
						</td>
					</tr>
					<tr>
						<?php 
							//$guest_number = $cb_email_info['adults'] + $cb_email_info['children'] + $cb_email_info['infants'];
							
							//$guest_number = $guest_number. " (";
							
							$guest_number = "";
							
							if ($cb_email_info['adults'] > 1){
								$guest_number = $guest_number. $cb_email_info['adults']. ' adults';
							} elseif($cb_email_info['adults'] > 0){
								$guest_number = $guest_number. $cb_email_info['adults']. ' adult';
							}
							
							if ($cb_email_info['children'] == 1){
								$guest_number = $guest_number. ' + '.$cb_email_info['children']. ' child';
							} elseif ($cb_email_info['children'] > 1){
								$guest_number = $guest_number. ' + '.$cb_email_info['children']. ' children';
							}
							
							if ($cb_email_info['infants'] == 1){
								$guest_number = $guest_number. ' + '.$cb_email_info['infants']. ' infant';
							} elseif ($cb_email_info['infants'] > 1){
								$guest_number = $guest_number. ' + '.$cb_email_info['infants']. ' infants';
							}
							
							//$guest_number = $guest_number. " )";
							
							
							if ($is_edit_mode){
								$guest_number = $email['guest_number'];
							}
							
						?>
						<td><b><?=lang('guest_number')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="guest_number" maxlength="100" value="<?=set_value('guest_number', $guest_number)?>">
							<?=form_error('guest_number')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$date_label = $cb_email_info['booking_type'] == RESERVATION_TYPE_HOTEL ? lang('check_in') : lang('start_date');
						?>
						<td><b><?=$date_label?>: <?=mark_required()?></b></td>
						<td>
							<input type="text" size="40" id="start_date" name="start_date" maxlength="100" value="<?=set_value('start_date', date('d M Y', strtotime($is_edit_mode ? $email['start_date'] : $cb_email_info['start_date'])))?>">
							<?=form_error('start_date')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$date_label = $cb_email_info['booking_type'] == RESERVATION_TYPE_HOTEL ? lang('check_out') : lang('end_date');
						?>
						<td><b><?=$date_label?>: <?=mark_required()?></b></td>
						<td>
							<input type="text" size="40" id="end_date" name="end_date" maxlength="100" value="<?=set_value('end_date', date('d M Y', strtotime($is_edit_mode ? $email['end_date'] :$cb_email_info['end_date'])))?>">
							<?=form_error('end_date')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$services = get_list_services_deposit($cb_email_info['services']);
							
							if ($is_edit_mode){
								$services = $email['services'];
							}
						?>
					
						<td><b><?=lang('services')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" id="services" name="services"  maxlength="1000"><?=set_value('services', $services)?></textarea>
							<?=form_error('services')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$total_price = "<font color='red'><b>".round($cb_email_info['selling_price'])." USD</b></font>";				
							if ($is_edit_mode){					
								$total_price = $email['total_price'];
							}
						
						?>	
						<td><b><?=lang('total_price')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="1" cols="70" id="total_price" name="total_price"><?=set_value('total_price', $total_price)?></textarea>
							<?=form_error('total_price')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$term_cond = '';				
							if ($is_edit_mode){					
								$term_cond = $email['term_cond'];
							}
						
						?>	
						<td><b><?=lang('term_cond')?>:</b></td>
						<td>
							<textarea rows="2" cols="70" id="term_cond" name="term_cond"><?=set_value('term_cond', $term_cond)?></textarea>
														
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$deposit = lang('default_deposit');				
							if ($is_edit_mode){					
								$deposit = $email['deposit'];
							}
						
						?>	
						<td><b><?=lang('deposit')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="2" cols="70" id="deposit" name="deposit"><?=set_value('deposit', $deposit)?></textarea>
							<?=form_error('deposit')?>							
						</td>
					</tr>
					
					
					<tr>
						<?php 
							
							$final_payment = lang('default_final_payment');				
							if ($is_edit_mode){					
								$final_payment = $email['final_payment'];
							}
						
						?>	
						<td><b><?=lang('final_payment')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="2" cols="70" id="final_payment" name="final_payment"><?=set_value('final_payment', $final_payment)?></textarea>
							<?=form_error('final_payment')?>							
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$payment_link = lang('default_payment_link');				
							if ($is_edit_mode){					
								$payment_link = $email['payment_link'];
							}
						
						?>	
						<td><b><?=lang('payment_link')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" id="payment_link" name="payment_link"><?=set_value('payment_link', $payment_link)?></textarea>
							<?=form_error('payment_link')?>								
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$payment_suggestion = lang('default_payment_suggestion');				
							if ($is_edit_mode){					
								$payment_suggestion = $email['payment_suggestion'];
							}
						
						?>	
						<td><b><?=lang('payment_suggestion')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" id="payment_suggestion" name="payment_suggestion"><?=set_value('payment_suggestion', $payment_suggestion)?></textarea>
							<?=form_error('payment_suggestion')?>								
						</td>
					</tr>
					
				</table>
			</td>
		</tr>

		
		<tr>
			<td colspan="2">
				<?php 
							
					$special_note = lang('special_note_depsit');				
					if ($is_edit_mode){					
						$special_note = $email['special_note'];
					}
				
				?>
				<?=lang('special_note')?>:					
				<textarea rows="2" cols="90" id="special_note" name="special_note"  maxlength="1000"><?=set_value('special_note', $special_note)?></textarea>
				<?=form_error('special_note')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<?=lang('best_regards')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<font color="#4f81bd"><?=str_replace("\n", "<br>",$cb_email_info['signature'])?></font>
			</td>
		</tr>
		
		<tr><td colspan="2"><hr size="1"></td></tr>
		
		<tr>
			<td colspan="2" align="right">
				
				<input type="button" onclick="send();" value="<?=lang('common_button_send')?>" name="btnSave" class="button">
				
				<input type="button" value="<?=lang('common_button_close')?>" name="btnClose" class="button" onclick="window.close();"/>
				
			</td>
		</tr>
					
	</table>	
</form>

<script language="javascript">

	bkLib.onDomLoaded(function() {nicEditors.allTextAreas();});
	
	function send() {

		nicEditors.findEditor('request').saveContent();
		nicEditors.findEditor('services').saveContent();
		nicEditors.findEditor('total_price').saveContent();
		nicEditors.findEditor('term_cond').saveContent();
		nicEditors.findEditor('deposit').saveContent();
		nicEditors.findEditor('final_payment').saveContent();
		nicEditors.findEditor('payment_link').saveContent();
		nicEditors.findEditor('payment_suggestion').saveContent();
		nicEditors.findEditor('special_note').saveContent();
		
		document.frm.action.value = "send";
		
		document.frm.submit();
	}


	$(document).ready(function() {

		<?php if (isset($sending_status)):?>

			<?php if($sending_status == 'ok'):?>
			
				window.close();
	
				window.opener.location.reload(true);

			<?php else:?>

			alert('There is a fatal error of sending email. Please give Mr.Khuyen $10 to resolve this problem');

			<?php endif;?>
		
		<?php endif;?>
	
		$(function() {
			$("#start_date").datepicker({showOn: 'button',dateFormat: 'dd M yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
		});

		$(function() {
			$("#end_date").datepicker({showOn: 'button',dateFormat: 'dd M yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
		});
		
	});
	
</script>
