
<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="next" name="action">
  
  
  <?php if(!empty($pro['promotion_type']) && ($pro['promotion_type'] == PROMOTION_TYPE_EARLY_BIRD || $pro['promotion_type'] == PROMOTION_TYPE_LAST_MINUTE)) :?>
  
	  <div class="form-group">
	    <label for="day_before_check_in" class="col-sm-2 control-label">
		    	
  			<?= $pro['promotion_type'] == PROMOTION_TYPE_EARLY_BIRD ? lang('pro_field_minimum_day_before_check_in') : lang('pro_field_maximum_day_before_check_in')?> 
	    	
	    	<?=mark_required()?>
	    </label>
	    <div class="col-sm-2">
	      <input type="text" class="form-control" id="day_before_check_in" name="day_before_check_in" value="<?=set_value('day_before_check_in', isset($pro['day_before_check_in'])?$pro['day_before_check_in']:'')?>">
	      <?=form_error('day_before_check_in')?>
	    </div>
	  </div>
  
  <?php endif;?>
  
		
  <div class="form-group">
    <label for="minimum_stay" class="col-sm-2 control-label"><?=lang('pro_field_minimum_stay')?></label>
    <div class="col-sm-2">
      	<select class="form-control" id="minimum_stay" name="minimum_stay">      	
	      	<?php for($i = 1; $i <= $night_limit; $i++):?>
	      		<option value="<?=$i?>" <?=set_select('minimum_stay',$i, !empty($pro['minimum_stay']) && $pro['minimum_stay'] == $i)?>><?=$i?></option>
	      	<?php endfor;?>
      	</select>
    </div>
    <span><?=lang('pro_nights')?></span>
  </div>
 
   <div class="form-group">
    <label for="book_date_from" class="col-sm-2 control-label">
    	<?=lang('pro_field_book_date_from')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="book_date_from">
      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." name="book_date_from" 
	    		value="<?=set_value('book_date_from',!empty($pro['book_date_from'])?date(DATE_FORMAT, strtotime($pro['book_date_from'])):'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('book_date_from')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="book_date_to" class="col-sm-2 control-label">
    	<?=lang('pro_field_book_date_to')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="book_date_to">
      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." name="book_date_to" 
	    		value="<?=set_value('book_date_to',!empty($pro['book_date_to'])?date(DATE_FORMAT, strtotime($pro['book_date_to'])):'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('book_date_to')?>
      
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="stay_date_from" class="col-sm-2 control-label">
    	<?=lang('pro_field_stay_date_from')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="stay_date_from">      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." name="stay_date_from" 
	    		value="<?=set_value('stay_date_from',!empty($pro['stay_date_from']) ? date(DATE_FORMAT, strtotime($pro['stay_date_from'])):'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>    	 
		<?=form_error('stay_date_from')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="stay_date_to" class="col-sm-2 control-label">
    	<?=lang('pro_field_stay_date_to')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="stay_date_to">      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." name="stay_date_to" 
	    		value="<?=set_value('stay_date_to',!empty($pro['stay_date_to']) ? date(DATE_FORMAT, strtotime($pro['stay_date_to'])):'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
		 
		<?=form_error('stay_date_to')?>      
    </div>
  </div>
  
  <div id="more_options" class="hidden">
	   <div class="form-group">
	    <label for="display_on" class="col-sm-2 control-label"><?=lang('pro_field_displayed_on')?> <?=mark_required()?></label>
	    <div class="col-sm-6">
	      	<?php foreach ($week_days as $key=>$value):?>
	    	<label class="checkbox-inline">
	  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox('display_on[]',$key, empty($pro['display_on']) ? true : is_bit_value_contain($pro['display_on'], $key))?> name="display_on[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('display_on[]')?>
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label for="check_in_on" class="col-sm-2 control-label"><?=lang('pro_field_check_in_on')?> <?=mark_required()?></label>
	    <div class="col-sm-6">
	      	<?php foreach ($week_days as $key=>$value):?>
	    	<label class="checkbox-inline">
	  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox('check_in_on[]',$key, empty($pro['check_in_on']) ? true : is_bit_value_contain($pro['check_in_on'], $key))?> name="check_in_on[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('check_in_on[]')?>
	    </div>
	  </div>
	
	  <div class="form-group">
	    <label for="maximum_stay" class="col-sm-2 control-label"><?=lang('pro_field_maximum_stay')?></label>
	    <div class="col-sm-2">
	      	<select class="form-control" name="maximum_stay">
	      		<option value=""><?=lang('na')?></option>      	
		      	<?php for($i = 1; $i <= $night_limit; $i++):?>
		      		<option value="<?=$i?>" <?=set_select('maximum_stay',$i, !empty($pro['maximum_stay']) && $pro['maximum_stay'] == $i)?>><?=$i?></option>
		      	<?php endfor;?>
	      	</select>
	    </div>
	    <span><?=lang('pro_nights')?></span>
	  </div>
	  
	  <div class="form-group">
	    <label for="book_time_from" class="col-sm-2 control-label"><?=lang('pro_field_book_time_from')?></label>
	    <div class="col-sm-2">
	      <input type="text" class="form-control" id="book_time_from" placeholder="<?=lang('pro_time_place_holder')?>" name="book_time_from" value="<?=set_value('book_time_from',!empty($pro['book_time_from']) ? $pro['book_time_from'] : '00:00')?>">
	      <?=form_error('book_time_from')?>      
	    </div>
	    <span><?=lang('pro_local_time')?></span>
	  </div>
	  
	  
	  <div class="form-group">
	    <label for="book_time_to" class="col-sm-2 control-label"><?=lang('pro_field_book_time_to')?></label>
	    <div class="col-sm-2">
	      <input type="text" class="form-control" id="book_time_to" placeholder="<?=lang('pro_time_place_holder')?>" name="book_time_to" value="<?=set_value('book_time_to',!empty($pro['book_time_to']) ? $pro['book_time_to'] : '00:00')?>">
	      <?=form_error('book_time_to')?>      
	    </div>
	    <span><?=lang('pro_local_time')?></span>
	  </div>
  
  </div> 
  
  <div class="well well-sm">
  	<a href="javascript:show_more_option()">
	  		<span class="fa fa-plus" id="show_option_icon"></span>
	  		<span id="show_option_text">
	  			<?=lang('pro_show_more_option')?>
	  		</span>
	  	</a>
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

	$('#book_date_from .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$('#book_date_to .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$('#stay_date_from .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$('#stay_date_to .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

    function show_more_option(){
        var css = $('#more_options').attr('class');

        if(css == 'hidden'){
        	$('#more_options').removeClass('hidden');
        	$('#more_options').addClass('show');
        	$('#show_option_icon').removeClass('fa-plus');
        	$('#show_option_icon').addClass('fa-minus');       	

        	$('#show_option_text').text('<?=lang('pro_hide_more_option')?>');
        } else {            
        	$('#more_options').removeClass('show');
        	$('#more_options').addClass('hidden');
        	$('#show_option_icon').removeClass('fa-minus');
        	$('#show_option_icon').addClass('fa-plus');
        	
        	$('#show_option_text').text('<?=lang('pro_show_more_option')?>');        	
        }
    }

</script>
