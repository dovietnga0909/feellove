<ul class="nav nav-tabs mg-bottom-20">
	<?php if( isset($hotel)):?>
	<li><a href="<?=site_url('/hotels/contracts/'.$hotel['id'])?>"><?=lang('contract_mnu_contract_list')?></a></li>
	<li class="active"><a href="<?=site_url('/hotels/contract_upload/'.$hotel['id'])?>"><?=lang('contract_mnu_contract_upload')?></a></li>
	<?php elseif( isset($cruise)):?>
	<li><a href="<?=site_url('/cruises/contracts/'.$cruise['id'])?>"><?=lang('contract_mnu_contract_list')?></a></li>
	<li class="active"><a href="<?=site_url('/cruises/contract_upload/'.$cruise['id'])?>"><?=lang('contract_mnu_contract_upload')?></a></li>
	<?php elseif( isset($tour)):?>
	<li><a href="<?=site_url('/tours/contracts/'.$tour['id'])?>"><?=lang('contract_mnu_contract_list')?></a></li>
	<li class="active"><a href="<?=site_url('/tours/contract_upload/'.$tour['id'])?>"><?=lang('contract_mnu_contract_upload')?></a></li>
	<?php endif;?>
	
</ul>

<?php if(isset($error) & !empty($error)):?>
<div class="row" style="margin: 0 0 20px">
	<div class="col-xs-8 bp-error">
	<?php if (is_array($error)):?>
		<?php foreach ($error as $er):?>
			<?=$er?>
		<?php endforeach;?>
	<?php else:?>
		<?=$error?>
	<?php endif;?>
	</div>
</div>
<?php endif;?>

<form role="form" name="frm" method="post" enctype="multipart/form-data">
<div class="row">
	<div class="col-xs-8">
		<ul style="padding-left: 20px;" class="note">
			<li>Maximum file upload is <?=UPLOAD_FILE_LIMIT?></li>
		</ul>

		<div class="form-group">
			<input type="file" name="contracts[]" multiple="multiple" id="contract"/>
		</div>
		<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_UPLOAD?>">
			<span class="fa fa-upload"></span>
			<?=lang('btn_upload')?>
		</button>
	</div>
</div>
</form>