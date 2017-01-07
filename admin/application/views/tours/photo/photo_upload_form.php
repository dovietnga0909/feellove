<ul class="nav nav-tabs mg-bottom-20">
	<li><a href="<?=site_url('/tours/photos/'.$tour['id'])?>"><?=lang('tour_mnu_photo_editing')?></a></li>
	<li class="active"><a href="<?=site_url('/tours/photo_upload/'.$tour['id'])?>"><?=lang('tour_mnu_photo_upload')?></a></li>
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
			<input type="file" name="photos[]" multiple="multiple" id="photo"/>
		</div>
		<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_UPLOAD?>">
			<span class="fa fa-upload"></span>
			<?=lang('btn_upload')?>
		</button>
	</div>
</div>
</form>