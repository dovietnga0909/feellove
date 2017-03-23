
<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="save" name="action">

  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"><?=lang('sur_field_name')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" placeholder="<?=lang('surcharge_name')?>..." name="name" value="<?=set_value('name')?>">
      <?=form_error('name')?>
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="start_date_ip" class="col-sm-2 control-label">
    	<?=lang('sur_field_start_date')?> <?=mark_required()?>
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
    	<?=lang('sur_field_end_date')?> <?=mark_required()?>
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
  
  <div class="form-group">
    <label for="week_day" class="col-sm-2 control-label"><?=lang('sur_field_week_day')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
    	<label class="checkbox-inline">
  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, true)?> name="week_day[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		<br>
	    <?=form_error('week_day[]')?>
    </div>
  </div>
  
  <div class="form-group">
    <label for="charge_type" class="col-sm-2 control-label"><?=lang('sur_field_charge_type')?> <?=mark_required()?></label>
    <div class="col-sm-3">
      
      <select class="form-control" id="charge_type" name="charge_type">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($charge_types as $key=>$value):?>
		  		<option value="<?=$key?>" <?=set_select('charge_type',$key)?>><?=$value?></option>
		  <?php endforeach;?>		
	  </select>
	  
      <?=form_error('charge_type')?>
    </div>
  </div>
  
  <div class="form-group">
    <label for="amount" class="col-sm-2 control-label"><?=lang('sur_field_amount')?> <?=mark_required()?></label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="amount" placeholder="<?=lang('sur_field_amount')?>..." name="amount" value="<?=set_value('amount')?>">      
      <?=form_error('amount')?>
    </div>
  </div>
  
  <div class="form-group">
    <label for="description" class="col-sm-2 control-label"><?=lang('sur_field_description')?></label>
    <div class="col-sm-6">
      <textarea class="form-control rich-text" rows="5" name="description" id="description" placeholder="<?=lang('surcharge_description')?>..."><?=set_value('description')?></textarea>
      <?=form_error('description')?>
    </div>
  </div>
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('hotels/surcharges/'.$hotel_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<script type="text/javascript">

	init_text_editor();

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

	$( document ).ready(function() {
		
		$('#amount').maskMoney({allowZero: false, allowNegative: false, thousands:',', decimal:'.', affixesStay: false,  precision: 0});
	});

</script>
