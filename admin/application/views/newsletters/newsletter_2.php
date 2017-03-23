<?=$pro_step?>

<?php
	if(isset($pro['promotion_full'])){
		
		foreach($pro['promotion_full'] as $key => $promotion_full){
			
			$pro_id[$key] = $promotion_full['id'];
		}
	}

?>

<form class="form-horizontal" role="form" method="post" onsubmit="update_data();">
	
	<input type="hidden" value="next" name="action">
	<?php if($pro['template_type'] == HOTEL_HTML):?>
	  	<div class="form-group">
			<label for="destination_id" class="col-sm-2 control-label"><?=lang('destination')?>:</label>
			<div class="col-xs-4">
				<select class="form-control input-sm" name="destination_id" id="destination_id" onchange="show_hotel(this.value)">
					<option value=""><?=lang('please_select')?></option>
					<?php foreach ($hotel_destinations as $destination):?>
						<option value="<?=$destination['id']?>" <?=set_select('destination_id', $destination['id'])?>><?=$destination['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
	<?php endif;?>
	
	<?php  if($pro['template_type'] == TOUR_HTML): ?>	
		<div role="tabpanel">

	  	<!-- Nav tabs -->
	  	<ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#domestic" aria-controls="Domestic" role="tab" data-toggle="tab">Domestic</a></li>
		    <li role="presentation"><a href="#outbound" aria-controls="outbound" role="tab" data-toggle="tab">Out bound</a></li>
		    <li role="presentation"><a href="#category" aria-controls="category" role="tab" data-toggle="tab">Category</a></li>
	  	</ul>
	
	  	<!-- Tab panes -->
		  	<div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="domestic" style="margin-top:20px">
					<?php if(count($tour_domestic) >0):?>
						<div class="col-xs-2">
							<label for="domestic_id" class="control-label"><?=lang('destination')?>:</label>
						</div>
						<div class="form-group col-xs-3 col-xs-offset-2">
							
							<select class="form-control input-sm" name="domestic_id" id="domestic_id" onchange="show_tour_domestic(this.value)">
								<option value=""><?=lang('please_select')?></option>
								<?php foreach ($tour_domestic as $domestic):?>
									<optgroup label="<?=$domestic['name']?>">
										<option value="<?=$domestic['id']?>" <?=set_select('destination_id', $domestic['id'])?>><?=$domestic['name']?></option>
										<?php foreach ($domestic['destinations'] as $destination):?>
											<option value="<?=$destination['id']?>" <?=set_select('destination_id', $destination['id'])?>><?=$destination['name']?></option>
										<?php endforeach;?>
									</optgroup>
								<?php endforeach;?>
							</select>
						</div>
					<?php else:?>
						<div class="col-xs-4 col-xs-offset-2">
							<p><?=lang('no_promotion')?></p>
						</div>
					<?php endif;?>
					
				</div>
				
			    <div role="tabpanel" class="tab-pane" id="outbound" style="margin-top:20px">
					<?php if(count($tour_outbound) >0):?>
						<div class="col-xs-2">
							<label for="outbound_id" class="control-label"><?=lang('destination')?>:</label>
						</div>
						<div class="form-group col-xs-3 col-xs-offset-2">
							<select class="form-control input-sm" name="outbound_id" id="outbound_id" onchange="show_tour_outbound(this.value)">
								<option value=""><?=lang('please_select')?></option>
								<?php foreach ($tour_outbound as $outbound):?>
									<optgroup label="<?=$outbound['name']?>">
										<option value="<?=$outbound['id']?>" <?=set_select('destination_id', $outbound['id'])?>><?=$outbound['name']?></option>
										<?php foreach ($outbound['destinations'] as $destination):?>
											<option value="<?=$destination['id']?>" <?=set_select('destination_id', $destination['id'])?>><?=$destination['name']?></option>
										<?php endforeach;?>
									</optgroup>
								<?php endforeach;?>
							</select>
						</div>
					<?php else:?>
						<div class="col-xs-4 col-xs-offset-2">
							<p><?=lang('no_promotion')?></p>
						</div>
					<?php endif;?>
				</div>
				
			    <div role="tabpanel" class="tab-pane" id="category" style="margin-top:20px">
			    	<?php if(count($tour_category) >0):?>
						<div class="col-xs-2">
							<label for="category_id" class="control-label"><?=lang('category')?>:</label>
						</div>
						<div class="form-group col-xs-4 col-xs-offset-2">
							<select class="form-control input-sm" name="category_id" id="category_id" onchange="show_category(this.value)">
								<option value=""><?=lang('please_select')?></option>
								<?php foreach ($tour_category as $category):?>
									<option value="<?=$category['id']?>" <?=set_select('category_id', $category['id'])?>><?=$category['name']?></option>
								<?php endforeach;?>
							</select>
						</div>
					<?php endif;?>
			    </div>
		  	</div>
		  	
		</div>
	
	<?php endif;?>
	
	
	<?php if($pro['template_type'] == CRUISE_HTML):?>
		<div class="form-group">
			<div class="col-xs-8">
				<?php if(count($cruises) >0):?>
					<div class="promotion_list" id="checkboxes">
						<?php foreach ($cruises as $key => $value):?>
							<div class="col-xs-8 col-xs-offset-3">
								<span class="cruise_<?=$key?>"><b><?=$value['name']?></b></span></br>
								<div class="col-xs-10 col-xs-offset-1" id="cruise_<?=$key?>">
									<?php foreach($value['promotions'] as $k => $promotion):?>
										<div class="checkbox">
								        	<label><input type="checkbox" name="promotions[]" value="<?=$promotion['id']?>"><?=$promotion['name']?></label>
								        </div>
									 <?php endforeach;?>
								</div>
							</div>
						<?php endforeach;?>
						<?=form_error('promotions')?>
					</div>
				<?php endif;?>
			</div>
		</div>
	
	<?php endif;?>
	
	<div class="form-group">
		<div class="col-xs-8">
			<div id="hotels">
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-xs-8" id="checkboxes">
			<div id="tours_domestic">
			</div>
			<div id="tours_outbound">
			</div>
			<div id="tours_categories">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-xs-8">
			<div id="cruises">
			</div>
		</div>
	</div>
	
	
  	<br>
  	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-6">
	        <?php
	           $str_pro_id = ''; 
	           
	           if(!empty($pro_id)) {
	               foreach ($pro_id as $id){
	                   $str_pro_id.= $id.',';
	               }
	               $str_pro_id = rtrim($str_pro_id, ',');
	           }
	        ?>
	        <input type="hidden" name="promotion_selected" id="promotion_selected" value="<?=$str_pro_id?>">
	      	<button type="submit" class="btn btn-primary">      		
				<?=lang('btn_next')?>&nbsp;
				<span class="fa fa-arrow-right"></span>
	      	</button>
	      <a class="btn btn-default mg-left-10" href="<?=site_url('newsletters')?>" role="button"><?=lang('btn_cancel')?></a>
	    </div>
  	</div>
</form>

<script>
set_selected_promotion();

function show_hotel(des_id){

	update_data();
	
	$.ajax({
		url: "/admin/newsletters/search_hotel/",
		type: "POST",
		data: {
			"des_id": des_id,
			"selected": null,
			"newsletter_id": '<?=isset($pro["id"])? $pro["id"] : ""?>'
		},
		success:function(value){
			if(value !=''){
				$('#hotels').html(value);

				set_selected_promotion();
			}
		}
	});
}

function show_tour_domestic(des_id){

	update_data();
	
	$.ajax({
		url: "/admin/newsletters/search_tour/",
		type: "POST",
		data: {
			"des_id": des_id,
			"selected": null,
			"newsletter_id": '<?=isset($pro["id"])? $pro["id"] : ""?>'
		},
		success:function(value){
			if(value !=''){
				$('#tours_domestic').html(value);

				set_selected_promotion();
			}
		}
	});
}

function show_tour_outbound(des_id){

	update_data();
	
	$.ajax({
		url: "/admin/newsletters/search_tour/",
		type: "POST",
		data: {
			"des_id": des_id,
			"selected": null,
			"newsletter_id": '<?=isset($pro["id"])? $pro["id"] : ""?>'
		},
		success:function(value){
			if(value !=''){
				$('#tours_outbound').html(value);

				set_selected_promotion();
			}
		}
	});
}

function show_category(cat_id){

	update_data();
	
	$.ajax({
		url: "/admin/newsletters/search_category/",
		type: "POST",
		data: {
			"cat_id": cat_id,
			"selected": null,
			"newsletter_id": '<?=isset($pro["id"])? $pro["id"] : ""?>'
		},
		success:function(value){
			if(value !=''){
				$('#tours_categories').html(value);

				set_selected_promotion();
			}
		}
	});
}


function set_selected_promotion()
{
	var selected = $('#promotion_selected').val().split(",");

	$('#checkboxes input:checkbox').each(function() {
		if(jQuery.inArray($(this).val(), selected) >= 0) {
			$(this).prop('checked', true);
		}
	});
}

// update selected promotion of destination
function update_data()
{
	var promotion_selected = $('#promotion_selected').val().split(",");
	
	// update promotion selected 
	$('#checkboxes input:checkbox').each(function() {
		var value = $(this).val();
		// add
		if($(this).prop('checked') && jQuery.inArray(value, promotion_selected) == -1)
		{
			promotion_selected.push(value);
		}
		// remove
		if(!$(this).prop('checked') && jQuery.inArray(value, promotion_selected) != -1)
		{
			promotion_selected = jQuery.grep(promotion_selected, function(m_value) {
				return m_value != value;
			});
		}
	});
	  
	$('#promotion_selected').val(promotion_selected);
}
</script>