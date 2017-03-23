
<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<input type="hidden" value="save" name="action">

	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="customer_name"><?=lang('voucher_field_customer')?></label>
	    
	    <div class="col-sm-6">
	      
	       <input type="text" class="form-control input-sm" placeholder="Customer name..." id="customer_name" name="customer_name" 
		    		value="<?=set_value('customer_name')?>">
		    		
		   <input type="hidden" id="customer_id" name="customer_id" value="<?=set_value('customer_id')?>">
		    		
	    </div>
	  </div>

	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="amount"><?=lang('voucher_field_amount')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		   	<input type="text" class="form-control input-sm price-cell" placeholder="500,000" id="amount" name="amount" 
		    		value="<?=set_value('amount')?>">
			<?=form_error('amount')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="customer_name"><?=lang('field_expired_date')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		    <div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="expired_date" name="expired_date" 
		    		value="<?=set_value('expired_date')?>">	
				<span id="cal_expired_date" class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
			<?=form_error('expired_date')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="number_voucher"><?=lang('voucher_field_number')?></label>
	    <div class="col-sm-3">
	    	
	    	<select name="number_voucher" class="form-control input-sm">
	    		<?php foreach ($number_vouchers as $value):?>
	    		<option value="<?=$value?>">
	    			<?=$value?>
	    		</option>
	    		<?php endforeach;?>
	    	</select>
		    		
	    </div>
	  </div>

	  <div class="form-group">
	    <div class="col-sm-offset-3 col-sm-6">
	      <button type="submit" class="btn btn-primary">
	      	<span class="fa fa-download"></span>	
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url()?>marketings/vouchers/" role="button"><?=lang('btn_cancel')?></a>
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

    set_customer_autocomplete();

$( 	document ).ready(function() {
		
		$('.price-cell').mask('000,000,000,000,000', {reverse: true});
	});

</script>
