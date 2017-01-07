<div class="well">
	<form class="form-inline" role="form" method="post">
	  
	  <div class="form-group">
	  	<label for="search_name"><?=lang('sur_field_name')?></label>
	  	<br>
	    <input type="text" class="form-control input-sm" placeholder="<?=lang('sur_field_name')?>..." id="search_name" name="name" 
	    	value="<?=set_value('name', !empty($search_criteria['name'])?$search_criteria['name']:'')?>">   
	  </div>
	  
	  <div class="form-group mg-left-10" id="start_date">
	  	<label for="search_start_date"><?=lang('sur_field_start_date')?></label>
	  	<br>
	  
		<div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_start_date" name="start_date" 
	    		value="<?=set_value('start_date', !empty($search_criteria['start_date'])?$search_criteria['start_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	    	
	  </div>
	  
	  <div class="form-group" id="end_date">
	  	<label for="search_end_date"><?=lang('sur_field_end_date')?></label>
	  	<br>
	  	
	    <div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_end_date" name="end_date" 
	    		value="<?=set_value('end_date', !empty($search_criteria['end_date'])?$search_criteria['end_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  </div>
	  
	   
	  <div class="form-group mg-left-10">
	    <label for="search_charge_type"><?=lang('sur_field_charge_type')?></label>
	    <br>
		<select class="form-control input-sm" id="search_charge_type" name="charge_type">
			<option value=""><?=lang('all')?></option>
			<?php foreach ($charge_types as $key=>$value):?>
				
				<option value="<?=$key?>" <?=set_select('charge_type', $key, !empty($search_criteria['charge_type']) && $key == $search_criteria['charge_type'])?>><?=$value?></option>
				
			<?php endforeach;?>
			
		</select>
	  </div>
	  
	  
	  
	   <div class="form-group mg-left-10">
	   	<label>&nbsp;</label>
	   	<br>
	  	<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
	  		<span class="fa fa-search"></span>
	  		<?=lang('btn_search')?>
	  	</button>	  	
	  </div>
	  
	  <div class="form-group mg-left-10">
	   	<label>&nbsp;</label>
	   	<br>
	  	<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
	  		<span class="fa fa-refresh"></span>
	  		<?=lang('btn_reset')?>
	  	</button>	  	
	  </div>
	</form>
</div>

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