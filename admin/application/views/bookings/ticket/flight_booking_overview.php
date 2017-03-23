<style>
	.panel-title{
		color: #004f8c;
	}
</style>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
    	<span class="fa fa-info"></span>
    	<?=lang('bo_contact')?>
    	
    	<a class="pull-right" target="blank" href="/admin/customers/edit/<?=$contact['id']?>"><?=lang('bo_c_edit')?></a>
    </h3>
  </div>
  <div class="panel-body">
  		<div class="row">
  			<div class="col-xs-2"><b><?=lang('bo_c_full_name')?>:</b></div>
  			<div class="col-xs-10">
  				<?=lang($c_titles[$contact['gender']])?>. <?=$contact['full_name']?>
  			</div>
  		</div>
  		
  		<div class="row">
  			<div class="col-xs-2"><b><?=lang('bo_c_phone')?>:</b></div>
  			<div class="col-xs-10">
  				<?=$contact['phone']?>
  			</div>
  		</div>
  		
  		<div class="row">
  			<div class="col-xs-2"><b><?=lang('bo_c_email')?>:</b></div>
  			<div class="col-xs-10">
  				<?=$contact['email']?>
  			</div>
  		</div>
  		
  		<div class="row">
  			<div class="col-xs-2"><b><?=lang('bo_c_address')?>:</b></div>
  			<div class="col-xs-10">
  				<?=$contact['address']?> <?=$contact['city']?>
  			</div>
  		</div>
  </div>
</div>


<?php 
	$adt_price = 0;
	$chd_price = 0;
	$inf_price = 0;
	$bagge_kg = 0;
	$baggage_price = 0;
	$tax_fee = 0;
	
	$depart_routes = array();
	
	$return_routes = array();
		
	foreach ($srs as $sr)
	{	
		$adt_price += $sr['adt_price'];
		
		$chd_price += $sr['chd_price'];
		
		$inf_price += $sr['inf_price'];
		
		$bagge_kg += $sr['baggage_kg'];
		
		if ($sr['baggage_kg'] > 0){
		
			$baggage_price += $sr['selling_price'];
		
		}
		
		$tax_fee += $sr['tax_fee'];
		
		
		if(!empty($sr['flight_class'])){
				
			if($sr['flight_way'] == 'depart'){
				$depart_routes[] = $sr;
			} else {
				$return_routes[] = $sr;
			}
		}
		
	}
	
	$total_price = $adt_price + $chd_price + $inf_price + $baggage_price + $tax_fee;
?>

