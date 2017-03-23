<?php if(empty($surcharges)):?>
	
	<p class="text-danger"><?=lang('rate_no_surcharge_available')?></p>
	
<?php else:?>
	
	<?php foreach ($surcharges as $key => $value):?>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('rate_field_surcharge_name')?>:</div>
		<div class="col-xs-9"><b><?=$value['name']?></b></div>
	</div>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('rate_surcharge_date_apply')?>:</div>
		<div class="col-xs-9"><?=lang('from')?> <b><?=date(DATE_FORMAT, strtotime($value['start_date']))?></b> <?=lang('to')?> <b><?=date(DATE_FORMAT, strtotime($value['end_date']))?></b></div>
	</div>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('rate_surcharge_week_day_apply')?>:</div>
		<div class="col-xs-9">
			<?php foreach ($week_days as $k=>$wd):?>
		    	<label class="checkbox-inline">    		
		  			<input type="checkbox" value="<?=$k?>" <?=set_checkbox('week_day[]',$k, is_bit_value_contain($value['week_day'], $k))?> name="week_day[]" > <?=lang($wd)?>
				</label>
			<?php endforeach;?>	
		</div>
	</div>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('sur_field_charge_type')?>:</div>
		<div class="col-xs-9"><?=$charge_types[$value['charge_type']]?></div>
	</div>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('sur_field_amount')?>:</div>
		<div class="col-xs-9"><b><?=number_format($value['amount'])?></b> <?=lang('vnd')?></div>
	</div>
	
	<?php if(!empty($value['description'])):?>
	
	<div class="form-group row">
		<div class="col-xs-3"><?=lang('sur_field_description')?>:</div>
		<div class="col-xs-9"><?=$value['description']?></div>
	</div>
	
	<?php endif;?>
	
	<div class="form-group row">
		<div class="col-xs-9 col-xs-offset-3">			
			<a role="button" target="blank_"  href="<?=site_url('hotels/surcharges/'.$value['hotel_id'].'/edit/'.$value['id'].'/')?>" class="btn btn-default btn-sm"><?=lang('rate_surcharge_edit')?></a>
		</div>
	</div>
	
	<?php if($key < count($surcharges) - 1):?>
		<hr>
	<?php endif;?>
	
	<?php endforeach;?>
	
<?php endif;?>