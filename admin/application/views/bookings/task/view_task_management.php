<form method="POST" name="frm" action="<?=site_url('customer/task_management/')?>">
	<input type="hidden" value="" name="action_type">
	<input type="hidden" value="" name="id">
	<input type="hidden" value="" name="task_done_type">
	<input type="hidden" value="" name="second_payment">
	<div id="search" style="padding: 5px 0; border: 1px solid #7BA0CD;">
		<table class="no_border" style="border-spacing: 0; width: 100%; padding: 0">
			<tr valign="middle">
				<td>
					<input type="text" size="15" name="name" tabindex="1" value="<?=set_value('name', isset($search_criteria['name'])? $search_criteria['name']: '')?>">
				</td>
				<td>
					<?php foreach ($task_filter as $key=>$value):?>
						<input type="checkbox" value="<?=$key?>" name="task_filter[]" class="case" onclick="change_time_filter(this, 1);search()" 
							<?=set_checkbox('task_filter', $key, isset($search_criteria['task_filter']) && in_array($key, $search_criteria['task_filter'])?TRUE:FALSE)?>>	
						<?php 
							$color_class = "";
							if ($key == 1){
								$color_class = "current";
							} else if ($key == 2){
								$color_class = "near";
							} else if ($key == 3){
								$color_class = "overdue";
							}
						?>
						<span class="<?=$color_class?>">
							<b><?=translate_text($value)?>
							<?php 
								if ($key == 1 && isset($current_task)) {
									echo('('.$current_task.')');
								} else if ($key == 2 && isset($near_future)){
									echo('('.$near_future.')');
								} else if ($key == 3 && isset($overdue)){
									echo('('.$overdue.')');
								}
							?>
							</b>
						</span>
					<?php endforeach;?>
				</td>
				<td>
					S:<select name="sale" onchange="search();">	
						<option value=""><?=lang('common_select_all')?></option>			
						<?php foreach ($sales as $k=>$value) :?>
																						
							<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['user_name']?></option>
							
						<?php endforeach ;?>				
					</select>
				</td>
				<td>
					T:<select name="task_type" onchange="search();" style="width: 90px">	
						<option value=""><?=lang('common_select_all')?></option>
						<?php foreach ($task_type as $k=>$value) :?>
																						
							<option value="<?=$k?>" <?=set_select('task_type', $k,isset($search_criteria['task_type']) &&  $search_criteria['task_type'] == $k? true: false)?>><?=translate_text($value)?></option>
							
						<?php endforeach ;?>
					</select>
				</td>
				<td>P:
					<select name="partner" onchange="search();" style="width: 120px">
						<option value=""><?=lang('common_select_all')?></option>
						<option value="0" <?php if(isset($search_criteria['partner']) &&  $search_criteria['partner'] == 0):?> selected="selected"<?php endif;?>><?=lang('blank')?></option>
						<?php 
							$results = get_option_group_partner($partners);
							$partner_types = $results['partner_types'];
							$partner_ops = $results['partners'];
						?>
						<?php foreach ($partner_types as $key => $partner_t):?>
							<optgroup label="<?=translate_text($partner_t)?>">
								<?php if(isset($partner_ops[translate_text($partner_t)])):?>
								<?php foreach ($partner_ops[translate_text($partner_t)] as $value):?>
									<option value="<?=$value['id']?>" <?=set_select('partner', $value['id'],isset($search_criteria['partner']) &&  $search_criteria['partner'] == $value['id']? true: false)?>><?=$value['short_name']?></option>
								<?php endforeach ;?>
								<?php endif;?>
							</optgroup>
						<?php endforeach ;?>
					</select>
				</td>
				<td>
					S-D:&nbsp;
					<input type="text" style="font-size: 11px" size="8" maxlength="10" autocomplete='off'
						onchange="change_time_filter(this, 2)"  
						name="startDate" id="startDate" value="<?=set_value('startDate', isset($search_criteria['start_date'])? $this->timedate->format($search_criteria['start_date'], DATE_FORMAT) : "")?>">
					&nbsp;
					E-D:&nbsp;
					<input type="text" style="font-size: 11px" size="8" maxlength="10" autocomplete='off'
						onchange="change_time_filter(this, 2)" 
						name="endDate" id="endDate" value="<?=set_value('endDate', isset($search_criteria['end_date'])? $this->timedate->format($search_criteria['end_date'], DATE_FORMAT) : "")?>">
				</td>
				<td>
					<input type="submit" value="<?=lang('common_button_search')?>" name="btnSearch" onclick="search();">
					<input type="button" onclick="resetForm();" value="<?=lang('common_button_reset')?>" name="btnReset">
				</td>
			</tr>
		</table>
	</div>
	
	<table class="border" id="tblTask">
	<?php if (isset($customer_meetings)):?>
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="lblHeader">Customer Meeting <label class="highlight">(<?=count($customer_meetings)?>)</label></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		<?=lang('customer')?>
            	</td>
            	<td align="center" width="32%" colspan="2">
            		<?=lang('service_reservation')?>
            	</td>
				<td align="center" width="7%">
	            	M-Date
	            </td> 
	            <td align="center" colspan="2">
	            	Meeting Note
	            </td>            
	            <td align="center" width="8%">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center" width="8%" nowrap="nowrap">
	            	Done
	            </td>
	       </tr>
	   </thead>
		<tbody>
		 <?php if (count($customer_meetings) >0 ) :?>
		<?php foreach ($customer_meetings as $customer_booking) :?>
		
		<tr>
			
			<td valign="middle">
				<?php 
					$title = "";
					if ($customer_booking['title'] == '1'){
						$title = "Mr.";
					} else {
						$title = "Ms.";
					}
				?>
				<a class="<?=$customer_booking['customer_id'].'_'.$customer_booking['start_date']?>" href="javascript:void(0)" <?php if ($customer_booking['is_duplicate']):?> style="color: #9C6500;" <?php endif;?>><?=$title.$customer_booking['full_name']?></a>
			</td>
			
			<td valign="top" colspan="2">
				<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
					<?php if($key != 0):?>
						<br>
					<?php endif;?>

					<?php if($service_reservation['detail_reservation'] != ""):?>					
						<span style="color: red; font-weight: bolder;">!</span>
					<?php endif;?>
					
					<span class="<?=$service_reservation['color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT).': '?></span>
					<?php if(is_allow_to_edit($customer_booking['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
					<a class="<?='info_'.$service_reservation['id']?>" href="javascript: void(0)" style="text-decoration: none;">
						<?=$service_reservation['service_name'].' - '?>						
					</a>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
					
					<span class="<?=$service_reservation['re_color']?> <?='payment_info_'.$service_reservation['id']?>"
						<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2) echo(' onclick="show_email('.$service_reservation['id'].')" ');?> 
						style="cursor: pointer;"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></span>
				<?php endforeach;?>	
			</td>
						
			<td valign="middle" align="center" class="<?=$customer_booking['meeting_color']?>">
				<?=$this->timedate->format($customer_booking['meeting_date'], DATE_FORMAT)?>				
			</td>
			<td colspan="2">
				<?=$customer_booking['meeting_address']?>
			</td>
			<td align="center" valign="middle"><?=$customer_booking['user_name']?></td>
			
			<td align="center" valign="middle">
				<input type="checkbox" value="<?=$customer_booking['id']?>" name="<?=TASK_CUSTOMER_MEETING?>" onclick="mark_as_done(this)">
			</td>
		</tr>
		<?php endforeach ;?>
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('service_not_found')?></label></td></tr>
		<?php endif ; ?>
		</tbody>	
	<?php endif;?>
	
	<?php if (isset($customer_payments)) :?>	
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td class="lblHeader" nowrap="nowrap">Customer Payment <label class="highlight">(<?=count($customer_payments)?>)</label></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		<?=lang('customer')?>
            	</td>
            	<td align="center" width="32%" colspan="2">
            		<?=lang('service_reservation')?>
            	</td>
				<td align="center" width="7%">
	            	P-Due
	            </td> 
	            <td align="center" colspan="2">
	            	Payment Amount
	            </td>            
	            <td align="center">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center">
	            	Done
	            </td>
	       </tr>
	   </thead>
		<tbody>
	  <?php if (count($customer_payments) >0 ) :?>
	  
		<?php foreach ($customer_payments as $customer_booking) :?>
		
		<tr>
			
			<td valign="middle">
				<?php 
					$title = "";
					if ($customer_booking['title'] == '1'){
						$title = "Mr.";
					} else {
						$title = "Ms.";
					}
				?>
				<a class="<?=$customer_booking['customer_id'].'_'.$customer_booking['start_date']?>" href="javascript:void(0)" <?php if ($customer_booking['is_duplicate']):?> style="color: #9C6500;" <?php endif;?>><?=$title.$customer_booking['full_name']?></a>
			</td>
			
			<td valign="top" colspan="2">
				<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
					<?php if($key != 0):?>
						<br>
					<?php endif;?>
					<?php if($service_reservation['detail_reservation'] != ""):?>					
						<span style="color: red; font-weight: bolder;">!</span>
					<?php endif;?>
					<span class="<?=$service_reservation['color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT).': '?></span>
					<?php if(is_allow_to_edit($customer_booking['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
					<a class="<?='info_'.$service_reservation['id']?>" href="javascript: void(0)" style="text-decoration: none;">
						<?=$service_reservation['service_name'].' - '?>						
					</a>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
					
					<span class="<?=$service_reservation['re_color']?> <?='payment_info_'.$service_reservation['id']?>"
						<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2) echo(' onclick="show_email('.$service_reservation['id'].')" ');?> 
						style="cursor: pointer;"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></span>
				<?php endforeach;?>	
			</td>
						
			<td valign="middle" align="center" class="<?=$customer_booking['p_due_color']?>">
				<?=$this->timedate->format($customer_booking['payment_due'], DATE_FORMAT)?>				
			</td>
			<td align="right" colspan="2">
				<?=number_format($customer_booking['payment_amount'], CURRENCY_DECIMAL)?>
			</td>
			<td align="center" valign="middle"><?=$customer_booking['user_name']?></td>
			
			<td align="center" valign="middle">
				<input type="checkbox" value="<?=$customer_booking['id']?>" class="cm_payment" name="<?=TASK_CUSTOMER_PAYMENT?>" onclick="cfr_customer_payment(this)">
			</td>
		</tr>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('service_not_found')?></label></td></tr>
		<?php endif ; ?>	
		</tbody>	
	<?php endif;?>
	
	
	<?php if (isset($reservation_services)) :?>
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td class="lblHeader" nowrap="nowrap">Service Reservation <label class="highlight">(<?=count($reservation_services)?>)</label></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		<?=lang('customer')?>
            	</td>
            	<td align="center" width="32%" colspan="2">
            		<?=lang('service_reservation')?>
            	</td>
				<td align="center" width="7%">
	            	<?=lang('r-due')?>
	            </td> 
	            <td align="center" colspan="2">
	            	Partner
	            </td>            
	            <td align="center">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center">
	            	Done
	            </td>
	       </tr>
	   </thead>
		<tbody>
	  <?php if (count($reservation_services) >0 ) :?>
	  
		<?php foreach ($reservation_services as $service_reservation) :?>
		
		<tr>
			
			<td valign="middle">
				<?php 
					$title = "";
					if ($service_reservation['title'] == '1'){
						$title = "Mr.";
					} else {
						$title = "Ms.";
					}
				?>
				<a class="<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>" href="javascript:void(0)"><?=$title.$service_reservation['full_name']?></a>
			</td>
			
			<td valign="top" colspan="2">
				<?php if($service_reservation['detail_reservation'] != ""):?>					
					<span style="color: red; font-weight: bolder;">!</span>
				<?php endif;?>
				<span class="<?=$service_reservation['color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT).': '?></span>
				<a class="info_<?=$service_reservation['id']?>" href="javascript:void(0);" style="text-decoration: none;">
					<?php if(strlen($service_reservation['service_name']) > 40):?>
						<?=substr($service_reservation['service_name'], 0, 40).'...'.' - '?>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
				</a>
				<span class="<?=$service_reservation['re_color']?> <?='payment_info_'.$service_reservation['id']?>"
						<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2) echo(' onclick="show_email('.$service_reservation['id'].')" ');?> 
						style="cursor: pointer;"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></span>
			</td>
						
			<td valign="middle" align="center" class="<?=$service_reservation['reserved_color']?>">
				<?=$this->timedate->format($service_reservation['reserved_date'], DATE_FORMAT)?>				
			</td>
			<td align="center" colspan="2">
				<?php foreach ($partners as $partner):?>
				<?php if($partner['id'] == $service_reservation['partner_id']):?>
					<?=$partner['short_name']?>
				<?php endif;?>
				<?php endforeach;?>
			</td>
			<td align="center" valign="middle"><?=$service_reservation['user_name']?></td>
			
			<td align="center" valign="middle">
				<input type="checkbox" value="<?=$service_reservation['id']?>" name="<?=TASK_SERVICE_RESERVATION?>" onclick="mark_as_done(this)">
			</td>
		</tr>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('service_not_found')?></label></td></tr>
		<?php endif ; ?>	
		</tbody>	
	<?php endif;?>
	
	
	<?php if (isset($transfer_services)) :?>
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td nowrap="nowrap" class="lblHeader">Transfer Reminder <label class="highlight">(<?=count($transfer_services)?>)</label></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		<?=lang('customer')?>
            	</td>
            	<td align="center" width="32%" colspan="2">
            		<?=lang('service_reservation')?>
            	</td>
				<td align="center" width="7%">
	            	S-Date
	            </td> 
	            <td align="center" colspan="2">
	            	Partner
	            </td>            
	            <td align="center">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center">
	            	Done
	            </td>
	       </tr>
	   </thead>
		<tbody>
	  <?php if (count($transfer_services) >0 ) :?>
	  
		<?php foreach ($transfer_services as $service_reservation) :?>
		
		<tr>
			
			<td valign="middle">
				<?php 
					$title = "";
					if ($service_reservation['title'] == '1'){
						$title = "Mr.";
					} else {
						$title = "Ms.";
					}
				?>
				<a class="<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>" href="javascript:void(0)"><?=$title.$service_reservation['full_name']?></a>
			</td>
			
			<td valign="top" colspan="2">
				<?php if($service_reservation['detail_reservation'] != ""):?>					
					<span style="color: red; font-weight: bolder;">!</span>
				<?php endif;?>
				<span class="<?=$service_reservation['color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT).': '?></span>
				<a class="info_<?=$service_reservation['id']?>" href="javascript:void(0);" style="text-decoration: none;">
					<?php if(strlen($service_reservation['service_name']) > 40):?>
						<?=substr($service_reservation['service_name'], 0, 40).'...'.' - '?>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
				</a>
				<span class="<?=$service_reservation['re_color']?> <?='payment_info_'.$service_reservation['id']?>"
						<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2) echo(' onclick="show_email('.$service_reservation['id'].')" ');?> 
						style="cursor: pointer;"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></span>
			</td>
						
			<td valign="middle" align="center" class="<?=$service_reservation['reserved_color']?>">
				<?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT)?>				
			</td>
			<td align="center" colspan="2">
				<?php foreach ($partners as $partner):?>
				<?php if($partner['id'] == $service_reservation['partner_id']):?>
					<?=$partner['short_name']?>
				<?php endif;?>
				<?php endforeach;?>
			</td>
			<td align="center" valign="middle"><?=$service_reservation['user_name']?></td>
			
			<td align="center" valign="middle">
				<input type="checkbox" value="<?=$service_reservation['id']?>" name="<?=TASK_TRANSFER_REMINDER?>" onclick="mark_as_done(this)">
			</td>
		</tr>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('service_not_found')?></label></td></tr>
		<?php endif ; ?>	
		</tbody>	
	<?php endif;?>
	
	<?php if (isset($service_payments)) :?>
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td class="lblHeader" nowrap="nowrap">Service Payment <label class="highlight">(<?=count($service_payments)?>)</label></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		<?=lang('customer')?>
            	</td>
            	<td align="center" width="32%" colspan="2">
            		<?=lang('service_reservation')?>
            	</td>
				<td align="center" width="7%">
	            	P-Due
	            </td>
	            <td align="center" width="7%">
	            	P-Amount
	            </td> 
	            <td align="center">
	            	Partner
	            </td>            
	            <td align="center">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center">
	            	Done
	            </td>
	       </tr>
	   </thead>
		<tbody>
	  <?php if (count($service_payments) >0 ) :?>
	  
		<?php foreach ($service_payments as $service_reservation) :?>
		<?php 
			$row_span = "";
			if (!empty($service_reservation['second_payment']) 
					&& $service_reservation['reservation_status'] != RESERVATION_DEPOSIT)
			{
				$row_span = 'rowspan="2"';
			}
		?>
		<tr>
			
			<td valign="middle" <?=$row_span?>>
				<?php 
					$title = "";
					if ($service_reservation['title'] == '1'){
						$title = "Mr.";
					} else {
						$title = "Ms.";
					}
				?>
				<a class="<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>" href="javascript:void(0)"><?=$title.$service_reservation['full_name']?></a>
			</td>
			
			<td valign="middle" <?=$row_span?> colspan="2">
				<?php if($service_reservation['detail_reservation'] != ""):?>					
					<span style="color: red; font-weight: bolder;">!</span>
				<?php endif;?>
				<span class="<?=$service_reservation['color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT).': '?></span>
				<a class="info_<?=$service_reservation['id']?>" href="javascript:void(0);" style="text-decoration: none;">
					<?php if(strlen($service_reservation['service_name']) > 40):?>
						<?=substr($service_reservation['service_name'], 0, 40).'...'.' - '?>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
				</a>
				<span class="<?=$service_reservation['re_color']?> <?='payment_info_'.$service_reservation['id']?>"
						<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2) echo(' onclick="show_email('.$service_reservation['id'].')" ');?> 
						style="cursor: pointer;"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></span>
			</td>
						
			<td valign="middle" align="center" class="<?=$service_reservation['reserved_color']?>">
				<?=$this->timedate->format($service_reservation['payment_due'], DATE_FORMAT)?>				
			</td>
			<td valign="middle" align="right">
				<?=number_format($service_reservation['payment'], CURRENCY_DECIMAL)?>
			</td>
			<td align="center">
				<?php foreach ($partners as $partner):?>
				<?php if($partner['id'] == $service_reservation['partner_id']):?>
					<?=$partner['short_name']?>
				<?php endif;?>
				<?php endforeach;?>
			</td>
			<td align="center" valign="middle"><?=$service_reservation['user_name']?></td>
			
			<td align="center" valign="middle">
				<input type="checkbox" value="<?=$service_reservation['id']?>" name="<?=TASK_SERVICE_PAYMENT?>" onclick="mark_as_done(this)">
			</td>
		</tr>
		<?php if (!empty($service_reservation['second_payment']) && $service_reservation['reservation_status'] != RESERVATION_DEPOSIT):?>
			<tr class="second_payment">
				<td align="center" class="<?=$service_reservation['reserved_color']?>"><?=$this->timedate->format($service_reservation['2_payment_due'], DATE_FORMAT)?></td>
				
				<td align="right"><?=number_format($service_reservation['2_payment'], CURRENCY_DECIMAL)?></td>	
			
				<td align="center">
					<?php foreach ($partners as $partner):?>
					<?php if($partner['id'] == $service_reservation['partner_id']):?>
						<?=$partner['short_name']?>
					<?php endif;?>
					<?php endforeach;?>
				</td>
				<td align="center" valign="middle"><?=$service_reservation['user_name']?></td>
				
				<td align="center" valign="middle">
					<input type="checkbox" value="<?=$service_reservation['id']?>" name="<?=TASK_SERVICE_PAYMENT?>" title="<?=$service_reservation['second_payment']?>" onclick="mark_as_done(this)">
				</td>
			</tr>
		<?php endif;?>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('service_not_found')?></label></td></tr>
		<?php endif ; ?>	
		</tbody>	
	<?php endif;?>
	
	<?php if (isset($todo_notes)) :?>
		<thead>
			<tr>
				<td class="gap" colspan="16"></td>
			</tr>
			<tr>
				<td class="lblHeader">To-do <label class="highlight">(<?=count($todo_notes)?>)</label></td>
				<td colspan="4" style="background: #fff; border: 0 !important;">
				<span class="ui-icon ui-icon-circle-plus" style="float: left;"></span>
				<label class="btnLabel" onclick="create_note()">Create To-do</label></td>
				<td colspan="2" align="right" style="background: #fff; border: 0 !important;"></td>
			</tr>
			<tr>
				<td align="center" width="10%">
            		Name
            	</td>
            	<td align="center" width="32%">
            		Details
            	</td>
				<td align="center" width="7%">
	            	Start Date
	            </td>
	            <td align="center" width="7%">
	            	Due Date
	            </td> 
	            <td align="center" colspan="2">
	            	Status
	            </td>            
	            <td align="center" width="8%">
	            	<?=lang('sale')?>
	            </td>
	            <td align="center" width="8%">
	            	Func
	            </td>
	       </tr>
	   </thead>
	   	<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right" colspan="2">
				<select name="to_do_status" id="to_do_status" onchange="search();">	
					<option value=""><?=lang('common_select_all')?></option>			
					<?php foreach ($todo_status as $k=>$value) :?>
						<option value="<?=$k?>" <?=set_select('to_do_status', $k,isset($search_criteria['to_do_status']) &&  $search_criteria['to_do_status'] == $k? true: false)?>><?=$value?></option>
					<?php endforeach ;?>				
				</select>
			</td>
			<td></td>
			<td></td>
		</tr>
		<?php if (count($todo_notes) >0 ) :?>
			<?php foreach ($todo_notes as $todo):?>
			<tr>
				<td><?=$todo['name']?></td>
				<td><?=$todo['details']?></td>
				<td align="center" class="<?=$todo['r_color']?>"><?=$todo['start_date']?></td>
				<td align="center" class="<?=$todo['reserved_color']?>"><?=$todo['due_date']?></td>
				<td align="center" class="<?=$todo['todo_color']?>" colspan="2">
					<?php foreach ($todo_status as $k=>$value) :?>
					<?php if($k == $todo['status']) echo $value;?>
					<?php endforeach ;?>
				</td>
				<td align="center"><?=$todo['user_name']?></td>
				<td align="center">
					<img alt="<?=lang('common_edit_button')?>" title="<?=lang('common_edit_button')?>" style="cursor: pointer;"
						src="<?=base_url() .'media/Edit.png';?>" onclick="editTodo(<?=$todo['id'];?>)">
					<?php if(is_allow_deletion($todo['user_id'])):?>
					&nbsp;
					<img title="<?=lang('common_del_button')?>" src="<?=base_url() .'media/Delete.png'?>" 
						onclick="deleteTodo(<?=$todo['id'];?>);" style="cursor: pointer;">
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error">To-do list is empty</label></td></tr>
		<?php endif ; ?>
	<?php endif ; ?>
	</table>
	<p>&nbsp;</p>
