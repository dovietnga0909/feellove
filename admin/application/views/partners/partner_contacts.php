<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label for="name" class="col-xs-2 control-label"><?=lang('partners_field_name')?>:</label>
		<div class="col-xs-6">
			<label class="control-label"><?=$partner['name']?></label>
		</div>
	</div>
	<h4><?=lang('partner_reservation_contact')?></h4>
	<div class="form-group">
		<label for="reservation_name" class="col-xs-2 control-label"><?=lang('partner_contact_name')?>
		</label>
		<div class="col-xs-6">
			<input type="text" class="form-control" id="reservation_name" name="reservation_name" value="<?=set_value('reservation_name', $partner['reservation_contact_name'])?>">
		</div>
	</div>
	<div class="form-group">
		<label for="reservation_phone" class="col-xs-2 control-label"><?=lang('partner_contact_phone')?>
		</label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="reservation_phone" name="reservation_phone" value="<?=set_value('reservation_phone', $partner['reservation_contact_phone'])?>">
		</div>
	</div>
	<div class="form-group">
		<label for="reservation_email" class="col-xs-2 control-label"><?=lang('partner_contact_email')?>
		</label>
		<div class="col-xs-6">
			<input type="text" class="form-control" id="reservation_email" name="reservation_email" value="<?=set_value('reservation_email', $partner['reservation_contact_email'])?>">
		</div>
	</div>
	
	<h4><?=lang('partner_sale_contact')?></h4>
	<div class="form-group">
		<label for="sale_name" class="col-xs-2 control-label"><?=lang('partner_contact_name')?>
		</label>
		<div class="col-xs-6">
			<input type="text" class="form-control" id="sale_name" name="sale_name" value="<?=set_value('sale_name', $partner['sale_contact_name'])?>">
		</div>
	</div>
	<div class="form-group">
		<label for="sale_phone" class="col-xs-2 control-label"><?=lang('partner_contact_phone')?>
		</label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="sale_phone" name="sale_phone" value="<?=set_value('sale_phone', $partner['sale_contact_phone'])?>">
		</div>
	</div>
	<div class="form-group">
		<label for="sale_email" class="col-xs-2 control-label"><?=lang('partner_contact_email')?>
		</label>
		<div class="col-xs-6">
			<input type="text" class="form-control" id="sale_email" name="sale_email" value="<?=set_value('sale_email', $partner['sale_contact_email'])?>">
		</div>
	</div>
	
	<h4><?=lang('partner_chat_contact')?></h4>
	<div class="form-group">
		<label for="skype_contact" class="col-xs-2 control-label"><?=lang('partner_skype_contact')?>
		</label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="skype_contact" name="skype_contact" value="<?=set_value('skype_contact', $partner['skype_contact'])?>">
		</div>
	</div>
	<div class="form-group">
		<label for="yahoo_contact" class="col-xs-2 control-label"><?=lang('partner_yahoo_contact')?>
		</label>
		<div class="col-xs-4">
			<input type="text" class="form-control" id="yahoo_contact" name="yahoo_contact" value="<?=set_value('yahoo_contact', $partner['yahoo_contact'])?>">
		</div>
	</div>

	<div class="form-group">
		<div class="col-xs-offset-2 col-xs-6">
			<button type="submit" class="btn btn-primary">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<?php if(!empty($hotel)):?>
				<a class="btn btn-default mg-left-10" href="<?=site_url('hotels/profiles/'.$hotel['id'])?>" role="button"><?=lang('btn_cancel')?></a>
			<?php else:?>
				<a class="btn btn-default mg-left-10" href="<?=site_url('partners')?>" role="button"><?=lang('btn_cancel')?></a>
			<?php endif;?>
		</div>
	</div>
</form>
