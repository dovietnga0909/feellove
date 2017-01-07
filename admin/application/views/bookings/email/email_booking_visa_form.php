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
				 
				$subject = lang('subject_text_visa').' - '. lang('guest'). $guest_name . " - BestPrice Vietnam" ;
				if ($is_edit_mode){					
					$subject = $email['subject'];
				}
			?>
		
			<td><?=lang('subject')?>: <?=mark_required()?></td>
						
			<td><input type="text" size="92" name="subject" maxlength="250" value="<?=set_value('subject', $subject)?>">
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
				$request = lang('default_request_visa');				
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
			<?php 
				$guest_number = $sr_email_info['adults'] + $sr_email_info['children'];
				
				if (count($sr_email_info['visa_users']) > 0) $guest_number = count($sr_email_info['visa_users']);
				
				if ($is_edit_mode){	
					$guest_number = $email['guest_number'];	
				}
			?>
			<td colspan="2">
				<b><?=lang('guest_number_visa')?>:&nbsp;</b>
				<select name="guest_number" onchange="select_guest_number()" id="guest_number">
					<?php for ($i = 1; $i <= 10; $i++) {?>
						<option value="<?=$i?>" <?=set_select('guest_number', $i, $guest_number == $i ? TRUE:FALSE)?>><?=$i?></option>
					<?php }?>
				</select>
				<br>
				<table class="border" cellpadding="0" cellspacing="0" width="100%">
					<thead>
						<tr>
							
							<th align="center">#</th>
							
							<th align="center"><?=lang('name')?></th>
							
							<th align="center"><?=lang('gender')?></th>
							
							<th align="center"><?=lang('birth_day')?> <br>(dd-mm-yyyy)</th>
							
							<th align="center"><?=lang('nationality')?></th>
							
							<th align="center"><?=lang('passport')?></th>
							<!-- 
							<th align="center"><?=lang('passport_expiry')?><br>(dd-mm-yyyy)</th>
							 -->
						</tr>
					</thead>
					<tbody>	
						<?php 
							$visa_users = $is_edit_mode? $email['visa_users'] : $sr_email_info['visa_users'];
						?>			
						<tr id="customer_1">
							<td>1</td>
							
							<td>
								<input type="text" size="28" name="name_1" maxlength="250" value="<?=set_value('name_1', isset($visa_users[0])? $visa_users[0]['name']: $sr_email_info['full_name'])?>">
								<?=form_error('name_1')?>
							</td>
							
							<td>
								<select name="gender_1">
									<option value="">-------</option>
									<option value="<?=lang('male')?>" <?=set_select('gender_1', lang('male'), isset($visa_users[0])? $visa_users[0]['gender'] == lang('male') : $sr_email_info['title'] == 1)?>><?=lang('male')?></option>
									<option value="<?=lang('female')?>" <?=set_select('gender_1', lang('female'), isset($visa_users[0])? $visa_users[0]['gender'] == lang('female') :  $sr_email_info['title'] == 0)?>><?=lang('female')?></option>
								</select>
								<?=form_error('gender_1')?>
							</td>
							
							<td>
								<input type="text" size="10" id="birth_day_1" name="birth_day_1" maxlength="100" value="<?=set_value('birth_day_1', isset($visa_users[0])? date('d-m-Y',strtotime($visa_users[0]['birth_day'])):'')?>">
								<?=form_error('birth_day_1')?>
							</td>
							<td>
								<input type="text" size="10" name="country_1" maxlength="100" value="<?=set_value('country_1', isset($visa_users[0])? $visa_users[0]['nationality']: $country)?>">
								<?=form_error('country_1')?>
							</td>
							<td>
								<input type="text" size="10" name="passport_1" maxlength="100" value="<?=set_value('passport_1', isset($visa_users[0])? $visa_users[0]['passport']: '')?>">
								<?=form_error('passport_1')?>
							</td>
							
							<!-- 
							<td>
								<input type="text" size="10" id="passport_expiry_1" name="passport_expiry_1" maxlength="100" value="<?=set_value('passport_expiry_1', isset($visa_users[0]) && !empty($visa_users[0]['passport_expiry'])? date('d-m-Y', strtotime($visa_users[0]['passport_expiry'])):'')?>">
								<?=form_error('passport_expiry_1')?>
							</td>
							 -->
						</tr>
						
						<?php for ($i = 2; $i <= 10; $i++) {?>
							<tr id="customer_<?=$i?>" <?php if ($i > $guest_number):?> style="display: none;" <?php endif;?>>
								<td><?=$i?></td>							
								<td>
									<input type="text" size="28" name="name_<?=$i?>" maxlength="250" value="<?=set_value('name_'.$i, isset($visa_users[$i-1])? $visa_users[$i-1]['name'] : '')?>">
									<?=form_error('name_'.$i)?>
								</td>
								
								<td>
									<select name="gender_<?=$i?>">
										<option value="">-------</option>
										<option value="<?=lang('male')?>" <?=set_select('gender_'.$i, lang('male'), isset($visa_users[$i-1])? $visa_users[$i-1]['gender'] == lang('male') : false)?>><?=lang('male')?></option>
										<option value="<?=lang('female')?>" <?=set_select('gender_'.$i, lang('female'), isset($visa_users[$i-1])? $visa_users[$i-1]['gender'] == lang('female'): false)?>><?=lang('female')?></option>
									</select>
									<?=form_error('gender_'.$i)?>
								</td>
								
								<td>
									<input type="text" size="10" id="birth_day_<?=$i?>" name="birth_day_<?=$i?>" maxlength="100" value="<?=set_value('birth_day_'.$i, isset($visa_users[$i-1])? date('d-m-Y', strtotime($visa_users[$i-1]['birth_day'])) : '')?>">
									<?=form_error('birth_day_'.$i)?>
								</td>
								<td>
									<input type="text" size="10" name="country_<?=$i?>" maxlength="100" value="<?=set_value('country_'.$i, isset($visa_users[$i-1])? $visa_users[$i-1]['nationality'] : $country)?>">							
									<?=form_error('country_'.$i)?>
								</td>
								<td>
									<input type="text" size="10" name="passport_<?=$i?>" maxlength="100" value="<?=set_value('passport_'.$i, isset($visa_users[$i-1])? $visa_users[$i-1]['passport'] : '')?>">
									<?=form_error('passport_'.$i)?>
								</td>
								
								<!-- 
								<td>
									
									<input type="text" size="10" id="passport_expiry_<?=$i?>" name="passport_expiry_<?=$i?>" maxlength="100" value="<?=set_value('passport_expiry_'.$i, isset($visa_users[$i-1])&&!empty($visa_users[$i-1]['passport_expiry'])? date('d-m-Y', strtotime($visa_users[$i-1]['passport_expiry'])) : '')?>">
									<?=form_error('passport_expiry_'.$i)?>
								</td>
								 -->
							</tr>			
						<?php }?>
					
					</tbody>
					
				</table>
				
			</td>
		</tr>
		
		<tr>
			
			<td><b><?=lang('type_of_visa')?>:</b> <?=mark_required()?></td>
						
			<td>
				<select name="type_of_visa">
					<option value="">---------</option>
					<?php foreach ($visa_types as $key=>$value):?>
					
					
					<?php			
						$is_checked = false;
		
						$is_checked = $sr_email_info['type_of_visa'] == $key;
						
						if ($is_edit_mode){					
							$is_checked = $email['type_of_visa'] == $key;
						}		
					?>
			
					
					<option value="<?=$key?>" <?=set_select('type_of_visa', $key, $is_checked)?>><?=translate_text($value)?></option>
					
					<?php endforeach;?>
				</select>
				
				<?=form_error('type_of_visa')?>
			</td>
		</tr>
		
		<tr>
			
			<td><b><?=lang('processing_time')?>:</b> <?=mark_required()?></td>
						
			<td>
				<select name="processing_time">
					<option value="">---------</option>
					<?php foreach ($visa_processing_times as $key=>$value):?>
					
					
					<?php			
						$is_checked = false;
		
						$is_checked = $sr_email_info['processing_time'] == $key;
						
						if ($is_edit_mode){					
							$is_checked = $email['processing_time'] == $key;
						}		
					?>
			
					
					<option value="<?=$key?>" <?=set_select('processing_time', $key, $is_checked)?>><?=translate_text($value)?></option>
					
					<?php endforeach;?>
				</select>
				
				<?=form_error('processing_time')?>
			</td>
		</tr>
		
		<tr>
			<?php			
				$start_date = '';
				
				if ($sr_email_info['start_date'] != $sr_email_info['end_date']){
					
					$start_date = date('d-m-Y',strtotime($sr_email_info['start_date']));
										
				}
				
				if ($is_edit_mode){					
					$start_date = date('d-m-Y',strtotime($email['start_date']));
				}		
			?>
			<td style="width: 150px"><b><?=lang('comming_date')?>:</b> <?=mark_required()?></td>
						
			<td><input type="text" size="20" id="start_date" name="start_date" maxlength="100" value="<?=set_value('start_date', $start_date)?>">
				<?=form_error('start_date')?>
			</td>
		</tr>
		
		<tr>
			<?php 							
				$special_note = '';				
				if ($is_edit_mode){					
					$special_note = $email['special_note'];
				}			
			?>
			<td colspan="2">
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

	function select_guest_number(){

		var guest_number = $("#guest_number option:selected").val();
		
		for (var i = 1; i <= 10; i++){

			var customer_id = '#customer_' + i;

			if (i <= guest_number){
				$(customer_id).show();
			} else {
				$(customer_id).hide();
			}
		
		}
		
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
			$("#start_date").datepicker({showOn: 'button',dateFormat: 'dd-mm-yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true, changeMonth: true, changeYear: true});
		});
	
		<?php for ($i = 1; $i <= 10; $i++) {?>
				
			$(function() {
				$("#birth_day_<?=$i ?>").datepicker({showOn: 'button',dateFormat: 'dd-mm-yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true, changeMonth: true, changeYear: true});
			});
	
			$(function() {
				$("#passport_expiry_<?=$i ?>").datepicker({showOn: 'button',dateFormat: 'dd-mm-yy', buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true, changeMonth: true, changeYear: true});
			});

		<?php }?>
		
	});
	
</script>
