<html>
<head>
</head>

<body style="font-family: Arial; font-size:12px;">	
	<table width="750" style="font-family: Arial; font-size:12px;">
		<tr>
			<td colspan="2">
				<span><?=lang('dear')?>&nbsp;<?=$dear?>,</span><br>
				<span><?=$request?></span>
				<br><br>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" width="100%" style="font-family: Arial; font-size:12px;border-top: 1px solid #7BA0CD;border-left: 1px solid #7BA0CD;empty-cells: show;">
				
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('guest_name')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$guest_name?>
						</td>
					</tr>
					<tr>
						
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('guest_number')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$guest_number?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('check_in')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=date('d M Y',strtotime($start_date))?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('check_out')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=date('d M Y',strtotime($end_date))?>
						</td>
					</tr>
					
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('number_of_night')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$night_number?>
						</td>
					</tr>
					
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('room_type')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$services?>
						</td>
					</tr>
					
					<tr>					
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('room_rate')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=$room_rate?>
						</td>
					</tr>
					
					<tr>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><b><?=lang('special_request')?>:</b></td>
						<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
							<?=str_replace("\n", "<br>",$special_request)?>
						</td>
					</tr>
					
				</table>
			</td>
		</tr>

		
		<?php if ($special_note != ''):?>
		<tr>
			<td colspan="2">
				<br>
				<?=$special_note?>
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
				<font color="#4f81bd"><?=str_replace("\n", "<br>",$signature)?></font>
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
				<span>Last sent: <?=date(DATE_TIME_FORMAT, strtotime($send_date))?></span>
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
