<div class="panel panel-default">
    	 
	  <div class="panel-heading">
	  	<?=lang('list_of_tour_departures')?>
	  	
	  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url('tours/departure/create/'.$tour['id'])?>">
  	        <span class="fa fa-arrow-circle-right"></span>
  	        <?=lang('create_btn_tour_departure')?>
	  	</a>

	  </div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('tours_field_departing_from')?></th>
				<th><?=lang('tours_field_departure_date_type')?></th>				
				<th><?=lang('field_last_modified')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
            <?php foreach ($tour_departures as $k => $departure):?>
            <tr>
                <td>
                    <?=$offset + $k + 1?>
					<?=get_order_arrow($departure, $max_pos, $min_pos, MODULE_TOUR_DEPARTURE, '', $tour_departures)?>
                </td>
                <td><?=$departure['name']?></td>
                <td>
                    <?php
                        foreach ($tour_departure_date_type as $key => $value) {
                            if($key == $departure['departure_date_type']) {
                                echo lang($value);
                                break;
                            }
                        }
                    ?>
                </td>
                <td>
                    <?=get_last_update($departure['date_modified'], $departure['last_modified_by'])?>
                </td>
                <td>
                    <a href="<?=site_url('tours/departure/edit/'.$departure['id'])?>"><span class="fa fa-edit"></span></a>
            		<a href="<?=site_url('tours/departure/delete/'.$departure['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
            			<span class="fa fa-times"></span>
            		</a>
                </td>
            </tr>
            <?php endforeach;?>
		</tbody>
    </table>
    
</div>