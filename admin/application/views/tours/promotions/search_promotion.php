<div class="well well-sm">
	<form class="form-inline" role="form" method="post">
	  
	  <div class="form-group mg-left-10" id="start_date">
	  	<label for="search_start_date"><?=lang('field_start_date')?></label>
	  	<br>
	  
		<div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_start_date" name="start_date" 
	    		value="<?=set_value('start_date', !empty($search_criteria['start_date'])?$search_criteria['start_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	    	
	  </div>
	  
	  <div class="form-group" id="end_date">
	  	<label for="search_end_date"><?=lang('field_end_date')?></label>
	  	<br>
	  	
	    <div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_end_date" name="end_date" 
	    		value="<?=set_value('end_date', !empty($search_criteria['end_date'])?$search_criteria['end_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  </div>
	  
	   
	  <div class="form-group mg-left-10">
	    <label for="search_pro_type"><?=lang('pro_field_type')?></label>
	    <br>
		<select class="form-control input-sm" id="search_pro_type" name="promotion_type">
			<option value=""><?=lang('all')?></option>
			<?php foreach ($promotion_types as $key=>$value):?>
				
				<option value="<?=$key?>" <?=set_select('promotion_type', $key, !empty($search_criteria['promotion_type']) && $key == $search_criteria['promotion_type'])?>><?=$value?></option>
				
			<?php endforeach;?>
			
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="search_show_on_web"><?=lang('pro_field_show_on_web')?></label>
	    <br>
		<select class="form-control input-sm" id="search_show_on_web" name="show_on_web">
			<option value=""><?=lang('all')?></option>
			<option value="1" <?=set_select('show_on_web',1, isset($search_criteria['show_on_web']) && $search_criteria['show_on_web'] == 1)?>><?=lang('yes')?></option>
		 	<option value="0" <?=set_select('show_on_web',0, isset($search_criteria['show_on_web']) && $search_criteria['show_on_web'] == 0)?>><?=lang('no')?></option>
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