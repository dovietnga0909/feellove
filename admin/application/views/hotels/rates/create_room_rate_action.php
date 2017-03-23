<style>
	.price-cell{min-width:90px}
	.container{width:95%}
</style>

<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
  <input type="hidden" value="save" name="action">
  <div class="form-group">
    <label for="start_date_ip" class="col-sm-2 control-label">
    	<?=lang('rra_field_start_date')?> <?=mark_required()?>
    </label>
    <div class="col-sm-3" id="start_date">
      	
      	<div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="start_date_ip" name="start_date" 
	    		value="<?=set_value('start_date')?>">
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
      	
     	 
		<?=form_error('start_date')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="end_date_ip" class="col-sm-2 control-label">
    	<?=lang('rra_field_end_date')?> <?=mark_required()?>
    </label>
    
    <div class="col-sm-3" id="end_date">      
       <div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="end_date_ip" name="end_date" 
	    		value="<?=set_value('end_date')?>">
	    		
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  <?=form_error('end_date')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="week_day" class="col-sm-2 control-label"><?=lang('rra_field_week_day')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
    	<label class="checkbox-inline">
  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, true)?> name="week_day[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		<br>
	    <?=form_error('week_day[]')?>
    </div>
  </div>
  
  <?php 
  		$has_full_occupancy = false;
  		$has_triple = false;
  		$has_double = false;
  		$has_extra_bed = false;
  		foreach ($room_types as $value){
			if($value['max_occupancy'] > TRIPLE) $has_full_occupancy = true;
			if($value['max_occupancy'] >= TRIPLE) $has_triple = true;
			if($value['max_occupancy'] > SINGLE) $has_double = true;
			if($value['max_extra_beds'] > 0) $has_extra_bed = true;
		}
  ?>
  
  <div class="form-group">
  	
  	<div class="panel panel-default">

		<table class="table table-bordered">
			<thead>
				<tr>
					<th><?=lang('rate_room_types')?></th>
					
					<?php if($has_full_occupancy):?>
					<th colspan="2"><?=lang('rate_full_occupancy')?></th>
					<?php endif;?>
					
					<?php if($has_triple):?>
					<th colspan="2"><?=lang('rate_triple')?></th>
					<?php endif;?>
					
					<?php if($has_double):?>
					<th colspan="2"><?=lang('rate_double')?></th>
					<?php endif;?>
									
					<th colspan="2"><?=lang('rate_single')?></th>
					
					<?php if($has_extra_bed):?>
					<th><?=lang('rate_extra_bed')?></th>
					<?php endif;?>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<?php if($has_full_occupancy):?>
						<th><?=lang('sell')?></th>
						<th><?=lang('net')?></th>
					<?php endif;?>
					
					<?php if($has_triple):?>
						<th><?=lang('sell')?></th>
						<th><?=lang('net')?></th>
					<?php endif;?>
					
					<?php if($has_double):?>
						<th><?=lang('sell')?></th>
						<th><?=lang('net')?></th>
					<?php endif;?>
					
					<th><?=lang('sell')?></th>
					<th><?=lang('net')?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($room_types as $value):?>
					<tr>
						<td align="right"><?=$value['name']?></td>
						
						<?php if($has_full_occupancy):?>
							<?php if($value['max_occupancy'] > TRIPLE):?>	
								<td>
									<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="rr_full_occupancy_<?=$value['id']?>" 
			    					value="<?=set_value('rr_full_occupancy_'.$value['id'])?>">
								</td>
								<td>
									<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="net_rr_full_occupancy_<?=$value['id']?>" 
			    					value="<?=set_value('net_rr_full_occupancy_'.$value['id'])?>">
								</td>
							<?php else:?>
								<td colspan="2">&nbsp;</td>
							<?php endif;?>
						<?php endif;?>
						
						<?php if($has_triple):?>
							<?php if($value['max_occupancy'] >= TRIPLE):?>
								<td>
									<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="rr_triple_<?=$value['id']?>" 
			    					value="<?=set_value('rr_triple_'.$value['id'])?>">
								</td>
								<td>
									<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="net_rr_triple_<?=$value['id']?>" 
			    					value="<?=set_value('net_rr_triple_'.$value['id'])?>">
								</td>
							<?php else:?>
								<td colspan="2">&nbsp;</td>
							<?php endif;?>
						<?php endif;?>
						
						<?php if($has_double):?>
					
							<?php if($value['max_occupancy'] > SINGLE):?>
								<td>
									<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="rr_double_<?=$value['id']?>" 
			    					value="<?=set_value('rr_double_'.$value['id'])?>">
		    					</td>
		    					<td>
		    						<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="net_rr_double_<?=$value['id']?>" 
			    					value="<?=set_value('net_rr_double_'.$value['id'])?>">
		    					</td>
							<?php else:?>
								<td colspan="2">&nbsp;</td>
							<?php endif;?>
						<?php endif;?>
						
						
						
						<td>
							<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="rr_single_<?=$value['id']?>" 
	    					value="<?=set_value('rr_single_'.$value['id'])?>">
						</td>
						
						<td>
							<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="net_rr_single_<?=$value['id']?>" 
	    					value="<?=set_value('net_rr_single_'.$value['id'])?>">
						</td>
						
						<?php if($has_extra_bed):?>
							<td>
							<?php if($value['max_extra_beds'] > 0):?>
							
								<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" name="rr_extra_bed_<?=$value['id']?>" 
		    					value="<?=set_value('rr_extra_bed_'.$value['id'])?>">
							
							<?php endif;?>
							</td>
						<?php endif;?>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
  
  </div>

 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('hotels/room-rate-action/'.$hotel_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<script type="text/javascript">

	$('#start_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$('#end_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    startDate: "<?=date(DATE_FORMAT)?>"
    });

	$( document ).ready(function() {
		
		$('.price-cell').mask('000,000,000,000,000', {reverse: true});
	});

</script>
