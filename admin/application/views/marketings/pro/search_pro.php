<div class="well well-sm">
	<form role="form" method="post">
	  <div class="row">
	  	  <div class="col-xs-3">
			    <label for="start_date"><?=lang('field_start_date')?></label>
			    <div class="input-group">
					<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date" name="start_date" 
		    		value="<?=set_value('start_date', !empty($search_criteria['start_date'])?$search_criteria['start_date']:'')?>">
					<span id="cal_start_date" class="input-group-addon"><span class="fa fa-calendar"></span></span>
				</div>		
		  </div>
		  
		  <div class="col-xs-3">
			    <label for="end_date"><?=lang('field_end_date')?></label>
			    <div class="input-group">
					<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date" name="end_date" 
		    		value="<?=set_value('end_date', !empty($search_criteria['end_date'])?$search_criteria['end_date']:'')?>">
					<span id="cal_end_date" class="input-group-addon"><span class="fa fa-calendar"></span></span>
				</div>		
		  </div>
	  	  
	  	 
	  	  <div class="col-xs-2">
			    <label for="status"><?=lang('field_status')?></label>
				<select class="form-control input-sm" id="status" name="status">
					<option value=""><?=lang('all')?></option>
					<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == STATUS_ACTIVE)?>><?=lang('active')?></option>
				  	<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == ''.STATUS_INACTIVE)?>><?=lang('inactive')?></option>
				</select>
		  </div>
	  
		  <div class="col-xs-3" style="padding-top:24px">
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

</script>