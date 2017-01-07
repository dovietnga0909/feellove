<html>
<head>

</head>

<body style="font-family: Arial; font-size:12px;">	
	<table style="font-family: Arial; font-size:12px;" width="750">
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
					<thead style="font-weight:bold; background-color: #D3DFEE;color: #333;">
						<tr>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">#</th>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('name')?></th>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('gender')?></th>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('birth_day')?></th>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('nationality')?></th>
							
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('passport')?></th>
							<!-- 
							<th align="center" style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=lang('passport_expiry')?></th>
							 -->
						</tr>
					</thead>
					<tbody>						
						
					<?php foreach ($visa_users as $key => $user):?>
						<tr>
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;"><?=($key + 1)?></td>							
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=$user['name']?>
							</td>
							
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=$user['gender']?>
							</td>
							
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=date('d-m-Y', strtotime($user['birth_day']))?>
							</td>
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=$user['nationality']?>							
							</td>
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?=$user['passport']?>
							</td>
							<!-- 
							<td style="border-bottom: 1px solid #7BA0CD;border-right: 1px solid #7BA0CD;padding: 4px 4px 4px 4px;">
								<?php if(!empty($user['passport_expiry'])):?>
								<?=date('d-m-Y', strtotime($user['passport_expiry']))?>
								<?php endif;?>
							</td>
							 -->
						</tr>			
					<?php endforeach;?>
					
					</tbody>
					
				</table>
				<br>
			</td>
		</tr>
		
		
		
		<?php if($type_of_visa > 0):?>
		<tr>	
			<td><b><?=lang('type_of_visa')?>:</b></td>		
			
			<td><?=translate_text($visa_types[$type_of_visa])?></td>
		</tr>
		<?php endif;?>
		
		
		<?php if($processing_time > 0):?>
		<tr>	
			<td><b><?=lang('processing_time')?>:</b></td>		
			
			<td><?=translate_text($visa_processing_times[$processing_time])?></td>
		</tr>
		<?php endif;?>

		<tr>
			<td style="width: 150px"><b><?=lang('comming_date')?>:</b></td>
			<td><?=date('d-m-Y', strtotime($start_date))?></td>			
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
