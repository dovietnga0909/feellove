<form class="form-inline" role="form" method="post">
<div class="well well-sm">
	
	  
	  <div class="form-group">
	    <label for="room_type"><?=lang('rate_room_type')?></label>
	  	<br>
		<select class="form-control input-sm" id="room_type" name="room_type">
			<option value=""><?=lang('rate_all_room_type')?></option>
			
			<?php foreach ($room_types as $value):?>
				<option value="<?=$value['id']?>" <?=set_select('room_type', $value['id'], isset($search_criteria['room_type']) && $search_criteria['room_type'] == $value['id'])?>><?=$value['name']?></option>
			<?php endforeach;?>
			
		</select>
	  </div>
	  
	  <div class="form-group" id="start_date" style="margin-left:20px">
	  	<label for="search_start_date"><?=lang('field_start_date')?></label>
	  	
		<div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_start_date" name="start_date" 
	    		value="<?=set_value('start_date', !empty($search_criteria['start_date'])?$search_criteria['start_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	    	
	  </div>
	  
	  <!-- 
	  <div class="form-group" id="end_date" style="margin-left:20px">
	  	<label for="search_end_date"><?=lang('field_end_date')?></label>
	  	<br>
	  	
	    <div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="search_end_date" name="end_date" 
	    		value="<?=set_value('end_date', !empty($search_criteria['end_date'])?$search_criteria['end_date']:'')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  </div>
	   -->
	  
	   <div class="form-group mg-left-10">
	   	<label>&nbsp;</label>
	   	<br>
	  	<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
	  		<span class="fa fa-search"></span>
	  		<?=lang('btn_search')?>
	  	</button>	  	
	  </div>
	  
	  
	  <div class="form-group pull-right">
	  	  	<label><?=lang('next_back_days')?></label>
	   		<br>	
		  <div class="input-group">
		      <span class="input-group-btn">
		      	
		        <button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_BACK?>">		        	
		        	&lt;
		        </button>
		      </span>
		      <input type="text" class="form-control input-sm" readonly="readonly" value="<?=$search_criteria['start_date']?> &divide; <?=$search_criteria['end_date']?>">
		      <span class="input-group-btn">
		        <button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_NEXT?>">
		        	&gt;	        	
		        </button>
		        
		      </span>
		    </div><!-- /input-group -->
		</div>
	 
</div>


</form>

<script type="text/javascript">
	
	$('#start_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true
    });

</script>