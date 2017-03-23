<?php if(empty($ad)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url()?>advertises/" role="button">
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
    <label for="name" class="col-sm-2 control-label"><?=lang('ad_field_name')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" placeholder="<?=lang('advertise_name')?>..." name="name" value="<?=set_value('name', $ad['name'])?>">
      <?=form_error('name')?>
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="status" class="col-sm-2 control-label">
    	<?=lang('field_status')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3">      
     	<select class="form-control" id="status" name="status">
		  	<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, $ad['status'] == STATUS_ACTIVE)?>><?=lang('active')?></option>
		  	<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE, $ad['status'] == STATUS_INACTIVE)?>><?=lang('inactive')?></option>
		</select>
		      
    </div>
  </div>
  
  <div class="form-group">
    <label for="data_type" class="col-sm-2 control-label"><?=lang('ad_field_data_type')?> <?=mark_required()?></label>
    <div class="col-sm-3">      
      <select class="form-control" id="data_type" name="data_type">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($data_types as $key=>$value):?>
		  		<option value="<?=$key?>" <?=set_select('data_type', $key, $key == $ad['data_type'])?>><?=$value?></option>
		  <?php endforeach;?>
	  </select> 
	  <?=form_error('data_type')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="start_date_ip" class="col-sm-2 control-label">
    	<?=lang('field_start_date')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="start_date">
      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date_ip" name="start_date" 
	    		value="<?=set_value('start_date', date(DATE_FORMAT, strtotime($ad['start_date'])))?>">
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
	    		value="<?=set_value('end_date', date(DATE_FORMAT, strtotime($ad['end_date'])))?>">
	    		
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  <?=form_error('end_date')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="week_day" class="col-sm-2 control-label"><?=lang('ad_field_week_day')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
    	<label class="checkbox-inline">
    		<?php 
    			$checked = is_bit_value_contain($ad['week_day'], $key);
    		?>
  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, $checked)?> name="week_day[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		<br>
	    <?=form_error('week_day[]')?>
    </div>
  </div>
	
	<!-- 
   <div class="form-group">
    <label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_display_on')?></label>
    <div class="col-sm-8">
      	
      	<?php foreach ($ad_pages as $key=>$value):?>
    	<label class="checkbox-inline">
    		<?php 
    			$checked = is_bit_value_contain($ad['display_on'], $key);
    		?>
  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('display_on[]',$key, $checked)?> name="display_on[]" > <?=$value?>
		</label>
		<?php endforeach;?>
		<br>
	   
	    <?=form_error('display_on[]')?>
    </div>
  </div>
  
   -->
   
  
  <div class="form-group">
    <label for="link" class="col-sm-2 control-label"><?=lang('ad_field_link')?> <?=mark_required()?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="link" placeholder="<?=lang('ad_field_link')?>..." name="link" value="<?=set_value('link', $ad['link'])?>">
      <?=form_error('link')?>
    </div>
  </div>
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('advertises')?>/" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>


<script type="text/javascript">

	$('#start_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#end_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

</script>



<?php endif;?>
