
<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<input type="hidden" value="save" name="action">

	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="name"><?=lang('pro_field_name')?> <?=mark_required()?></label>
	    
	    <div class="col-sm-6">
	      
	       <input type="text" class="form-control input-sm" placeholder="Promotion Name..." id="name" name="name" 
		    		value="<?=set_value('name')?>">    		
		   <?=form_error('name')?>			    		
	    </div>
	  </div>

	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="status"><?=lang('pro_field_status')?></label>
	    <div class="col-sm-3">
		   	<select name="status" class="form-control input-sm">
		   		<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE)?>><?=lang('active')?></option>
				<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE)?>><?=lang('inactive')?></option>
		   	</select>
	    </div>
	    
	    <label class="col-sm-2 control-label" for=""><?=lang('pro_field_public')?></label>
	      <div class="col-sm-3">
	      	 <select name="public" class="form-control input-sm">
		   		<option value="<?=STATUS_ACTIVE?>" <?=set_select('public', STATUS_ACTIVE)?>><?=lang('yes')?></option>
				<option value="<?=STATUS_INACTIVE?>" <?=set_select('public', STATUS_INACTIVE)?>><?=lang('no')?></option>
		   	</select>
	      </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="expired_date"><?=lang('field_expired_date')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		    <div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="expired_date" name="expired_date" 
		    		value="<?=set_value('expired_date')?>">	
				<span id="cal_expired_date" class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
			<?=form_error('expired_date')?>				
	    </div>
	    <label class="col-sm-2 control-label" for="is_multiple_time"><?=lang('pro_field_is_multiple_time')?></label>
        <div class="col-sm-3">
            <select class="form-control input-sm" name="is_multiple_time" id="is_multiple_time">
                <option value="<?=STATUS_ACTIVE?>" <?=set_select('is_multiple_time', STATUS_ACTIVE)?>><?=lang('yes')?></option>
                <option value="<?=STATUS_INACTIVE?>" <?=set_select('is_multiple_time', STATUS_INACTIVE)?>><?=lang('no')?></option>
            </select>
        </div>
	  </div>
	  
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="max_nr_booked"><?=lang('pro_field_max_booked')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm" placeholder="500..." id="max_nr_booked" name="max_nr_booked" 
		    		value="<?=set_value('max_nr_booked')?>">
		    		
		    <?=form_error('max_nr_booked')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	  	  <label class="col-sm-3 control-label" for="apply_all"><?=lang('pro_field_apply_all')?></label>
	      <div class="col-sm-3">
	      	 <select name="apply_all" class="form-control input-sm">
	      	 	<option value="<?=STATUS_INACTIVE?>" <?=set_select('apply_all', STATUS_INACTIVE)?>><?=lang('no')?></option>
		   		<option value="<?=STATUS_ACTIVE?>" <?=set_select('apply_all', STATUS_ACTIVE)?>><?=lang('yes')?></option>
		   	</select>
	      </div>
	  </div>    
	  
	  <!-- 
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="init_nr_booked"><?=lang('pro_field_init_booked')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm" placeholder="45..." id="init_nr_booked" name="init_nr_booked" 
		    		value="<?=set_value('init_nr_booked')?>">				
	    </div>
	  </div>
	   -->
	   
	  <hr>
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="hotel_discount_type"><?=lang('pro_field_hotel_discount_type')?></label>
	    <div class="col-sm-4">
		    <select class="form-control input-sm" name="hotel_discount_type">
		    	<option value="">----- <?=lang('no_discount')?> ----------</option>
		    	<?php foreach ($hotel_discount_types as $key=>$value):?>
		    		
		    		<option value="<?=$key?>" <?=set_select('hotel_discount_type', $key)?>><?=$value?></option>
		    		
		    	<?php endforeach;?>
		    </select>			
	    </div>
	  </div>
	 
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="hotel_get"><?=lang('pro_field_hotel_get')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="10..." id="hotel_get" name="hotel_get" 
		    		value="<?=set_value('hotel_get')?>">
		    <?=form_error('hotel_get')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="hotel_get_max"><?=lang('pro_field_hotel_get_max')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="2,000,000..." id="hotel_get_max" name="hotel_get_max" 
		    		value="<?=set_value('hotel_get_max')?>">
		    <?=form_error('hotel_get_max')?>				
	    </div>
	  </div>
	  
		<hr>
		
	
	<div class="form-group">
	    <label class="col-sm-3 control-label" for="flight_discount_type"><?=lang('pro_field_flight_discount_type')?></label>
	    <div class="col-sm-4">
		    <select class="form-control input-sm" name="flight_discount_type">
		    	<option value="">----- <?=lang('no_discount')?> ----------</option>
		    	<?php foreach ($flight_discount_types as $key=>$value):?>
		    		
		    		<option value="<?=$key?>" <?=set_select('flight_discount_type', $key)?>><?=$value?></option>
		    		
		    	<?php endforeach;?>
		    </select>			
	    </div>
	  </div>
	 
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="flight_get"><?=lang('pro_field_flight_get')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="10..." id="flight_get" name="flight_get" 
		    		value="<?=set_value('flight_get')?>">
		    <?=form_error('flight_get')?>				
	    </div>
	  </div>
	  
	  <hr>
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="cruise_discount_type"><?=lang('pro_field_cruise_discount_type')?></label>
	    <div class="col-sm-4">
		    <select class="form-control input-sm" name="cruise_discount_type">
		    	<option value="">----- <?=lang('no_discount')?> ----------</option>
		    	<?php foreach ($cruise_discount_types as $key=>$value):?>
		    		
		    		<option value="<?=$key?>" <?=set_select('cruise_discount_type', $key)?>><?=$value?></option>
		    		
		    	<?php endforeach;?>
		    </select>			
	    </div>
	  </div>
	 
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="cruise_get"><?=lang('pro_field_cruise_get')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="10..." id="cruise_get" name="cruise_get" 
		    		value="<?=set_value('cruise_get')?>">
		    <?=form_error('cruise_get')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="cruise_get_max"><?=lang('pro_field_cruise_get_max')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="2,000,000..." id="cruise_get_max" name="cruise_get_max" 
		    		value="<?=set_value('cruise_get_max')?>">
		    <?=form_error('cruise_get_max')?>				
	    </div>
	  </div>
	  
	  <!-- 
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="flight_get_max"><?=lang('pro_field_flight_get_max')?></label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control input-sm price-cell" placeholder="2,000,000..." id="flight_get_max" name="flight_get_max" 
		    		value="<?=set_value('flight_get_max')?>">
		    <?=form_error('flight_get_max')?>				
	    </div>
	  </div>
	   -->
	  
	  <hr>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="discount_note"><?=lang('pro_field_discount_note')?></label>
	    <div class="col-sm-9">
		    <textarea rows="3" class="form-control" name="discount_note">
		    	<?=set_value('discount_note')?>
		    </textarea>				
	    </div>
	  </div>
	   
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="description"><?=lang('pro_field_description')?></label>
	    <div class="col-sm-9">
		    <textarea rows="10" class="form-control rich-text" name="description">
		    	<?=set_value('description')?>
		    </textarea>				
	    </div>
	  </div>
		
	
	 <br>
		 	
	  <div class="form-group">
	    <div class="col-sm-offset-3 col-sm-6">
	      <button type="submit" class="btn btn-primary btn-lg">
	      	<span class="fa fa-download"></span>	
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url()?>marketings" role="button"><?=lang('btn_cancel')?></a>
	    </div>
	  </div>
</form>

<script type="text/javascript">

	$('#expired_date').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate:"<?=date(DATE_FORMAT)?>"
    });

    $('#cal_expired_date').click(function(){
		$('#expired_date').focus();
    });

	$( document ).ready(function() {
		
		$('.price-cell').mask('000,000,000,000,000', {reverse: true});
	});
	
    tinymce.init({
			selector: "textarea.rich-text",
			menubar: false,
			theme: "modern",
		    plugins: [
		        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code fullscreen",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "emoticons template paste textcolor"
		    ],
		    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		    toolbar2: "print preview media | forecolor backcolor code",
		    image_advtab: true,
	});

</script>
