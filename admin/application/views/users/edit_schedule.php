<?php if(empty($schedule)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('users/schedules')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label for="user_id" class="col-xs-3 control-label"><?=lang('h_user')?>: <?=mark_required()?></label>
		<div class="col-xs-4">
			<select class="form-control input-sm" id="user_id" name="user_id">
				<option value=""><?=lang('please_select')?></option>
				
				<?php foreach ($hotline_users as $user):?>
				
					<option value="<?=$user['id']?>" <?=set_select('user_id', $user['id'], $user['id'] == $schedule['user_id'])?>><?=$user['username']?></option>
				
				<?php endforeach;?>
				
			</select>
			
			<?=form_error('user_id')?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="status" class="col-xs-3 control-label"><?=lang('field_status')?>:</label>
		<div class="col-xs-4">
			<select class="form-control" name="status">
				<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, STATUS_ACTIVE == $schedule['status'])?>><?=lang('active')?></option>
				<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_ACTIVE, STATUS_INACTIVE == strval($schedule['status']))?>><?=lang('inactive')?></option>
			</select>
			<?=form_error('status')?>
		</div>
	</div>
	
	<div class="form-group">
	    <label for="start_date" class="col-sm-3 control-label">
	    	<?=lang('field_start_date')?> <?=mark_required()?>
	    </label>
	    <div class="col-sm-4">
	      	
	      	<div class="input-group">			    
				<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date" name="start_date" 
		    		value="<?=set_value('start_date', date(DATE_FORMAT, strtotime($schedule['start_date'])))?>">
				<span class="input-group-addon" id="start_date_cal"><span class="fa fa-calendar"></span></span>				  
			</div>
			
			<?=form_error('start_date')?>
	      
	    </div>
	 </div>
  
  	<div class="form-group">
	    <label for="end_date" class="col-sm-3 control-label">
	    	<?=lang('field_end_date')?> <?=mark_required()?>
	    </label>
	    
	    <div class="col-sm-4">
	    
	      	<div class="input-group">			    
				<input type="text" class="form-control" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date" name="end_date" 
		    		value="<?=set_value('end_date', date(DATE_FORMAT, strtotime($schedule['end_date'])))?>">
				<span class="input-group-addon" id="end_date_cal"><span class="fa fa-calendar"></span></span>				  
			</div>
			
			<?=form_error('end_date')?>
	      
	    </div>
	 </div>
  
	  <div class="form-group">
	    <label for="week_day" class="col-sm-3 control-label"><?=lang('h_week_day')?> <?=mark_required()?></label>
	    <div class="col-sm-6">
	      	<?php foreach ($week_days as $key=>$value):?>
	    	<label class="checkbox-inline">
	    		<?php 
	    			$checked = is_bit_value_contain($schedule['week_day'], $key);
	    		?>
	  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, $checked)?> name="week_day[]" > <?=lang($value)?>
			</label>
			<?php endforeach;?>
			<br>
		    <?=form_error('week_day[]')?>
	    </div>
	  </div>
	
	
	<div class="form-group">
	    <div class="col-xs-offset-3 col-xs-6">
	    	<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<a class="btn btn-default mg-left-10" href="<?=site_url('users/schedules')?>" role="button"><?=lang('btn_cancel')?></a>
	    </div>
	</div>
</form>

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

	$('#start_date_cal').click(function(){
		$('#start_date').focus();
	});

	$('#end_date_cal').click(function(){
		$('#end_date').focus();
	});
	
</script>

<?php endif;?>