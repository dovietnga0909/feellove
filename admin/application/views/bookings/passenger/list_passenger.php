<p>
		<b><?=($customer_booking['title']==1? 'Mr.':'Ms.')?>
		<?=$customer_booking['customer_name']?>
		</b>
		(Phone: <a href="tel:<?=$customer_booking['phone']?>"><?=$customer_booking['phone']?></a>, Email: <a href="mailto:<?=$customer_booking['email']?>"><?=$customer_booking['email']?></a>,
		IP Address: <?=$customer_booking['ip_address']?>)
</p>

<a href="<?=site_url('bookings/create-p/'.$customer_booking['id'])?>/" type="button" class="btn btn-primary pull-right btn-sm"><?=lang('title_create_passenger')?></a>

<p>
	<b>Booking Time: </b> <?=bpv_format_date($customer_booking['date_created'], DATE_TIME_FORMAT)?>
	&nbsp; &nbsp;
	<b><?=lang('start_date')?>:</b> <?=bpv_format_date($customer_booking['start_date'], DATE_FORMAT)?> 
	&nbsp; &nbsp;
	<b><?=lang('end_date')?>:</b> <?=bpv_format_date($customer_booking['end_date'], DATE_FORMAT)?> 
</p>

<?php 
	$has_ticket_number = false;
	$has_nationality = false;
	$has_passport = false;
	$has_passport_exp = false;
	
	foreach ($customer_booking['flight_users'] as $key => $passenger){
		if(!empty($passenger['ticket_number'])) $has_ticket_number = true;
		if(!empty($passenger['nationality'])) $has_nationality = true;
		if(!empty($passenger['passport'])) $has_passport = true;
		if(!empty($passenger['passportexp'])) $has_passport_exp = true;
	}
?>
	
<div class="booking-table">
	
	<table>
		<thead>
			<tr>
				<td>#</td>
				<td><?=lang('passenger_name')?></td>
				<td><?=lang('passenger_gender')?></td>
				<td><?=lang('passenger_baggage')?></td>
				<?php if($has_ticket_number):?>
					<td><?=lang('ticket_number')?></td>
				<?php endif;?>
				<td><?=lang('passenger_birthday')?></td>
				
				<?php if($has_nationality):?>
					<td><?=lang('passenger_nationality')?></td>
				<?php endif;?>
				
				<?php if($has_passport):?>
					<td><?=lang('passenger_passport')?></td>
				<?php endif;?>
				
				<?php if($has_passport_exp):?>
					<td><?=lang('passenger_passportexp')?></td>
				<?php endif;?>
				
				<td><?=lang('field_last_modified')?></td>
				<td><?=lang('field_action')?></td>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach ($customer_booking['flight_users'] as $key => $passenger):?>
				
				<tr>
					<td><?=($key+1)?></td>
					<td>
						<a href="/admin/bookings/edit-p/<?=$passenger['id']?>/">
							<?=$passenger['first_name']?>, <?=$passenger['last_name']?>
						</a>
					</td>
					
					<td>
						
						<?php 
							$a_txt = $passenger['type'] == 1? 'adult' : ($passenger['type'] == 2? 'child' : 'infant');
						?>
						
						<?=$a_txt?>, <?=$passenger['gender'] == 1? 'male':'female'?>

					
					</td>
					
					<td>
						<?=$passenger['checked_baggage']?>
					</td>
					
					<?php if($has_ticket_number):?>
						<td>
							<?=$passenger['ticket_number']?>
						</td>
					<?php endif;?>
					
					<td align="center">
						<?=is_null($passenger['birth_day'])? '' : date(DATE_FORMAT, strtotime($passenger['birth_day']))?>
					</td>
					
					<?php if($has_nationality):?>
						<td align="center">
							<?=!empty($passenger['nationality'])? $passenger['nationality'] : ''?>
						</td>
					<?php endif;?>
					
					<?php if($has_passport):?>
						<td align="center">
							<?=!empty($passenger['passport'])? $passenger['passport'] : ''?>
						</td>
					<?php endif;?>
					
					<?php if($has_passport_exp):?>
						<td align="center">
							<?=!empty($passenger['passportexp'])? date(DATE_FORMAT, strtotime($passenger['passportexp'])) : ''?>
						</td>
					<?php endif;?>
					
					<td>
						<?php if(!empty($passenger['last_modified_by'])):?>
							<?=get_last_update($passenger['date_modified'])?> <?=lang('by')?> <?=$passenger['last_modified_by']?>
						<?php endif;?>
					</td>
					
					<td class="col-action" align="right">
					
						<a href="/admin/bookings/edit-p/<?=$passenger['id']?>/">
							<span class="fa fa-edit"></span>
						</a>
						
						<a href="/admin/bookings/delete-p/<?=$passenger['id']?>/" onclick="return confirm('Give Mr.Khuyen $10 to delete this Passenger?')">
							<span class="fa fa-times"></span>
						</a>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>