<div class="well">
	<form class="form-inline" role="form" method="post">
	  
	  <div class="form-group">
	  	<label for="search_name"><?=lang('can_field_name')?></label>
	  	<br>
	    <input type="text" class="form-control input-sm" placeholder="<?=lang('can_field_name')?>..." id="search_name" name="name" 
	    	value="<?=set_value('name', !empty($search_criteria['name'])?$search_criteria['name']:'')?>">   
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="search_fit"><?=lang('can_field_fit')?></label>
	    <br>
		<select class="form-control input-sm" id="search_fit" style="width:120px" name="fit">
			<option value=""><?=lang('all')?></option>
			<?php foreach ($fits as $value):?>
				<option value="<?=$value['fit']?>" <?=set_select('fit', $value['fit'], !empty($search_criteria['fit']) && $search_criteria['fit'] == $value['fit'])?>><?=$value['fit']?></option>
			<?php endforeach;?>
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="search_fit_cutoff"><?=lang('can_field_fit_cutoff')?></label>
	    <br>
		<select class="form-control input-sm" id="search_fit_cutoff" style="width:120px" name="fit_cutoff">
		  <option value=""><?=lang('all')?></option>
		  
		  <?php foreach ($fit_cutoffs as $value):?>
				<option value="<?=$value['fit_cutoff']?>" <?=set_select('fit_cutoff', $value['fit_cutoff'], !empty($search_criteria['fit_cutoff']) && $search_criteria['fit_cutoff'] == $value['fit_cutoff'])?>>
					<?=$value['fit_cutoff']?>
				</option>
			<?php endforeach;?>
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="search_git_cutoff"><?=lang('can_field_git_cutoff')?></label>
	    <br>
		<select class="form-control input-sm" id="search_git_cutoff" style="width:120px" name="git_cutoff">
		  <option value=""><?=lang('all')?></option>
		   <?php foreach ($git_cutoffs as $value):?>
				<option value="<?=$value['git_cutoff']?>" <?=set_select('git_cutoff', $value['git_cutoff'], !empty($search_criteria['git_cutoff']) && $search_criteria['git_cutoff'] == $value['git_cutoff'])?>>
					<?=$value['git_cutoff']?>
				</option>
			<?php endforeach;?>
		</select>
	  </div>
	  
	  <div class="form-group mg-left-10">
	    <label for="service_type"><?=lang('can_service_type')?></label>
	    <br>
		<select class="form-control input-sm" id="service_type" style="width:120px" name="service_type">
		  	<option value=""><?=lang('all')?></option>
			<?php foreach ($service_type as $key=>$value):?>
				<option value="<?=$key?>" <?=set_select('service_type', $key, !empty($search_criteria['service_type']) && $search_criteria['service_type'] == $key)?>> <?=$value?></option>
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