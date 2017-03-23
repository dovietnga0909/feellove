
<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
  <input type="hidden" value="next" name="action">

  <div class="form-group">
    <label for="discount_type" class="col-sm-2 control-label"><?=lang('pro_field_discount_type')?></label>
    <div class="col-sm-3">      
      <select class="form-control" name="discount_type" id="discount_type" onchange="change_discount_type()">		  
		  <?php foreach ($discount_types as $key => $value):?>
		  		<option value="<?=$key?>" <?=set_select('discount_type',$key, !empty($pro['discount_type']) && $pro['discount_type'] == $key)?>><?=$value?></option>
		  		<?php 
		  			break;
		  		?>
		  <?php endforeach;?>
	  </select>
	  
      <?=form_error('discount_type')?>
    </div>
  </div>
  
  <div class="form-group" id="apply_on_area">
    <label for="apply_on" class="col-sm-2 control-label"><?=lang('pro_field_apply_on')?></label>
    <div class="col-sm-3">      
      <select class="form-control" name="apply_on" id="apply_on" onchange="change_apply_on()">		  
		  <?php foreach ($apply_on as $key => $value):?>
		  		<option value="<?=$key?>" <?=set_select('apply_on',$key, !empty($pro['apply_on']) && $pro['apply_on'] == $key)?>><?=$value?></option>
		  		<?php 
		  			break;
		  		?>
		  <?php endforeach;?>
	  </select>
	  
      <?=form_error('apply_on')?>
    </div>
  </div>
  
  <?php for ($i = 1; $i <= 7; $i++):?>
  
  <div class="form-group<?php if($i>1):?> hidden<?php endif;?>" id="area_get_<?=$i?>">
    <label for="get_<?=$i?>" class="col-sm-2 control-label"><?=lang('pro_field_get')?></label>
    <div class="col-sm-2">
      <input type="text" class="form-control" placeholder="0.00" id="get_<?=$i?>" name="get_<?=$i?>" value="<?=set_value('get_'.$i, !empty($pro['get_'.$i])?$pro['get_'.$i]:'')?>">      
      <?=form_error('get_'.$i)?>
    </div>
    <span class="help-block" id="label_get_<?=$i?>"><?=lang('pro_discount')?></span>
  </div>
  
  <?php endfor;?>
  
  <div class="form-group hidden" id="apply_on_free_night_area">
    <label for="apply_on" class="col-sm-2 control-label"><?=lang('pro_field_apply_on')?></label>
    <div class="col-sm-3">      
      <select class="form-control" name="apply_on_free_night" id="apply_on_free_night">		  
		  <?php foreach ($apply_on_free_night as $key => $value):?>
		  	<option value="<?=$key?>" <?=set_select('apply_on_free_night',$key, !empty($pro['apply_on']) && $pro['apply_on'] == $key)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
	  
    </div>
  </div>
  
  <!-- 
  <div class="form-group">
    <label for="minimum_room" class="col-sm-2 control-label"><?=lang('pro_field_minimum_room')?></label>
    <div class="col-sm-3">      
      <select class="form-control" name="minimum_room">		  
		  <?php for($i = 1; $i <= $room_limit; $i++):?>
	      	<option value="<?=$i?>" <?=set_select('minimum_room',$i, !empty($pro['minimum_room']) && $pro['minimum_room'] == $i)?>><?=$i?></option>
	      <?php endfor;?>
	  </select>
    </div>
  </div>
   -->
   
  <div class="form-group hidden" id="recurring_benefit_area">
    <label for="recurring_benefit" class="col-sm-2 control-label"><?=lang('pro_field_recurring_benefit')?></label>
    <div class="col-sm-3">      
      <select class="form-control" name="recurring_benefit">		  
		  <?php foreach ($recurring_benefits as $key => $value):?>
		  	<option value="<?=$key?>" <?=set_select('recurring_benefit',$key, !empty($pro['recurring_benefit']) && $pro['recurring_benefit'] == $key)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
    </div>
  </div>
  
  <br>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">      		
		<?=lang('btn_next')?>&nbsp;
		<span class="fa fa-arrow-right"></span>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('cruises/promotions/'.$cruise_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<script type="text/javascript">

    function change_discount_type(is_init){

    	if(is_init == undefined) is_init = false;
    	
    	change_text_label(is_init);
        
		var discount_type = $('#discount_type').val();
		
		if(discount_type == 1){ // normal % discount
			
		}

		if(discount_type == 2 || discount_type == 4){ // amount discount per booking or free nights
			$('#apply_on_area').addClass('hidden');
			
			for(var i = 2; i <= 7; i++){
				$('#area_get_' + i).removeClass('show');
				$('#area_get_' + i).addClass('hidden');					
			}			
		} else {
			$('#apply_on_area').removeClass('hidden');
			$('#apply_on_area').addClass('show');

			change_apply_on();
		}

		if(discount_type == 4){ // free night
			$('#apply_on_free_night_area').removeClass('hidden');
			$('#apply_on_free_night_area').addClass('show');
		} else {
			$('#apply_on_free_night_area').removeClass('show');
			$('#apply_on_free_night_area').addClass('hidden');
		}

    }

    function change_text_label(is_init){
    	var discount_type = $('#discount_type').val();

    	if(discount_type == 1){ // normal % discount
			for(var i = 1; i <= 7; i++){
				if(!is_init){
					$('#get_' + i).val('');
				}
				$('#get_' + i).attr('placeholder','0.00');
				$('#label_get_' + i).text('<?=lang('pro_discount')?>');
			}
        } 

        if (discount_type == 2){ // amount discount per booking

        	for(var i = 1; i <= 7; i++){
        		if(!is_init){
					$('#get_' + i).val('');
				}
				$('#get_' + i).attr('placeholder','0');
				$('#label_get_' + i).text('<?=lang('pro_discount_amount_per_booking')?>');
			}
			
        }

        if (discount_type == 3){ // amount discount per night

        	for(var i = 1; i <= 7; i++){
        		if(!is_init){
					$('#get_' + i).val('');
				}
				$('#get_' + i).attr('placeholder','0');
				$('#label_get_' + i).text('<?=lang('pro_discount_amount_per_night')?>');
			}
			
        }

        if (discount_type == 4){ // free night

        	for(var i = 1; i <= 7; i++){
        		if(!is_init){
					$('#get_' + i).val('');
				}
				$('#get_' + i).attr('placeholder','0');
				$('#label_get_' + i).text('<?=lang('pro_free_night')?>');
			}

        	$('#recurring_benefit_area').removeClass('hidden');
			$('#recurring_benefit_area').addClass('show');
        } else {
        	$('#recurring_benefit_area').removeClass('show');
			$('#recurring_benefit_area').addClass('hidden');
        }
    }

    function change_apply_on(){

		var pro_nights = <?=json_encode($pro_nights)?>;
		var pro_week_days = <?=json_encode($pro_week_days)?>;
		
    	var discount_type = $('#discount_type').val();

    	var apply_on = $('#apply_on').val();

    	if(discount_type == 1 || discount_type == 3){ // %discount or amount discount per night

			if(apply_on == 1 || apply_on == 4 || apply_on == 5){ // every night OR first night OR last night

				if(discount_type == 1){ // % discount
					$('#label_get_1').text('<?=lang('pro_discount')?>');
				}

				if(discount_type == 3){ // amount discount per night
					$('#label_get_1').text('<?=lang('pro_discount_amount_per_night')?>');
				}
								
				for(var i = 2; i <= 7; i++){
					$('#area_get_' + i).removeClass('show');
					$('#area_get_' + i).addClass('hidden');					
				}
				
			} 

			if(apply_on == 2){ // specific night
				for(var i = 1; i <= 7; i++){
					$('#area_get_' + i).removeClass('hidden');
					$('#area_get_' + i).addClass('show');
					
					if(discount_type == 1){ // % discount
						$('#label_get_' + i).html('<?=lang('pro_discount_on')?> ' + pro_nights[i]);
					}

					if(discount_type == 3){ // amount discount per night
						$('#label_get_' + i).html('<?=lang('pro_discount_amount_per_night_on')?> ' + pro_nights[i]);
					}					
				}

				$('#recurring_benefit_area').removeClass('hidden');
				$('#recurring_benefit_area').addClass('show');
			} else {

				$('#recurring_benefit_area').removeClass('show');
				$('#recurring_benefit_area').addClass('hidden');
			}

			if(apply_on == 3){ // specific day of week
				for(var i = 1; i <= 7; i++){
					$('#area_get_' + i).removeClass('hidden');
					$('#area_get_' + i).addClass('show');

					if(discount_type == 1){ // % discount
						$('#label_get_' + i).text('<?=lang('pro_discount_on')?> ' + pro_week_days[i]);
					}

					if(discount_type == 3){ // amount discount per night
						$('#label_get_' + i).text('<?=lang('pro_discount_amount_per_night_on')?> ' + pro_week_days[i]);
					}						
				}

			}
						
    	}	
    }

    change_discount_type(true);

</script>
