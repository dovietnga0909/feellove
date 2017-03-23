<?php if(empty($partner)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('partners')?>" role="button">
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
		<div class="form-group">
			<label for="name" class="col-xs-3 control-label"><?=lang('partners_field_name')?>:</label>
			<div class="col-xs-6"><label class="control-label"><?=$partner['name']?></label></div>
		</div>
		<div class="form-group">
			<label for="bank_account_name" class="col-xs-3 control-label"><?=lang('partners_field_bank_acc_name')?>: <?=mark_required()?></label>
			<div class="col-xs-6">
				<input type="text" class="form-control" id="bank_account_name" name="bank_account_name" value="<?=set_value('bank_account_name', $partner['bank_account_name'])?>">
				<?=form_error('bank_account_name')?>
			</div>
		</div>
		<div class="form-group">
			<label for="bank_account_number" class="col-xs-3 control-label"><?=lang('partners_field_bank_acc_number')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="<?=set_value('bank_account_number', $partner['bank_account_number'])?>">
				<?=form_error('bank_account_number')?>
			</div>
		</div>
		<div class="form-group">
			<label for="bank_branch_name" class="col-xs-3 control-label"><?=lang('partners_field_bank_branch_name')?>: <?=mark_required()?></label>
			<div class="col-xs-6">
				<input type="text" class="form-control" id="bank_branch_name" name="bank_branch_name" value="<?=set_value('bank_branch_name', $partner['bank_branch_name'])?>">
				<?=form_error('bank_branch_name')?>
			</div>
		</div>
		<div class="form-group">
			<label for="payment_type" class="col-xs-3 control-label"><?=lang('partners_field_payment_type')?>: <?=mark_required()?></label>
			<div class="col-xs-6">
				<?php foreach ($payment_types as $k => $value):?>
				<div class="radio">
				  <label>
				    <input type="radio" name="payment_type" id="payment_type" value="<?=$k?>" <?=set_radio('payment_type', $k, $partner['payment_type'] == $k ? TRUE : FALSE)?>>
				    <?=lang($value)?>
				  </label>
				</div>
				<?php endforeach;?>
				<?=form_error('payment_type')?>
			</div>
		</div>
		<div class="form-group">
		    <div class="col-xs-offset-3 col-xs-6">
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
<?php endif;?>