<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
<div class="form-group">
	<label for="name" class="col-xs-2 control-label"><?=lang('hotels_field_name')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
		<?=form_error('name')?>
	</div>
</div>
<div class="form-group">
	<label for="address" class="col-xs-2 control-label"><?=lang('hotels_field_address')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control" rows="2" name="address"><?=set_value('address')?></textarea>
		<?=form_error('address')?>
	</div>
</div>
<div class="form-group">
	<label for="destination_id" class="col-xs-2 control-label"><?=lang('hotels_field_hotel_area')?>: <?=mark_required()?></label>
	<div class="col-xs-3">
	<select class="form-control" name="destination_id">
		<option value=""><?=lang('hotels_select_hotel_area')?></option>
		<?php foreach ($destinations as $des):?>
			 <optgroup label="<?=$des['name']?>">
				<option value="<?=$des['id']?>" <?=set_select('destination_id', $des['id'])?>><?=$des['name']?></option>
				<?php foreach ($des['children'] as $sub_des):?>
					<option value="<?=$sub_des['id']?>" <?=set_select('destination_id', $sub_des['id'])?>><?=$sub_des['name']?></option>
				<?php endforeach;?>
			</optgroup>
		<?php endforeach;?>
	</select>
	<?=form_error('destination_id')?>
	</div>
</div>
<div class="form-group">
	<label for="star" class="col-xs-2 control-label"><?=lang('hotels_field_star')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<select class="form-control" name="star">
			<option value=""><?=lang('hotels_select_star')?></option>
			<?php foreach ($hotel_star as $star):?>
			<option value="<?=$star?>" <?=set_select('star', $star)?>><?=$star?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('star')?>
	</div>
</div>
<div class="form-group">
	<label for="partner_id" class="col-xs-2 control-label"><?=lang('hotels_field_partner')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<select class="form-control" name="partner_id">
			<option value=""><?=lang('hotels_select_partner')?></option>
			<?php foreach ($partners as $partner):?>
			<option value="<?=$partner['id']?>" <?=set_select('partner_id', $partner['id'])?>><?=$partner['name']?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('partner_id')?>
	</div>
</div>
<div class="form-group">
	<label for="description" class="col-xs-2 control-label"><?=lang('hotels_field_description')?>: <?=mark_required()?></label>
	<div class="col-xs-10">
		<textarea class="form-control rich-text" rows="10" name="description"><?=set_value('description')?></textarea>
		<?=form_error('description')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-6">
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