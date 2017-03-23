<?php if($voucher === FALSE):?>
	
	<center>The vouche was deleted!</center>
	
<?php else:?>
	<center><b>Code: <?=$voucher['code'] ?> (Id: <?=$id ?>)</b></center>
	<?=$voucher['log']?>
	
<?php endif;?>