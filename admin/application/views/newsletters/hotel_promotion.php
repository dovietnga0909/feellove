<?php if(count($hotels) >0):?>

<div class="hotels_promotion">
	<div class="promotion_list" id="checkboxes">
		<?php foreach ($hotels as $key => $value):?>
			<div class="col-xs-8 col-xs-offset-3">
				<span class="hotel_<?=$key?>"><b><?=$value['name']?></b></span></br>
				<div class="col-xs-10 col-xs-offset-1" id="hotel_<?=$key?>">
					<?php foreach($value['promotions'] as $k => $promotion):?>
					    <div class="checkbox">
					        <label>
						        <input type="checkbox" name="promotions[]" value="<?=$promotion['id']?>">
						        <?=$promotion['name']?>
					        </label>
					    </div>
					 <?php endforeach;?>
				</div>
			</div>
		<?php endforeach;?>
		<?=form_error('promotions')?>
	</div>
</div>
<?php else:?>
<div class="col-xs-4 col-xs-offset-3">
	<p><?=lang('no_promotion')?></p>
</div>
<?php endif;?>