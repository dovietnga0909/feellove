	
<style type="text/css">

.ui-autocomplete-loading {
	background: white url('/admin/css/images/ui-anim_basic_16x16.gif') right center no-repeat;
}

</style>

	<div id="search" <?php if($is_advanced_search):?> style="display: none;" <?php endif;?>>
		<?=$search?>
	</div>
	<div id="advanced_search" <?php if(!$is_advanced_search):?> style="display: none;" <?php endif;?>>
		<?=$advanced_search?>
	</div>
	<br/>
	<input type="hidden" value="<?=$search_criteria['sort_column']?>" name="sort_column"></input>
	<input type="hidden" value="<?=$search_criteria['sort_order']?>" name="sort_order"></input>	
	<table class="border" cellpadding="0" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td align="center" width="10%">
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
				
				<td align="center" width="6%">
            		
            		<a href="javascript:void(0)" onclick="sort('sr.start_date')"><?=lang('s-date')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.start_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
            	</td>
				<td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.end_date')"><?=lang('e-date')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.end_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>   	        
	            <td align="center">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.service_name')"><?=lang('service_name')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.service_name"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	            
	            <?php if($is_accounting || isset($search_criteria['multi_partners'])):?>
	            	 <td align="center">
		            	Partner
		            </td>
	            <?php endif;?>
	            
	            <td align="center" width="6%">
	            	<?=lang('r-status')?>
	            </td>
	            <!-- 
	            <td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.reserved_date')"><?=lang('r-date')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.reserved_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	             -->
	            <td align="center" width="6%">
	            	<?=lang('payment')?>
	            </td>
	          	
	          	<td align="center" width="6%">
	            	<a href="javascript:void(0)" onclick="sort('sr.1_payment_due')"><?=lang('p-due')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.1_payment_due"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	            
	            <td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.1_payment_date')"><?=lang('p-date')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.1_payment_date"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	            
	            <td align="center" width="6%">
	            	<?=lang('sale')?>
	            </td>	            
	            <td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.net_price')"><?=lang('net_price')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.net_price"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	            <td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.selling_price')"><?=lang('selling_price')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.selling_price"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>
	             <td align="center" width="6%">	            	
	            	<a href="javascript:void(0)" onclick="sort('sr.profit')"><?=lang('profit')?>
	            		<?php if ($search_criteria['sort_column'] == "sr.profit"):?>	            			
	            			<?php if($search_criteria['sort_order'] == 'desc'):?>
	            				
	            				<img border="none" alt="<?=lang('cruise_changeorder_down')?>" title="<?=lang('tour_changeorder_down')?>" src="<?=base_url() .'media/arrowdown.gif'?>">
	            			
	            			<?php else:?>
	            				<img border="none" alt="<?=lang('cruise_changeorder_up')?>" title="<?=lang('tour_changeorder_up')?>" src="<?=base_url() .'media/arrowup.gif'?>">
	            			<?php endif;?>
	            		<?php endif;?>
	            	</a>
	            </td>	                         
	            <td align="center" width="6%">
	            	<?=lang('common_function')?>
	            </td>
	       </tr>
	   </thead>
		<tr>
			<td>
				
				<input id="customer_auto" name="customer_auto" value="<?=isset($search_criteria['customer_name'])? $search_criteria['customer_name'] : ''?>"/>
				<input name="customer" id="customer" type="hidden" value="<?=(isset($search_criteria['customer_id'])? $search_criteria['customer_id'] : '')?>">
					 
			</td>
			<td colspan="2">&nbsp;</td>
			<td align="right">r.n: <b><?=$room_night?></b> - incentive: <b><?=$room_night_incentive?></b></td>
			
			<?php if($is_accounting || isset($search_criteria['multi_partners'])):?>
	            	<td>&nbsp;</td>
	        <?php endif;?>
	            
			<td>
				<select name="reservation_status" onchange="search();" style="width: 60px;">				
				
					<option value=""><?=lang('common_select_all')?></option>				
								
					<?php foreach ($reservation_status as $key => $value) :?>
																
						<option value="<?=$key?>" <?=set_select('reservation_status', strval($key), array_key_exists('reservation_status',$search_criteria) && $search_criteria['reservation_status'] == strval($key)?TRUE:FALSE)?>><?=translate_text($value)?></option>		
					
					<?php endforeach ;?>	
								
				</select>
			</td>
			<!-- 
			<td>&nbsp;</td>
			 -->
			<td align="right">
				<b><?=number_format($r_payment, CURRENCY_DECIMAL)?></b>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>	
			<td>
				<select name="sale" onchange="search();" style="width: 60px;">	
					<option value=""><?=lang('common_select_all')?></option>			
					<?php foreach ($sales as $k=>$value) :?>
																					
						<option value="<?=$value['id']?>" <?=set_select('sale', $value['id'],isset($search_criteria['user_id']) &&  $search_criteria['user_id'] == $value['id']? true: false)?>><?=$value['user_name']?></option>
						
					<?php endforeach ;?>				
				</select>
			</td>
			<td align="right"><b><?=number_format($net, CURRENCY_DECIMAL)?></b></td>
			<td align="right"><b><?=number_format($sel, CURRENCY_DECIMAL)?></b></td>
			<td align="right"><b><?=number_format($sel - $net, CURRENCY_DECIMAL)?></b></td>
			<td>&nbsp;</td>
		</tr>
	  <?php if (count($service_reservations) >0 ) :?>
	  
		<?php foreach ($service_reservations as $service_reservation) :?>
			<?php 
				$row_span = "";
				if ($service_reservation['2_payment'] != 0)
				{
					$row_span = "rowspan=\"2\"";
				}
			?>
			<tr>
				
				<td <?=$row_span?>>
				
					<?php 
						$title = "";
						if ($service_reservation['title'] == '1'){
							$title = "Mr.";
						} else {
							$title = "Ms.";
						}
					?>
					<a id="<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>" href="javascript:void(0)" onclick="view_customer(<?=$service_reservation['customer_id']?>)"><?=$title.$service_reservation['full_name']?></a>
				
				
				</td>
				
				<td <?=$row_span?> align="center" class="<?=$service_reservation['start_date_color']?>"><?=$this->timedate->format($service_reservation['start_date'], DATE_FORMAT)?></td>
				
				<td <?=$row_span?> align="center"><?=$this->timedate->format($service_reservation['end_date'], DATE_FORMAT)?></td>
				
				<td <?=$row_span?> align="left">
					
					<?php if($service_reservation['detail_reservation'] != ""):?>					
						<span style="color: red; font-weight: bolder;">!</span>
					<?php endif;?>
					
					<?php if(is_allow_to_edit($service_reservation['user_created_id'], DATA_CUSTOMER_BOOKING, $service_reservation['user_id'])):?>
					<a id="info_<?=$service_reservation['id']?>" href="javascript:void(0);" onclick="edit(<?=$service_reservation['customer_booking_id']?>, <?=$service_reservation['id']?>);" style="text-decoration: none;">
						<?=$service_reservation['service_name']?>
					</a>
					<?php else:?>				
						<?=$service_reservation['service_name']?>
					<?php endif;?>
					
					<?php if($service_reservation['origin_id'] > 0):?>
						- <span style="cursor:pointer" id="package_<?=$service_reservation['id']?>"><?=lang('package')?></span>
					<?php endif;?>
				</td>
				
				<?php if($is_accounting || isset($search_criteria['multi_partners'])):?>
	            	 <td align="center" <?=$row_span?>>
		            	<?=$service_reservation['partner_name']?>
		            </td>
	            <?php endif;?>
	            
				<td <?=$row_span?> align="center" class="<?=$service_reservation['status_color']?>"><?=translate_text($reservation_status[$service_reservation['reservation_status']])?></td>
				
				<!-- 
				<td <?=$row_span?> align="center"><?=$this->timedate->format($service_reservation['reserved_date'], DATE_FORMAT)?></td>
				 -->
				 
				<td align="right"><?=number_format($service_reservation['1_payment'], CURRENCY_DECIMAL)?></td>	
						
				<td align="center" class="<?=$service_reservation['p_due_color_1']?>"><?=$this->timedate->format($service_reservation['1_payment_due'], DATE_FORMAT)?></td>
				
				<td align="center"><?=$this->timedate->format($service_reservation['1_payment_date'], DATE_FORMAT)?></td>
				
				<td <?=$row_span?> align="center"><?=$service_reservation['user_name']?></td>
				
				<td <?=$row_span?> valign="top" align="right">
					<?php if(is_show_net_sell_profit($service_reservation['user_name'], $service_reservation['user_id'])):?>					
						<?=number_format($service_reservation['net_price'], CURRENCY_DECIMAL)?>
					<?php else:?>
						&nbsp;
					<?php endif;?>
				</td>
				
				<td <?=$row_span?> valign="top" align="right">
					<?php if(is_show_net_sell_profit($service_reservation['user_name'], $service_reservation['user_id'])):?>					
						<?=number_format($service_reservation['selling_price'], CURRENCY_DECIMAL)?>
					<?php else:?>
						&nbsp;
					<?php endif;?>
				</td>
				
				<td <?=$row_span?> valign="top" align="right">
					<?php if(is_show_net_sell_profit($service_reservation['user_name'], $service_reservation['user_id'])):?>					
						<?=number_format($service_reservation['selling_price'] - $service_reservation['net_price'], CURRENCY_DECIMAL)?>
					<?php else:?>
						&nbsp;
					<?php endif;?>
				</td>
				
				<td <?=$row_span?> align="center" class="column_function">
					<?php $privilege = get_right(DATA_SERVICE_RESERVATION, $service_reservation['user_created_id'])?>
					
					<img alt="" src="<?=base_url() .($service_reservation['email_id'] == 0 ? 'media/email.gif' : 'media/email_send.gif')?>" style="cursor: pointer;" onclick="send_email(<?=$service_reservation['id']?>)">
					
					<?php if(($privilege == EDIT_PRIVILEGE || $privilege == FULL_PRIVILEGE) &&
							is_allow_to_edit($service_reservation['user_created_id'], DATA_SERVICE_RESERVATION, $service_reservation['user_id'])):?>
					&nbsp;
					<a href="javascript:void(0)" onclick="edit(<?=$service_reservation['customer_booking_id']?>,<?=$service_reservation['id']?>);">
						<img width="10" height="10" alt="<?=lang('common_button_edit')?>" title="<?=lang('common_button_edit')?>" src="<?=base_url() .'media/Edit.png'?>">
					</a>
					<?php endif;?>
					
					<?php if($privilege == FULL_PRIVILEGE || is_allow_deletion($service_reservation['user_created_id'], DATA_SERVICE_RESERVATION, $service_reservation['user_id'])):?>
					&nbsp;
					<a href="javascript:void(0)" onclick="deleteServiceReservation(<?=$service_reservation['id']?>);">
						<img width="10" height="10" alt="<?=lang('common_button_del')?>" title="<?=lang('common_button_del')?>" src="<?=base_url() .'media/Delete.png'?>">
					</a>
					<?php endif;?>
				</td>
			</tr>
			<?php if ($service_reservation['2_payment'] != 0):?>
				<tr>
					
					<td align="right"><?=number_format($service_reservation['2_payment'], CURRENCY_DECIMAL)?></td>	
						
					<td align="center" class="<?=$service_reservation['p_due_color_2']?>"><?=$this->timedate->format($service_reservation['2_payment_due'], DATE_FORMAT)?></td>
				
					<td align="center"><?=$this->timedate->format($service_reservation['2_payment_date'], DATE_FORMAT)?></td>
					
				</tr>
			<?php endif;?>
		<?php endforeach ;?>
		
		<?php else: ?>			
		<tr>
			<td colspan="12" align="center" valign="middle"><label class="error"><?=lang('service_reservation_not_found')?></label></td></tr>
		<?php endif ; ?>	
		
	</table>
	
	<?php if (count($service_reservations) >0 ) :?>	
		<div class="paging" align="right">
			<?=$paging_text?>&nbsp;&nbsp;&nbsp;<?=$this->pagination->create_links()?>
		</div>
	<?php endif ; ?>
	
	
	<script language="javascript">

		function sort(column){
			var sort_column = document.frm.sort_column.value;
	
			var sort_order = document.frm.sort_order.value;
	
			document.frm.sort_column.value = column;
			
			if (column != sort_column){
				if (column == 'sr.service_name'){
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
			<?php if($is_advanced_search):?>
				document.frm.action_type.value = "advanced_search";
			<?php else:?>
				document.frm.action_type.value = "search";
			<?php endif;?>
			document.frm.submit();
		}

		function view_advanced_search(){

			$("#advanced_search").show();

			$("#search").hide();
		}
	
		function edit(cb_id, service_reservation_id){

			document.frm.action = '<?=site_url('customer/service_reservation/index')?>' + '/'+ cb_id;
			
			document.frm.service_reservation_id.value = service_reservation_id;
			
			document.frm.action_type.value = "edit";
			
			document.frm.submit();
		}

		function deleteServiceReservation(service_reservation_id){

			if (confirm("<?=lang('common_message_delete')?>")) {
				
				document.frm.service_reservation_id.value = service_reservation_id;
				
				document.frm.action_type.value = "delete";
				
				document.frm.submit();

			}
		}

		function view_customer(c_id){

			document.frm.action = '<?=site_url('customer/customer/index')?>';
			
			document.frm.c_id.value = c_id;
			
			document.frm.action_type.value = "view";
			
			document.frm.submit();
		}

		function send_email(id){
			var url = "<?=site_url('customer/email/index/')?>" + "/" + id;
			
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
					 search();
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

			set_customer_autocomplete();
			
			<?php if (count($service_reservations) >0 ) :?>
			  
			<?php foreach ($service_reservations as $service_reservation) :?>

			var id = "#<?=$service_reservation['customer_id'].'_'.$service_reservation['id']?>";

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

			set_tip("#info_<?=$service_reservation['id']?>", "<?=$str_description?>", 'rightTop', 'leftMiddle',320);
			
			<?php endif;?>
				

			<?php if ($service_reservation['origin_id'] > 0):?>
				
				set_tip("#package_<?=$service_reservation['id']?>", "<?='<b>'.$service_reservation['origin'].'</b>'?>", 'rightTop', 'leftMiddle',320);
			<?php endif;?>

			<?php endforeach;?>

			<?php endif;?>
		
		});
	
	</script>