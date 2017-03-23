
	<?php
		
	
		$is_email_content = !empty($action) && $action == 'email_content';
		
		
		//$action = 'download';
	
		$title_color = $is_email_content ? "#004f8c" : '#000';
		$border_color = $is_email_content ? "#ddd" : '#666';
		$backgound_color = $is_email_content ? "#F5F5F5" : '#ddd';
		
		$pnr_color = $is_email_content ? 'red' : '#000';
		
		$seperation = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td style="height:20px;empty-cells:show;"></td><tr></table>';
		
		$font_size = 'font-size:12px;';
		
		$font_family = 'font-family: Arial;';
		
		$line_text = 'clear:right;margin-bottom:3px;'.$font_size.$font_family;
		
		$table_style = 'border-top: 1px solid '. $border_color .';'.'border-left: 1px solid '. $border_color .';'.$font_size.$font_family;
		
		$td_style = 'border-bottom: 1px solid '.$border_color.';border-right:1px solid '.$border_color.'; empty-cells:show;padding:3px 5px;'.$font_size.$font_family;
		
		$td_title_style = $td_style.';font-size:13px; font-weight:bold;background-color:'.$backgound_color.';color:'.$title_color;
		
		$fare_rule_td_style = 'padding:3px 5px;'.$font_size.$font_family;
	?>
	
	<?php if(!$is_email_content):?>

		<table width="100%">
			<tr>
				<td width="35%" valign="top">
					<img style="max-width:100%" src="<?=site_url('media/bestpricevn_logo_31102014.jpg')?>" />
				</td>
				<td align="right" valign="top">
					<div style="<?=$line_text?>;font-size: 14px;font-weight:bold;"><?=lang('tf_company_name')?></div>
					<div style="<?=$line_text?>"><b>VP Hà Nội:</b> 12A, Ngõ Bà Triệu, Phố Bà Triệu, Hai Bà Trưng, HN</div>
					<div style="<?=$line_text?>"><b>VP Sài Gòn:</b> P.102, Nhà DMC, 223 Điện Biên Phủ, Quận Bình Thạnh, HCM</div>
					<div style="<?=$line_text?>">Website: www.snotevn.com:8888 - Tel: (04) 3978 1425</div>
					<div style="<?=$line_text?>"><b>Hotline:</b> <?=$cb['hotline_number']?> (<?=$cb['hotline_name']?>)</div>
				</td>
			</tr>
		</table>
		
		<?=$seperation?>
	
	<?php endif;?>
	
	
	<div style="font-size:16px;font-weight:bold;color:<?=$title_color?>;text-align:center;<?=$font_family?>">
		<?=lang('tf_form_name')?>
	</div>
	
	<?=$seperation?>
	
	<?php 
	$adt_price = 0;
	$chd_price = 0;
	$inf_price = 0;
	$bagge_kg = 0;
	$baggage_price = 0;
	$tax_fee = 0;
	
	$depart_routes = array();
	
	$return_routes = array();
	
	$flight_codes = array();
		
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
			
			$flight_codes[] = $sr['flight_code'];
		}
		
	}
	
	$total_price = $adt_price + $chd_price + $inf_price + $baggage_price + $tax_fee;
