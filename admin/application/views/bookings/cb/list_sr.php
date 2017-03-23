<p>
	<b>
		<?php if(isset($c_titles[$customer_booking['title']])):?>
			<?=(lang($c_titles[$customer_booking['title']]))?>
		<?php endif;?>
		<?=$customer_booking['customer_name']?>
	</b>
	(Phone: <a href="tel:<?=$customer_booking['phone']?>"><?=$customer_booking['phone']?></a>, Email: <a href="mailto:<?=$customer_booking['email']?>"><?=$customer_booking['email']?></a>,
	IP Address: <?=$customer_booking['ip_address']?>)
	
	
	
	<a href="<?=site_url('bookings/create-sr/'.$customer_booking['id'])?>" type="button" class="btn btn-primary pull-right btn-xs"><?=lang('create_service_reservation')?></a>
</p>

<p>
	<b>Booking Time: </b> <?=bpv_format_date($customer_booking['date_created'], DATE_TIME_FORMAT)?>
	&nbsp; &nbsp;
	<b><?=lang('start_date')?>:</b> <?=bpv_format_date($customer_booking['start_date'], DATE_FORMAT)?> 
	&nbsp; &nbsp;
	<b><?=lang('end_date')?>:</b> <?=bpv_format_date($customer_booking['end_date'], DATE_FORMAT)?> 
	
	<?php if(!empty($customer_booking['vnisc_booking_code'])):?>
		&nbsp; &nbsp;
		<b><?=lang('vnisc_booking_code')?>:</b> <?=$customer_booking['vnisc_booking_code']?>
	<?php endif;?> 
</p>

<?php 
	$has_flight_booking = false;
	foreach ($customer_booking['service_reservations'] as $key => $sr){
		if($sr['reservation_type'] == RESERVATION_TYPE_FLIGHT) $has_flight_booking = true; break;
	}	
?>

<div class="booking-table">
	
	<table>
		<thead>
			<tr>
				<td><?=lang('s-date')?></td>
				<td><?=lang('e-date')?></td>
				<td><?=lang('service_name')?></td>
				<td><?=lang('status')?></td>
				<?php if($has_flight_booking):?>
					<td><?=lang('pnr')?></td>
				<?php endif;?>
				
				<td><?=lang('net_price')?></td>
				<td><?=lang('selling_price')?></td>
				<td><?=lang('profit')?></td>
				<td><?=lang('field_action')?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				
				<?php if($has_flight_booking):?>
					<td>&nbsp;</td>
				<?php endif;?>
				<td align="right"><b><?=number_format($customer_booking['net_price'], CURRENCY_DECIMAL)?></b></td>
				<td align="right"><b><?=number_format($customer_booking['selling_price'], CURRENCY_DECIMAL)?></b></td>
				<td align="right"><b><?=number_format($customer_booking['selling_price'] - $customer_booking['net_price'], CURRENCY_DECIMAL)?></b></td>
				<td align="center">
					<a style="text-decoration:underline;" href="<?=site_url('bookings/edit/'.$customer_booking['id'])?>">Go CB</a>
				</td>
			</tr>
			
			<?php foreach ($customer_booking['service_reservations'] as $key => $sr):?>
				
				<tr>
					<td><span class="<?=$sr['color']?>"><?=bpv_format_date($sr['start_date'], DATE_FORMAT)?></span></td>
					<td><?=bpv_format_date($sr['end_date'], DATE_FORMAT)?></td>
					
					<td>
						
						<?php if($sr['detail_reservation'] != ""):?>					
							<span style="color: red; font-weight: bolder;">!</span>
						<?php endif;?>
											
						
						<?php if(is_allow_to_edit($customer_booking['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
						
						<a id="<?='info_'.$sr['id']?>" href="<?=site_url('bookings/edit-sr/'.$sr['id'])?>" style="text-decoration: none;">
							<?=$sr['service_name']?>						
						</a>
						
						<?php else:?>
						
							<?=$sr['service_name']?>
							
						<?php endif;?>
						
						
					
					</td>
					
					<td align="center">
						<span id="<?='payment_info_'.$sr['id']?>" class="<?=$sr['re_color']?>" style="cursor: pointer;">
							<?=lang($reservation_status[$sr['reservation_status']])?>
						
							<?php if($sr['warning'] == "normal"):?>									
								<img  style="border: 0; width: 10px; height: 10px;" src="<?=base_url() .'media/warning.png'?>">
							<?php elseif($sr['warning'] == "high"):?>
								<img style="border: 0; width: 10px; height: 10px;" src="<?=base_url() .'media/warning.gif'?>">
							<?php endif;?>
						</span>
					</td>
					

					<?php if($has_flight_booking):?>
						<td><?=($sr['flight_pnr'])?></td>
					<?php endif;?>
					
					<td align="right"><?=number_format($sr['net_price'], CURRENCY_DECIMAL)?></td>
					<td align="right"><?=number_format($sr['selling_price'], CURRENCY_DECIMAL)?></td>
					<td align="right"><?=number_format($sr['selling_price'] - $sr['net_price'], CURRENCY_DECIMAL)?></td>
					<td align="right" class="col-function">
						<?php $privilege = get_right(DATA_SERVICE_RESERVATION, $sr['user_created_id'])?>
					
						<?php if(($privilege == EDIT_PRIVILEGE || $privilege == FULL_PRIVILEGE) &&
								is_allow_to_edit($sr['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
						<a href="<?=site_url('bookings/edit-sr/'.$sr['id'])?>">
							<span class="fa fa-edit"></span>
						</a>
						<?php endif;?>
						
						<?php if($privilege == FULL_PRIVILEGE || is_allow_deletion($sr['user_created_id'], DATA_SERVICE_RESERVATION, $customer_booking['user_id'])):?>
							&nbsp;
							<a href="<?=site_url('bookings/delete-sr/'.$sr['id'])?>" onclick="return deletesr()">
								<span class="fa fa-times"></span>
							</a>
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<script type="text/javascript">

	function deletesr(id) {
		if (confirm("<?=lang('confirm_delete')?>")) {
			return true;
		}
		return false;
	}

<?php foreach ($customer_booking['service_reservations'] as $key => $service_reservation):?>

		<?php 
			$content = get_sr_tip_content($service_reservation, $customer_booking);
		?>
		<?php if(!empty($content)):?>
			$("#<?='info_'.$service_reservation['id']?>").popover({'html':true, 'content':"<?=$content?>",'trigger':'hover','placement':'top'});
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
	
		$("#<?='payment_info_'.$service_reservation['id']?>").popover({'html':true, 'content':"<?=$service_reservation['warning_message']?>",'trigger':'hover','placement':'top'});
		
	<?php endif;?>


<?php endforeach;?>
	
</script>