<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
      	<h3 class="panel-title">
      		<span class="fa fa-space-shuttle"></span>
      		<?=lang('bo_flight_route')?>
      		
      		<?php if(!$cb['is_flight_domistic']):?>
      			<?=$cb['flight_from']?> - <?=$cb['flight_to']?>, <?=date('d/m', strtotime($cb['flight_depart']))?>
      		<?php endif;?>
      	</h3>
      </div>
      
      <?php if($cb['is_flight_domistic']):?>
      		
      		<!-- Table -->
		      <table class="table table-bordered">
		        <thead>
		          <tr>
		            <th width="15%"><?=lang('bo_airline')?></th>
					<th width="10%"><?=lang('bo_flight_code')?></th>
					<th width="20%"><?=lang('bo_route')?></th>
					<th width="15%"><?=lang('bo_departure_date')?></th>
					<th width="10%"><?=lang('bo_departure_time')?></th>
					<th width="10%"><?=lang('bo_arrival_time')?></th>
					<th width="10%"><?=lang('bo_flight_class')?></th>
					<th width="10%"><?=lang('bo_flight_pnr')?></th>
		          </tr>
		        </thead>
		        <tbody>
		        	
		        	<?php foreach ($srs as $sr):?>
		        		<?php if(!empty($sr['flight_class'])):?>
		        		<tr>
			        		<td>
			        			<img src="http://flightbooking.bestpricevn.com/Images/Airlines/<?=$sr['airline']?>.gif">
			        			<?=$sr['airline_name']?>
			        		</td>
			        		<td><?=$sr['flight_code']?></td>
			        		<td><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? ' ('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? ' ('.$sr['flight_to_code'].')':''?></td>
			        		<td><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
			        		<td><?=$sr['departure_time']?></td>
			        		<td><?=$sr['arrival_time']?></td>
			        		<td><?=$sr['flight_class']?></td>
			        		<td><strong class="error"><?=$sr['flight_pnr']?></strong></td>
		        		</tr>
        				<?php endif;?>
		        	<?php endforeach;?>
		        	
		        </tbody>
		      </table>
      		
      <?php else:?>

    	
      <!-- Table -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="15%"><?=lang('bo_airline')?></th>
			<th width="10%"><?=lang('bo_flight_code')?></th>
			<th width="20%"><?=lang('bo_route')?></th>
			<th width="15%"><?=lang('bo_departure_date')?></th>
			<th width="10%"><?=lang('bo_departure_time')?></th>
			<th width="10%"><?=lang('bo_arrival_time')?></th>
			<th width="10%"><?=lang('bo_flight_class')?></th>
			<th width="10%"><?=lang('bo_flight_pnr')?></th>
          </tr>
        </thead>
        <tbody>
        		
        	<?php foreach ($depart_routes as $key=>$sr):?>
     
        		<tr>
	        		<td>
	        			<img src="http://flightbooking.bestpricevn.com/Images/Airlines/<?=$sr['airline']?>.gif">
	        			<?=$sr['airline_name']?>
	        		</td>
	        		<td><?=$sr['flight_code']?></td>
	        		<td><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? ' ('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? ' ('.$sr['flight_to_code'].')':''?></td>
	        		<td><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
	        		<td><?=$sr['departure_time']?></td>
	        		<td><?=$sr['arrival_time']?></td>
	        		<td><?=$sr['flight_class']?></td>
	        		<td><strong class="error"><?=$sr['flight_pnr']?></strong></td>
        		</tr>
        		
        		<?php if(isset($depart_routes[$key + 1])):?>
				
					<?php 
						$next_sr = $depart_routes[$key + 1];
						
						$delay = calculate_flying_delay($sr, $next_sr);
					?>
								
					<tr>
						<td colspan="8" align="right" style="font-size:12px"><?=lang_arg('bo_flight_wait', $next_sr['flight_from'], $delay['h'], $delay['m'])?></td>
					</tr>
					
				<?php endif;?>
        	
        	<?php endforeach;?>
        	
   
        </tbody>
        
      </table>
      
      <?php if(count($return_routes) > 0):?>
      
	      <div class="panel-heading">
	      	<h3 class="panel-title">
	      		<span class="fa fa-space-shuttle"></span>
	      		<?=lang('bo_flight_route')?>
	      		
	      		<?php if(!$cb['is_flight_domistic']):?>
	      			<?=$cb['flight_to']?> - <?=$cb['flight_from']?>, <?=date('d/m', strtotime($cb['flight_return']))?>
	      		<?php endif;?>
	      	</h3>
	      </div>
	      
	       <table class="table table-bordered">
      		<tbody>
      		<?php foreach ($return_routes as $key=>$sr):?>
     
        		<tr>
	        		<td width="15%">
	        			<img src="http://flightbooking.bestpricevn.com/Images/Airlines/<?=$sr['airline']?>.gif">
	        			<?=$sr['airline_name']?>
	        		</td>
	        		<td width="10%"><?=$sr['flight_code']?></td>
	        		<td width="20%"><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? '('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? '('.$sr['flight_to_code'].')':''?></td>
	        		<td width="15%"><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
	        		<td width="10%"><?=$sr['departure_time']?></td>
	        		<td width="10%"><?=$sr['arrival_time']?></td>
	        		<td width="10%"><?=$sr['flight_class']?></td>
	        		<td width="10%"><strong class="error"><?=$sr['flight_pnr']?></strong></td>
        		</tr>
        		
        		<?php if(isset($return_routes[$key + 1])):?>
				
					<?php 
						$next_sr = $return_routes[$key + 1];
						
						$delay = calculate_flying_delay($sr, $next_sr);
					?>
								
					<tr>
						<td colspan="8" align="right" style="font-size:12px"><?=lang_arg('bo_flight_wait', $next_sr['flight_from'], $delay['h'], $delay['m'])?></td>
					</tr>
					
				<?php endif;?>
        	
        	<?php endforeach;?>
        	</tbody>
        	
        	</table>
        	
        <?php endif;?>
      
      <?php endif;?>
      
