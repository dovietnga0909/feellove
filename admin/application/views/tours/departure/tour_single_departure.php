<form class="form-horizontal" role="form" method="post">

<div class="form-group">
    <label for="destination_id" class="col-xs-3 control-label"><?=lang('tours_field_departing_from')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
	    <select class="form-control" name="destination_id" id="destination_id">
    		<option value=""><?=lang('tours_empty_select')?></option>
    		<?php foreach ($departing_froms as $des):?>
    		<option value="<?=$des['id']?>" <?=set_select('destination_id', $des['id'], $tour_departure['destination_id'] == $des['id'] ? true:false)?>><?=$des['name']?></option>
    		<?php endforeach;?>
    	</select>
    	<?=form_error('destination_id')?>
	</div>
</div>

<div class="form-group">
	<label for="departure_date_type" class="col-xs-3 control-label"><?=lang('tours_field_departure_date_type')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
	<select class="form-control" name="departure_date_type" id="departure_date_type">
		<option value=""><?=lang('tours_empty_select')?></option>
		<?php foreach ($tour_departure_date_type as $key => $value):?>
		<option value="<?=$key?>" <?=set_select('departure_date_type', $key, $tour_departure['departure_date_type'] == $key ? true : false)?>><?=lang($value)?></option>
		<?php endforeach;?>
	</select>
	<?=form_error('departure_date_type')?>
	</div>
</div>

<?php if(!empty($tour_departure)):?>
<div class="hide" id="group_weekdays">

    <div class="panel panel-default">
    	 
    	  <div class="panel-heading">
    	  	<?=lang('list_of_tour_departure_dates')?>
    	  	
    	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/departure/create_date/'.$tour_departure['id'])?>">
	  	        <span class="fa fa-arrow-circle-right"></span>
	  	        <?=lang('create_btn_tour_departure_date')?>
    	  	</a>
  
    	  </div>
    	<table class="table table-striped">
    		<thead>
    			<tr>
    				<th>#</th>
    				<th><?=lang('tours_field_start_date')?></th>
    				<th><?=lang('tours_field_end_date')?></th>				
    				<th><?=lang('tours_field_weekdays')?></th>
    				<th><?=lang('field_action')?></th>
    			</tr>
    		</thead>
    		<tbody>
    		    <?php foreach ($tour_departure_dates as $k => $departure_date):?>
    		    <tr>
    		        <td><?=$k + 1?></td>
    		        <td><?=date(DATE_FORMAT, strtotime($departure_date['start_date']))?></td>
    		        <td><?=date(DATE_FORMAT, strtotime($departure_date['end_date']))?></td>
    		        <td>
    		            <?php foreach($week_days as $k=>$wd):?>
        					<?php if(is_bit_value_contain($departure_date['weekdays'], $k)):?>
        						<?=lang($wd)?>,
        					<?php endif;?>
        				<?php endforeach;?>
    		        </td>
    		        <td>
		                <a href="<?=site_url('tours/departure/edit_date/'.$departure_date['id'])?>"><span class="fa fa-edit"></span></a>
						<a href="<?=site_url('tours/departure/delete_date/'.$departure_date['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
							<span class="fa fa-times"></span>
						</a>
    		        </td>
    		    </tr>
    		    <?php endforeach;?>
    		</tbody>
    	</table>
	</div>
	
</div>
<?php endif;?>

<div class="form-group hide" id="group_specific_dates">
    <label for="specific_dates" class="col-xs-3 control-label"><?=lang('tours_field_specific_dates')?> <?=mark_required()?></label>
    <div class="col-xs-8">
        <textarea class="form-control" rows="3" name="specific_dates" 
            id="specific_dates" placeholder="Ex: <?=date(DATE_FORMAT)?>;<?=date(DATE_FORMAT, strtotime('+3 days'))?>"><?=set_value('specific_dates', $tour['departure_specific_date'])?></textarea>
    </div>
    
    <?=form_error('specific_dates')?>
</div>

<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
	<span class="fa fa-download"></span>
	<?=lang('btn_save')?>
</button>
<a class="btn btn-default mg-left-10" href="<?=site_url('tours/profiles/'.$tour['id'])?>" role="button"><?=lang('btn_cancel')?></a>

</form>

<script type="text/javascript">

	$('#departure_date_type').change(function() {

		_get_selected_type();
		
	});

	function _get_selected_type() {
		// hide all groups
		$('#group_weekdays').addClass('hide');
    	$('#group_specific_dates').addClass('hide');

		// get selected and show it
	    var selected = $('#departure_date_type').val();
	    
	    if(selected == 2) {
	    	$('#group_weekdays').removeClass('hide');
	    } else if(selected == 3) {
	    	$('#group_specific_dates').removeClass('hide');
	    }
	}

	_get_selected_type();
</script>