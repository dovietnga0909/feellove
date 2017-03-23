<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
	<input type="hidden" value="next" name="action">
	
  	<div class="form-group">
	    <label for="name" class="col-sm-2 control-label"><?=lang('newsletters_sub')?> <?=mark_required()?></label>
	    <div class="col-sm-6">
	      <input type="text" class="form-control" id="name" placeholder="<?=lang('newsletters')?>..." name="name" value="<?=set_value('name', isset($pro)?$pro['name']:'')?>">
	      	<?=form_error('name')?>
	    </div>
  	</div>
 	
 	<div class="form-group">
	    <label for="display_name" class="col-sm-2 control-label"><?=lang('display_name')?> <?=mark_required()?></label>
	    <div class="col-sm-6">
	      <input type="text" class="form-control" id="display_name" placeholder="<?=lang('display_name')?>..." name="display_name" value="<?=set_value('display_name', isset($pro)?$pro['display_name']:'')?>">
	      	<?=form_error('display_name')?>
	    </div>
  	</div>
  
 	<div class="form-group">
	    <label for="template_type" class="col-sm-2 control-label"><?=lang('template_type')?> <?=mark_required()?></label>
	    <div class="col-sm-3">
	      
	      	<select class="form-control" id="template_type" name="template_type">
			  	<option value=""><?=lang('please_select')?></option>
			  	<?php foreach ($templates_type as $key => $value):?>
			  		<option value="<?=$key?>" <?=set_select('template_type', $key, isset($pro) && $pro['template_type'] == $key)?>><?=lang($value)?></option>
			 	 <?php endforeach;?>
		  		</select>
	      <?=form_error('template_type')?>
	    </div>
  	</div>
	
	<div class="form-group">
	    <label for="customer_gender" class="col-sm-2 control-label"><?=lang('customer_gender')?> <?=mark_required()?></label>
	    <div class="col-xs-10">
			<?php foreach ($customer_gender as $key => $gender):?>
			<div class="col-xs-3 pd-left-0">
			    <div class="checkbox">
			        <label>
			        <input type="checkbox" name="customer_gender[]" value="<?=$key?>" <?=set_checkbox('customer_gender', $key, isset($pro['customer_gender']) ?is_bit_value_contain($pro['customer_gender'], $key): false)?>> <?=lang($gender)?>
			        </label>
			    </div>
			</div>
			<?php endforeach;?>
			<div class="col-xs-12 pd-left-0">
				<?=form_error('customer_gender')?>
			</div>
		</div>
	</div>
	
	<div class="form-group">
	    <label for="customer_type" class="col-sm-2 control-label"><?=lang('customer_type')?> <?=mark_required()?></label>
	    <div class="col-xs-10">
			<?php foreach ($customer_type as $key => $customer):?>
			<div class="col-xs-3 pd-left-0">
			    <div class="checkbox">
			        <label>
			        <input type="checkbox" name="customer_type[]" value="<?=$key?>" <?=set_checkbox('customer_type', $key, isset($pro['customer_type']) ?is_bit_value_contain($pro['customer_type'], $key): false)?>> <?=lang($customer)?>
			        </label>
			    </div>
			</div>
			<?php endforeach;?>
			<div class="col-xs-12 pd-left-0">
				<?=form_error('customer_type')?>
			</div>
		</div>
	</div>
	
  	<br>
  	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-6">
	      	<button type="submit" class="btn btn-primary">      		
				<?=lang('btn_next')?>&nbsp;
				<span class="fa fa-arrow-right"></span>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url('newsletters')?>" role="button"><?=lang('btn_cancel')?></a>
	    </div>
  	</div>
</form>