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
	.col-md-3{
		width:22%;	
	}
	.col-md-9{
		padding-left:0;
		padding-right:0;
		width: 77%;
	}
	.container{
		width:1350px;
	}
	.row-item-cell{
		border: 1px solid #CCC;
		padding: 3px 6px;
		height:34px;	
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
	
</style>

<?php if(isset($search_rate)):?>
	<?=$search_rate?>
<?php endif;?>



<?php foreach ($room_type_shows as $room_type):?>

<div class="clearfix">
	<div class="col-xs-2 price-cell-container">&nbsp;</div>
	<?php foreach ($month_shows as $value):?>
		<div class="col-xs-<?=$value['col']?> price-cell-container row-item-cell txt-c">
			<b><?=date('M-Y', strtotime($value['date']))?></b>
		</div>		
	<?php endforeach;?>
</div>

<div class="clearfix">
	<div class="col-xs-2 price-cell-container row-item-cell">
		<b><?=$room_type['name']?></b>		
	</div>
	
	<?php foreach ($date_shows as $value):?>
		<div class="col-xs-1 price-cell-container row-item-cell txt-c<?php if(is_weekend($value)):?> week-end-bg<?php endif;?>"><?=date('d', strtotime($value))?></div>
	<?php endforeach;?>

</div>

<?php if($room_type['max_occupancy'] > TRIPLE):?>
	
	<div class="clearfix">
		<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_full_occupancy')?></div>
		
		<?php foreach ($date_shows as $value):?>
			
			<?php 
				$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);
				$rate_value = is_null($room_rate) || is_null($room_rate['full_occupancy_rate']) ? '' : number_format($room_rate['full_occupancy_rate']); 
			?>
			
			<div class="col-xs-1 price-cell-container">
				<input type="text" value="<?=$rate_value?>" class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
			</div>
		<?php endforeach;?>
	
	</div>

<?php endif;?>
	
<?php if($room_type['max_occupancy'] >= TRIPLE):?>
	
	<div class="clearfix">
		<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_triple')?></div>
		
		<?php foreach ($date_shows as $value):?>
		
			<?php 
				$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);
				$rate_value = is_null($room_rate) || is_null($room_rate['triple_rate']) ? '' : number_format($room_rate['triple_rate']); 
				
				$net_value = is_null($room_rate) || is_null($room_rate['triple_net']) ? '' : number_format($room_rate['triple_net']);
			?>
			
			<div class="col-xs-1 price-cell-container">
				<input type="text" value="<?=$rate_value?>" class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
			</div>
			
		<?php endforeach;?>
	
	</div>
	
<?php endif;?>


<?php if($room_type['max_occupancy'] > SINGLE):?>
	
	<div class="clearfix">
		<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_double')?></div>
		
		<?php foreach ($date_shows as $value):?>
			<?php 
				$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);
				$rate_value = is_null($room_rate) || is_null($room_rate['double_rate']) ? '' : number_format($room_rate['double_rate']); 
			?>
			
			<div class="col-xs-1 price-cell-container">
				<input type="text" value="<?=$rate_value?>" class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
			</div>
		<?php endforeach;?>
	
	</div>
	
<?php endif;?>


<div class="clearfix">
	<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_single')?></div>
	
	<?php foreach ($date_shows as $value):?>
		<?php 
			$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);
			$rate_value = is_null($room_rate) || is_null($room_rate['single_rate']) ? '' : number_format($room_rate['single_rate']); 
		?>
		
		<div class="col-xs-1 price-cell-container">
			<input type="text" value="<?=$rate_value?>" class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
		</div>
	<?php endforeach;?>

</div>

<?php if($room_type['max_extra_beds'] > 0):?>
	<div class="clearfix">
		<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_extra_bed')?></div>
		
		<?php foreach ($date_shows as $value):?>
			<?php 
				$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);
				$rate_value = is_null($room_rate) || is_null($room_rate['extra_bed_rate']) ? '' : number_format($room_rate['extra_bed_rate']); 
			?>
			
			<div class="col-xs-1 price-cell-container">
				<input type="text" value="<?=$rate_value?>" class="form-control price-cell txt-r <?php if(is_weekend($value)):?> week-end-bg<?php endif;?>">
			</div>
		<?php endforeach;?>
	
	</div>
<?php endif;?>


<div class="clearfix">
	<div class="col-xs-2 price-cell-container row-item-cell"><?=lang('rate_surcharge')?></div>
	<?php foreach ($date_shows as $value):?>
		<div class="col-xs-1 price-cell-container row-item-cell txt-r">
			<?php 
				$room_rate = get_room_rate_record($room_rates, $room_type['id'], $value);		
			?>
			<?php if(!is_null($room_rate) && $room_rate['has_surcharge'] == STATUS_ACTIVE):?>
				<a href="javascript:void(0)" onclick="get_surcharge_info(<?=$hotel_id?>,'<?=$value?>')"><?=lang('rate_yes')?></a>
			<?php endif;?>
		</div>
	<?php endforeach;?>
</div>
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
	

	function get_surcharge_data(hotel_id, date){

		$.ajax({
			url: "<?=site_url('show-surcharge-info')?>/",
			type: "POST",
			cache: true,
			data: {
				"hotel_id":hotel_id,
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