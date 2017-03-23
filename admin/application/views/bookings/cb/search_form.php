<div class="cb-search">
	<div class="form-group">
		<input type="text" class="form-control input-sm" size="25" name="name" value="<?=set_value('name', isset($search_criteria['name'])? $search_criteria['name']: '')?>">
	</div>
	&nbsp;
	<?php foreach ($booking_filter as $key=>$value):?>
		<div class="checkbox">
		    <label>
		      <input type="checkbox" value="<?=$key?>" name="booking_filter[]" onclick="search()" <?=set_checkbox('booking_filter', $key, isset($search_criteria['booking_filter']) && in_array($key, $search_criteria['booking_filter'])?TRUE:FALSE)?>>
		      	<?php 
					$color_class = "";
					if ($key == 1){
						$color_class = "current";
					} else if ($key == 2){
						$color_class = "near";
					} else if ($key == 4){
						$color_class = "past";
					}
				?>
				<span class="<?=$color_class?>"><b><?=lang($value)?></b></span>
		    </label>
		    &nbsp;
		  </div>	
	<?php endforeach;?>
	
	&nbsp;
	<select name="approve_status" onchange="search();" class="form-control input-sm" style="width:60px">	
		<option value=""><?=lang('all')?></option>			
		<?php foreach ($approve_status as $key=>$value) :?>
																		
			<option value="<?=$key?>" <?=set_select('approve_status', $key,isset($search_criteria['approve_status']) &&  strval($search_criteria['approve_status']) == strval($key)? true: false)?>><?=lang($value)?></option>
			
		<?php endforeach ;?>				
	</select>
	
	&nbsp;
	<button type="button" class="btn btn-primary" onclick="search('<?=ACTION_SEARCH?>')"><?=lang('btn_search')?></button>
	
	&nbsp;
	<button type="button" class="btn btn-default" onclick="search('<?=ACTION_RESET?>')"><?=lang('btn_reset')?></button>
	
	<a href="javascript: void(0)" style="text-decoration:underline" onclick="show_advanced_search()"><?=lang('advanced_search')?></a>
	&nbsp;
	<a href="<?=site_url('bookings/create')?>" style="text-decoration:underline"><?=lang('create_customer_booking')?></a>
</div>

<script language="javascript">
	function goSRs(){
		window.location = "<?=site_url('bookings/list_rs')?>";
	}	
</script>