<div class="well well-sm">
	<form role="form" method="post">
	  <div class="row">
	  
	  	  <div class="col-xs-2">
			    <label for="code"><?=lang('voucher_field_code')?></label>
			   <input type="text" class="form-control input-sm" placeholder="code..." id="code" name="code" 
		    		value="<?=set_value('code', !empty($search_criteria['code'])?$search_criteria['code']:'')?>">	
		  </div>
		  
	  	
	  	  <div class="col-xs-3">
			    <label for="customer_name"><?=lang('voucher_field_customer')?></label>
			    <input type="text" class="form-control input-sm" placeholder="Customer name..." id="customer_name" name="customer_name" 
		    		value="<?=set_value('customer_name', !empty($search_criteria['customer_name'])?$search_criteria['customer_name']:'')?>">
		    		
		    	<input type="hidden" id="customer_id" name="customer_id" 
		    		value="<?=set_value('customer_id', !empty($search_criteria['customer_id'])?$search_criteria['customer_id']:'')?>">	
		  </div>
	  
	  	  <div class="col-xs-2">
			    <label for="start_date"><?=lang('field_start_date')?></label>
			   <input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date" name="start_date" 
		    		value="<?=set_value('start_date', !empty($search_criteria['start_date'])?$search_criteria['start_date']:'')?>">	
		  </div>
		  
		  <div class="col-xs-2">
			    <label for="end_date"><?=lang('field_end_date')?></label>
			   <input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date" name="end_date" 
		    		value="<?=set_value('end_date', !empty($search_criteria['end_date'])?$search_criteria['end_date']:'')?>">	
		  </div>
		  
		 <div class="col-xs-3">
		 	<div class="row">
		 		<div class="col-xs-6">
				   <label for="used"><?=lang('voucher_field_delivered')?></label>
					<select class="form-control input-sm" id="delivered" name="delivered">
						<option value=""><?=lang('all')?></option>
						<option value="<?=STATUS_ACTIVE?>" <?=set_select('delivered', STATUS_ACTIVE, isset($search_criteria['delivered']) && $search_criteria['delivered'] == STATUS_ACTIVE)?>><?=lang('yes')?></option>
					  	<option value="<?=STATUS_INACTIVE?>" <?=set_select('delivered', STATUS_INACTIVE, isset($search_criteria['delivered']) && $search_criteria['delivered'] == ''.STATUS_INACTIVE)?>><?=lang('no')?></option>
					</select>
				</div>
				
				<div class="col-xs-6">
					<label for="used"><?=lang('pro_field_status')?></label>
					<select class="form-control input-sm" id="status" name="status">
						<option value="" ><?=lang('all')?></option>
						<?php foreach ($voucher_status as $key=>$value): ?>
							<option value="<?=$key?>" <?=set_select('status', $key, isset($search_criteria['status']) && $search_criteria['status'] == $key)?>><?=$value?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>	
		  </div>
		  
		  	
	  </div>
	  
	  		<div class="row">
  	 			<div class="col-xs-4 col-xs-offset-8 text-right" style="padding-top:10px">
  	 				
	  	 			<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
				  		<span class="fa fa-search"></span>
				  		<?=lang('btn_search')?>
				  	</button>
				  	&nbsp;&nbsp;
				  	<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
				  		<span class="fa fa-refresh"></span>
				  		<?=lang('btn_reset')?>
				  	</button>	  	
		  	
  	 			</div>
	  	 	</div>

	  
	</form>
</div>

<script type="text/javascript">

	$('#start_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

	$('#end_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

    $('#cal_start_date').click(function(){
		$('#start_date').focus();
    });

    $('#cal_end_date').click(function(){
		$('#end_date').focus();
    });

    set_customer_autocomplete();
        

</script>