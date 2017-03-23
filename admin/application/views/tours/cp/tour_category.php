<form class="form-horizontal" role="form" method="post">
<?php if(validation_errors() != ''):?>
<div class="mg-bottom-20 bp-error">
	<?php echo validation_errors(); ?>
</div>
<?php endif;?>

<?php if(!empty($categories)):?>

<div class="form-group">
<?php foreach ($categories as $cat):?>
    <div class="col-xs-4">
        <div class="checkbox">
        <label>
            <input type="checkbox" name="tour_category[]" value="<?=$cat['id']?>" <?=set_checkbox('tour_category', $cat['id'], in_array($cat['id'], $tour_categories))?>>
            <?=$cat['name']?>
        </label>
        </div>
    </div>
<?php endforeach;?>
</div>

<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
	<span class="fa fa-download"></span>
	<?=lang('btn_save')?>
</button>
<a class="btn btn-default mg-left-10" href="<?=site_url('tours/profiles/'.$tour['id'])?>" role="button"><?=lang('btn_cancel')?></a>

<?php else:?>
    <?=lang('empty_tour_category')?>
<?php endif;?>
</form>