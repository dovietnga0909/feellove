<div class="well well-sm">
	<form class="form-inline" role="form" name="sfrm" method="post" action="<?=site_url().'accounts/'?>">
		<div class="form-group">
			<label for="search_name"><?=lang('users_field_email')?></label>
	  		<br>
			<input type="text" placeholder="<?=lang('list_user_search_holder')?>" class="form-control input-sm" name="search_text"
				value="<?=set_value('search_text', isset($search_criteria['search_text'])?$search_criteria['search_text']:'')?>">
		</div>
		
		<div class="form-group mg-left-10">
			<label for="display_on"><?=lang('register_account')?></label>
	  		<br>
			<select name="display_on" class="form-control input-sm">
				
				<option value=""><?=lang('all')?></option>
				<option value="<?=SIGN_IN?>" <?=set_select('display_on', SIGN_IN, isset($search_criteria['display_on']) && $search_criteria['display_on'] == SIGN_IN)?>><?=lang('sign-in')?></option>
				<option value="<?=NEWS_LETTER?>" <?=set_select('display_on', NEWS_LETTER, isset($search_criteria['display_on']) && $search_criteria['display_on'] == NEWS_LETTER)?>><?=lang('news-letter')?></option>
				<option value="<?=LETTER_TO_SIGN?>" <?=set_select('display_on', LETTER_TO_SIGN, isset($search_criteria['display_on']) && $search_criteria['display_on'] == LETTER_TO_SIGN)?>><?=lang('letter-sign')?></option>
				<option value="<?=SYSTEM?>" <?=set_select('display_on', SYSTEM, isset($search_criteria['display_on']) && $search_criteria['display_on'] == SYSTEM)?>><?=lang('system')?></option>
				
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