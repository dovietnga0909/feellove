<form method="POST" name="frm" enctype="multipart/form-data">

	<input type="hidden" name="action" value=''>
	
	<?php 
		$sr_email_info = $sr_email_infos[0];
		$email = $sr_email_info['email'];
	?>
	
	<table class="no_border" width="100%" style="padding-left: 10px;">
		<tr>
			<?php			
				$send_to = $sr_email_info['email_partner'];					
				if ($is_edit_mode){					
					$send_to = $email['send_to'];
				}		
			?>
			
			<td width="150"><?=lang('send_to')?>: <?=mark_required()?></td>
						
			<td><input type="text" size="50" name="send_to" maxlength="200" value="<?=set_value('send_to', $send_to)?>">
					<?=form_error('send_to')?>
			</td>
		</tr>
		
		<tr>
			<?php 
				
				$title = $sr_email_info['title'] == 1 ? 'Mr.' : 'Ms.';
				$guest_name = $title.$sr_email_info['full_name'];
				
				$departure = date('d-m-Y', strtotime($sr_email_info['start_date']));
							
				$subject = lang('subject_text_transfer'). $departure. " - ".$guest_name. " - BestPrice Vietnam";
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
			<td><?=lang('dear')?>: <?=mark_required()?></td>
						
			<td>
				<?php			
					$dear = '';				
					if ($is_edit_mode){					
						$dear = $email['dear'];
					}		
				?>
				<input type="text" size="50" name="dear" maxlength="100" value="<?=set_value('dear', $dear)?>">
				<?=form_error('dear')?>
			</td>
		</tr>
		
		<tr>
			<?php			
				$request = count($sr_email_infos) > 1 ? lang('default_request_transfer_included') : lang('default_request_transfer');				
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
				
				<?php foreach ($sr_email_infos as $key=>$sr_email_info):?>
					
					<?php 
						$email = $sr_email_info['email'];
						
						$name_surfix = "_".$key;
						
					?>
				
				<?php if(count($sr_email_infos) > 1):?>
					<b><?=lang('transfer').($key + 1)?>:</b><br>
				<?php endif;?>	
						
				<table class="border" cellpadding="0" cellspacing="0">				
					<tr>
					
						<?php			
							$route = $sr_email_info['service_name'];				
							if ($is_edit_mode){					
								$route = $email['route'];
							}		
						?>
						
						<td width="150"><b><?=lang('route')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="route<?=$name_surfix?>" maxlength="200" value="<?=set_value('route'.$name_surfix, $route)?>">
							<?=form_error('route'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<td width="150"><b><?=lang('car_type')?>:</b> <?=mark_required()?></td>
						<td>
							
							<select name="car<?=$name_surfix?>">
							
								<option value="">---------</option>
								
								<?php foreach ($transfer_types as $key=>$value):?>
								
									<option value="<?=$key?>" <?=set_select('car'.$name_surfix, $key, $is_edit_mode && $email['car'] == $key)?>><?=translate_text($value)?></option>
								
								<?php endforeach;?>
																
							</select>
							
							<?=form_error('car'.$name_surfix)?>
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
						<td><b><?=lang('guest_name_transfer')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="guest_name<?=$name_surfix?>" maxlength="250" value="<?=set_value('guest_name'.$name_surfix, $guest_name)?>">
							<?=form_error('guest_name'.$name_surfix)?>
						</td>
					</tr>
					<tr>
						<?php 
							//$guest_number = $sr_email_info['adults'] + $sr_email_info['children'] + $sr_email_info['infants'];
							
							//$guest_number = $guest_number. " (";
							
							$guest_number = "";
							
							$guest_number = $guest_number. $sr_email_info['adults']. ' ' .lang('adults');
							
							if ($sr_email_info['children'] > 0){
								$guest_number = $guest_number. ' + '.$sr_email_info['children']. ' ' .lang('children');
							} 
							
							if ($sr_email_info['infants'] >0){
								$guest_number = $guest_number. ' + '.$sr_email_info['infants']. ' ' .lang('infants');
							} 
							
							//$guest_number = $guest_number. " )";
							
							if ($is_edit_mode){
								$guest_number = $email['guest_number'];
							}
							
						?>
						<td><b><?=lang('guest_number_transfer')?>:</b> <?=mark_required()?></td>
						<td>
							<input type="text" size="92" name="guest_number<?=$name_surfix?>" maxlength="100" value="<?=set_value('guest_number'.$name_surfix, $guest_number)?>">
							<?=form_error('guest_number'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$flight_name = '';				
							if ($is_edit_mode){					
								$flight_name = $email['flight_name'];
							}
						
						?>	
						<td><b><?=lang('flight_name')?>:</b></td>
						<td>
							<input type="text" size="92" name="flight_name<?=$name_surfix?>" maxlength="250" value="<?=set_value('flight_name'.$name_surfix, $flight_name)?>">
							<?=form_error('flight_name'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<?php 
							
							$tour_name = '';				
							if ($is_edit_mode){					
								$tour_name = $email['tour_name'];
							}
						
						?>	
						
						<td><b><?=lang('tour')?>:</b></td>
						<td>
							<select name="tour_name<?=$name_surfix?>">
							
								<option value="">----------------------------------</option>
								
								<?php foreach ($tours as $value):?>
								
									<option value="<?=$value['service_name']?>" <?=set_select('tour_name'.$name_surfix, $value['service_name'], $is_edit_mode && $email['tour_name'] == $value['service_name'])?>><?=$value['service_name']?></option>
								
								<?php endforeach;?>
																
							</select>
							
							<?=form_error('tour_name'.$name_surfix)?>
						</td>
					</tr>
					
					
					<tr>
						<?php 							
											
						?>						
						<td><b><?=lang('pick_up_time')?>:</b> <?=mark_required()?></td>
						<td>
							<b><?=lang('hour')?>:</b> <?=mark_required()?>&nbsp;
							<select name="pick_up_hour<?=$name_surfix?>">
								<option value="">----</option>
								<?php foreach ($hours['hours'] as $value):?>
									<option value="<?=$value?>" <?=set_select('pick_up_hour'.$name_surfix, $value, $is_edit_mode && strval($email['pick_up_hour']) == strval($value))?>><?=$value?></option>
								<?php endforeach;?>
							</select>
							:
							<select name="pick_up_minute<?=$name_surfix?>">
								<option value="">----</option>
								<?php foreach ($hours['minutes'] as $value):?>
									<option value="<?=$value?>" <?=set_select('pick_up_minute'.$name_surfix, $value, $is_edit_mode && strval($email['pick_up_minute']) == strval($value))?>><?=$value?></option>
								<?php endforeach;?>
							</select>
							
							&nbsp;&nbsp;&nbsp;&nbsp;
							<b><?=lang('day')?>:</b> <?=mark_required()?>&nbsp;
							<input type="text" size="20" id="start_date<?=$name_surfix?>" name="start_date<?=$name_surfix?>" maxlength="100" value="<?=set_value('start_date'.$name_surfix, date('d-m-Y', strtotime($is_edit_mode ? $email['start_date'] : $sr_email_info['start_date'])))?>">
							
							<?=form_error('pick_up_hour'.$name_surfix)?>
							<?=form_error('pick_up_minute'.$name_surfix)?>
							<?=form_error('start_date'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<?php 							
							$pick_up = '';				
							if ($is_edit_mode){					
								$pick_up = $email['pick_up'];
							}						
						?>						
						<td><b><?=lang('pick_up_transfer')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" name="pick_up<?=$name_surfix?>"  maxlength="500"><?=set_value('pick_up'.$name_surfix, $pick_up)?></textarea>
							<?=form_error('pick_up'.$name_surfix)?>
						</td>
					</tr>
					
					
					<tr>
						<?php 							
											
						?>						
						<td><b><?=lang('drop_off_time')?>:</b></td>
						<td>
							<b><?=lang('hour')?>:</b> <span style="visibility: hidden;"><?=mark_required()?></span>&nbsp;
							<select name="drop_off_hour<?=$name_surfix?>">
								<option value="">----</option>
								<?php foreach ($hours['hours'] as $value):?>
									<option value="<?=$value?>" <?=set_select('drop_off_hour'.$name_surfix, $value, $is_edit_mode && strval($email['drop_off_hour'])== strval($value))?>><?=$value?></option>
								<?php endforeach;?>
							</select>							
							:
							<select name="drop_off_minute<?=$name_surfix?>">
								<option value="">----</option>
								<?php foreach ($hours['minutes'] as $value):?>
									<option value="<?=$value?>" <?=set_select('drop_off_minute'.$name_surfix, $value, $is_edit_mode && strval($email['drop_off_minute']) == strval($value))?>><?=$value?></option>
								<?php endforeach;?>
							</select>
							
							&nbsp;&nbsp;&nbsp;&nbsp;
							
							<?php 
								
								$end_date = $is_edit_mode ? $email['end_date'] : $sr_email_info['end_date'];
								
								if ($end_date != ''){
									
									$end_date = date('d-m-Y', strtotime($end_date));
								}
							
							?>
							
							<b><?=lang('day')?>:</b>&nbsp;<span style="visibility: hidden;"><?=mark_required()?></span>
							<input type="text" size="20" id="end_date<?=$name_surfix?>" name="end_date<?=$name_surfix?>" maxlength="100" value="<?=set_value('end_date'.$name_surfix, $end_date)?>">
							
							<?=form_error('drop_off_hour'.$name_surfix)?>
							<?=form_error('drop_off_minute'.$name_surfix)?>
							<?=form_error('end_date'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<?php 							
							$drop_off = '';				
							if ($is_edit_mode){					
								$drop_off = $email['drop_off'];
							}						
						?>						
						<td><b><?=lang('drop_off_transfer')?>:</b> <?=mark_required()?></td>
						<td>
							<textarea rows="4" cols="70" name="drop_off<?=$name_surfix?>"  maxlength="500"><?=set_value('drop_off'.$name_surfix, $drop_off)?></textarea>
							<?=form_error('drop_off'.$name_surfix)?>
						</td>
					</tr>
					
					<tr>
						<td><b><?=lang('special_request_transfer')?>:</b></td>
						<td>
							<textarea rows="4" cols="70" name="special_request<?=$name_surfix?>"  maxlength="1000"><?=set_value('special_request'.$name_surfix, $is_edit_mode? $email['special_request']: '')?></textarea>
							<?=form_error('special_request'.$name_surfix)?>
						</td>
					</tr>
					
				</table>
				<?php if(count($sr_email_infos) > 1):?>
					<br>
				<?php endif;?>
				<?php endforeach;?>
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

		<?php foreach ($sr_email_infos as $key=>$sr_email_info):?>
		
			<?php 
				$name_surfix = "_".$key;
			?>
		
		
			$(function() {
				$("#start_date<?=$name_surfix?>").datepicker({showOn: 'button',dateFormat: 'dd-mm-yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			});
	
			$(function() {
				$("#end_date<?=$name_surfix?>").datepicker({showOn: 'button',dateFormat: 'dd-mm-yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			});

		<?php endforeach;?>

	});
</script>
