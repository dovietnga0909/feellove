<html>
<head>
<style type="text/css">

	body,table{font-family:Arial,Verdana;font-size:12px;}

</style>
</head>
<body style="font-family: Arial;font-size:12px;">

<p>Dear <?=$user['user_name']?>,</p>

<p>The user <b><?=$login_user_name?></b> has assigned (or modified) a customer booking to you</p>


<h2>Customer Booking Information</h2>
<div style="width: 600px;">
	<table width="100%">
		<tr>
			<td width="250">
				<b><?=lang('full_name')?>:</b>
			</td>
			<td>
				<?=$customer_booking['customer_name']?>
			</td>
		</tr>
		
		<tr>
			<td ><b><?=lang('email_address')?>:</b></td>
			<td ><?=$customer_booking['email']?></td>
		</tr>	
		
		<tr>
			<td ><b><?=lang('phone_number')?>:</b></td>
			<td >
				<?=$customer_booking['phone']?>
			</td>
		</tr>
		
		<tr>
			<td ><b><?=lang('start_date')?>:</b></td>
			<td >
				<?=$this->timedate->format($customer_booking['start_date'], DATE_FORMAT)?>
			</td>
		</tr>
		
		<tr>
			<td ><b><?=lang('end_date')?>:</b></td>
			<td >
				<?=$this->timedate->format($customer_booking['end_date'], DATE_FORMAT)?>
			</td>
		</tr>			
	
	</table>	
</div>

<p>Please login the system to check the detail modification.</p>

<p>&nbsp;</p>
<p>
<b>BestPrice Vietnam., JSC</b><br>
Head Office: 29/131, Trai Ca Alley, Truong Dinh Street,<br>
HBT Dist, Hanoi, Vietnam<br>
Tel: (+84) 4 3576-5748<br>
Website: <a href="<?=site_url()?>">http://www.bestpricevn.com</a><br>	
</p>
</body>
</html>