?>

      <!-- Table -->
      <table width="100%" cellspacing="0" style="<?=$table_style?>">
        <thead>
          <tr>
          	  <th align="left" colspan="5" style="<?=$td_title_style?>">
          	  	 <?=lang('tf_pnr_info')?>
          	  </th>
          </tr>
          <tr>
            <th align="left" style="<?=$td_style?>" width="5%" >#</th>
            <th align="left" style="<?=$td_style?>" width="20%" ><?=lang('tf_pnr')?></th>
			<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('bo_airline')?></th>
			<th align="left" style="<?=$td_style?>" width="35%" ><?=lang('bo_flight_code')?></th>
			<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('tf_pnr_status')?></th>
          </tr>
        </thead>
        <tbody>
        	<?php if($cb['is_flight_domistic']):?>
	        	<?php $i=0;?>
	        	<?php foreach ($srs as $sr):?>
	        		
	        		<?php if(!empty($sr['flight_class'])):?>
	        			<?php $i++;?>
	        		<tr>
	        			<td style="<?=$td_style?>"><?=$i;?></td>
		        		<td style="<?=$td_style?>"><span style="color:<?=$pnr_color?>;font-weight:bold"><?=$sr['flight_pnr']?></span></td>
		        		<td style="<?=$td_style?>"><?=$sr['airline_name']?></td>
		        		<td style="<?=$td_style?>"><?=$sr['flight_code']?></td>
		        		
		        		<?php 
		        			$pnr_status = in_array($sr['reservation_status'], array(RESERVATION_FULL_PAID, RESERVATION_CLOSE_WIN)) ? lang('tf_status_confirm') : lang('tf_status_no_confirm');
		        			
		        			$confirmed = $this->input->post('confirmed');
		        						
		        		?>
		        		
		        		<td style="<?=$td_style?>" class="pnr-status" pnr-status="<?=$pnr_status?>">
		        			
		        			<?=empty($confirmed) ? $pnr_status : lang('tf_status_confirm')?>
		        			
		        		</td>
			        </tr>
	        		<?php endif;?>
	        	<?php endforeach;?>
        	<?php else:?>
        		
        		<?php $sr = $srs[0];?>
        		
        		<tr>
	        		<td style="<?=$td_style?>">1</td>
	        		<td style="<?=$td_style?>"><span style="color:<?=$pnr_color?>;font-weight:bold"><?=$sr['flight_pnr']?></span></td>
	        		<td style="<?=$td_style?>"><?=$sr['airline_name']?></td>
	        		<td style="<?=$td_style?>"><?=implode(', ', $flight_codes)?></td>
	       
        			<?php 
	        			$pnr_status = in_array($sr['reservation_status'], array(RESERVATION_FULL_PAID, RESERVATION_CLOSE_WIN)) ? lang('tf_status_confirm') : lang('tf_status_no_confirm');
	        			
	        			$confirmed = $this->input->post('confirmed');
	        						
	        		?>
	        		
	        		<td style="<?=$td_style?>" class="pnr-status" pnr-status="<?=$pnr_status?>">
	        			
	        			<?=empty($confirmed) ? $pnr_status : lang('tf_status_confirm')?>
	        			
	        		</td>
	        		
		        </tr>
        	<?php endif;?>
        </tbody>
   </table>

	<?=$seperation?>
	
      <!-- Table -->
      <table width="100%" cellspacing="0" style="<?=$table_style?>">
        <thead>
          <tr>
          	  <th align="left" colspan="<?=$cb['is_flight_domistic']?5:6?>" style="<?=$td_title_style?>">
          	  	 <?=lang('bo_passenger')?>
          	  </th>
          </tr>
          <tr>
            <th align="left" style="<?=$td_style?>" width="5%" >#</th>
            <?php if($cb['is_flight_domistic']):?>
            	<th align="left" style="<?=$td_style?>" width="40%" ><?=lang('bo_passenger_name')?></th>
            <?php else:?>
            	<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('bo_passenger_name')?></th>
            	<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('bo_passenger_ticket_number')?></th>
            <?php endif;?>
			<th align="left" style="<?=$td_style?>" width="15%" ><?=lang('bo_passenger_gender')?></th>
			<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('bo_passenger_baggage')?></th>
			<th align="left" style="<?=$td_style?>" width="20%" ><?=lang('bo_passenger_birthday')?></th>
			
          </tr>
        </thead>
        <tbody>
         
         <?php foreach ($passengers as $key => $passenger):?>
				
				<tr>
					<td style="<?=$td_style?>"><?=($key+1)?></td>
					<td style="<?=$td_style?>"><?=strtoupper($passenger['full_name'])?></td>
					
					<?php if(!$cb['is_flight_domistic']):?>
						<td style="<?=$td_style?>"><?=$passenger['ticket_number']?></td>
					<?php endif;?>
					
					<td style="<?=$td_style?>">
						
						<?php 
							$a_txt = $passenger['type'] == 1? lang('bo_adult') : ($passenger['type'] == 2? lang('bo_child') : lang('bo_infant'));
						?>
						
						<?=$a_txt?>, <?=$passenger['gender'] == 1? lang('bo_male'):lang('bo_female')?>

					
					</td>
					
					<td style="<?=$td_style?>">
						<?=$passenger['checked_baggage']?>
					</td>
					
					<td style="<?=$td_style?>">
						<?=is_null($passenger['birth_day'])? '' : date(DATE_FORMAT, strtotime($passenger['birth_day']))?>
					</td>
					
					
				</tr>
			<?php endforeach;?>
         
        </tbody>
      </table>
      
      <?=$seperation?>
      
      
      <!-- Table -->
     <table width="100%" cellspacing="0" style="<?=$table_style?>">
        <thead>
          <tr>
          	  <th align="left" colspan="7" style="<?=$td_title_style?>">
          	  	
          	  	<?=lang('bo_flight_route')?>
	      		<?php if(!$cb['is_flight_domistic']):?>
	      			<?=$cb['flight_from']?> - <?=$cb['flight_to']?>, <?=date('d/m', strtotime($cb['flight_depart']))?>
	      		<?php endif;?>
          	  	
          	  </th>
          </tr>
          <tr>
            <th style="<?=$td_style?>" align="left" width="15%" ><?=lang('bo_airline')?></th>
			<th style="<?=$td_style?>" align="left" width="10%" ><?=lang('bo_flight_code')?></th>
			<th style="<?=$td_style?>" align="left" width="20%" ><?=lang('bo_route')?></th>
			<th style="<?=$td_style?>" align="left" width="15%" ><?=lang('bo_departure_date')?></th>
			<th style="<?=$td_style?>" align="left" width="10%" ><?=lang('bo_departure_time')?></th>
			<th style="<?=$td_style?>" align="left" width="10%" ><?=lang('bo_arrival_time')?></th>
			<th style="<?=$td_style?>" align="left" width="20%" ><?=lang('tf_pnr')?></th>
          </tr>
        </thead>
		        

      <?php if($cb['is_flight_domistic']):?>

		        <tbody>
		        	
		        	<?php foreach ($srs as $sr):?>
		        		<?php if(!empty($sr['flight_class'])):?>
		        		<tr>
			        		<td style="<?=$td_style?>">
			        			
			        			<?=$sr['airline_name']?>
			        		</td>
			        		<td style="<?=$td_style?>"><?=$sr['flight_code']?></td>
			        		<td style="<?=$td_style?>"><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? ' ('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? ' ('.$sr['flight_to_code'].')':''?></td>
			        		<td style="<?=$td_style?>"><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
			        		<td style="<?=$td_style?>"><?=$sr['departure_time']?></td>
			        		<td style="<?=$td_style?>"><?=$sr['arrival_time']?></td>
			        		<td style="<?=$td_style?>"><span style="color:<?=$pnr_color?>;font-weight:bold"><?=$sr['flight_pnr']?></span></td>
		        		</tr>
        				<?php endif;?>
		        	<?php endforeach;?>
		        	
		        </tbody>
		      
      		
      <?php else:?>

        <tbody>
        		
        	<?php foreach ($depart_routes as $key=>$sr):?>
     
        		<tr>
	        		<td style="<?=$td_style?>">
	        			
	        			<?=$sr['airline_name']?>
	        		</td>
	        		<td style="<?=$td_style?>"><?=$sr['flight_code']?></td>
	        		<td style="<?=$td_style?>"><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? ' ('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? ' ('.$sr['flight_to_code'].')':''?></td>
	        		<td style="<?=$td_style?>"><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
	        		<td style="<?=$td_style?>"><?=$sr['departure_time']?></td>
	        		<td style="<?=$td_style?>"><?=$sr['arrival_time']?></td>
	        		
	        		<td style="<?=$td_style?>"><span style="color:<?=$pnr_color?>;font-weight:bold"><?=$sr['flight_pnr']?></span></td>
        		</tr>
        		
        		<?php if(isset($depart_routes[$key + 1])):?>
				
					<?php 
						$next_sr = $depart_routes[$key + 1];
						
						$delay = calculate_flying_delay($sr, $next_sr);
					?>
								
					<tr>
						<td style="<?=$td_style?>" colspan="7" align="right"><?=lang_arg('bo_flight_wait', $next_sr['flight_from'], $delay['h'], $delay['m'])?></td>
					</tr>
					
				<?php endif;?>
        	
        	<?php endforeach;?>
        	
   
        </tbody>
        
    
      
      <?php if(count($return_routes) > 0):?>
   
	       <thead>
	          <tr>
	          	  <th align="left" colspan="7" style="<?=$td_title_style?>">
	          	  	
	          	  <?=lang('bo_flight_route')?>
	      		
		      		<?php if(!$cb['is_flight_domistic']):?>
		      			<?=$cb['flight_to']?> - <?=$cb['flight_from']?>, <?=date('d/m', strtotime($cb['flight_return']))?>
		      		<?php endif;?>
	          	  	
	          	  </th>
	          </tr>
          </thead>
	      
      		<tbody>
      		<?php foreach ($return_routes as $key=>$sr):?>
     
        		<tr>
	        		<td style="<?=$td_style?>" width="15%">
	        			
	        			<?=$sr['airline_name']?>
	        		</td>
	        		<td style="<?=$td_style?>" width="10%"><?=$sr['flight_code']?></td>
	        		<td style="<?=$td_style?>" width="20%"><?=$sr['flight_from']?><?=!empty($sr['flight_from_code']) ? '('.$sr['flight_from_code'].')':''?> - <?=$sr['flight_to']?><?=!empty($sr['flight_to_code']) ? '('.$sr['flight_to_code'].')':''?></td>
	        		<td style="<?=$td_style?>" width="15%"><?=bpv_format_date($sr['start_date'], 'd/m/Y', true)?></td>
	        		<td style="<?=$td_style?>" width="10%"><?=$sr['departure_time']?></td>
	        		<td style="<?=$td_style?>" width="10%"><?=$sr['arrival_time']?></td>
	        		<td style="<?=$td_style?>"><span style="color:<?=$pnr_color?>;font-weight:bold"><?=$sr['flight_pnr']?></span></td>
        		</tr>
        		
        		<?php if(isset($return_routes[$key + 1])):?>
				
					<?php 
						$next_sr = $return_routes[$key + 1];
						
						$delay = calculate_flying_delay($sr, $next_sr);
					?>
								
					<tr>
						<td style="<?=$td_style?>" colspan="7" align="right"><?=lang_arg('bo_flight_wait', $next_sr['flight_from'], $delay['h'], $delay['m'])?></td>
					</tr>
					
				<?php endif;?>
        	
        	<?php endforeach;?>
        	</tbody>
        	
        <?php endif;?>
      
      <?php endif;?>
     </table>
     
	 <?=$seperation?>
 <!-- Table -->
      <table width="100%" cellspacing="0" bor style="<?=$table_style?>;border:1px solid <?=$border_color?>">
        <thead>
          <tr>
          	  <th align="left" colspan="2" style="<?=$td_title_style?>;border-right:0">
          	  	 <?=lang('bo_fare_rules')?>
          	  </th>
          </tr>
        </thead>
        
    <tbody>
    	<?php 
    		$sr_index = 0;
    	?>
	  	<?php foreach ($srs as $sr):?>
	       	<?php if(!empty($sr['flight_class'])):?>
	       	
	       		<?php $sr_index++;?>
	       		
	       		<?php if(!$is_email_content):?>
	       			
	       			<tr>
		  				<td style="<?=$fare_rule_td_style?>" valign="top" style="width:25%"><?=lang('bo_fare_rules_of')?> <?=$sr['flight_code']?>:</td>
		  				<td style="<?=$fare_rule_td_style?>" valign="top">
		  					<?=$sr['fare_rule_short']?>
		  				</td>
		  			</tr>
	       			
	       		<?php else:?>
	       			
	       			<tr>
		  				<td style="<?=$fare_rule_td_style?>" valign="top" style="width:25%"><p><b><?=lang('bo_fare_rules_of')?> <?=$sr['flight_code']?>:</b></p></td>
		  				<td style="<?=$fare_rule_td_style?>" valign="top">
		  					<?=!empty($sr['fare_rules']) ? $sr['fare_rules'] : $sr['fare_rule_short']?>
		  				</td>
		  			</tr>
		  			<?php if($sr_index < count($flight_codes)):?>
		  				<tr><td colspan="2"><hr style="border:0;border-bottom:1px solid <?=$border_color?>"></hr></td></tr>
	       			<?php endif;?>
	       		<?php endif;?>
	       		
	 		<?php endif;?>
	 	<?php endforeach;?>
 	</tbody>
 	
 	</table>
 	
 	<?=$seperation?>

	<div style="color:<?=$pnr_color?>;<?=$font_family?>">
		<div style="<?=$line_text?>;font-size:14px;font-weight:bold;">(*) Quý khách cần chú ý:</div>
		<div style="<?=$line_text?>">- Mang giấy tờ tùy thân như CMT , bằng lái xe hoặc thẻ Đảng nếu có.</div>
		<div style="<?=$line_text?>">- Trẻ em dưới 12 tuổi bắt buộc phải mang giấy khai sinh bản chính.</div>
		<div style="<?=$line_text?>">- Có mặt làm thủ tục trước 180 phút và trễ nhất phải trước 60 phút so với giờ bay.</div>
	</div>
	
	<?=$seperation?>
	
	<?php if(!$is_email_content):?>
	
	<div style="font-weight:bold;margin-bottom:5px;text-align:center;<?=$font_family?>">Cảm ơn quý khách đã sử dụng dịch vụ của Best Price.</div>
	<div style="font-weight:bold;text-align:center;<?=$font_family?>">CHÚC QUÝ KHÁCH THƯỢNG LỘ BÌNH AN!</div>
	
	<?php endif;?>