</form>
<div class="ajaxBusy" id="createForm">
	<div class="title" id="formTitle">
		<label id="lblTitle">Create To-do</label>
		<label class="note" id="lblEditor" style="float: right; font-weight: normal;"></label>
	</div>
	<ul>
		<li>&nbsp;</li>
		<li>Name: <?=mark_required()?></li>
		<li><input type="text" name="task_name" id="task_name" maxlength="200" style="width: 340px"></li>
		<li><label id="lbNameError" style="color: red"></label>&nbsp;</li>
		<li>Details:</li>
		<li><textarea rows="3" style="width: 340px" name="task_details" id="task_details"></textarea></li>
		<li>
			<span style="float: left; width: 50%">Start Date: <?=mark_required()?></span>
			<span>Due Date: <?=mark_required()?></span>
		</li>
		<li>
			<span style="float: left; width: 50%"><input type="text" name="task_start_date" id="task_start_date" maxlength="10" style="width: 120px"></span>
			<span><input type="text" name="task_due_date" id="task_due_date" maxlength="10" style="width: 120px"></span>
		</li>
		<li><label id="lbError" style="color: red"></label>&nbsp;</li>
	</ul>
	<div style="margin: 0">
		<div style="float: left;">
		<ul style="width: 200px">
			<li style="width: 100%; float: left;">
				<?php if(is_administrator()):?>
				<span style="float:left;width: 50%">Sale:</span>
				<?php endif;?>
				<span style="float:left;width: 50%" class="status_block">Status:</span>
			</li>
			<li>
				<?php if(is_administrator()):?>
				<span style="float:left;width: 50%">
					<select name="todo_user" id="todo_user" style="width: 60px;">
						<?php foreach ($sales as $k=>$value) :?>
																						
							<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['user_name']?></option>
							
						<?php endforeach ;?>				
					</select>
				</span>
				<?php endif;?>
				<span style="float:left;width: 50%" class="status_block">
					<select name="todo_status" id="todo_status">				
						<?php foreach ($todo_status as $k=>$value) :?>
							<option value="<?=$k?>"><?=$value?></option>
						<?php endforeach ;?>				
					</select>
				</span>
			</li>
		</ul>
		</div>
		<span style="float: right; margin-top: 5px">
			<input type="button" onclick="createTodo();" value="Save" class="button">
			<input type="button" onclick="hideForm('createForm');" value="Cancel" class="button">
			<input type="hidden" id="todoId">
		</span>
	</div>
