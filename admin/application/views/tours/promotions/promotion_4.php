
<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="next" name="action">
	
  
  <div class="form-group">
    <label for="room_type" class="col-sm-2 control-label"><?=lang('pro_field_tour_departure')?></label>
    <div class="col-sm-3">      
      <select class="form-control" id="room_type" name="room_type" onchange="show_hide_tour_departure()">		  
		  <?php foreach ($pro_tour_departures as $key => $value):?>
		  	<option value="<?=$key?>" <?=set_select('room_type',$key, isset($pro['room_type']) && $pro['room_type'] == $key)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
    </div>
  </div>
  
  <div id="room_type_area" class="hidden">
  <?php foreach ($tour_departures as $tour_departure):?> 
  		<?php if(!empty($accommodations)):?>
			  <div class="form-group">
			    <div class="col-sm-2"></div>
			    <div class="col-sm-4">
			  		<div class="checkbox">
					    <label>
					      <input type="checkbox" id="tour_<?=$tour_departure['id']?>" onclick="show_hide_tour_acc(<?=$tour_departure['id']?>)"  
					      name="pro_tours[]" <?=set_checkbox('pro_tours[]', $tour_departure['id'], is_tour_departure_promotion($pro, $tour_departure['id']))?> 
					      value="<?=$tour_departure['id']?>"> <?=$tour_departure['name']?>
					    </label>
					</div>
			    </div>
			  
			    <div class="col-sm-4">
			    	<input type="text" class="form-control" placeholder="<?=lang('pro_offer_note_for')?> <?=$tour_departure['name']?>" name="offer_<?=$tour_departure['id']?>" value="<?=get_tour_departure_offer_note_pro($pro, $tour_departure['id'])?>">
			    </div>
			  </div>
			  
			  <?php foreach ($accommodations as $acc):?>
  				
  					 <div class="form-group hidden tour_acc_<?=$tour_departure['id']?>">
					    <div class="col-sm-2 col-sm-offset-2">
					  		<label style="font-weight:normal;"><?=$acc['name']?></label>
					  		<input type="hidden" name="tour_acc_<?=$tour_departure['id']?>[]" value="<?=$acc['id']?>">
					    </div>
					  
					    <div class="col-sm-2">
					    	<input style="width:40%;float:left" type="text" class="form-control" name="get_<?=$tour_departure['id'].'_'.$acc['id']?>" value="<?=get_acc_get($pro, $acc['id'], $tour_departure['id'])?>">
					    	<span class="help-block">&nbsp;<?=get_unit_label($pro, 0)?></span>
					    </div>
					    
					    <div class="col-sm-4">
					    	<input type="text" class="form-control" placeholder="<?=lang('pro_offer_note_for')?> <?=$acc['name']?>" name="offer_<?=$tour_departure['id'].'_'.$acc['id']?>" value="<?=get_acc_offer_note($pro, $acc['id'], $tour_departure['id'])?>">
					    </div>
			    
					 </div>	
  					
  			  <?php endforeach;?>
  			  
  		<?php endif;?>
  <?php endforeach;?>
  	<br>
  </div>
  	
  <div class="form-group">
    <label for="cancellation_policy" class="col-sm-2 control-label"><?=lang('pro_field_cancellation_policy')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      <select class="form-control" id="cancellation_policy" name="cancellation_policy" onchange="on_select_cancellation()">	
      	  <option value=""><?=lang('please_select')?></option>	  
		  <?php foreach ($cancellations as $key => $value):?>
		  	<?php 
		  		$checked = isset($pro['cancellation_id'])? $pro['cancellation_id'] == $value['id'] : $tour['cancellation_id'] == $value['id'];
		  		$label = $value['name'];
		  		if($value['id'] == $tour['cancellation_id']) $label = $label .' (default cruise cancellation)';
		  	?>
		  	<option value="<?=$value['id']?>" <?=set_select('cancellation_policy',$value['id'], $checked)?> <?php if($value['id'] == $tour['cancellation_id']):?> style="font-weight:bold;"<?php endif;?>><?=$label?></option>
		  <?php endforeach;?>
	  </select>
	  <?=form_error('cancellation_policy')?>
	  <span class="help-block" id="cancellation_content"></span>
    </div>
  </div>
 
  <br>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">      		
		<?=lang('btn_next')?>&nbsp;
		<span class="fa fa-arrow-right"></span>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('tours/promotions/'.$tour_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<script type="text/javascript">

	function on_select_cancellation(){
		
		var cancellations = <?=json_encode($cancellations)?>;
		var id = $('#cancellation_policy').val();

		$('#cancellation_content').html('');
		for(var i = 0; i < cancellations.length; i++){
			var can = cancellations[i];
			if(can.id == id){
				$('#cancellation_content').html(can.content);
				break;
			}
		}
	}

	function show_hide_tour_departure(){

		var room_type = $('#room_type').val();

		if(room_type == 1){ // all room type

			$('#room_type_area').removeClass('show');
			$('#room_type_area').addClass('hidden');			
			
		} else { // specific room type 

			$('#room_type_area').removeClass('hidden');
			$('#room_type_area').addClass('show');
		}
	}

	function show_hide_tour_acc(tour_id){
		
		var is_checked = $('#tour_'+tour_id).is(':checked');
		if(is_checked){
			$('.tour_acc_'+tour_id).removeClass('hidden');
			$('.tour_acc_'+tour_id).addClass('show');
		} else {
			$('.tour_acc_'+tour_id).removeClass('show');
			$('.tour_acc_'+tour_id).addClass('hidden');
		}
	}

	on_select_cancellation();

	show_hide_tour_departure();

	<?php foreach ($tour_departures as $tour_departure):?> 
		<?php if(!empty($accommodations)):?>
			show_hide_tour_acc(<?=$tour_departure['id']?>);
		<?php endif;?>
	<?php endforeach;?>
</script>
