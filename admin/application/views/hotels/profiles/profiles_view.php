<?php if(empty($hotel)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('hotels')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

	<?php if(isset($save_status) && $save_status === FALSE):?>
		<div class="alert alert-danger">
			<?=lang('fail_to_save')?>
		</div>
	<?php endif;?>
	<form class="form-horizontal" role="form" method="post">
		<div class="row">
			<div class="col-xs-8">
				<div class="form-group">
					<label for="name" class="col-xs-3 control-label"><?=lang('hotels_field_name')?>: <?=mark_required()?></label>
					<div class="col-xs-8">
						<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $hotel['name'])?>">
						<?=form_error('name')?>
					</div>
				</div>
				<div class="form-group">
					<label for="address" class="col-xs-3 control-label"><?=lang('hotels_field_address')?>: <?=mark_required()?></label>
					<div class="col-xs-9">
						<textarea class="form-control" rows="2" name="address"><?=set_value('address', $hotel['address'])?></textarea>
						<?=form_error('address')?>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="pull-right">
					<?php if(!empty($hotel['picture'])):?>
					<img width="175" src="<?=get_static_resources('/images/hotels/uploads/'.$hotel['picture'])?>">
					<?php endif;?>
					<div class="text-center">
						<a href="<?=site_url('hotels/photos/'.$hotel['id'])?>"><?=lang('update_hotel_photo')?></a>
					</div>
				</div>
			</div>
		</div>
		
		<?php if(is_admin()):?>
		<div class="form-group">
			<label for="partner_id" class="col-xs-2 control-label"><?=lang('hotels_field_partner')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<select class="form-control" name="partner_id">
					<option value=""><?=lang('hotels_select_partner')?></option>
					<?php foreach ($partners as $partner):?>
					<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'], $partner['id']==$hotel['partner_id'] ? TRUE : FALSE)?>><?=$partner['name']?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('partner_id')?>
			</div>
		</div>
		<div class="form-group">
			<label for="keywords" class="col-xs-2 control-label"><?=lang('hotels_field_keywords')?>: <?=mark_required()?></label>
			<div class="col-xs-8">
			    <textarea class="form-control" rows="2" name="keywords"><?=set_value('keywords', $hotel['keywords'])?></textarea>
				<?=form_error('keywords')?>
			</div>
		</div>
		<?php endif;?>
		
		<div class="form-group">
			<label for="destination_id" class="col-xs-2 control-label"><?=lang('hotels_field_hotel_area')?>: <?=mark_required()?></label>
			<div class="col-xs-3">
			<select class="form-control" name="destination_id">
				<option value=""><?=lang('hotels_select_hotel_area')?></option>
				<?php foreach ($destinations as $des):?>
					<optgroup label="<?=$des['name']?>">
					<option value="<?=$des['id']?>" <?=set_select('destination_id', $des['id'], $des['id']==$hotel['destination_id'] ? TRUE : FALSE)?>><?=$des['name']?></option>
					<?php foreach ($des['children'] as $sub_des):?>
						<option value="<?=$sub_des['id']?>" <?=set_select('destination_id', $sub_des['id'], $sub_des['id']==$hotel['destination_id'] ? TRUE : FALSE)?>><?=$sub_des['name']?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach;?>
			</select>
			<?=form_error('destination_id')?>
			</div>
		</div>
		<div class="form-group">
			<label for="star" class="col-xs-2 control-label"><?=lang('field_status')?>:</label>
			<div class="col-xs-2">
				<select class="form-control" name="status">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($status_config as $key => $value):?>
					<option value="<?=$key?>" <?=set_select('status', $key, $key==$hotel['status'] ? TRUE : FALSE)?>><?=lang($value)?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('status')?>
			</div>
		</div>
		<div class="form-group">
			<label for="star" class="col-xs-2 control-label"><?=lang('hotels_field_star')?>: <?=mark_required()?></label>
			<div class="col-xs-2">
				<select class="form-control" name="star">
					<option value=""><?=lang('hotels_select_star')?></option>
					<?php foreach ($hotel_star as $star):?>
					<option value="<?=$star?>" <?=set_select('star', $star, $star==$hotel['star'] ? TRUE : FALSE)?>><?=$star?></option>
					<?php endforeach;?>
				</select>
				<?=form_error('star')?>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-xs-2 control-label"><?=lang('hotels_field_description')?>: <?=mark_required()?></label>
			<div class="col-xs-10">
				<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description', $hotel['description'])?></textarea>
				<?=form_error('description')?>
			</div>
		</div>
		
		<?php if(!empty($hotel['partner'])):?>
		<div class="form-group hr">
			<div class="col-xs-offset-2 col-xs-10">
				<h5 style="margin-top: 0"><b><?=lang('partner_contact')?></b></h5>
				<ul>
					<li>
						<span class="col-xs-2"><?=lang('partners_field_phone')?>:</span>
						<?=$hotel['partner']['phone']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partners_field_fax')?>:</span>
						<?=$hotel['partner']['fax']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partners_field_email')?>:</span>
						<?=$hotel['partner']['email']?>
					</li>
				</ul>
				
				<h5><b><?=lang('partner_reservation_contact')?></b></h5>
				<ul>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_name')?>:</span>
						<?=$hotel['partner']['reservation_contact_name']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_phone')?>:</span>
						<?=$hotel['partner']['reservation_contact_phone']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_email')?>:</span>
						<?=$hotel['partner']['reservation_contact_email']?>
					</li>
				</ul>
				
				<h5 style="clear: both; width: 100%"><b><?=lang('partner_sale_contact')?></b></h5>
				<ul>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_name')?>:</span>
						<?=$hotel['partner']['sale_contact_name']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_phone')?>:</span>
						<?=$hotel['partner']['sale_contact_phone']?>
					</li>
					<li>
						<span class="col-xs-2"><?=lang('partner_contact_email')?>:</span>
						<?=$hotel['partner']['sale_contact_email']?>
					</li>
				</ul>
			</div>
		</div>
		<?php endif;?>
		
		<div class="form-group hr">
		    <div class="col-xs-offset-2 col-xs-8">
		    	<button type="submit" class="btn btn-primary">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('hotels')?>" role="button"><?=lang('btn_cancel')?></a>
		    </div>
		</div>
	</form>
	
	<script type="text/javascript">
		init_text_editor();
	</script>
<?php endif;?>