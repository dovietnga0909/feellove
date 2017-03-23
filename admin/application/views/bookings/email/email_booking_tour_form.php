<form method="POST" name="frm" enctype="multipart/form-data">

	<input type="hidden" name="action" value=''>
	
	<table class="no_border" width="100%" style="padding-left: 10px;">
		<tr>
			<?php			
				$send_to = $sr_email_info['email_partner'];				
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
				$title = $sr_email_info['title'] == 1 ? 'Mr.' : 'Ms.';							
				$guest_name = $title.$sr_email_info['full_name']; 
				$departure = date('d M Y', strtotime($sr_email_info['start_date']));
				
				$subject = "Booking: ". $sr_email_info['service_name']. " - ". $departure. " - ". $guest_name ." - BestPrice Vietnam";
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
				$dear = '';				
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
				$request = lang('default_request');				
				if ($is_edit_mode){					
					$request = $email['request'];
				}		
			?>
			<td colspan="2">
				<?=lang('request')?>: <?=mark_required()?>	
				<textarea rows="2" cols="90" name="request"  maxlength="1000"><?=set_value('request', $request)?></textarea>
				<?=form_error('request')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<table class="border" cellpadding="0" cellspacing="0">
				
					<tr>
						<td width="150"><b><?=lang('tour_name')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="tour_name" maxlength="250" value="<?=set_value('tour_name', $is_edit_mode ? $email['tour_name'] : $sr_email_info['service_name'])?>">
							<?=form_error('tour_name')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$title = $sr_email_info['title'] == 1 ? 'Mr.' : 'Ms.';
							
							$guest_name = $title.$sr_email_info['full_name'];
							
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
							//$guest_number = $sr_email_info['adults'] + $sr_email_info['children'] + $sr_email_info['infants'];
							
							//$guest_number = $guest_number. " (";
							
							$guest_number = "";
							
							if ($sr_email_info['adults'] > 1){
								$guest_number = $guest_number. $sr_email_info['adults']. ' adults';
							} else {
								$guest_number = $guest_number. $sr_email_info['adults']. ' adult';
							}
							
							if ($sr_email_info['children'] == 1){
								$guest_number = $guest_number. ' + '.$sr_email_info['children']. ' child';
							} elseif ($sr_email_info['children'] > 1){
								$guest_number = $guest_number. ' + '.$sr_email_info['children']. ' children';
							}
							
							if ($sr_email_info['infants'] == 1){
								$guest_number = $guest_number. ' + '.$sr_email_info['infants']. ' infant';
							} elseif ($sr_email_info['infants'] > 1){
								$guest_number = $guest_number. ' + '.$sr_email_info['infants']. ' infants';
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
						<td><b><?=lang('start_date')?>: <?=mark_required()?></b></td>
						<td>
							<input type="text" size="40" id="start_date" name="start_date" maxlength="100" value="<?=set_value('start_date', date('d M Y', strtotime($is_edit_mode ? $email['start_date'] : $sr_email_info['start_date'])))?>">
							<?=form_error('start_date')?>
						</td>
					</tr>
					
					<tr>
						<td><b><?=lang('end_date')?>: <?=mark_required()?></b></td>
						<td>
							<input type="text" size="40" id="end_date" name="end_date" maxlength="100" value="<?=set_value('end_date', date('d M Y', strtotime($is_edit_mode ? $email['end_date'] :$sr_email_info['end_date'])))?>">
							<?=form_error('end_date')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							$services = $sr_email_info['booking_services'] != ''? $sr_email_info['booking_services'] : $sr_email_info['description'];
							
							if (count($sr_email_info['related_services']) > 0){
								
								foreach ($sr_email_info['related_services'] as $value){
									$services = $services."\n".	$value['service_name'];
								}	
							}
							
							if ($is_edit_mode){
								$services = $email['services'];
							}
						?>
					
						<td><b><?=lang('services')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" name="services"  maxlength="1000"><?=set_value('services', $services)?></textarea>
							<?=form_error('services')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$total_price = lang('default_total_price');				
							if ($is_edit_mode){					
								$total_price = $email['total_price'];
							}
						
						?>	
						<td><b><?=lang('total_price')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="total_price" maxlength="200" value="<?=set_value('total_price', $total_price)?>">
							<?=form_error('total_price')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$guest_information = lang('default_guest_information');				
							if ($is_edit_mode){					
								$guest_information = $email['guest_information'];
							}
						
						?>	
						<td><b><?=lang('guest_information')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="guest_information" maxlength="250" value="<?=set_value('guest_information', $guest_information)?>">
							<?=form_error('guest_information')?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$pick_up = lang('default_pick_up');				
							if ($is_edit_mode){					
								$pick_up = $email['pick_up'];
							}
						
						?>	
						<td><b><?=lang('pick_up')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="pick_up" maxlength="500" value="<?=set_value('pick_up', $pick_up)?>">
							<?=form_error('pick_up')?>
						</td>
					</tr>
					
					<tr>
						<tr>
						<td><b><?=lang('special_request')?>:</b></td>
						<td>
							<textarea rows="4" cols="70" name="special_request"  maxlength="1000"><?=set_value('special_request', $is_edit_mode? $email['special_request']: $sr_email_info['special_request'])?></textarea>
							<?=form_error('special_request')?>
						</td>
					</tr>
					</tr>
				</table>
			</td>
		</tr>

		
		<tr>
			<td colspan="2">
				<?php 
							
					$special_note = '';				
					if ($is_edit_mode){					
						$special_note = $email['special_note'];
					}
				
				?>
				<?=lang('special_note')?>:					
				<textarea rows="2" cols="90" name="special_note"  maxlength="1000"><?=set_value('special_note', $special_note)?></textarea>
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
				<font color="#4f81bd"><?=str_replace("\n", "<br>",$sr_email_info['signature'])?></font>
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

	function send() {
		
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
