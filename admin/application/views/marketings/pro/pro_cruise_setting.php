
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

	<div class="col-xs-8" >
		<div class="row">
			<div class="col-xs-6">
				<label><?=lang('select_cruise')?>:</label>
			</div>
			<div class="col-xs-6">
				<label><?=lang('pro_field_specific_cruises_get') ?>:</label>
			</div>
		</div>
		<?php if(count($cruises) > 0):?>

		<div class="row" >
			<div class="checkbox col-xs-6">
				<label>
					<input type="checkbox" name="all" id="all" value="1">
					<?=lang('select_all_cruise')?>
				</label>
			</div>
			<div class="col-xs-4" >
				<input type="text" id="get_all_cruises" class="form-control input-sm price-cell">
			</div>
			<div class="col-xs-2">
				<button type="button" onclick="apply_cruise_get()" class="btn btn-primary"><?=lang('pro_field_apply_all') ?></button>
			</div>

		</div>
		<hr>

			<?php foreach ($cruises as $cruise):?>

			<div class="row" style="padding-bottom: 5px">
				<div class="checkbox col-xs-6">
					<label>
						<input type="checkbox"  name = "cruises[]" onclick="display_specific_cruises()" class="cruises" value="<?=$cruise['id']?>" <?=set_checkbox('cruises', $cruise['id'], $cruise['selected'])?>>
						<?=$cruise['name']?>
					</label>
				</div>
				<div class="col-xs-4">
					<input type="text" name="specific_cruises_<?=$cruise['id'] ?>" id="specific_cruises_<?=$cruise['id'] ?>" value="<?=($cruise['cruise_get'] > 0 ? set_value('specific_cruises_'.$cruise['id'], $cruise['cruise_get']) : '')?>" class="form-control input-sm price-cell cruises_get">
				</div>
			</div>
			<?php endforeach;?>

		<hr>


		  <button type="submit" class="btn btn-primary btn-lg">
	      	<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url()?>marketings/cruise-pro/<?=$pro_id?>" role="button"><?=lang('btn_cancel')?></a>

		<?php endif;?>
	</div>

</div>

</form>

<script type="text/javascript">

	//select and deselect
	$("#all").click(function () {
		$(".cruises").prop("checked", this.checked);

		display_specific_cruises();
	});

	//If one item deselect then button CheckAll is UnCheck
	$(".cruises").click(function () {
		if (!$(this).is(":checked"))
			$("#all").prop("checked", false);
	});

	$(".cruises").each(function () {
		$("#all").prop("checked", $(this).is(":checked"));
	});

	$('.price-cell').mask('000,000,000,000,000', {reverse: true});

	//apply for all cruise get
	function apply_cruise_get () {
			var cruises_get = $('#get_all_cruises').val();
			if ($('#get_all_cruises') != ""){
				$('.cruises_get').val(cruises_get);
			}
		};

	//hide and show specific cruises input
	function display_specific_cruises() {
			$('.cruises').each(function(){
				var cruise_id = $(this).val();
				if($(this).is(':checked')){
					$('#specific_cruises_' + cruise_id).show();
				} else {
					$('#specific_cruises_' + cruise_id).hide();
				}
			});
	};

	display_specific_cruises();
</script>