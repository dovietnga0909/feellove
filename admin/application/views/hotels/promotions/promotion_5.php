<?php if(isset($pro_step)):?>
<?=$pro_step?>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="save" name="action">
   
   <?php if(!isset($view_mode)):?>
   		<p class="text-info"><?=lang('pro_review_notification')?></p>
   <?php endif;?>
   
   <div class="form-group">
    <label for="name" class="col-sm-2"><?=lang('pro_field_name')?></label>
    <div class="col-sm-6">
      <?=$pro['name']?>
    </div>
  </div>
 
  
  <div class="form-group">
    <label for="promotion_type" class="col-sm-2"><?=lang('pro_field_type')?></label>
    <div class="col-sm-6">
      <?=$promotion_types[$pro['promotion_type']]?>
    </div>
  </div>
	
  <div class="form-group">
    <label for="show_on_web" class="col-sm-2"><?=lang('pro_field_show_on_web')?></label>
    <div class="col-sm-6">
      <?=$pro['show_on_web'] == 1? lang('yes') : lang('no')?>
    </div>
  </div>	
  
  <div class="form-group">
    <label for="description" class="col-sm-2"><?=lang('pro_field_offer')?></label>
    <div class="col-sm-6">
      <?=$pro['offer']?>
    </div>
  </div>
  
  <hr>	
  
  <?php if(!empty($pro['promotion_type']) && ($pro['promotion_type'] == PROMOTION_TYPE_EARLY_BIRD || $pro['promotion_type'] == PROMOTION_TYPE_LAST_MINUTE)) :?>
  
	  <div class="form-group">
	    <label for="day_before_check_in" class="col-sm-2">
		    	
  			<?= $pro['promotion_type'] == PROMOTION_TYPE_EARLY_BIRD ? lang('pro_field_minimum_day_before_check_in') : lang('pro_field_maximum_day_before_check_in')?> 
	    	
	    	
	    </label>
	    <div class="col-sm-6">
	      <?=$pro['day_before_check_in']?>
	    </div>
	  </div>
  
  <?php endif;?>
  
  <!-- 
  <div class="form-group">
    <label for="minimum_stay" class="col-sm-2"><?=lang('pro_field_minimum_stay')?></label>
    <div class="col-sm-6">
      	<?=$pro['minimum_stay']?>
      	
      	<span><?=lang('pro_nights')?></span>
    </div>
  </div>
   -->
   
   <div class="form-group">
    <label for="book_date_from" class="col-sm-2">
    	<?=lang('pro_field_book_date_from')?>
    </label>
    <div class="col-sm-6">
      	
      	<?=date(DATE_FORMAT, strtotime($pro['book_date_from']))?>
      
    </div>
  </div>
  
   <div class="form-group">
    <label for="book_date_from" class="col-sm-2">
    	<?=lang('pro_field_book_date_to')?>
    </label>
    <div class="col-sm-6">
      	
      	<?=date(DATE_FORMAT, strtotime($pro['book_date_to']))?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-2">
    	<?=lang('pro_field_stay_date_from')?>
    </label>
    <div class="col-sm-6">
      	
      	<?=date(DATE_FORMAT, strtotime($pro['stay_date_from']))?>
      
    </div>
  </div>
  
   <div class="form-group">
    <label class="col-sm-2">
    	<?=lang('pro_field_stay_date_to')?>
    </label>
    <div class="col-sm-6">
      	
      	<?=date(DATE_FORMAT, strtotime($pro['stay_date_to']))?>
      
    </div>
  </div>
  
   <fieldset disabled>
  <div class="form-group">
    <label class="col-sm-2"><?=lang('pro_field_displayed_on')?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
    	<label class="checkbox-inline">
  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox('display_on[]',$key, empty($pro['display_on']) ? true : is_bit_value_contain($pro['display_on'], $key))?> name="display_on[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		
    </div>
  </div>
  
  <div class="form-group">
    <label for="check_in_on" class="col-sm-2"><?=lang('pro_field_check_in_on')?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
    	<label class="checkbox-inline">
  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox('check_in_on[]',$key, empty($pro['check_in_on']) ? true : is_bit_value_contain($pro['check_in_on'], $key))?> name="check_in_on[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		
    </div>
  </div>
  </fieldset>
  
  <!-- 
   <div class="form-group">
	    <label for="maximum_stay" class="col-sm-2"><?=lang('pro_field_maximum_stay')?></label>
	    <div class="col-sm-6">
	    	<?=empty($pro['maximum_stay'])? lang('na') : $pro['maximum_stay']?>
	    	<span><?=lang('pro_nights')?></span>
	    </div>
	    
	  </div>
   -->
   	  
	  <div class="form-group">
	    <label for="book_time_from" class="col-sm-2"><?=lang('pro_field_book_time_from')?></label>
	    <div class="col-sm-6">
	    	<?=$pro['book_time_from']?>	    	
	    </div>
	  </div>
	  
	  
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_book_time_to')?></label>
	    <div class="col-sm-6">
	    	<?=$pro['book_time_to']?>
	    	
	    </div>
	  </div>
	  
	  <hr>
	  
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_discount_type')?></label>
	    <div class="col-sm-6">      
	      <?=$discount_types[$pro['discount_type']]?>
	    </div>
	  </div>
	  
	  <?php if(is_show_apply_on($pro)):?>
	  
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_apply_on')?></label>
	    <div class="col-sm-6">      
	      <?=$apply_on[$pro['apply_on']]?>
	    </div>
	  </div>
	  
	  <?php endif;?>
	  
	  <?php for ($i = 1; $i <= 7; $i++):?>
  		  <?php if(is_show_get($pro, $i)):?>
		  <div class="form-group">
		    <label class="col-sm-2"><?=lang('pro_field_get')?></label>
		    <div class="col-sm-6">
		      <?=$pro['get_'.$i]?>
		      <span><?=get_unit_label($pro, $i)?></span>
		    </div>
		  </div>
		  <?php endif;?>
	  <?php endfor;?>
	  
	  <??>
	  
	  <?php if(is_show_apply_on_free_night($pro)):?>
	  
	  <div class="form-group">
	    <label for="apply_on" class="col-sm-2"><?=lang('pro_field_apply_on')?></label>
	    <div class="col-sm-6">      
	      <?=$apply_on_free_night[$pro['apply_on']]?>
	    </div>
	  </div>
	  
	  <?php endif;?>
	  
	  <!-- 
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_minimum_room')?></label>
	    <div class="col-sm-6">      
	      <?=$pro['minimum_room']?>
	    </div>
	  </div>
	   -->
	   
	   
	  <?php if(is_show_recurring_benefit($pro)):?>
	  <div class="form-group">
	    <label for="recurring_benefit" class="col-sm-2"><?=lang('pro_field_recurring_benefit')?></label>
	    <div class="col-sm-6">      
	     <?=$recurring_benefits[$pro['recurring_benefit']]?>
	    </div>
	  </div>
	  <?php endif;?>
	  
	  <hr>
	  
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_cruise_tour')?></label>
	    <div class="col-sm-6">      
	      <?=$pro_room_types[$pro['room_type']]?>
	    </div>
	  </div>
	 
	 <?php if($pro['room_type'] == 2):?>	  
	 <fieldset disabled>
	  <?php foreach ($hotel_room_types as $room_type):?>	  		
		  <div class="form-group">
		    <div class="col-sm-2"></div>
		    <div class="col-sm-2">
		  		<div class="checkbox">
				    <label>
				      <input type="checkbox" <?=set_checkbox('pro_room_types[]', $room_type['id'], is_room_type_promotion($pro, $room_type['id']))?>> <?=$room_type['name']?>
				    </label>
				</div>
		    </div>
		    <div class="col-sm-2">
		    <?php if(get_room_type_get($pro,$room_type['id']) != ''):?>
			    <span class="help-block"><?=get_room_type_get($pro,$room_type['id']). ' '.get_unit_label($pro, 0)?></span>
		    <?php endif;?>
		     </div>
		    
		    <div class="col-sm-3">
		    	<span class="help-block"><?=get_room_type_offer_note_pro($pro,$room_type['id'])?></span>
		    </div>
		  </div>
	  <?php endforeach;?>
	  </fieldset>
	  <?php endif;?>
	  
	  	
	  <div class="form-group">
	    <label class="col-sm-2"><?=lang('pro_field_cancellation_policy')?></label>
	    <div class="col-sm-6">
	      <?php foreach ($cancellations as $key => $value):?>
	      	<?php if($value['id'] == $pro['cancellation_id']):?>
	      		<b><?=$value['name']?></b>
	      		<br>
	      		<?=$value['content']?>
	      		
	      		<?php break;?>	
	      	<?php endif;?>
	      <?php endforeach;?>
	     
	    </div>
	  </div>
  	
  <br>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">      		
		<?=lang('btn_save')?>&nbsp;
		<span class="fa fa-download"></span>
      </button>
      
      <?php if(isset($view_mode)):?>
      <a class="btn btn-primary" href="<?=site_url('hotels/promotions/'.$hotel_id.'/edit/'.$pro['id'])?>" role="button"><span class="fa fa-edit"></span> <?=lang('btn_edit')?></a>
      <?php endif;?>
      <a class="btn btn-default mg-left-10" href="<?=site_url('hotels/promotions/'.$hotel_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

