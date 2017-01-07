<table class="no_border" cellpadding="0" cellspacing="0" width="100%">
	<tr valign="middle">
		<td>
			<input type="text" size="30" name="name" value="<?=set_value('name', isset($search_criteria['name'])? $search_criteria['name']: '')?>">
		</td>
		
		<td align="right">
			
		</td>
		
		<td align="right">
			<input type="submit" value="<?=lang('common_button_search')?>" name="btnSearch" class="button" onclick="search();">
			<input type="button" onclick="resetForm();" value="<?=lang('common_button_reset')?>" name="btnReset" class="button">
			<i><a href="javascript: void(0)" onclick="view_advanced_search()"><?=lang('advanced_search')?></a></i>
			&nbsp;
			
			<?php if(isset($customer_booking)):?>
			
			<i><a href="javascript: void(0)" onclick="create(<?=$customer_booking['id']?>)"><?=lang('create_service_reservation')?></a></i>
			
			<?php endif;?>
		</td>	
	</tr>
</table>
<script language="javascript">
	function view_advanced_search(){
	}
	function create(customer_booking_id) {
		document.frm.customer_booking_id.value = customer_booking_id;
		document.frm.action_type.value = 'create';
		document.frm.submit();
	}
	function search() {
		document.frm.action_type.value = 'search';
		document.frm.submit();
	}
	function resetForm() {
		document.frm.name.value = "";
		document.frm.action_type.value = 'reset';
		document.frm.submit();
	}	
</script>