</div>


<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
      	<h3 class="panel-title">
      		<span class="fa fa-users"></span>
      		<?=lang('bo_passenger')?>
      	</h3>
      </div>

      <!-- Table -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="5%">#</th>
            <th width="40%"><?=lang('bo_passenger_name')?></th>
			<th width="15%"><?=lang('bo_passenger_gender')?></th>
			<th width="20%"><?=lang('bo_passenger_baggage')?></th>
			<th width="20%"><?=lang('bo_passenger_birthday')?></th>
			
          </tr>
        </thead>
        <tbody>
         
         <?php foreach ($passengers as $key => $passenger):?>
				
				<tr>
					<td><?=($key+1)?></td>
					<td><b><?=$passenger['first_name']?>, <?=$passenger['last_name']?></b></td>
					
					<td>
						
						<?php 
							$a_txt = $passenger['type'] == 1? lang('bo_adult') : ($passenger['type'] == 2? lang('bo_child') : lang('bo_infant'));
						?>
						
						<?=$a_txt?>, <?=$passenger['gender'] == 1? lang('bo_male'):lang('bo_female')?>

					
					</td>
					
					<td>
						<?=$passenger['checked_baggage']?>
					</td>
					
					<td>
						<?=is_null($passenger['birth_day'])? '' : date(DATE_FORMAT, strtotime($passenger['birth_day']))?>
					</td>
					
					
				</tr>
			<?php endforeach;?>
         
        </tbody>
      </table>
</div>

<div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
      	<h3 class="panel-title">
      		<span class="fa fa-money"></span>
      		<?=lang('bo_price')?>
      	</h3>
      </div>
       <!-- Table -->
      <table class="table table-bordered">
      <thead>
          <tr>
            <th><?=$cb['adults']?> <?=lang('bo_adult')?></th>
            <?php if($cb['children'] > 0):?>
				<th><?=$cb['children']?> <?=lang('bo_child')?></th>
			<?php endif;?>
			<?php if($cb['infants'] > 0):?>
				<th><?=$cb['infants']?> <?=lang('bo_infant')?></th>
			<?php endif;?>
				<th><?=lang('bo_tax_fee')?></th>
				
			<?php if($bagge_kg > 0):?>
				<th><?=$bagge_kg. ' Kg '. lang('bo_passenger_baggage')?></th>
			<?php endif;?>
			
				
				<th><?=lang('bo_total_price')?></th>
          </tr>
      </thead>
      
      <tbody>
      		<tr>
      			<td><?=number_format($adt_price)?> VND</td>
      			<?php if($cb['children'] > 0):?>
      				<td><?=number_format($chd_price)?> VND</td>
      			<?php endif;?>
      			<?php if($cb['infants'] > 0):?>
      				<td><?=number_format($inf_price)?> VND</td>
      			<?php endif;?>
      			
      			<td><?=number_format($tax_fee)?> VND</td>
      			
      			<?php if($bagge_kg > 0):?>
      				<td><?=number_format($baggage_price)?> VND</td>
      			<?php endif;?>
      			
      			<td><strong class="error"><?=number_format($total_price)?> VND</strong></td>
      				
      		</tr>
      </tbody>
      
      </table>
</div>

<?php 
	$has_fare_rules = false;
	foreach ($srs as $sr){
		if(!empty($sr['flight_class']) && !empty($sr['fare_rules'])){
			$has_fare_rules = true;
		}
	}
?>
<?php if($has_fare_rules):?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
    	<span class="fa fa-info"></span>
    	<?=lang('bo_fare_rules')?>
    </h3>
  </div>
  <div class="panel-body">
  	<?php foreach ($srs as $sr):?>
       	<?php if(!empty($sr['flight_class']) && !empty($sr['fare_rules'])):?>
  			<h4 style="border-bottom:1px solid #CCC"><?=lang('bo_fare_rules_of')?> <?=$sr['flight_code']?>:</h4>
  			<div><?=$sr['fare_rules']?></div>
  			<br>
 		<?php endif;?>
 	<?php endforeach;?>
  </div>
</div>
<?php endif;?>