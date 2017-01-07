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
		<ul class="list-unstyled">
        	<?php foreach ($uploaded_contracts as $key => $contract):?>
        	<li>
        		<div class="form-group thumb" id="thumb_<?=$key?>">
        			<div class="form-group">
        				<?=$contract['name']?>
        			</div>
        			<div class="form-group">
        			     <textarea rows="3" class="form-control input-sm" name="description_<?=$key?>" 
        					placeholder="<?=lang('contract_field_description')?>">"<?=set_value('description_'.$key)?></textarea>
        				 <?=form_error('description_'.$key)?>
        			</div>
        		</div>
        	</li>
        	<?php endforeach;?>
        </ul>
	</div>
</div>
<div class="row">
	<div class="col-xs-8">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-save-photo">
				<span class="fa fa-download"></span>
				<?=lang('btn_save')?>
			</button>
			<a class="btn btn-default mg-left-10" href="<?=site_url($redirect_url)?>" role="button"><?=lang('btn_cancel')?></a>
		</div>
	</div>
</div>
</form>