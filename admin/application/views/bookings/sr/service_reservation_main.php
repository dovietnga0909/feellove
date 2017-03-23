
<?php 
	$customer_booking_id = "";
	
	if (isset($search_criteria['customer_booking_id'])){
		
		$customer_booking_id = $search_criteria['customer_booking_id'];
		
	}
?>

<form method="POST" name="frm" enctype="multipart/form-data">
	<input type="hidden" name="action_type" value=''>
	<input type="hidden" name="service_reservation_id" value=''>
	<input type="hidden" name="customer_booking_id" value=''>
	
	<?php if($action == 'edit' || $action == 'save_edit') : ?>
	
		<?=$edit_view?>
		
	<?php elseif($action == 'create' || $action == 'save_create'): ?>
	
		<?=$create_view?>
		
	<?php else : ?>		
		<?=$list_view?>	
	<?php endif;?>
	
</form>

<script>
	var cruises = <?=$cruises?>;
	var hotels = <?=$hotels?>;
	var tours = <?=$tours?>;
	var cars = <?=$cars?>
</script>