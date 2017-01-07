<?php if(empty($passenger)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url()?>bookings/passenger/" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<input type="hidden" value="save" name="action">
	
	<div class="form-group">
	    <label for="full_name" class="col-sm-2 control-label"><?=lang('passenger_age_type')?>: <?=mark_required()?></label>
	    <div class="col-sm-3">
	    	<select name="type" class="form-control">
		    	<option value=""><?=lang('please_select')?></option>
		     	<?php foreach ($passenger_age_types as $key=>$value):?>
		     		<option value="<?=$key?>" <?=set_select('type', $key, $key == $passenger['type'])?>><?=$value?></option>
		     	<?php endforeach;?>
	     	</select>
	     	<?=form_error('type')?>
	    </div>
	 </div>
	
	<div class="form-group">
	 	<label for="gender" class="col-sm-2 control-label"><?=lang('passenger_gender')?>: <?=mark_required()?></label>
	    <div class="col-sm-3">
	     	<select name="gender" class="form-control">
		    	<option value=""><?=lang('please_select')?></option>
		     	<?php foreach ($passenger_genders as $key=>$value):?>
		     		<option value="<?=$key?>" <?=set_select('gender', $key, $key == $passenger['gender'])?>><?=$value?></option>
		     	<?php endforeach;?>
	     	</select>
	      	<?=form_error('gender')?>
	    </div>
	</div>
	 
  <div class="form-group">
    <label for="full_name" class="col-sm-2 control-label"><?=lang('passenger_name')?>: <?=mark_required()?></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="full_name" name="full_name" value="<?=set_value('full_name', $passenger['full_name'])?>">
      <?=form_error('full_name')?>
    </div>
  </div>
  
 <div class="form-group">
    <label for="ticket_number" class="col-sm-2 control-label">
    	<?=lang('ticket_number')?>:
    </label>
    <div class="col-sm-6">
      	
      	<input type="text" class="form-control" name="ticket_number" value="<?=set_value('ticket_number', $passenger['ticket_number'])?>">
     	 
		<?=form_error('ticket_number')?>
      
    </div>
  </div>
  
 
  <div class="form-group">
    <label for="birth_day" class="col-sm-2 control-label">
    	<?=lang('passenger_birthday')?>:
    </label>
    <div class="col-sm-3">
      	
      	<div class="input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="birth_day" name="birth_day" 
	    	value="<?=set_value('birth_day', !empty($passenger['birth_day']) ?  date(DATE_FORMAT, strtotime($passenger['birth_day'])) : '')?>">
			<span class="input-group-addon" id="birth_day_cal"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('birth_day')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="checked_baggage" class="col-sm-2 control-label">
    	<?=lang('passenger_baggage')?>:
    </label>
    <div class="col-sm-6">
      	
      	<textarea rows="3" class="form-control" name="checked_baggage"><?=set_value('checked_baggage', $passenger['checked_baggage'])?></textarea>
     	 
		<?=form_error('checked_baggage')?>
      
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="nationality" class="col-sm-2 control-label">
    	<?=lang('passenger_nationality')?>:
    </label>
    <div class="col-xs-3">
      	
      	<select class="form-control" name="nationality">
			<?php foreach ($passenger_nationalities as $key=>$value):?>
				<?php 
					$nationality_value = $value[0].' ('.strtoupper($key).')';
				?>
				<option value="<?=$nationality_value?>" <?=set_select('nationality', $nationality_value, $passenger['nationality'] == $nationality_value)?>>
					<?=$nationality_value?>
				</option>
				<?php if($key=='vn'):?>
					<option disabled="disabled">------------------------------</option>
				<?php endif;?>
			<?php endforeach;?>
		</select>
     	 
		<?=form_error('nationality')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="passport" class="col-sm-2 control-label"><?=lang('passenger_passport')?>:</label>
    <div class="col-xs-6">
      <input type="text" class="form-control" id="passport" name="passport" value="<?=set_value('passport', !empty($passenger['passport']) ? $passenger['passport'] : '') ?>">
      <?=form_error('passport')?>
    </div>
  </div>
  
  <div class="form-group">
    <label for="passportexp" class="col-sm-2 control-label">
    	<?=lang('passenger_passportexp')?>:
    </label>
    <div class="col-sm-3">
      	
      	<div class="input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="passportexp" name="passportexp" 
	    	value="<?=set_value('passportexp', !empty($passenger['passportexp']) ?  date(DATE_FORMAT, strtotime($passenger['passportexp'])) : '')?>">
			<span class="input-group-addon" id="passportexp_cal"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('passportexp')?>
      
    </div>
  </div>
  
 <br>
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary btn-lg" name="action" value="<?=ACTION_SAVE?>">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      
      <button type="submit" class="btn btn-default btn-lg mg-left-10" name="action" value="<?=ACTION_CANCEL?>">	
		<?=lang('btn_cancel')?>
      </button>
    </div>
  </div>
</form>


<script type="text/javascript">

	$('#birth_day').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });


	$('#passportexp').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

    $('#birth_day_cal').click(function(){
    	$('#birth_day').focus();
    });

    $('#passportexp_cal').click(function(){
    	$('#passportexp').focus();
    });


</script>



<?php endif;?>
