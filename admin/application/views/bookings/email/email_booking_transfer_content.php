<html>
<head>
	
</head>

<body style="font-family: Arial; font-size:12px;">
	
	<?php 
		$email = $emails[0];
	?>
	
	<table style="font-family: Arial; font-size:12px;" width="750">
		<tr>
			<td colspan="2">
				<span><?=lang('dear')?>&nbsp;<?=$email['dear']?>,</span><br>
				<span><?=$email['request']?></span>
				<br><br>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				
				<?php foreach ($emails as $key=>$value):?>
				
				<?php if(count($emails) > 1):?>
					<b><?=lang('transfer').($key + 1)?>:</b><br>
				<?php endif;?>
				
				<table cellpadding="0" cellspacing="0" width="100%" style="font-family: Arial; font-size:12px;border-top: 1px solid #7BA0CD;border-left: 1px solid #7BA0CD;empty-cells: show;">
				
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;" width="150"><b><?=lang('route')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$value['route']?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('car_type')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							
							<?=$value['car']?>&nbsp;<?=lang('seat')?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('guest_name_transfer')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$value['guest_name']?>
						</td>
					</tr>
					<tr>
						
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('guest_number_transfer')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$value['guest_number']?>
						</td>
					</tr>
					
					<?php if ($value['flight_name'] != ''):?>
						<tr>
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('flight_name')?>:</b></td>
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=$value['flight_name']?>
							</td>
						</tr>
					<?php endif;?>
					
					<?php if ($value['tour_name'] != ''):?>
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('tour')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$value['tour_name']?>
						</td>
					</tr>
					<?php endif;?>
					
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('pick_up_time')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<b><?=lang('hour')?>:</b>&nbsp;<?=$value['pick_up_hour']. ":".$value['pick_up_minute']?>&nbsp;&nbsp;&nbsp;&nbsp;
							<b><?=lang('day')?>:&nbsp;</b><?=date('d-m-Y', strtotime($value['start_date']))?>
						</td>
					</tr>
					
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('pick_up_transfer')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=str_replace("\n", "<br>",$value['pick_up'])?>
						</td>
					</tr>
					
					<?php if ($value['end_date'] != ''):?>
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('drop_off_time')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?php if($value['drop_off_hour'] != '' && $value['drop_off_minute'] != ''):?>
								<b><?=lang('hour')?>:</b>&nbsp;<?=$value['drop_off_hour']. ":".$value['drop_off_minute']?>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php endif;?>
							<b><?=lang('day')?>:&nbsp;</b><?=date('d-m-Y', strtotime($value['end_date']))?>
						</td>
					</tr>
					
					<?php endif;?>
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('drop_off_transfer')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=str_replace("\n", "<br>",$value['drop_off'])?>
						</td>
					</tr>
					<?php if ($value['special_request'] != ''):?>
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('special_request_transfer')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=str_replace("\n", "<br>",$value['special_request'])?>
						</td>
					</tr>
					<?php endif;?>
					
				</table>
				
				<?php if(count($emails) > 1):?>
					<br>
				<?php endif;?>
				
				<?php endforeach;?>
			</td>
		</tr>

		<?php if ($email['special_note'] != ''):?>
		<tr>
			<td colspan="2">
				<br>
				<?=$email['special_note']?>
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<td colspan="2">
				<br>
				<?=lang('best_regards')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<font color="#4f81bd"><?=str_replace("\n", "<br>",$email['signature'])?></font>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<img src="cid:bestpricevn-logo.png"/>
			</td>
		</tr>
		
		<?php if(isset($action) && $action == "view"):?>
		
		<tr>
			<td colspan="2" align="right">
				<span>Last sent: <?=date(DATE_TIME_FORMAT, strtotime($email['send_date']))?></span>
				<hr size="1">
			</td>
		</tr>
		
		<tr>
			<td colspan="2" align="right">
				<form method="POST" name="frm" enctype="multipart/form-data">
					<input type="hidden" name="action" value="edit">
					<input type="button" onclick="edit();" value="<?=lang('common_button_edit')?>" name="btnSave" class="button">
					<input type="button" value="<?=lang('common_button_close')?>" name="btnClose" class="button" onclick="window.close();"/>
				</form>
			</td>
		</tr>
		
		<script language="javascript">

			function edit() {
				
				document.frm.submit();
			}
			
		</script>
		
		<?php endif;?>
					
	</table>	
</body>
</html>

