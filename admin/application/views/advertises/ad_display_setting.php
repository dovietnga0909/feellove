<?php if(empty($ad)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url()?>advertises/" role="button">
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

<form class="form-horizontal" role="form" method="post">
  <input type="hidden" value="save" name="action">

  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"><?=lang('ad_field_name')?></label>
    <div class="col-sm-6">
      <label id="name" class="control-label"><?=$ad['name']?></label>
    </div>
  </div>
  
   	<div class="form-group">
    	<label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_display_on')?></label>
    
    	<div class="col-sm-6">
    		<div class="row">
		   		<div class="col-sm-6">
		      	
			      	<?php foreach ($ad_pages as $key=>$value):?>
			      	<?php if($key > 6) break;?>
			      	<div class="checkbox">
			    	<label>
			    		<?php 
			    			$checked = is_bit_value_contain($ad['display_on'], $key);
			    		?>
			  			<input type="checkbox" class="display_on" value="<?=$key?>" onclick="show_on(this)" <?=set_checkbox('display_on[]',$key, $checked)?> name="display_on[]" > <?=$value?>
					</label>
					</div>
					<?php endforeach;?>
			 
		   	 	</div>
		    
				<div class="col-sm-6">
		    	
			    	<?php foreach ($ad_pages as $key=>$value):?>
			      	<?php if($key <= 6) continue;?>
			      	<div class="checkbox">
			    	<label>
			    		<?php 
			    			$checked = is_bit_value_contain($ad['display_on'], $key);
			    		?>
			  			<input type="checkbox" class="display_on" value="<?=$key?>" onclick="show_on(this)" <?=set_checkbox('display_on[]',$key, $checked)?> name="display_on[]" > <?=$value?>
					</label>
					</div>
					<?php endforeach;?>
			    	
			    </div>
	   		</div>
	    
    		<div class="row">
    		 	<div class="col-sm-12">
	    			<?=form_error('display_on[]')?>
	    	 	</div>
	    	</div>
   		</div>
   	
  	</div>
	
	<div class="form-group">
    	<label for="ad_area" class="col-sm-2 control-label"><?=lang('ad_field_ad_area')?></label>
    	
    	<div class="col-sm-6">	
	      	<?php foreach ($ad_areas as $key=>$value):?>
	      	<div class="checkbox checkbox-inline">
	    	<label>
	    		<?php 
	    			$checked = is_bit_value_contain($ad['ad_area'], $key);
	    		?>
	  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox('ad_area[]',$key, $checked)?> name="ad_area[]" > <?=$value?>
			</label>
			</div>
			<?php endforeach;?>
			<br>
			<?=form_error('ad_area[]')?> 
		 </div>
     </div>
     
     
  <?php 
  	$is_show_hotel_des = is_bit_value_contain($ad['display_on'], AD_PAGE_HOTEL_DESTINATION);
  ?>
  
  <div class="form-group<?php if($is_show_hotel_des):?> show<?php else:?> hidden<?php endif;?>" id="all_hotel_des">
  	<hr>
    <label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_hotel_des')?></label>
    <div class="col-sm-4">
      	
      	<div class="checkbox">
	    	<label style="border-bottom:1px solid #EEE">    		
	  			<input type="checkbox" value="1" class="all_hotel_des" onclick="all_select('hotel')" <?=set_checkbox('all_hotel_des',1, $ad['all_hotel_des'] == STATUS_ACTIVE)?> name="all_hotel_des" > <?=lang('ad_select_all_hotel_des')?>
			</label>
						
		</div>
		
		<div style="margin-top:7px"><b>OR</b></div>
      	
      	<?php foreach ($hotel_des as $key=>$value):?>      	
      	<div class="checkbox">
    	<label>
    		<?php 
    			$checked = false;
    		?>
  			<input type="checkbox" class="hotel_des" value="<?=$value['id']?>" onclick="item_select('hotel')" <?=set_checkbox('hotel_des[]',$value['id'], $value['is_selected'])?> name="hotel_des[]" > <?=$value['name']?>
		</label>
		</div>
		<?php endforeach;?>
      	
      	<?=form_error('hotel_des[]')?>
    </div>
    
   </div>

  <?php 
  	$is_show_flight_des = is_bit_value_contain($ad['display_on'], AD_PAGE_FLIGHT_DESTINATION);
  ?>
  
  <div class="form-group<?php if($is_show_flight_des):?> show<?php else:?> hidden<?php endif;?>" id="all_flight_des">
  	<hr>
    <label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_flight_des')?></label>
    <div class="col-sm-4">
      	
      	<div class="checkbox">
	    	<label style="border-bottom:1px solid #EEE">    		
	  			<input type="checkbox" value="1" class="all_flight_des" onclick="all_select('flight')" <?=set_checkbox('all_flight_des',1, $ad['all_flight_des'] == STATUS_ACTIVE)?> name="all_flight_des" > <?=lang('ad_select_all_flight_des')?>
			</label>
						
		</div>
		
		<div style="margin-top:7px"><b>OR</b></div>
      	
      	<?php foreach ($flight_des as $key=>$value):?>      	
      	<div class="checkbox">
    	<label>
    		<?php 
    			$checked = false;
    		?>
  			<input type="checkbox" class="flight_des" value="<?=$value['id']?>" onclick="item_select('flight')" <?=set_checkbox('flight_des[]',$value['id'], $value['is_selected'])?> name="flight_des[]" > <?=$value['name']?>
		</label>
		</div>
		<?php endforeach;?>
      	
      	<?=form_error('flight_des[]')?>
    </div>
    
  </div>
   
  <?php 
  	$is_show_tour_des = is_bit_value_contain($ad['display_on'], AD_PAGE_TOUR_DESTINATION);
  ?>
  
  <div class="form-group<?php if($is_show_tour_des):?> show<?php else:?> hidden<?php endif;?>" id="all_tour_des">
  	<hr>
    <label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_tour_des')?></label>
    <div class="col-sm-6">
      	
      	<div class="checkbox">
	    	<label style="border-bottom:1px solid #EEE">    		
	  			<input type="checkbox" value="1" class="all_tour_des" onclick="all_select('tour')" 
	  			<?=set_checkbox('all_tour_des',1, $ad['all_tour_des'] == STATUS_ACTIVE )?> 
	  			name="all_tour_des" > 
	  			<?=lang('ad_select_all_tour_des')?>
			</label>
						
		</div>
		
		<div style="margin-top:7px"><b>OR</b></div>
		
		<div class="row">
		
	      	<div class="col-sm-6">
	      	
		      	<?php foreach ($domistic_destinations as $value):?>  
		      		
			      	<div class="checkbox">
				    	<label>
				  			<input type="checkbox" class="tour_des" value="<?=$value['id']?>" onclick="item_select('tour')" <?=set_checkbox('tour_des[]',$value['id'], $value['is_selected'])?> name="tour_des[]" > <b><?=$value['name']?></b>
						</label>
					</div>
					
					<?php if(!empty($value['destinations'])):?>
						<?php foreach ($value['destinations'] as $des):?>
							<div class="checkbox" style="margin-left:20px;">
						    	<label>
						  			<input type="checkbox" class="tour_des" value="<?=$des['id']?>" onclick="item_select('tour')" <?=set_checkbox('tour_des[]',$des['id'], $des['is_selected'])?> name="tour_des[]" > <?=$des['name']?>
								</label>
							</div>
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
			
			</div>
			
			<div class="col-sm-6">
				
				<?php foreach ($outbound_destinations as $value):?>  
		      		
			      	<div class="checkbox">
				    	<label>
				  			<input type="checkbox" class="tour_des" value="<?=$value['id']?>" onclick="item_select('tour')" <?=set_checkbox('tour_des[]',$value['id'], $value['is_selected'])?> name="tour_des[]" > <b><?=$value['name']?></b>
						</label>
					</div>
					
					<?php if(!empty($value['destinations'])):?>
						<?php foreach ($value['destinations'] as $des):?>
							<div class="checkbox" style="margin-left:20px;">
						    	<label>
						  			<input type="checkbox" class="tour_des" value="<?=$des['id']?>" onclick="item_select('tour')" <?=set_checkbox('tour_des[]',$des['id'], $des['is_selected'])?> name="tour_des[]" > <?=$des['name']?>
								</label>
							</div>
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
				
	      	</div>
      	
      		<?=form_error('tour_des[]')?>
      	</div>
    </div>
    
  </div>
  
  <?php 
  	$is_show_tour_cat_des 	= is_bit_value_contain($ad['display_on'], AD_PAGE_TOUR_CATEGORY);
  ?>
  
  <div class="form-group<?php if($is_show_tour_cat_des):?> show<?php else:?> hidden<?php endif;?>" id="all_tour_cat_des">
  	<hr>
    <label for="display_on" class="col-sm-2 control-label"><?=lang('ad_field_tour_cat_des')?></label>
    <div class="col-sm-4">
      	
      	<div class="checkbox">
	    	<label style="border-bottom:1px solid #EEE">    		
	  			<input type="checkbox" value="1" class="all_tour_cat_des" 
	  			onclick="all_select('tour_cat')" <?=set_checkbox('all_tour_cat_des',1, $ad['all_tour_cat_des'] == STATUS_ACTIVE)?> name="all_tour_cat_des" > <?=lang('ad_select_all_tour_cat_des')?>
			</label>
		</div>
		
		<div style="margin-top:7px"><b>OR</b></div>
      	
      	<?php foreach ($tour_cat_des as $key=>$value):?>      	
      	<div class="checkbox">
	    	<label>
	    		<?php 
	    			$checked = false;
	    		?>
	  			<input type="checkbox" 
	  				class="tour_cat_des" 
	  				value="<?=$value['id']?>" 
	  				onclick="item_select('tour_cat')" 
	  				<?=set_checkbox('tour_cat_des[]',$value['id'], $value['is_selected'])?> 
	  				name="tour_cat_des[]" > <?=$value['name']?>
			</label>
		</div>
		<?php endforeach;?>
      	
      	<?=form_error('tour_cat_des[]')?>
    </div>
    
  </div>
 
  <div class="form-group">
  	<br>
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('advertises')?>/" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<?php endif;?>

<script type="text/javascript">

	function all_select(module){

		var all_checkbox_css = '.all_' + module + '_des';
		
		var checked = $(all_checkbox_css).is(':checked');

		if(checked){
			$('.' + module + '_des').prop('checked', true);
		} else {
			$('.' + module + '_des').prop('checked', false);
		}
	}

	function item_select(module){

		var all_checkbox_css = '.all_' + module + '_des';

		var all_selected = true;
		
		$('.' + module + '_des').each(function(){

			if(!$(this).is(':checked')){
				all_selected = false;
			}
			
		});

		$('.all_' + module + '_des').prop('checked', all_selected);	
	}

	function show_on(obj){
		
		if($(obj).val() == <?=AD_PAGE_HOTEL_DESTINATION?> || $(obj).val() == <?=AD_PAGE_FLIGHT_DESTINATION?> || $(obj).val() == <?=AD_PAGE_TOUR_DESTINATION?> || $(obj).val() == <?=AD_PAGE_TOUR_CATEGORY?>){

			var module = $(obj).val();
			
			if(module == <?=AD_PAGE_HOTEL_DESTINATION?>){
				module = 'hotel';
			}else if(module == <?=AD_PAGE_FLIGHT_DESTINATION?>){
				module = 'flight';
			}else if(module == <?=AD_PAGE_TOUR_DESTINATION?>){
				module = 'tour';
			}else if(module == <?=AD_PAGE_TOUR_CATEGORY?>){
				module = 'tour_cat';
			}
			
			if($(obj).is(':checked')){

				$('#all_' + module + '_des').removeClass('hidden');
				$('#all_' + module + '_des').addClass('show');

					
			} else {

				$('#all_' + module + '_des').removeClass('show');
				$('#all_' + module + '_des').addClass('hidden');
			}
			
		}
		
	}

	function check_display_on(){
		$('.display_on').each(function(){
			show_on(this);
		});
	}

	check_display_on();
		
</script>
