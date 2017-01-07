<?php if(empty($review)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('hotels/')?>" role="button">
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

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="review_id" value="<?=$review['id']?>">

<div class="form-group">
	<label for="customer_name" class="col-xs-3 control-label"><?=lang('reviews_field_cus_name')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<input type="text" class="form-control" name="customer_name" id="customer_name" value="<?=set_value('customer_name', $review['customer_name'])?>">
		<?=form_error('customer_name')?>
		<input type="hidden" id="customer_id" name="customer_id" value="<?=set_value('customer_id', $review['customer_id'])?>">
	</div>
</div>
<div class="form-group">
	<label for="customer_type" class="col-xs-3 control-label"><?=lang('reviews_field_cus_type')?>: <?=mark_required()?></label>
	<div class="col-xs-2">
		<select class="form-control" name="customer_type">
			<option value=""><?=lang('please_select')?></option>
			<?php foreach ($customer_types as $k => $type):?>
			<option value="<?=$k?>" <?=set_select('customer_type', $k, $k==$review['customer_type'] ? true:false)?>><?=lang($type)?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('customer_type')?>
	</div>
</div>
<div class="form-group">
	<label for="customer_city" class="col-xs-3 control-label"><?=lang('reviews_field_cus_city')?>: <?=mark_required()?></label>
	<div class="col-xs-2">
		<select class="form-control" name="customer_city">
			<option value=""><?=lang('please_select')?></option>
			<?php foreach ($customer_cities as $city):?>
			<option value="<?=$city['id']?>" <?=set_select('customer_city', $city['id'], $city['id']==$review['customer_city'] ? true:false)?>><?=$city['name']?></option>
			<?php endforeach;?>
		</select>
		<?=form_error('customer_city')?>
	</div>
</div>
<div class="form-group">
	<label for="review_date" class="col-xs-3 control-label"><?=lang('reviews_field_date')?>: <?=mark_required()?></label>
	<div class="col-xs-2" id="review_date">
		<div class="input-append date input-group">			    
			<input type="text" class="form-control input-sm" placeholder="<?=DATE_FORMAT_CALLENDAR?>..." id="review_date_ip" name="review_date" 
	    		value="<?=set_value('review_date', date(DATE_FORMAT, strtotime($review['review_date'])))?>">
	    		
			<span class="input-group-addon"><span class="fa fa-calendar"></span></span>				  
		</div>
		<?=form_error('review_date')?>
	</div>
</div>
<div class="form-group">
	<label for="review_for" class="col-xs-3 control-label"><?=lang('reviews_field_for')?>: <?=mark_required()?></label>
	<div class="col-xs-4">
		<?php
			$review_for_name = !empty($review['hotel_id']) ? $review['review_for_hotel'] : $review['review_for_tour'];
		?>
		<input type="text" class="form-control" name="review_for" id="review_for" value="<?=set_value('review_for', $review_for_name)?>" readonly="readonly">
		<?=form_error('review_for')?>
		<input type="hidden" id="hotel_id" name="hotel_id" value="<?=set_value('hotel_id', $review['hotel_id'])?>">
		<input type="hidden" id="tour_id" name="tour_id" value="<?=set_value('tour_id', $review['tour_id'])?>">
	</div>
</div>
<div class="form-group">
	<label for="total_score" class="col-xs-3 control-label"><?=lang('reviews_field_score')?>:</label>
	<div class="col-xs-4">
		<table class="table">
					
			<?php foreach ($score_types as $key_type => $value_type) :?>
			
				<?php 
					// default : display for hotel review
					$style = ($key_type == $review_for['type']) ? '' : "style='display: none;'";
				?>
				
				<tbody id = 'score_type_<?=$key_type?>' <?=$style?>>
				
					<?php foreach ($value_type as $key => $value) :?>
						<tr>
							<td style="vertical-align: middle;"><?=lang($value)?></td>
							<td>
								<input type="text" size="12" autocomplete="off" maxlength="3" style="text-align: right;" 
									name="<?=$key_type?>_<?=$key?>" id="<?=$key_type?>_<?=$key?>" onkeyup="totalScore();" 
									value="<?=set_value($key_type.'_'.$key, ($style == '') ? $review['review_scores'][$key] : '')?>">
								
								<?=form_error($key_type.'_'.$key)?>
							</td>
						</tr>
					<?php endforeach ;?>	
					
				</tbody>
			
			<?php endforeach ;?>
			
			<tr>
				<td><?=lang('reviews_field_total_score')?></td>
				<td>
					<input type="text" size="12" maxlength="1" style="text-align: right;" name="total_score" id="total_score" readonly="readonly" value="<?=set_value('total_score', $review['total_score'])?>">
				</td>
			</tr>	
		</table>
	</div>
</div>
<div class="form-group">
	<label for="title" class="col-xs-3 control-label"><?=lang('reviews_field_title')?>:</label>
	<div class="col-xs-9">
		<input type="text" class="form-control" name="title" id="title" value="<?=set_value('title', $review['title'])?>">
		<?=form_error('title')?>
	</div>
</div>
<div class="form-group">
	<label for="review_content" class="col-xs-3 control-label"><?=lang('reviews_field_review_content')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<textarea class="form-control" rows="9" name="review_content"><?=set_value('review_content', $review['review_content'])?></textarea>
		<?=form_error('review_content')?>
	</div>
</div>
<div class="form-group">
    <div class="col-xs-offset-3 col-xs-6">
    	<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<?php if(isset($hotel)):?>
		<a class="btn btn-default mg-left-10" href="<?=site_url('hotels/reviews/'.$hotel['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    	<?php elseif(isset($cruise)):?>
    	<a class="btn btn-default mg-left-10" href="<?=site_url('cruises/reviews/'.$cruise['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    	<?php elseif(isset($tour)):?>
    	<a class="btn btn-default mg-left-10" href="<?=site_url('tours/reviews/'.$tour['id'])?>" role="button"><?=lang('btn_cancel')?></a>
    	<?php endif;?>
    </div>
</div>

</form>

<script type="text/javascript">

	$('#review_date .input-append.date.input-group').datepicker({
	    format: "<?=DATE_FORMAT_CALLENDAR?>",
	    autoclose: true,
	    todayHighlight: true,
	    //startDate: "<?=date(DATE_FORMAT)?>"
    });

	var scoreTypes = new Array();

	function displayScoreTypes(type)
	{
		<?php foreach ($score_types as $key => $value) :?>

			if (type == <?=$key?>)
			{
				
				$('#score_type_<?=$key?>').show();

				
				var i = 0;
				scoreTypes = new Array();
				<?php foreach ($value as $key_type => $value_type) :?>	
					scoreTypes[i] = '<?=$key?>_<?=$key_type?>';
					i++;
				<?php endforeach ;?>
				
				totalScore();
				
			} else {

				$('#score_type_<?=$key?>').hide();
			}

		<?php endforeach ;?>
	}

	// defaut for cruise tour
	function setScoreTypesDefault()
	{
		var i = 0;
		scoreTypes = new Array();
		<?php foreach ($score_types[$review_for['type']] as $key => $value) :?>	
			scoreTypes[i] = '<?=$review_for['type']?>_<?=$key?>';
			i++;
		<?php endforeach ;?>
	}

	init_review();

</script>
<?php endif;?>