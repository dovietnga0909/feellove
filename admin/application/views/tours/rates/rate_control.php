<style type="text/css">
	.price-cell{
		padding: 3px 6px;
		font-size:12px;
		border-radius: 0;	
	}
	.price-cell-container{
		padding-left: 0px;
		padding-right:0px;	
	}
	.row-item-cell{
		border: 1px solid #CCC;
		padding: 3px 6px;
		height:34px;	
		line-height: 26px;
	}
	.txt-c{
		text-align:center;	
	}
	.txt-r{
		text-align:right;	
	}
	.week-end-bg{
		background-color:#EEE;
	}
	.mg-b-10{
		margin-bottom:10px;	
	}
	.ellipsis {
      text-overflow: ellipsis;
    
      /* Required for text-overflow to do anything */
      white-space: nowrap;
      overflow: hidden;
    }
</style>

<?php if(isset($search_rate)):?>
	<?=$search_rate?>
<?php endif;?>



<?php foreach ($accommodation_shows as $accommodation):?>

<div class="clearfix">
	<div class="col-xs-2 price-cell-container">&nbsp;</div>
	<?php foreach ($month_shows as $value):?>
		<div class="col-xs-<?=$value['col']?> price-cell-container row-item-cell txt-c">
			<b><?=date('M-Y', strtotime($value['date']))?></b>
		</div>		
	<?php endforeach;?>
</div>

<div class="clearfix">
	<div class="col-xs-2 price-cell-container row-item-cell ellipsis">
		<b><?=$accommodation['name']?></b>		
	</div>
	
	<?php foreach ($date_shows as $value):?>
		<div class="col-xs-1 price-cell-container row-item-cell txt-c<?php if(is_weekend($value)):?> week-end-bg<?php endif;?>"><?=date('d', strtotime($value))?></div>
	<?php endforeach;?>

</div>

<div class="clearfix">
	<?php foreach ($group_size as $g_size):?>
	<div class="col-xs-2 price-cell-container row-item-cell"><?=lang($g_size)?></div>
	
	<?php foreach ($date_shows as $value):?>
		
		<?php 
			$tour_rate = get_accommodation_rate_record($tour_rates, $accommodation['id'], $value);
			$rate_value = is_null($tour_rate) || is_null($tour_rate[$g_size.'_rate']) ? '' : number_format($tour_rate[$g_size.'_rate']); 
		?>
		
		<div class="col-xs-1 price-cell-container">
			<input type="text" value="<?=$rate_value?>" 
				class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
		</div>
	<?php endforeach;?>

	<?php endforeach;?>
</div>

<!-- 
<div class="clearfix">
	<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_surcharge')?></div>
	<?php foreach ($date_shows as $value):?>
		<div class="col-xs-1 price-cell-container row-item-cell txt-r">
			<?php 
				$room_rate = get_room_rate_record($room_rates, $accommodation['id'], $value);		
			?>
			<?php if(!is_null($room_rate) && $room_rate['has_surcharge'] == STATUS_ACTIVE):?>
				<a href="javascript:void(0)" onclick="get_surcharge_info(<?=$hotel_id?>,'<?=$value?>')"><?=lang('rate_yes')?></a>
			<?php endif;?>
		</div>
	<?php endforeach;?>
</div>
 -->
<br>
<?php endforeach;?>


<!-- Modal -->
<div class="modal fade" id="surcharge_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('surcharge_on')?> <span id="sur_date"></span></h4>
      </div>
      <div class="modal-body" id="modal-body">
      	ABC
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?=lang('btn_close')?></button>
    </div>
  </div>
</div>

<script type="text/javascript">

	function get_surcharge_info(hotel_id, date){

		$('#sur_date').text(date);

		$('#modal-body').html('');

		$('#surcharge_info').modal();
		
		get_surcharge_data(hotel_id, date);
	}
	

	function get_surcharge_data(cruise_id, date){

		$.ajax({
			url: "<?=site_url('show-surcharge-info')?>/",
			type: "POST",
			cache: true,
			data: {
				"cruise_id":cruise_id,
				"date":date
			},
			success:function(value){
				$('#modal-body').html(value);
				
			},
			error:function(var1, var2, var3){
							
			}
		});	
			
	}
	
	$( document ).ready(function() {
		$('.price-cell').maskMoney({allowZero: false, allowNegative: false, thousands:',', decimal:'.', affixesStay: false,  precision: 0});
	});
</script>