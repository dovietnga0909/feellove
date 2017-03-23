
<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="next" name="action">
	
  
  <div class="form-group">
    <label for="room_type" class="col-sm-2 control-label"><?=lang('pro_field_room_type')?></label>
    <div class="col-sm-3">      
      <select class="form-control" id="room_type" name="room_type" onchange="show_hide_room_type()">		  
		  <?php foreach ($pro_room_types as $key => $value):?>
		  	<option value="<?=$key?>" <?=set_select('room_type',$key, isset($pro['room_type']) && $pro['room_type'] == $key)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
    </div>
  </div>
  
  <div id="room_type_area" class="hidden">
  <?php foreach ($hotel_room_types as $room_type):?>  
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-2">
  		<div class="checkbox">
		    <label>
		      <input type="checkbox" name="pro_room_types[]" <?=set_checkbox('pro_room_types[]', $room_type['id'], is_room_type_promotion($pro, $room_type['id']))?> value="<?=$room_type['id']?>"> <?=$room_type['name']?>
		    </label>
		</div>
    </div>
    <?php if(is_show_room_type_get($pro)):?>
	    <div class="col-sm-2">
	    	<input style="width:40%;float:left" type="text" class="form-control" name="get_<?=$room_type['id']?>" value="<?=get_room_type_get($pro, $room_type['id'])?>">
	    	<span class="help-block">&nbsp;<?=get_unit_label($pro, 0)?></span>
	    </div>
    <?php endif;?>
    
    <div class="col-sm-3">
    	<input type="text" class="form-control" placeholder="<?=lang('pro_offer_note_for')?> <?=$room_type['name']?>" name="offer_<?=$room_type['id']?>" value="<?=get_room_type_offer_note_pro($pro,$room_type['id'])?>">
    </div>
  </div>
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
		  		$checked = isset($pro['cancellation_id'])? $pro['cancellation_id'] == $value['id'] : $hotel['cancellation_id'] == $value['id'];
		  		$label = $value['name'];
		  		if($value['id'] == $hotel['cancellation_id']) $label = $label .' (default hotel cancellation)';
		  	?>
		  	<option value="<?=$value['id']?>" <?=set_select('cancellation_policy',$value['id'], $checked)?> <?php if($value['id'] == $hotel['cancellation_id']):?> style="font-weight:bold;"<?php endif;?>><?=$label?></option>
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
      <a class="btn btn-default mg-left-10" href="<?=site_url('hotels/promotions/'.$hotel_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
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

	function show_hide_room_type(){

		var room_type = $('#room_type').val();

		if(room_type == 1){ // all room type

			$('#room_type_area').removeClass('show');
			$('#room_type_area').addClass('hidden');			
			
		} else { // specific room type 

			$('#room_type_area').removeClass('hidden');
			$('#room_type_area').addClass('show');
		}
	}

	on_select_cancellation();

	show_hide_room_type();

</script>
