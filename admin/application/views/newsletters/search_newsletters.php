<div class="well well-sm">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'newsletters/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('newsletters')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('newsletters')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
	    <div class="form-group mg-left-10">
			<label for="type"><?=lang('templates_type')?>:</label>
			<br>
			<select class="form-control input-sm" name="template_type" id="template_type">
				<option value=""><?=lang('select_templates_type')?></option>
				<?php foreach ($templates_type as $k => $type):?>
				<option value="<?=$k?>" <?=set_select('template_type', $k, isset($search_criteria['template_type']) && $search_criteria['template_type'] == $k)?>><?=lang($type)?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
	        <label for="status"><?=lang('field_status')?></label>
	        <br>
	        <select class="form-control input-sm" id="status" name="status">
	        	<option value=""><?=lang('all')?></option>
	        	<?php foreach($status as $s=> $value):?>
	        	<option value="<?=$s?>" <?=set_select('status', $s, isset($search_criteria['status']) && $search_criteria['status'] == $s)?>><?=lang($value)?></option>
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