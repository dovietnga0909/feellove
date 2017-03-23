<?php if(empty($voucher)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url()?>marketings/vouchers" role="button">
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
	    <label class="col-sm-3 control-label" for="customer_name"><?=lang('voucher_field_customer')?></label>
	    
	    <div class="col-sm-6">
	      
	       <input type="text" class="form-control input-sm" placeholder="Customer name..." id="customer_name" name="customer_name" 
		    		value="<?=set_value('customer_name', $voucher['customer_name'])?>">
		    		
		   <input type="hidden" id="customer_id" name="customer_id" value="<?=set_value('customer_id', $voucher['customer_id'])?>">
		    		
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="code"><?=lang('voucher_field_code')?></label>
	    <div class="col-sm-3">
		   	<input type="text" class="form-control input-sm" id="code" name="code" readonly="readonly" 
		    		value="<?=set_value('code', $voucher['code'])?>">
			<?=form_error('code')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="amount"><?=lang('voucher_field_amount')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		   	<input type="text" class="form-control input-sm price-cell" placeholder="500,000" id="amount" name="amount" 
		    		value="<?=set_value('amount', number_format($voucher['amount']))?>">
			<?=form_error('amount')?>				
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="customer_name"><?=lang('field_expired_date')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
		    <div class="input-group">
				<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="expired_date" name="expired_date" 
		    		value="<?=set_value('expired_date', bpv_format_date($voucher['expired_date'], DATE_FORMAT))?>">	
				<span id="cal_expired_date" class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
			<?=form_error('expired_date')?>				
	    </div>
	  </div>
	  
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="delivered"><?=lang('voucher_field_delivered')?></label>
	    <div class="col-sm-3">
	    	<select name="delivered" class="form-control input-sm">
	    		<option value="0" <?=set_select('delivered',0, $voucher['delivered'] == 0)?>><?=lang('no')?></option>
	    		<option value="1" <?=set_select('delivered',1, $voucher['delivered'] == 1)?>><?=lang('yes')?></option>
	    	</select>
		    		
	    </div>
	  </div>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label" for="status"><?=lang('field_status')?></label>
	    <div class="col-sm-3">
	    	<select name="status" class="form-control input-sm">
	    		<?php foreach ($voucher_status as $key=>$value):?>
	    			<option value="<?=$key?>" <?=set_select('status', $key, $key == $voucher['status'])?>><?=$value?></option>
	    		<?php endforeach;?>
	    	</select>	
	    </div>
	  </div>
	  
	  <?php if($voucher['used'] == STATUS_ACTIVE):?>
	  
	  <div class="form-group">
	    <label class="col-sm-3 control-label"><?=lang('voucher_field_used_by')?></label>
	    <div class="col-sm-6">
			<?php if($voucher['customer_used_name'] != ''):?>
				<label class="control-label">
				<a target="_blank" href="<?=site_url('customers/edit/'.$voucher['customer_used_id'])?>"><?=$voucher['customer_used_name']?></a>
					(<?=bpv_format_date($voucher['date_used'], DATE_TIME_FORMAT)?>)
				</label>
				
			<?php endif;?>	    		
	    </div>
	  </div>
	 
	  <?php endif;?>

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
	    todayHighlight: true
    });

    $('#cal_expired_date').click(function(){
		$('#expired_date').focus();
    });

    set_customer_autocomplete();

$( 	document ).ready(function() {
		
		$('.price-cell').mask('000,000,000,000,000', {reverse: true});
	});

</script>


<?php endif;?>