</div>
<div class="ajaxBusy" id="frmCustomerPayment">
	<div class="title">
		<label id="lblPaymentTitle">Customer Payment</label>
		<label class="note" id="lblPaymentEditor" style="float: right; font-weight: normal;"></label>
	</div>
	<ul style="font-size: 12px">
		<li><label id="lbPaymentError" style="color: red"></label>&nbsp;</li>
		<li style="margin-bottom: 7px">Customer: <label style="font-weight: bold;" id="customer_name"></label></li>
		<li style="margin-bottom: 10px; float: left;width: 100%">
			<span style="float: left; width: 50%">NET: <label id="NET"></label></span>
			<span style="float: left; width: 50%">SELL: <label id="SELL"></label></span>
		</li>
		<li>Onepay: </li>
		<li><input type="text" name="onepay" id="onepay" maxlength="10" style="width: 340px"></li>
		<li><label id="lbOnepayError" style="color: red"></label>&nbsp;</li>
		<li>Cash:</li>
		<li><input type="text" name="cash" id="cash" maxlength="10" style="width: 340px"></li>
		<li><label id="lbCashError" style="color: red"></label>&nbsp;</li>
		<li>POS:</li>
		<li><input type="text" name="pos" id="pos" maxlength="10" style="width: 340px"></li>
		<li><label id="lbPosError" style="color: red"></label>&nbsp;</li>
	</ul>
	<span style="float: right; margin: 10px 3px 0 0">
		<input type="button" onclick="update_payment();" value="Save" id="btnSave" class="button">
		<input type="button" onclick="hidePayment();" id="btnCancel" value="Cancel" class="button">
		<input type="hidden" id="cb_id">
	</span>
