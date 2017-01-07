
<style type="text/css">
	.container{width:100%}
</style>


<form method="POST" name="frm" class="form-inline" role="form">

	<input type="hidden" name="action" value="<?=ACTION_SEARCH?>">
	<input type="hidden" value="<?=$search_criteria['sort_column']?>" name="sort_column"></input>
	<input type="hidden" value="<?=$search_criteria['sort_order']?>" name="sort_order"></input>
	
	
	<div id="search" <?php if($action == ACTION_ADVANCED_SEARCH):?> style="display: none;" <?php endif;?>>
		<?=$search?>
	</div>
	<div id="advanced_search" <?php if($action != ACTION_ADVANCED_SEARCH):?> style="display: none;" <?php endif;?>>
		<?=$advanced_search?>
	</div>
	
	<!-- 
	<div style="margin: 0 0 5px 10px; cursor: pointer;"><a href="javascript:void(0)" onclick="show_task()"><b>Today's Task</b></a> <label id="today_task" style="color: red;"></label></div>
	 -->

<div class="booking-table">
	<table>
		<thead>
			<tr>
				<td align="center" width="2%">#</td>
				<td align="center" width="12%">
            		<a href="javascript:void(0)" onclick="sort('c.full_name')"><?=lang('customer')?>
            			<?php if ($search_criteria['sort_column'] == "c.full_name"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
            	</td>
            	<td align="center">
            		<?=lang('service_reservation')?>
            	</td>
            	<td align="center" width="10%">
	            	<a href="javascript:void(0)" onclick="sort('cb.request_date')"><?=lang('r-date')?>
	            		<?php if ($search_criteria['sort_column'] == "cb.request_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
            	<td align="center" width="5%">
	            	<a href="javascript:void(0)" onclick="sort('cb.start_date')"><?=lang('s-date')?>
	            		<?php if ($search_criteria['sort_column'] == "cb.start_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	            <!-- 	            
				<td align="center" width="5%">
	            	<a href="javascript:void(0)" onclick="sort('cb.meeting_date')"><?=lang('m-date')?>
	            		<?php if ($search_criteria['sort_column'] == "cb.meeting_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>  
	             -->          
	            <td align="center" width="5%">
	            	<?=lang('status')?>
	            </td>
	            <td align="center" width="5%">
	            	<?=lang('sale')?>
	            	
	            	<?php if (isset($search_criteria['payment_due']) && $search_criteria['payment_due'] == "show"):?>
            			<img onclick="showHideColumn(this,'payment_due')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/open.gif'?>">
	            	<?php else:?>
	            		<img onclick="showHideColumn(this,'payment_due')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/close.gif'?>">
	            	<?php endif;?>
	            	
	            	<?php if (isset($search_criteria['payment_amount']) && $search_criteria['payment_amount'] == "show"):?>
            			<img onclick="showHideColumn(this,'payment_amount')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 40px;" alt="" src="<?=site_url().'media/open.gif'?>">
	            	<?php else:?>
	            		<img onclick="showHideColumn(this,'payment_amount')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 40px;" alt="" src="<?=site_url().'media/close.gif'?>">
	            	<?php endif;?>
	            	
	            </td>
	            
	            <td align="center" width="5%" class="payment_due" <?php if (!isset($search_criteria['payment_due']) || $search_criteria['payment_due'] == "hide"):?> style="display: none;" <?php endif;?>>
	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.payment_due')"><?=lang('p-due')?>
            			<?php if ($search_criteria['sort_column'] == "cb.payment_due"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
	            </td>
	            <td align="center" width="5%" class="payment_amount" <?php if (!isset($search_criteria['payment_amount']) || $search_criteria['payment_amount'] == "hide"):?> style="display: none;" <?php endif;?>>
	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.payment_amount')"><?=lang('p-amount')?>
            			<?php if ($search_criteria['sort_column'] == "cb.payment_amount"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
	            </td>
	            
	            <td align="center" width="5%">	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.net_price')"><?=lang('net_price')?>
            			<?php if ($search_criteria['sort_column'] == "cb.net_price"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
	            </td>	            
	            <td align="center" width="5%">	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.selling_price')"><?=lang('selling_price')?>
            			<?php if ($search_criteria['sort_column'] == "cb.selling_price"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
            		<?php if (isset($search_criteria['actual_selling']) && $search_criteria['actual_selling'] == "show"):?>
            			<img onclick="showHideColumn(this,'actual_selling')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/open.gif'?>">
	            	<?php else:?>
	            		<img onclick="showHideColumn(this,'actual_selling')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/close.gif'?>">
	            	<?php endif;?>
	            </td>
	            
	            <td align="center" width="5%" class="actual_selling" <?php if (!isset($search_criteria['actual_selling']) || $search_criteria['actual_selling'] == "hide"):?> style="display: none;" <?php endif;?>>	            		            	
	            	<a href="javascript:void(0)" onclick="sort('cb.actual_selling')"><?=lang('actual_selling')?>
            			<?php if ($search_criteria['sort_column'] == "cb.actual_selling"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img  border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
	            </td>
	            
	            <td align="center" width="5%">	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.profit')"><?=lang('profit')?>
            			<?php if ($search_criteria['sort_column'] == "cb.profit"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
            		
            		<?php if (!isset($search_criteria['actual_profit']) || $search_criteria['actual_profit'] == "show"):?>
            			<img onclick="showHideColumn(this,'actual_profit')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/open.gif'?>">
	            	<?php else:?>
	            		<img onclick="showHideColumn(this,'actual_profit')" style="cursor: pointer; position: absolute; margin-top: -20px; margin-left: 20px;" alt="" src="<?=site_url().'media/close.gif'?>">
	            	<?php endif;?>
	            	
            	</td>
	            
	            <td align="center" width="6%" class="actual_profit" <?php if (isset($search_criteria['actual_profit']) && $search_criteria['actual_profit'] == "hide"):?> style="display: none;" <?php endif;?>>	            	
	            	<a href="javascript:void(0)" onclick="sort('cb.actual_profit')"><?=lang('actual_profit')?>
            			<?php if ($search_criteria['sort_column'] == "cb.actual_profit"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
            		</a>
	            </td>
	          
	            <td align="center" width="6%">
	            	<a href="<?=site_url('customer/service_reservation/index/')?>">
	            		<img width="10" height="10" style="border: 0; margin-top: -20px;position: absolute; margin-left: -10px; cursor: pointer;" alt="Service Reservation" title="Service Reservation" src="<?=base_url() .'media/doc_upload.png'?>" />
	            	</a>
	            	<?=lang('common_function')?>
	            </td>
	       </tr>
	   </thead>
	   <tbody>
			<tr>
				<td colspan="2">					
					 <input id="customer_auto" name="customer_auto" class="form-control input-sm" value="<?=isset($search_criteria['customer_name'])? $search_criteria['customer_name'] : ''?>"/>
					 <input name="customer" id="customer" type="hidden" value="<?=(isset($search_criteria['customer_id'])? $search_criteria['customer_id'] : '')?>">
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					&nbsp;
				</td>
				<!-- 
				<td>
					&nbsp;
				</td>
				 -->
				<td>
					<select name="status" onchange="search('<?=ACTION_SEARCH?>');" style="width: 60px;">
						<option value=""><?=lang('all')?></option>						
						<option value="-1" <?php if(isset($search_criteria['status']) && $search_criteria['status'] == -1):?>selected="selected" <?php endif;?>><?=lang('processing')?></option>						
						<option value="-2" <?php if(isset($search_criteria['status']) && $search_criteria['status'] == -2):?>selected="selected" <?php endif;?>><?=lang('booked')?></option>
						<option value="-3" <?php if(isset($search_criteria['status']) && $search_criteria['status'] == -3):?>selected="selected" <?php endif;?>><?=lang('finished')?></option>
						<option disabled="disabled" value="">--------------</option>							
						<?php foreach ($status as $key => $value) :?>
																						
							<option value="<?=$key?>" <?=set_select('status', $key, isset($search_criteria['status']) && $search_criteria['status'] == $key? true: false)?>><?=lang($value)?></option>
							
							<?php if ($key == 4):?>
								<option disabled="disabled" value="">--------------</option>	
							<?php endif;?>
							
						<?php endforeach ;?>				
					</select>
				</td>
				<td>
					<select name="sale" onchange="search('<?=ACTION_SEARCH?>');" style="width: 60px;">	
						<option value=""><?=lang('all')?></option>			
						<?php foreach ($sales as $k=>$value) :?>
																						
							<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['username']?></option>
							
						<?php endforeach ;?>				
					</select>
				</td>
				<td class="payment_due" <?php if (!isset($search_criteria['payment_due']) || $search_criteria['payment_due'] == "hide"):?> style="display: none;" <?php endif;?>>
					&nbsp;
				</td>
				<td align="right" class="payment_amount" <?php if (!isset($search_criteria['payment_amount']) || $search_criteria['payment_amount'] == "hide"):?> style="display: none;" <?php endif;?>>
					<b><?=number_format($amount, CURRENCY_DECIMAL)?></b>
				</td>
				<td align="right">
					<b><?=number_format($net, CURRENCY_DECIMAL)?></b>
				</td>	
				
				
				<td align="right"><b><?=number_format($sel, CURRENCY_DECIMAL)?></b></td>
				
				<td align="right" class="actual_selling" <?php if (!isset($search_criteria['actual_selling']) || $search_criteria['actual_selling'] == "hide"):?> style="display: none;" <?php endif;?>>
					<b><?=number_format($actual_sel, CURRENCY_DECIMAL)?></b>
				</td>
				
				<td align="right"><b><?=number_format($sel - $net, CURRENCY_DECIMAL)?></b></td>
				
				<td align="right" class="actual_profit" <?php if (isset($search_criteria['actual_profit']) && $search_criteria['actual_profit'] == "hide"):?> style="display: none;" <?php endif;?>>
					<b><?=number_format($actual_sel - $net, CURRENCY_DECIMAL)?></b>
				</td>
				
				
				
				
				
				<td>&nbsp;</td>
				
			</tr>
		</tbody>
		<tbody>
	  <?php if (count($customer_bookings) >0 ) :?>
	  
		<?php foreach ($customer_bookings as $customer_booking) :?>
			
		<tr <?php if($customer_booking['ticket_booked_warnign']):?>class="ticket-warning"<?php endif;?>>
			<td><?=$customer_booking['id']?></td>
			<td valign="top">
				<?php 
					$title = $customer_booking['title'] > 0 ? '('.lang($c_titles[$customer_booking['title']]).') ' : '';
				?>
				<a id="<?=get_customer_unique_id($customer_booking)?>" href="<?=site_url('customers/edit/'.$customer_booking['customer_id'])?>" <?php if ($customer_booking['is_duplicate']):?> style="color: #9C6500;" <?php endif;?>><?=$title.$customer_booking['full_name']?></a>
				
				<?php if($customer_booking['payment_warning'] == "normal"):?>									
					<img id="customer_payment_warning_<?=$customer_booking['id']?>" style="cursor: pointer; border: 0; width: 10px; height: 10px; float: right;" src="<?=base_url() .'media/warning.png'?>">
				<?php elseif($customer_booking['payment_warning'] == "high"):?>
					<img id="customer_payment_warning_<?=$customer_booking['id']?>" style="cursor: pointer; border: 0; width: 10px; height: 10px; float: right;" src="<?=base_url() .'media/warning.gif'?>">
				<?php endif;?>
				  
				<?php if ($customer_booking['approve_status'] == 1):?>
					<img id="<?='b_approve_'.$customer_booking['id']?>" style="border: 0; cursor: pointer; float: right;" src="<?=base_url() .'media/check.gif'?>">
				<?php endif;?>
					
				
			</td>
			
			<td valign="top">
				<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
					<?php if($key != 0):?>
						<br>
					<?php endif;?>
					
					<?php if($service_reservation['detail_reservation'] != ""):?>					
						<span style="color: red; font-weight: bolder;">!</span>
					<?php endif;?>
										
					<span class="<?=$service_reservation['color']?>"><?=bpv_format_date($service_reservation['start_date'], DATE_FORMAT).': '?></span>
					<?php if(is_allow_to_edit($customer_booking['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
					<a id="<?='info_'.$service_reservation['id']?>" href="<?=site_url('bookings/edit-sr/'.$service_reservation['id'])?>" style="text-decoration: none;">
						<?=$service_reservation['service_name'].' - '?>						
					</a>
					<?php else:?>
						<?=$service_reservation['service_name'].' - '?>
					<?php endif;?>
					
					<span id="<?='payment_info_'.$service_reservation['id']?>" class="<?=$service_reservation['re_color']?>" style="cursor: pointer;"><?=lang($reservation_status[$service_reservation['reservation_status']])?>
					
						<?php if($service_reservation['warning'] == "normal"):?>									
							<img  style="border: 0; width: 10px; height: 10px;" src="<?=base_url() .'media/warning.png'?>">
						<?php elseif($service_reservation['warning'] == "high"):?>
							<img style="border: 0; width: 10px; height: 10px;" src="<?=base_url() .'media/warning.gif'?>">
						<?php endif;?>
					</span>
					
					
				<?php endforeach;?>	
			</td>
			<td valign="top" class="<?=$customer_booking['ticket_payment_color']?>"><?=bpv_format_date($customer_booking['request_date'], DATE_TIME_FORMAT)?></td>
			
			<td valign="top" class="<?=$customer_booking['color']?>"><?=bpv_format_date($customer_booking['start_date'])?></td>
			<!-- 
			<td valign="top">
				<span class="<?=$customer_booking['meeting_color']?>" style="cursor: pointer;" id="<?='mt_'.$customer_booking['id']?>"><?=bpv_format_date($customer_booking['meeting_date'], DATE_FORMAT)?></span>				
			</td>
			 -->
			<td align="center" valign="top" class="<?=$customer_booking['status_color']?>">
				<span style="cursor: pointer;" id="<?='note_'.$customer_booking['id']?>">
					<?=lang($status[$customer_booking['status']])?>
				</span>
			</td>
			
			<td align="center" valign="top"><?=$customer_booking['username']?></td>
			
			<td valign="top" align="center" class="payment_due <?=$customer_booking['p_due_color']?>" <?php if (!isset($search_criteria['payment_due']) || $search_criteria['payment_due'] == "hide"):?> style="display: none;" <?php endif;?>>
				<?=bpv_format_date($customer_booking['payment_due'], DATE_FORMAT)?>
			</td>
			
			<td valign="top" align="right" class="payment_amount" <?php if (!isset($search_criteria['payment_amount']) || $search_criteria['payment_amount'] == "hide"):?> style="display: none;" <?php endif;?>>
				<?=number_format($customer_booking['payment_amount'], CURRENCY_DECIMAL)?>
			</td>
			
			<td valign="top" align="right">
				<?php if(is_show_net_sell_profit($customer_booking['username'], $customer_booking['user_id'])):?>				
					<?=number_format($customer_booking['net_price'], CURRENCY_DECIMAL)?>
				<?php else:?>
					&nbsp;
				<?php endif;?>
			</td>
			
			<td valign="top" align="right">				
				
				<?php if(is_show_net_sell_profit($customer_booking['username'], $customer_booking['user_id'])):?>				
					<?=number_format($customer_booking['selling_price'], CURRENCY_DECIMAL)?>
				<?php else:?>
					&nbsp;
				<?php endif;?>
				
			</td>
			
			<td valign="top" align="right" class="actual_selling" <?php if (!isset($search_criteria['actual_selling']) || $search_criteria['actual_selling'] == "hide"):?> style="display: none;" <?php endif;?>>
								
				<?php if(is_show_net_sell_profit($customer_booking['username'], $customer_booking['user_id'])):?>				
					<?=number_format($customer_booking['actual_selling'], CURRENCY_DECIMAL)?>
				<?php else:?>
					&nbsp;
				<?php endif;?>
			</td>
				
			<td valign="top" align="right">
				<?php if(is_show_net_sell_profit($customer_booking['username'], $customer_booking['user_id'])):?>				
					<?=number_format($customer_booking['selling_price'] - $customer_booking['net_price'], CURRENCY_DECIMAL)?>
				<?php else:?>
					&nbsp;
				<?php endif;?>
				
			</td>
			
			<td valign="top" align="right" class="actual_profit" <?php if (isset($search_criteria['actual_profit']) && $search_criteria['actual_profit'] == "hide"):?> style="display: none;" <?php endif;?>>
				
				<?php if(is_show_net_sell_profit($customer_booking['username'], $customer_booking['user_id'])):?>				
					
					<?php if (is_profit_warning($customer_booking['selling_price'], $customer_booking['net_price'], $customer_booking['actual_profit'], $customer_booking['status'])):?>
						<img style="border: 0; width: 10px; height: 10px; float: left;" src="<?=base_url() .'media/warning.gif'?>">
					<?php endif;?>
					<?=number_format($customer_booking['actual_profit'], CURRENCY_DECIMAL)?>
					
				<?php else:?>
					&nbsp;
				<?php endif;?>
				
			</td>
				
		
			
			<td align="left" valign="top" class="col-action">
				
				
				<a style="border: 0; text-decoration: none;" href="<?=site_url('bookings/sr/' . $customer_booking['id'])?>">
					<span class="fa fa-list"></span>
				</a>
				
				<?php $privilege = get_right(DATA_CUSTOMER_BOOKING, $customer_booking['user_created_id'])?>
				
				<?php if(($privilege == EDIT_PRIVILEGE || $privilege == FULL_PRIVILEGE) &&
						is_allow_to_edit($customer_booking['user_created_id'], DATA_CUSTOMER_BOOKING, $customer_booking['user_id'])):?>
				
				<a style="border: 0; text-decoration: none;" href="<?=site_url('bookings/edit/'.$customer_booking['id'])?>">
					<span class="fa fa-edit"></span>
				</a>
				<?php endif;?>
				
				<?php if($privilege == FULL_PRIVILEGE && is_allow_deletion($customer_booking['user_created_id'], DATA_CUSTOMER_BOOKING, $customer_booking['user_id'], $customer_booking['approve_status'])):?>
				
				<a style="border: 0; text-decoration: none;" href="<?=site_url('bookings/delete/'.$customer_booking['id'])?>" onclick="return deletecb()">
					<span class="fa fa-times"></span>
				</a>
				<?php endif;?>
				
				
		
			</td>
		</tr>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="16" align="center" valign="middle"><label class="error"><?=lang('customer_booking_not_found')?></label></td>
		</tr>
		<?php endif ; ?>	
		</tbody>	
	</table>
</div>

	<?php if (count($customer_bookings) >0 ) :?>	
		<div class="clearfix" style="margin-top:10px">
			<?=$paging_info['paging_links']?>
			<p class="paging-txt pull-right">
				<?=$paging_info['paging_text']?>
			</p>
		</div>
	<?php endif ; ?>
	
	
</form>
	
<p>
	<script type="text/javascript">

		function show_task(id) {
			
			var url = "<?=site_url('bookings/task_management/')?>";
			
			window.open(url, '_blank', 'width=1200,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=50,screeny=50');
			
		}

		function get_today_task() {
			$.ajax({
				url: "customer/task_management/get_task_info/",
				type: "GET",
				success:function(value){
					$('#today_task').html(value);
				}
			});
		}
	
		function sort(column){
			var sort_column = document.frm.sort_column.value;

			var sort_order = document.frm.sort_order.value;

			document.frm.sort_column.value = column;
			
			if (column != sort_column){
				if (column == 'c.full_name'){
					document.frm.sort_order.value = 'asc';
				} else {
					document.frm.sort_order.value = 'desc';
				}
			} else {
				if (sort_order == 'desc'){
					document.frm.sort_order.value = 'asc';
				} else {
					document.frm.sort_order.value = 'desc';
				}
			}
			<?php if($action == ACTION_ADVANCED_SEARCH):?>
				document.frm.action.value = "advanced_search";
			<?php else:?>
				document.frm.action.value = "search";
			<?php endif;?>
			document.frm.submit();
		}
	

		function deletecb(id) {
			if (confirm("<?=lang('confirm_delete')?>")) {
				return true;
			}
			return false;
		}

		function showHideColumn(obj, column){

			$src = $(obj).attr("src");

			var display = "show";

			if ($src == "<?=base_url().'media/open.gif'?>"){
				
				$(obj).attr("src", "<?=base_url().'media/close.gif'?>");

				$('td.'+column).hide();

				display = "hide";
				
			} else {
				$(obj).attr("src", "<?=base_url().'media/open.gif'?>");

				$('td.'+column).show();
			}
			
			$.ajax({
				url: "<?=site_url('bookings/bookings/').'/showHideActualSellProfit'?>",
				type: "POST",
				data: {	
					td_column: column,
					td_show: display								
				},
				success:function(value){									
					//alert(value);
				}
			});
		}

		function show_email(id) {
			
			var url = "<?=site_url('bookings/email/index/')?>" + "/" + id;
			
			window.open(url, '_blank', '<?=$atts?>');
			
		}

		function set_customer_autocomplete(){

			 $("#customer_auto").autocomplete({
				 
				 source: function( request, response ) {
					 
					 $.ajax({
						 url: "<?=site_url('customer/customer_booking').'/search_customer/'?>",
						 dataType: "json",
						 type: "POST",
						 data: {							 
							 customer_name: request.term
						 },
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								 return {
									 label: (item.title == 1 ? 'Mr.' : 'Ms.')+item.full_name,
									 value: item.full_name,
									 id: item.id
							 	}
					 		}));

					 	}
					 });
				 
				 },
				 minLength: 2,
				 select: function( event, ui ) {					 
					 $("#customer").val(ui.item.id);
					 $("#customer_auto").val(ui.item.value);
					 search('<?=ACTION_SEARCH?>');
				 },
				 open: function() {
					$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
				 },
				 close: function() {
				 	$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
				 }
			});
			
		}

		$(document).ready(function() 
		{
			//set_customer_autocomplete();	
				
			 <?php if (count($customer_bookings) >0 ) :?>			  
				<?php foreach ($customer_bookings as $customer_booking) :?>

				var id = "#<?=get_customer_unique_id($customer_booking)?>";

				var content = "<?=get_customer_tip_content($customer_booking)?>";
					
		
				$(id).popover({'html':true, 'content':content,'trigger':'hover'});
				
				
				<?php if ($customer_booking['approve_note'] != ''):?>
					<?php 
						$content = format_tip_conntent($customer_booking['approve_note']);
					?>
					
					$("#<?='b_approve_'.$customer_booking['id']?>").popover({'html':true, 'content':"<?=$content?>",'trigger':'hover'});						
				
				<?php endif;?>

				<?php if(isset($customer_booking['payment_warning_message'])):?>

					$("#customer_payment_warning_<?=$customer_booking['id']?>").popover({'html':true, 'content':'<?=$customer_booking['payment_warning_message']?>','trigger':'hover'});
					
				<?php endif;?> 
				
				<?php if ($customer_booking['meeting_address'] != ''):?>
					<?php 
						$content = format_tip_conntent($customer_booking['meeting_address']);
					?>
					$("#<?='mt_'.$customer_booking['id']?>").popover({'html':true, 'content':"<?=$content?>",'trigger':'hover'});				
						
				<?php endif;?>

				<?php if ($customer_booking['note'] != ''):?>
					<?php 
						$content = format_tip_conntent($customer_booking['note']);
					?>

					$("#<?='note_'.$customer_booking['id']?>").popover({'html':true, 'content':"<?=$content?>",'trigger':'hover'});						
					
				<?php endif;?>

				<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>
					<?php 
						$content = get_sr_tip_content($service_reservation, $customer_booking);
					?>
					<?php if ($content != ''):?>
						$("#<?='info_'.$service_reservation['id']?>").popover({'html':true, 'content':"<?=$content?>",'trigger':'hover'});			
				
					<?php endif;?>
				
				
					<?php if($service_reservation['reservation_status'] == 1 || $service_reservation['reservation_status'] == 2):?>
						$("#<?='payment_info_'.$service_reservation['id']?>").click(
							function(){
								//show_email(<?=$service_reservation['id']?>);
								alert('Give me $10 for updating this functions!');
							}	
						);
					<?php endif;?>
				
					<?php if($service_reservation['warning_message'] != ""):?>
	
						$("#<?='payment_info_'.$service_reservation['id']?>").popover({'html':true, 'content':"<?=$service_reservation['warning_message']?>",'trigger':'hover'});
						
					<?php endif;?>

			
				
				<?php endforeach;?>
				
				<?php endforeach;?>
			 <?php endif;?>

			 //get_today_task();
		});
	</script>

</p>

