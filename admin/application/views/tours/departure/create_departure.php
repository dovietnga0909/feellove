<form class="form-horizontal" role="form" method="post">
    
    <div class="form-group">
        <label for="destination_id" class="col-xs-3 control-label"><?=lang('tours_field_departing_from')?>: <?=mark_required()?></label>
    	<div class="col-xs-4">
    	    <select class="form-control" name="destination_id" id="destination_id">
        		<option value=""><?=lang('tours_empty_select')?></option>
        		<?php foreach ($departing_froms as $des):?>
        		<option value="<?=$des['id']?>" <?=set_select('destination_id', $des['id'])?>><?=$des['name']?></option>
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
    		<option value="<?=$key?>" <?=set_select('departure_date_type', $key)?>><?=lang($value)?></option>
    		<?php endforeach;?>
    	</select>
    	<?=form_error('departure_date_type')?>
    	</div>
    </div>
    
    <div class="form-group">
        <label for="service_includes" class="col-xs-3 control-label"><?=lang('tours_field_service_includes')?>: </label>
    	<div class="col-xs-9">
    	     <textarea rows="5" class="form-control" name="service_includes" id="service_includes"><?=set_value('service_includes')?></textarea>
    	</div>
    </div>
    
    <div class="form-group">
        <label for="service_excludes" class="col-xs-3 control-label"><?=lang('tours_field_service_excludes')?>: </label>
    	<div class="col-xs-9">
    	     <textarea rows="5" class="form-control" name="service_excludes" id="service_excludes"><?=set_value('service_excludes')?></textarea>
    	</div>
    </div>

    <button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
    	<span class="fa fa-download"></span>
    	<?=lang('btn_save')?>
    </button>
    <a class="btn btn-default mg-left-10" href="<?=site_url('tours/departure/'.$tour['id'])?>" role="button"><?=lang('btn_cancel')?></a>

</form>