<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'categories/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('tour_category_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_tour_category_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="status"><?=lang('field_status')?>:</label>
			<br>
			<select class="form-control input-sm" name="status">
				<option value=""><?=lang('please_select')?></option>
				<?php foreach ($status_config as $key => $value):?>
				<option value="<?=$key?>" <?=set_select('status', $key, isset($search_criteria['status']) && $search_criteria['status'] == $key)?>><?=lang($value)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label>&nbsp;</label>
		   	<br>
			<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
				<span class="fa fa-search"></span>
				<?=lang('btn_search')?>
			</button>
		</div>
		
		<div class="form-group mg-left-10">
		   	<label>&nbsp;</label>
		   	<br>
		  	<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
		  		<span class="fa fa-refresh"></span>
		  		<?=lang('btn_reset')?>
		  	</button>	  	
		 </div>
	</form>
</div>