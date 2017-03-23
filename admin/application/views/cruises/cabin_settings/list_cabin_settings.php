<form class="form-horizontal" role="form" method="post">
<?php if(validation_errors() != ''):?>
<div class="mg-bottom-20 bp-error">
	<?php echo validation_errors(); ?>
</div>
<?php endif;?>
<div class="panel panel-default">
	<div class="panel-heading">
  		<?=lang('list_of_cabin_settings')?>
  	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center v-middle">#</th>
				<th class="v-middle" width="20%"><?=lang('cruise_cabins_field_name')?></th>
				<th class="text-center v-middle"><?=lang('cabin_settings_field_numb_of_cabins')?></th>
				<th class="text-center v-middle"><?=lang('cabin_settings_field_max_occupancy')?></th>
				<th class="text-center v-middle"><?=lang('cabin_settings_field_max_extra_beds')?></th>
				<th class="text-center v-middle"><?=lang('cabin_settings_field_max_children')?></th>
				<!-- 
				<th class="text-center v-middle"><?=lang('cabin_settings_field_rack_rate')?></th>
				<th class="text-center v-middle"><?=lang('cabin_settings_field_min_rate')?></th>
				-->
				<th class="text-center v-middle"><?=lang('cabin_settings_field_included_vat')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($cabins)):?>
				<?php foreach ($cabins as $k => $cabin):?>
				<tr>
					<td class="v-middle text-center"><?=$k + 1?></td>
					<td class="v-middle">
						<?=$cabin['name']?>
					</td>
					<td class="v-middle">
						<?php $numb_err_class = (form_error('number_of_cabins_'.$k) != '') ? ' input-error' : '';?>
						<input type="text" class="form-control input-sm cabin-settings-control<?=$numb_err_class?>" name="number_of_cabins_<?=$k?>"
							value="<?=set_value('number_of_cabins_'.$k, $cabin['number_of_cabins'])?>">
					</td>
					<td class="v-middle">
						<?php $max_oc_err_class = (form_error('max_occupancy_'.$k) != '') ? ' input-error' : '';?>
						<select class="form-control input-sm cabin-settings-control<?=$max_oc_err_class?>" name="max_occupancy_<?=$k?>">
							<?php for($i=0; $i<=$max_occupancy; $i++):?>
							<option value="<?=$i?>" <?=set_select('max_occupancy_'.$k, $i, $i==$cabin['max_occupancy'] ? TRUE : FALSE)?>><?=$i?></option>
							<?php endfor;?>
						</select>
					</td>
					<td class="v-middle">
						<select class="form-control input-sm cabin-settings-control" name="max_extra_beds_<?=$k?>">
							<?php for($i=0; $i<=$max_extra_beds; $i++):?>
							<option value="<?=$i?>" <?=set_select('max_extra_beds_'.$k, $i, $i==$cabin['max_extra_beds'] ? TRUE : FALSE)?>><?=$i?></option>
							<?php endfor;?>
						</select>
					</td>
					<td class="v-middle">
						<select class="form-control input-sm cabin-settings-control" name="max_children_<?=$k?>">
							<?php for($i=0; $i<=$max_children; $i++):?>
							<option value="<?=$i?>" <?=set_select('max_children_'.$k, $i, $i==$cabin['max_children'] ? TRUE : FALSE)?>><?=$i?></option>
							<?php endfor;?>
						</select>
					</td>
					<!-- 
					<td class="v-middle">
						<?php $rack_rate_err_class = (form_error('rack_rate_'.$k) != '') ? ' input-error' : '';?>
						<input type="text" class="form-control input-sm cabin-settings-control<?=$rack_rate_err_class?>" name="rack_rate_<?=$k?>"
							value="<?=set_value('rack_rate_'.$k, $cabin['rack_rate'])?>">
					</td>
					<td class="v-middle">
						<?php $min_rate_err_class = (form_error('min_rate_'.$k) != '') ? ' input-error' : '';?>
						<input type="text" class="form-control input-sm cabin-settings-control<?=$min_rate_err_class?>" name="min_rate_<?=$k?>"
							value="<?=set_value('rack_rate_'.$k, $cabin['min_rate'])?>">
					</td>
					 -->
					<td class="v-middle text-center">
						<input type="checkbox" name="included_vat_<?=$k?>" value="1"
							<?=set_checkbox('included_vat_'.$k, 1, 1==$cabin['included_vat'] ? TRUE : FALSE)?>>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="9" class="error text-center">
						<div class="alert alert-warning"><?=lang('no_cabins_created')?></div>
					</td>
				</tr>
			<?php endif;?>
		</tbody>
	</table>
</div>
<?php if(!empty($cabins)):?>
<div>
	<button type="submit" class="btn btn-primary">
		<span class="fa fa-download"></span>
		<?=lang('btn_save')?>
	</button>
	<a class="btn btn-default mg-left-10" href="<?=site_url('cruises/cabins/'.$cruise['id'])?>" role="button"><?=lang('btn_cancel')?></a>
</div>
<?php endif;?>
</form>