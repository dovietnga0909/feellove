
<?php if(isset($save_status) && $save_status === TRUE):?>

	<div class="alert alert-success alert-dismissable bpt-message" id="bpt_message">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <?=lang('update_successful')?>
	</div>
	<script>
		//self remove after 3 seconds 
		setTimeout(function(){
			$("#bpt_message").remove(); 
		}, 5000);
	</script>

<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<input type="hidden" value="save" name="action">
	
<div class="row">

	<input type="hidden" id="pro_id" name="pro_id" value="<?=$pro_id?>">

	<div class="col-xs-4">
		
		<label><?=lang('destination')?>:</label>
		
		<select class="form-control" name="des_id" id="des_id" onchange="select_des()">
			<option value=""><?=lang('hotels_select_hotel_area')?></option>
			<?php foreach ($destinations as $des):?>
				
				<?php 
					if($des['number_of_hotels'] == 0) continue;
				?>
				
				<optgroup label="<?=$des['name']?>">
				<option value="<?=$des['id']?>" <?=set_select('destination', $des['id'], $des['id']==$des_id)?>><?=$des['name']?></option>
				<?php foreach ($des['children'] as $sub_des):?>
						
						<?php 
							if($sub_des['number_of_hotels'] == 0) continue;
						?>
				
					<option value="<?=$sub_des['id']?>" <?=set_select('destination_id', $sub_des['id'], $sub_des['id']==$des_id)?>><?=$sub_des['name']?></option>
				<?php endforeach;?>
				</optgroup>
			<?php endforeach;?>
		</select>
		
	
	</div>
	
	<div class="col-xs-8">
		<label><?=lang('select_hotel')?>:</label>
		
		<?php if(count($hotels) > 0):?>
		
		<div class="checkbox">
			<label>
				<input type="checkbox" name="all" id="all" value="1" onclick="select_all_hotels()">
				<?=lang('select_all_hotel')?>
			</label>
		</div>
		<hr>
			<?php foreach ($hotels as $hotel):?>
			
				<div class="checkbox">
					<label>
						<input type="checkbox" name="hotels[]" class="hotels" value="<?=$hotel['id']?>" <?=set_checkbox('hotels', $hotel['id'], $hotel['selected'])?>>
						<?=$hotel['name']?>
					</label>
				</div>
			
			<?php endforeach;?>
		
		<hr>
		
		
		  <button type="submit" class="btn btn-primary btn-lg">
	      	<span class="fa fa-download"></span>	
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url()?>marketings/hotel-pro/<?=$pro_id?>" role="button"><?=lang('btn_cancel')?></a>
	      
		<?php endif;?>
	</div>

</div>

</form>

<script type="text/javascript">

	function select_des(){
		var des_id = $('#des_id').val();
		var pro_id = $('#pro_id').val();

		window.location = '<?=site_url('marketings/hotel-pro')?>/'+pro_id+'/'+des_id;
	}

	function select_all_hotels(){

		var is_all = $('#all').is(':checked');
	
		$('.hotels').prop('checked', is_all);
	}

</script>