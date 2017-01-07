<ul class="nav nav-tabs mg-bottom-20">
	<li <?php if($group_id == 0) echo 'class="active"'?>>
		<a href="<?=site_url('/cruises/facilities/'.$cruise['id'])?>">
		<?=lang('cruises_field_all_facilities')?>
		<span class="badge"><?=$facility_count[0]?></span>
		</a>
	</li>
	<?php foreach ($facility_groups as $k => $group):?>
	<li <?php if($group_id == $k) echo 'class="active"'?>>
		<a href="<?=site_url('/cruises/facilities/'.$cruise['id'].'?g_id='.$k)?>">
		<?=lang($group)?>
		<span class="badge"><?=$facility_count[$k]?></span>
		</a>
	</li>
	<?php endforeach;?>
</ul>

<div class="panel panel-default">
	<!-- Default panel contents -->
	<?php if(count($facilities) > 0):?>
	<div class="panel-heading">
	  	<?=lang('filter_show')?>
	  	<span id="filter_all" class="selected">
	  		<a href="javascript:void()"><?=sprintf($this->lang->line('filter_all_items'), count($facilities))?></a>
	  	</span>
	  	<?php if($numb_avaiable > 0):?>
	  	<span id="filter_avb">
	  		<a href="javascript:void()"><?=$numb_avaiable. ' '. lang('filter_avaiable')?></a>
	  	</span>
	  	<?php endif;?>
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('cruises/create_facility/'.$cruise['id'])?>" role="button">
	  		<span class="fa fa-arrow-circle-right"></span>
	  		<?=lang('create_facility_create_btn')?>
	  	</a>
	</div>
	<?php endif;?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?=lang('facilities_field_name')?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($facilities)):?>
				<?php foreach ($facilities as $facility):?>
				<tr class="<?php echo  $facility['avaiable'] == STATUS_AVAIABLE ? 'avb' : 'not-avb';?>">
					<td>
						<?php if($facility['cruise_id'] == $cruise['id']):?>
						<a href="<?=site_url('cruises/edit_facility/'.$facility['id'])?>" title="<?=lang('ico_edit')?>">
							<?=$facility['name']?>
						</a>
						<?php else:?>
							<?=$facility['name']?>
						<?php endif;?>
					</td>
					<td class="text-right">
						<?php 
							$params = 'c_id='.$cruise['id'].'&f_id='.$facility['id'];
							if(!empty($group_id)) $params .= '&g_id='.$group_id;
						?>
						<?php if($facility['avaiable'] == STATUS_AVAIABLE):?>
							<a href="javascript:void(0)" data-url="<?=site_url('/cruises/facilities/update/?'.$params)?>" 
								title="<?=lang('ico_deactivate')?>" class="avaiable btn-update">
								<span class="fa fa-check"></span> <span class="item-label"><?=lang('facilities_avaiable')?></span>
							</a>
						<?php else:?>
							<a href="javascript:void(0)" data-url="<?=site_url('/cruises/facilities/update/?'.$params)?>" 
								title="<?=lang('ico_activate')?>" class="not-avaiable btn-update">
								<span class="fa fa-check"></span> <span class="item-label"><?=lang('facilities_avaiable')?></span>
							</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="6" class="error text-center">
						<?=lang('no_search_results')?>
					</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
<script>
	$( "#filter_all" ).click(function() {
		$('.not-avb').show();
		$('#filter_all').addClass('selected');
		$('#filter_avb').removeClass('selected');
	});

	$( "#filter_avb" ).click(function() {
		$('.not-avb').hide();
		$('#filter_avb').addClass('selected');
		$('#filter_all').removeClass('selected');
	});

	$.fn.loading = function(show){
	    if(show){
	        this.addClass('item-disable');
	        this.append('<div class="item-mask">Updating...</div>');
	        this.blur();
	    } else {
	    	this.removeClass('item-disable');
	    	this.find('.item-mask').remove();
	    }
	};

	$('.btn-update').click(function() {
		var obj = $(this);

		if(obj.hasClass( "item-disable" )) {
			console.log('Already call...');
			return false;
		} else {
			$.ajax({
				url: $(this).attr('data-url'),
				type: "GET",
				success:function(value){
					obj.loading(false);
					
					if(value == '0') {
						obj.addClass('not-avaiable');
						obj.removeClass('avaiable');
					} else if(value == '1') {
						obj.addClass('avaiable');
						obj.removeClass('not-avaiable');
					}
	    		},
			});
			obj.loading(true);
		}
	})
</script>