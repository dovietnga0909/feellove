<style type="text/css">	
	.padding-20{
		padding:20px !important;	
	}
</style>

<form class="form-horizontal" role="form" method="post">

  <div>		
	<table class="table table-bordered">
 		<tr>
 			<td class="padding-20" width="30%">
 				<label><?=lang('rate_on')?></label>
 				<div class="checkbox">
			    	<label>
			  			<input onclick="all_day_change()" type="checkbox" value="1" <?=set_checkbox('all_day', 1, true)?> name="all_day" class="all-day"> <?=lang('all_day')?>
					</label>
				</div>
 				
 				<br>
 				<span><?=lang('one_more_these_day')?>:</span>
 				
 				<?php foreach ($week_days as $key=>$value):?>
				<div class="checkbox">
			    	<label>
			  			<input type="checkbox" onclick="week_day_change()" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, true)?> name="week_day[]" class="week-day"> <?=lang($value)?>
					</label>
				</div>
				<?php endforeach;?>
		
				<br>
	   			<?=form_error('week_day[]')?>
 			</td>
 			<td class="padding-20">
 				<label><?=lang('rate_for')?></label>
 				<div class="checkbox">
			    	<label>
			  			<input onclick="all_room_change()" type="checkbox" value="2" <?=set_checkbox('all_room', 2)?> name="all_room" class="all-room"> <?=lang('all_room')?>
			  			
					</label>
				</div> 				
 				<br>
 				<span><?=lang('one_more_these_room')?>:</span>
 				
 				<?php foreach ($room_types as $value):?>
				<div class="checkbox">
			    	<label>
			  			<input type="checkbox" onchange="room_type_change()" value="<?=$value['id']?>" <?=set_checkbox('room_types[]',$value['id'], false)?> name="room_types[]" class="room-types" occupancy="<?=$value['max_occupancy']?>"> <?=$value['name']?>
			  			
			  			<span style="font-size:11px">
			  			(<?=get_max_occupancy_text($value)?>)
			  			</span>
					</label>
				</div>
				<?php endforeach;?>
				<br>
				
			    <?=form_error('room_types[]')?>
 			
 			</td>
 		</tr>
	</table>
	  
  </div> 

  
  <div class="form-group">
    <label for="start_date_ip" class="col-sm-2 control-label">
    	<?=lang('field_start_date')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="start_date">
      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date_ip" name="start_date" 
	    		value="<?=set_value('start_date')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('start_date')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="end_date_ip" class="col-sm-2 control-label">
    	<?=lang('field_end_date')?> <?=mark_required()?>
    </label>
    
    <div class="col-sm-3" id="end_date">      
       <div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date_ip" name="end_date" 
	    		value="<?=set_value('end_date')?>">
	    		
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  <?=form_error('end_date')?>     
    </div>
  </div>
  
  <?php if(is_hotel_has_family_room($room_types)):?>
  <div class="form-group" id="family_room_area" style="display:none">
    <label for="full_occupancy" class="col-sm-2 control-label"><?=lang('rate_full_occupancy')?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control price-cell" id="full_occupancy" name="full_occupancy" value="<?=set_value('full_occupancy')?>">      
      <?=form_error('full_occupancy')?>
    </div>
    <div class="col-sm-7">
    	<p class="help-block" style="font-size:11px"><b><?=lang('rate_apply_for')?>:</b> <?=$names['full_occupancy']?></p>
    </div>
  </div>
  <?php endif;?>
  
  <?php if(is_hotel_has_tripple_room($room_types) || is_hotel_has_family_room($room_types)):?>
  <div class="form-group" id="triple_room_area" style="display:none">
    <label for="triple" class="col-sm-2 control-label"><?=lang('rate_triple')?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control price-cell" id="triple" name="triple" value="<?=set_value('triple')?>">      
      <?=form_error('triple')?>
    </div>
    
    <div class="col-sm-7">
    	<p class="help-block" style="font-size:11px"><b><?=lang('rate_apply_for')?>:</b> <?=$names['triple']?></p>
    </div>
    
  </div>
  <?php endif;?>
  
  <div class="form-group">
    <label for="double" class="col-sm-2 control-label"><?=lang('rate_double')?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control price-cell" id="double" name="double" value="<?=set_value('double')?>">      
      <?=form_error('double')?>
    </div>
  </div>
 
  <div class="form-group">
    <label for="single" class="col-sm-2 control-label"><?=lang('rate_single')?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control price-cell" id="single" name="single" value="<?=set_value('single')?>">      
      <?=form_error('single')?>
    </div>
  </div>
	
  <?php if(is_hotel_has_room_extra_bed($room_types)):?>
  
  <div class="form-group">
    <label for="extra_bed" class="col-sm-2 control-label"><?=lang('rate_extra_bed')?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control price-cell" id="extra_bed" name="extra_bed" value="<?=set_value('extra_bed')?>">      
      <?=form_error('extra_bed')?>
    </div>
    
    <div class="col-sm-7">
    	<p class="help-block" style="font-size:11px"><b><?=lang('rate_apply_for')?>:</b> <?=$names['extra_bed']?></p>
    </div>
  </div>
  	
  <?php endif;?>	 
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary btn-lg" name="submit_action" value="<?=ACTION_SAVE?>">      
	  		<span class="fa fa-download"></span>	
			<?=lang('btn_save')?>
	  </button>
	  
	  <button type="submit" class="btn btn-default mg-left-10" name="submit_action" value="<?=ACTION_CANCEL?>">  		
			<?=lang('btn_cancel')?>
	  </button>
      
    </div>
  </div>
</form>

<script type="text/javascript">

	$('#start_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$('#end_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

    function all_day_change(){
		var checked = $('.all-day').is(':checked');
		if(checked){
			$('.week-day').prop('checked', true);
		}else{
			$('.week-day').prop('checked', false);
		}
    }

    function all_room_change(){
    	var checked = $('.all-room').is(':checked');
		if(checked){
			$('.room-types').prop('checked', true);
		}else{
			$('.room-types').prop('checked', false);
		}

		show_hide_triple_family_room();	
    }

    function week_day_change(){

		var all_selected = true;

		$('.week-day').each(function(){

			if(!$(this).is(':checked')){

				all_selected = false;
				
			}
		});

		if(all_selected){
			$('.all-day').prop('checked',true);
		} else {
			$('.all-day').prop('checked',false);
		}
	
    }

    function room_type_change(){

    	var all_selected = true;

		$('.room-types').each(function(){

			if(!$(this).is(':checked')){

				all_selected = false;
				
			}
		});

		if(all_selected){
			$('.all-room').prop('checked',true);
		} else {
			$('.all-room').prop('checked',false);
		}

		show_hide_triple_family_room();	
    }

    function show_hide_triple_family_room(){

    	var max_occupancy = 0;
		
    	$('.room-types').each(function(){

			if($(this).is(':checked')){

				var occupancy = $(this).attr('occupancy');

				if(occupancy > max_occupancy) max_occupancy = occupancy;

				
			}
		});

		//alert('max = ' + max_occupancy);

		if(max_occupancy > <?=TRIPLE?>){

			$('#family_room_area').show();
			
			$('#triple_room_area').show();
			
		} else {
			$('#family_room_area').hide();
			
			if(max_occupancy == <?=TRIPLE?>){
				
				$('#triple_room_area').show();
					
			} else {
				
				$('#triple_room_area').hide();
				
			}
		}
    }

    $( document ).ready(function() {
		
		$('.price-cell').maskMoney({allowZero: false, allowNegative: false, thousands:',', decimal:'.', affixesStay: false,  precision: 0});
	});

</script>