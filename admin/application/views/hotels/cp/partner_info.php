<div id="hotel_title_<?=$hotel['id']?>" class="hide">
	<?=$hotel['partner_name']?>
</div>

<div id="hotel_content_<?=$hotel['id']?>" class="hide">
	<h5><b><?=lang('partner_mnu_edit')?></b></h5>
	<ul>
		<li><?=lang('partners_field_phone')?>: <?=$hotel['phone']?></li>
		<li><?=lang('partners_field_fax')?>: <?=$hotel['fax']?></li>
		<li><?=lang('partners_field_email')?>: <?=$hotel['email']?></li>
	</ul>
	
	<h5><b><?=lang('partner_sale_contact')?></b></h5>
	<ul>
		<li><?=lang('partner_contact_name')?>: <?=$hotel['sale_contact_name']?></li>
		<li><?=lang('partner_contact_phone')?>: <?=$hotel['sale_contact_phone']?></li>
		<li><?=lang('partner_contact_email')?>: <?=$hotel['sale_contact_email']?></li>
	</ul>
	
	<h5><b><?=lang('partner_reservation_contact')?></b></h5>
	<ul>
		<li><?=lang('partner_contact_name')?>: <?=$hotel['reservation_contact_name']?></li>
		<li><?=lang('partner_contact_phone')?>: <?=$hotel['reservation_contact_phone']?></li>
		<li><?=lang('partner_contact_email')?>: <?=$hotel['reservation_contact_email']?></li>
	</ul>
</div>

<script type="text/javascript">
	$('#hotel_detail_<?=$hotel['id']?>').popover(
		{
			html: true,
			trigger: 'hover',
			title: $('#hotel_title_<?=$hotel['id']?>').html(), 
			content: $('#hotel_content_<?=$hotel['id']?>').html(),
		}
	);

</script>