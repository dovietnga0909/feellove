<div class="well">
	<form class="form-inline" role="form" name="sfrm" method="post">
		<div class="form-group">
			<label for="search_name"><?=lang('hotels_field_name')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_hotel_search_holder')?>" class="form-control" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="star"><?=lang('hotels_field_star')?>:</label>
			<br>
			<select class="form-control" name="star" id="star">
				<option value=""><?=lang('hotels_select_star')?></option>
				<?php foreach ($hotel_star as $val):?>
				<option value="<?=$val?>" <?=set_select('star', $val, isset($search_criteria['star']) && $search_criteria['star'] == $val)?>><?=$val?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label for="partner_id"><?=lang('hotels_field_partner')?>:</label>
			<br>
			<select class="form-control" name="partner_id" id="partner_id">
				<option value=""><?=lang('hotels_select_partner')?></option>
				<?php foreach ($partners as $partner):?>
				<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], isset($search_criteria['partner_id']) && $search_criteria['partner_id'] == $partner['id'])?>><?=$partner['name']?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div class="form-group mg-left-10">
			<label>&nbsp;</label>
		   	<br>
			<button type="submit" class="btn btn-primary" name="submit_action" value="<?=ACTION_SEARCH?>">
				<span class="fa fa-search"></span>
				<?=lang('btn_search')?>
			</button>
		</div>
		
		<div class="form-group mg-left-10">
		   	<label>&nbsp;</label>
		   	<br>
		  	<button type="submit" class="btn btn-default" name="submit_action" value="<?=ACTION_RESET?>">
		  		<span class="fa fa-refresh"></span>
		  		<?=lang('btn_reset')?>
		  	</button>	  	
		 </div>
	</form>
</div>