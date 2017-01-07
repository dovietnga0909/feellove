<html>

<body style="font-family: Arial,sans-serif; font-size:12px;">
	
	<table width="750" style="font-family: Arial,sans-serif; font-size:12px;">
		<tr>
			<td colspan="2" style="font-size:12px">
				<span><?=lang('dear')?>&nbsp;<?=$dear?>,</span><br><br>
				<span><?=$request?></span>
				<br>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" width="100%" style="font-family: Arial,sans-serif; font-size:12px; border-top: 1px solid #7BA0CD;border-left: 1px solid #7BA0CD;empty-cells: show;">
					
					<?php 
						$name_label = $booking_type == RESERVATION_TYPE_HOTEL ? lang('hotel_name') : lang('tour_name');
					?>
						
					<tr>
						<td width="150" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=$name_label?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$tour_name?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('guest_name')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$guest_name?>
						</td>
					</tr>
					<tr>
						
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('guest_number')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$guest_number?>
						</td>
					</tr>
					
						<?php 
							$date_label = $booking_type == RESERVATION_TYPE_HOTEL ? lang('check_in') : lang('start_date');
						?>
						
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=$date_label?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=date('d M Y',strtotime($start_date))?>
						</td>
					</tr>
					
						<?php 
							$date_label = $booking_type == RESERVATION_TYPE_HOTEL ? lang('check_out') : lang('end_date');
						?>
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=$date_label?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=date('d M Y',strtotime($end_date))?>
						</td>
					</tr>
					
					<tr>
					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('services')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=str_replace("\n", "<br>",$services)?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('total_price')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$total_price?>
						</td>
					</tr>
					
					
					<?php if(trim($term_cond) != '' && trim($term_cond) != '<br>'):?>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('term_cond')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$term_cond?>
						</td>
					</tr>
					
					<?php endif;?>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('deposit')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$deposit?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('final_payment')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$final_payment?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('payment_link')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$payment_link?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px"><b><?=lang('payment_suggestion')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;font-size:12px">
							<?=$payment_suggestion?>
						</td>
					</tr>
					
				</table>
			</td>
		</tr>

		<?php if($special_note != ''):?>
		<tr>
			<td colspan="2" style="font-size:12px">
				<br>
				<?=$special_note?>
			</td>
		</tr>
		<?php endif;?>
		
		<tr>
			<td colspan="2" style="font-size:12px">
				<br>
				<?=lang('best_regards')?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" style="font-size:12px">
				<font color="#4f81bd"><?=str_replace("\n", "<br>",$signature)?></font>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" style="font-size:12px">
				<img src="cid:bestpricevn-logo.png"/>
			</td>
		</tr>
		
		<?php if(isset($action) && $action == "view"):?>
		
		<tr>
			<td colspan="2" align="right" style="font-size:12px">
				<span>Last sent: <?=date(DATE_TIME_FORMAT, strtotime($send_date))?></span>
				<hr size="1">
			</td>
		</tr>
		
		<tr>
			<td colspan="2" align="right" style="font-size:12px">
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

