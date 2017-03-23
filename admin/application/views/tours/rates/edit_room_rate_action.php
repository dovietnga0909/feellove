<style>
	.price-cell{min-width:90px}
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
	    		value="<?=set_value('start_date', date(DATE_FORMAT, strtotime($hra['start_date'])))?>">
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
	    		value="<?=set_value('end_date',date(DATE_FORMAT, strtotime($hra['end_date'])))?>">
	    		
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>	
	  <?=form_error('end_date')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="week_day" class="col-sm-2 control-label"><?=lang('rra_field_week_day')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      	<?php foreach ($week_days as $key=>$value):?>
      	
      	<?php 
      		$checked = is_bit_value_contain($hra['week_day'], $key);
      	?>
      	
    	<label class="checkbox-inline">
  			<input type="checkbox" id="check_box_<?=$key?>" value="<?=$key?>" <?=set_checkbox('week_day[]',$key, $checked)?> name="week_day[]" > <?=lang($value)?>
		</label>
		<?php endforeach;?>
		<br>
	    <?=form_error('week_day[]')?>
    </div>
  </div>
  
  <?php if ($tour['departure_type'] == MULTIPLE_DEPARTING_FROM && !empty($tour_departures)):?>
    <div class="form-group">
    	<label for="tour_departures" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_tour_departures')?>: <?=mark_required()?></label>
    	
    	<div class="col-xs-10" style="padding-left: 0">
        	<?php foreach ($tour_departures as $value):?>
        	<div class="col-xs-3 checkbox">
        	    <label>
        		<input type="checkbox" value="<?=$value['id']?>" name="tour_departures[]"
        			<?=set_checkbox('tour_departures[]', $value['id'], in_array($value['id'], $hra['tour_departures']))?>>
        		<?=$value['name']?>
        		</label>
            </div>
        	<?php endforeach;?>
        	
        	<div class="col-xs-12">
        	<?=form_error('tour_departures[]')?>
        	</div>
    	</div>
    </div>
    <?php endif;?>
  
  <div class="form-group">
  	
  	<div class="panel panel-default">

		<table class="table table-bordered">
			<thead>
				<tr>
					<th></th>
					
					<?php foreach ($accommodations as $value):?>
					<th colspan="2"><?=$value['name']?></th>
					<?php endforeach;?>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					
					<?php foreach ($accommodations as $value):?>
					<th><?=lang('sell')?></th>
					<th><?=lang('net')?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>
				<?php $rras = $hra['rras'];?>
				<?php foreach ($group_size as $key => $value):?>
					<tr>
						<td align="right"><?=lang($value)?></td>
						
						<?php foreach ($accommodations as $accom):?>
						
							<?php
								$rra;
								foreach ($rras as $ra) {
									if($ra['accommodation_id'] == $accom['id']) {
										$rra = $ra;
									}
 								}
							?>
						<td>
							<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" 
							name="<?='sell_'.$accom['id'].'_'.$key?>" value="<?=set_value('sell_'.$accom['id'].'_'.$key, $rra[$value.'_rate'])?>">
						</td>
						
						<td>
							<input type="text" class="form-control input-sm price-cell" placeholder="0000,000" 
							name="<?='net_'.$accom['id'].'_'.$key?>" value="<?=set_value('net_'.$accom['id'].'_'.$key, $rra[$value.'_net'])?>">
						</td>
						<?php endforeach;?>
						
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
      <a class="btn btn-default mg-left-10" href="<?=site_url('tours/room-rate-action/'.$tour_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
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
