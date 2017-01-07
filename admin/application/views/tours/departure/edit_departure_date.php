<form class="form-horizontal" role="form" method="post">
    
    <div class="form-group">
    	<label for="start_date_ip" class="col-xs-3 control-label">
        	<?=lang('tours_field_start_date')?> <?=mark_required()?>
        </label>
    	<div class="col-xs-3" id="start_date">
    
    		<div class="input-append date input-group">
    			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date_ip" name="start_date"
    				value="<?=set_value('start_date', date(DATE_FORMAT, strtotime($tour_departure_date['start_date'])))?>"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
    		</div>
          
        	<?=form_error('start_date')?>
        </div>
    </div>
    
    <div class="form-group">
    	<label for="end_date_ip" class="col-xs-3 control-label">
        	<?=lang('tours_field_end_date')?> <?=mark_required()?>
        </label>
    
    	<div class="col-xs-3" id="end_date">
    		<div class="input-append date input-group">
    			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date_ip" name="end_date"
    				value="<?=set_value('end_date', date(DATE_FORMAT, strtotime($tour_departure_date['end_date'])))?>"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
    		</div>	
        	
            <?=form_error('end_date')?>     
        </div>
    </div>
    
    <div class="form-group">
    	<label for="week_day" class="col-xs-3 control-label"><?=lang('tours_field_weekdays')?> <?=mark_required()?></label>
    	<div class="col-xs-9">
          	<?php foreach ($week_days as $key=>$value):?>
          	
          	<?php 
          		$checked = is_bit_value_contain($tour_departure_date['weekdays'], $key);
          	?>
      	
        	<label class="checkbox-inline"> <input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>"
    			<?=set_checkbox('week_day[]', $key, $checked)?> name="week_day[]"> <?=lang($value)?>
        	</label>
        	<?php endforeach;?>
        	<br>
            <?=form_error('week_day[]')?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
    	<span class="fa fa-download"></span>
    	<?=lang('btn_save')?>
    </button>
    
    <?php if($tour['departure_type'] == MULTIPLE_DEPARTING_FROM):?>
        <a class="btn btn-default mg-left-10" href="<?=site_url('tours/departure/edit/'.$tour_departure_date['tour_departure_id'])?>" role="button"><?=lang('btn_cancel')?></a>
    <?php else:?>
        <a class="btn btn-default mg-left-10" href="<?=site_url('tours/departure/'.$tour_departure_date['tour_id'])?>" role="button"><?=lang('btn_cancel')?></a>
    <?php endif;?>

</form>

<script>
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
</script>