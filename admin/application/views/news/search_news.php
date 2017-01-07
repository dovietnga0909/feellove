<div class="well">
<form role="form" name="sfrm" method="post" action="<?=site_url().'news/'?>">
<div class="row">
    <div class="col-xs-4">
		<label for="search_name"><?=lang('news_field_name')?></label>
  		<br>
		<input type="text" placeholder="<?=lang('list_news_search_holder')?>" class="form-control input-sm" name="search_text"
			value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
	</div>
	
	<div class="col-xs-2">
		<label for="type"><?=lang('news_field_type')?>:</label>
		<br>
		<select class="form-control input-sm" name="type" id="type">
			<option value=""><?=lang('news_select_type')?></option>
			<?php foreach ($news_types as $k => $type):?>
			<option value="<?=$k?>" <?=set_select('type', $k, isset($search_criteria['type']) && $search_criteria['type'] == $k)?>><?=lang($type)?></option>
			<?php endforeach;?>
		</select>
	</div>
	
	<div class="col-xs-2">
        <label for="status"><?=lang('field_status')?></label>
        <br>
        <select class="form-control input-sm" id="status" name="status">
        	<option value=""><?=lang('all')?></option>
        	<option value="<?=STATUS_ACTIVE?>" <?=set_select('status', STATUS_ACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == STATUS_ACTIVE)?>><?=lang('active')?></option>
          	<option value="<?=STATUS_INACTIVE?>" <?=set_select('status', STATUS_INACTIVE, isset($search_criteria['status']) && $search_criteria['status'] == ''.STATUS_INACTIVE)?>><?=lang('inactive')?></option>
        </select>
    </div>
	  
    <div class="col-xs-2">
		<label for="status"><?=lang('news_field_category')?></label>
	    <br>
	    <select class="form-control input-sm" id="category" name="category">
          <option value=""><?=lang('all')?></option>
          <?php foreach ($categories as $key => $cat):?>
          <option value="<?=$key?>" <?=set_select('category', $key, isset($search_criteria['category']) && $search_criteria['category'] == $key)?>><?=lang($cat)?></option>
          <?php endforeach;?>
        </select>
    </div>
	
	<div class="col-xs-1">
		<label>&nbsp;</label>
	   	<br>
		<button type="submit" class="btn btn-primary btn-sm" name="submit_action" value="<?=ACTION_SEARCH?>">
			<span class="fa fa-search"></span>
			<?=lang('btn_search')?>
		</button>
	</div>
	
	<div class="col-xs-1">
	   	<label>&nbsp;</label>
	   	<br>
	  	<button type="submit" class="btn btn-default btn-sm" name="submit_action" value="<?=ACTION_RESET?>">
	  		<span class="fa fa-refresh"></span>
	  		<?=lang('btn_reset')?>
	  	</button>	  	
	 </div>
</div>
</form>
</div>