</div>
<div id="overlay"></div>
<script>
	
		$(document).ready(function() 
		{
			<?php if(isset($reload_opener)):?>
				window.opener.location.reload(false);
			<?php endif;?>
			
			$('#tblTask thead tr:visible td:first-child').css('border-left', '1px solid #7BA0CD');
			$('#tblTask tbody tr:not([class="second_payment"]) td:first-child').css('border-left', '1px solid #7BA0CD');
			$("#task_due_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			$("#task_start_date").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			$("#startDate").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			$("#endDate").datepicker({showOn: 'button',dateFormat: "<?=DATE_FORMAT_JS?>", buttonImage: '<?=base_url() ."media/calendar.gif"?>', buttonImageOnly: true});
			
			<?php if (isset($customer_meetings)) :?>			  
			<?php foreach ($customer_meetings as $customer_booking) :?>

			var id = ".<?=$customer_booking['customer_id'].'_'.$customer_booking['start_date']?>";

			var content = "<?='* '.$customer_booking['email'].'<br>'.'* '.$customer_booking['phone'].'<br> * '.$countries[$customer_booking['country']][0].'<br> * '.$customer_booking['city']?>";
				
			set_tip(id, content, 'rightTop', 'bottomLeft', 200);
			
			<?php if ($customer_booking['meeting_address'] != ''):?>
				<?php 
					
					$str_ma = str_replace("\"","&quot;",$customer_booking['meeting_address']);	
					
					$str_ma = str_replace("\n", "<br>", $str_ma);
					
					$str_ma = str_replace("\r", "", $str_ma);
				?>

				set_tip(".<?='mt_'.$customer_booking['id']?>", "<?=$str_ma?>", 'rightTop', 'bottomLeft');					
					
			<?php endif;?>

			<?php if ($customer_booking['note'] != ''):?>
				<?php 
					
					$str_note = str_replace("\"","&quot;",$customer_booking['note']);	
					
					$str_note = str_replace("\n", "<br>", $str_note);
					
					$str_note = str_replace("\r", "", $str_note);
					
				?>

				set_tip(".<?='note_'.$customer_booking['id']?>", "<?=$str_note?>", 'rightTop', 'bottomLeft');					
				
			<?php endif;?>

			<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
				
				<?php if ($service_reservation['description'] != '' || $service_reservation['detail_reservation'] != ''):?>
			
				<?php 
					$str_description = "";
					
					if ($service_reservation['detail_reservation'] != ""){
						
						$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
						
					}
					
					$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
					
					if ($str_description != '') $str_description = $str_description."<br><br>";
					
					if ($service_reservation['description'] != ''){
						
						$str_description = $str_description. "<b>Special Request: </b>";						
						
						$descriptions = str_replace("\"","&quot;",$service_reservation['description']);	
					
						$descriptions = str_replace("\n", "<br>", $descriptions);
						
						$descriptions = str_replace("\r", "", $descriptions);
						
						$str_description = $str_description.$descriptions;
					
					}
				?>

				set_tip(".<?='info_'.$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);				
			
			<?php endif;?>
			
			<?php endforeach;?>
			
			<?php endforeach;?>
		 <?php endif;?>

		 <?php if (isset($customer_payments)) :?>			  
			<?php foreach ($customer_payments as $customer_booking) :?>

			var id = ".<?=$customer_booking['customer_id'].'_'.$customer_booking['start_date']?>";

			var content = "<?='* '.$customer_booking['email'].'<br>'.'* '.$customer_booking['phone'].'<br> * '.$countries[$customer_booking['country']][0].'<br> * '.$customer_booking['city']?>";
				
			set_tip(id, content, 'rightTop', 'bottomLeft', 200);

			<?php if ($customer_booking['note'] != ''):?>
			<?php 
				
				$str_note = str_replace("\"","&quot;",$customer_booking['note']);	
				
				$str_note = str_replace("\n", "<br>", $str_note);
				
				$str_note = str_replace("\r", "", $str_note);
				
			?>

			set_tip(".<?='note_'.$customer_booking['id']?>", "<?=$str_note?>", 'rightTop', 'bottomLeft');					
			
			<?php endif;?>
	
			<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
				
				<?php if ($service_reservation['description'] != '' || $service_reservation['detail_reservation'] != ''):?>
			
				<?php 
					$str_description = "";
					
					if ($service_reservation['detail_reservation'] != ""){
						
						$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
						
					}
					
					$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
					
					if ($str_description != '') $str_description = $str_description."<br><br>";
					
					if ($service_reservation['description'] != ''){
						
						$str_description = $str_description. "<b>Special Request: </b>";						
						
						$descriptions = str_replace("\"","&quot;",$service_reservation['description']);	
					
						$descriptions = str_replace("\n", "<br>", $descriptions);
						
						$descriptions = str_replace("\r", "", $descriptions);
						
						$str_description = $str_description.$descriptions;
					
					}
				?>
	
				set_tip(".<?='info_'.$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);				
			
			<?php endif;?>
			
			<?php endforeach;?>
			<?php endforeach;?>
		<?php endif;?>

		<?php if (isset($reservation_services)) :?>			  
			<?php foreach ($reservation_services as $service_reservation) :?>
	
			var id = ".<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>";
	
			var content = "<?='* '.$service_reservation['email'].'<br>'.'* '.$service_reservation['phone'].'<br> * '.$countries[$service_reservation['country']][0].'<br> * '.$service_reservation['city']?>";
				
			set_tip(id, content, 'rightTop', 'bottomLeft', 200);

			<?php if ($service_reservation['description'] != '' || $service_reservation['detail_reservation'] != ''):?>
			
					<?php 
						$str_description = "";
						
						if ($service_reservation['detail_reservation'] != ""){
							
							$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
							
						}
						
						$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
						
						if ($str_description != '') $str_description = $str_description."<br><br>";
						
						if ($service_reservation['description'] != ''){
							
							$str_description = $str_description. "<b>Special Request: </b>";						
							
							$descriptions = str_replace("\"","&quot;",$service_reservation['description']);	
						
							$descriptions = str_replace("\n", "<br>", $descriptions);
							
							$descriptions = str_replace("\r", "", $descriptions);
							
							$str_description = $str_description.$descriptions;
						
						}
					?>
		
					set_tip(".<?='info_'.$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);				
				
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>

		<?php if (isset($transfer_services)) :?>			  
			<?php foreach ($transfer_services as $service_reservation) :?>
	
			var id = ".<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>";
			
			var content = "<?='* '.$service_reservation['email'].'<br>'.'* '.$service_reservation['phone'].'<br> * '.$countries[$service_reservation['country']][0].'<br> * '.$service_reservation['city']?>";
				
			set_tip(id, content, 'rightTop', 'bottomLeft', 200);

			<?php if ($service_reservation['description'] != '' || $service_reservation['detail_reservation'] != ''):?>
			
				<?php 
					$str_description = "";
					
					if ($service_reservation['detail_reservation'] != ""){
						
						$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
						
					}
					
					$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
					
					if ($str_description != '') $str_description = $str_description."<br><br>";
					
					if ($service_reservation['description'] != ''){
						
						$str_description = $str_description. "<b>Special Request: </b>";						
						
						$descriptions = str_replace("\"","&quot;",$service_reservation['description']);	
					
						$descriptions = str_replace("\n", "<br>", $descriptions);
						
						$descriptions = str_replace("\r", "", $descriptions);
						
						$str_description = $str_description.$descriptions;
					
					}
				?>
	
				set_tip(".<?='info_'.$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);				
			
			<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>

		<?php if (isset($service_payments)) :?>			  
			<?php foreach ($service_payments as $service_reservation) :?>
	
			var id = ".<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>";
			
			var content = "<?='* '.$service_reservation['email'].'<br>'.'* '.$service_reservation['phone'].'<br> * '.$countries[$service_reservation['country']][0].'<br> * '.$service_reservation['city']?>";
				
			set_tip(id, content, 'rightTop', 'bottomLeft', 200);

			<?php if ($service_reservation['description'] != '' || $service_reservation['detail_reservation'] != ''):?>
			
				<?php 
					$str_description = "";
					
					if ($service_reservation['detail_reservation'] != ""){
						
						$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
						
					}
					
					$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
					
					if ($str_description != '') $str_description = $str_description."<br><br>";
					
					if ($service_reservation['description'] != ''){
						
						$str_description = $str_description. "<b>Special Request: </b>";						
						
						$descriptions = str_replace("\"","&quot;",$service_reservation['description']);	
					
						$descriptions = str_replace("\n", "<br>", $descriptions);
						
						$descriptions = str_replace("\r", "", $descriptions);
						
						$str_description = $str_description.$descriptions;
					
					}
				?>
	
				set_tip(".<?='info_'.$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);				
			
			<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
		});	

		function show_email(id) {
			var url = "<?=site_url('customer/email/index/')?>" + "/" + id;
			window.open(url, '_blank', '<?=$atts?>');
		}
	</script>

