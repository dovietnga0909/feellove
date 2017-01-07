
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
			<option value=""><?=lang('tours_select_destination')?></option>
			<?php foreach ($destinations_domistic as $des):?>

				<?php
					//if($des['nr_tour_domisticc'] == 0) continue;
				?>

				<optgroup label="<?=$des['name']?>">
				<option value="<?=$des['id']?>" <?=set_select('des_id', $des['id'], $des['id']==$des_id)?>><?=$des['name']?></option>

				<?php foreach ($des['destinations'] as $sub_des):?>

						<?php
							//if($sub_des['nr_tour_domisticc'] == 0) continue;
						?>

					<option value="<?=$sub_des['id']?>" <?=set_select('des_id', $sub_des['id'], $sub_des['id']==$des_id)?>><?=$sub_des['name']?></option>
				<?php endforeach;?>
				</optgroup>
			<?php endforeach;?>

			<option value="" disabled="disabled">----------Outbound Destination---------</option>

			<?php foreach ($destinations_outbound as $des):?>

				<?php
					//if($des['nr_tour_domisticc'] == 0) continue;
				?>

				<optgroup label="<?=$des['name']?>">
				<option value="<?=$des['id']?>" <?=set_select('des_id', $des['id'], $des['id']==$des_id)?>><?=$des['name']?></option>

				<?php foreach ($des['destinations'] as $sub_des):?>

						<?php
							//if($sub_des['nr_tour_domisticc'] == 0) continue;
						?>

					<option value="<?=$sub_des['id']?>" <?=set_select('des_id', $sub_des['id'], $sub_des['id']==$des_id)?>><?=$sub_des['name']?></option>
				<?php endforeach;?>
				</optgroup>
			<?php endforeach;?>
		</select>


	</div>

	<div class="col-xs-8">


		<?php if(count($tours) > 0):?>

		<div class="row">
			<div class="col-xs-6">
				<label><?=lang('select_tour')?>:</label>
			</div>
			<div class="col-xs-3">
				<label><?=lang('specific_tour_get')?>:</label>
			</div>
		</div>

		<div class="row">
			<div class="checkbox col-xs-6">
				<label>
					<input type="checkbox" name="all" id="all" value="1" onclick="select_all_tours()">
					<?=lang('select_all_tour')?>
				</label>
			</div>

			<div class="col-xs-3">
				<input type="text" id="tour_get" name="tour_get" class="form-control input-sm price-cell" value="<?=set_value('tour_get')?>">
			</div>

			<div class="col-xs-3">
				<button type="button" class="btn btn-primary" onclick="apply_tour_get()"><?=lang('pro_field_apply_all')?></button>
			</div>
		</div>

		<hr>
			<?php foreach ($tours as $tour):?>
				<div class="row" style="margin-bottom:5px">
					<div class="col-xs-6">

						<div class="checkbox">
							<label>
								<input type="checkbox" onclick="show_hide_tour_get()" name="tours[]" class="tours" value="<?=$tour['id']?>" <?=set_checkbox('tours', $tour['id'], $tour['selected'])?>>
								<?=$tour['name']?>
							</label>
						</div>

					</div>
					<div class="col-xs-3">
						<input type="text" id="tour_get_<?=$tour['id']?>" name="tour_get_<?=$tour['id']?>" value="<?=($tour['tour_get'] > 0 ? set_value('tour_get_'.$tour['id'], $tour['tour_get']):'')?>" class="form-control input-sm price-cell tour_get" style="display:none">
					</div>
				</div>


			<?php endforeach;?>

		<hr>


		  <button type="submit" class="btn btn-primary btn-lg">
	      	<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url()?>marketings/tour-pro/<?=$pro_id?>" role="button"><?=lang('btn_cancel')?></a>

		<?php endif;?>
	</div>

</div>

</form>

<script type="text/javascript">

	function select_des(){
		var des_id = $('#des_id').val();
		var pro_id = $('#pro_id').val();

		window.location = '<?=site_url('marketings/tour-pro')?>/'+pro_id+'/'+des_id;
	}

	function select_all_tours(){

		var is_all = $('#all').is(':checked');

		$('.tours').prop('checked', is_all);

		show_hide_tour_get();
	}

	function apply_tour_get(){

		var tour_get = $('#tour_get').val();

		if(tour_get != ''){
			$('.tour_get').val(tour_get);
		}

	}

	function show_hide_tour_get(){
		$('.tours').each(function(){
			var tour_id = $(this).val();
			if($(this).is(':checked')){
				$('#tour_get_' + tour_id).show();
			} else {
				$('#tour_get_' + tour_id).hide();
			}
		});
	}
	$('.price-cell').mask('000,000,000,000,000', {reverse: true});

	show_hide_tour_get();

</script>