<div id="cruise_title_<?=$cruise['id']?>" class="hide">
	<?=$cruise['partner_name']?>
</div>

<div id="cruise_content_<?=$cruise['id']?>" class="hide">
	<h5><b><?=lang('partner_mnu_edit')?></b></h5>
	<ul>
		<li><?=lang('partners_field_phone')?>: <?=$cruise['phone']?></li>
		<li><?=lang('partners_field_fax')?>: <?=$cruise['fax']?></li>
		<li><?=lang('partners_field_email')?>: <?=$cruise['email']?></li>
	</ul>
	
	<h5><b><?=lang('partner_sale_contact')?></b></h5>
	<ul>
		<li><?=lang('partner_contact_name')?>: <?=$cruise['sale_contact_name']?></li>
		<li><?=lang('partner_contact_phone')?>: <?=$cruise['sale_contact_phone']?></li>
		<li><?=lang('partner_contact_email')?>: <?=$cruise['sale_contact_email']?></li>
	</ul>
	
	<h5><b><?=lang('partner_reservation_contact')?></b></h5>
	<ul>
		<li><?=lang('partner_contact_name')?>: <?=$cruise['reservation_contact_name']?></li>
		<li><?=lang('partner_contact_phone')?>: <?=$cruise['reservation_contact_phone']?></li>
		<li><?=lang('partner_contact_email')?>: <?=$cruise['reservation_contact_email']?></li>
	</ul>
</div>

<script type="text/javascript">
	$('#cruise_detail_<?=$cruise['id']?>').popover(
		{
			html: true,
			trigger: 'hover',
			title: $('#cruise_title_<?=$cruise['id']?>').html(), 
			content: $('#cruise_content_<?=$cruise['id']?>').html(),
		}
	);

</script>