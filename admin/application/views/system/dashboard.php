<div class="well well-sm">
    <form role="form" name="frm" method="post">
    	<div class="row">
            <div class="col-xs-2" id="group_start_date">
                <label>Start Date</label>
                <div class="input-append date input-group">
        		  <input type="text" class="form-control input-sm" id="start_date" name="start_date" 
        		      value="<?=set_value('start_date', !empty($search_criteria['start_date']) ? $search_criteria['start_date'] : '')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
        		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
        		</div>
            </div>
            <div class="col-xs-2" id="group_end_date">
                <label>End Date</label>
                <div class="input-append date input-group">
        		  <input type="text" class="form-control input-sm" id="end_date" name="end_date" 
        		      value="<?=set_value('end_date', !empty($search_criteria['end_date']) ? $search_criteria['end_date'] : '')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
        		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
        		</div>
            </div>
            <div class="col-xs-2">
                <label>Status</label>
                <select name="booking_status" class="form-control input-sm">
                    <option value="0">---</option>
                    <?php foreach ($booking_status as $val => $status):?>
                    <option value="<?=$val?>" <?=set_select('booking_status', $val, !empty($search_criteria['booking_status']) && in_array($val, $search_criteria['booking_status']))?>><?=$status?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="col-xs-2 hide">
                <label>No. of return</label>
                <select name="number_of_return" class="form-control input-sm">
                    <option value="0">---</option>
                    <?php for ($i = 1; $i <= 5; $i++):?>
                    <option value="<?=$i?>" <?=set_select('number_of_return', $i, !empty($search_criteria['duplicated_cb']) && $search_criteria['duplicated_cb'] == $i)?>>> <?=$i?></option>
                    <?php endfor;?>
                </select>
            </div>
            <div class="col-xs-1">
                <label>&nbsp;</label><br>
                <button type="submit" name="action" value="apply" class="btn btn-primary btn-sm btn-block">
                <span class="fa fa-search"></span> Apply
                </button>
            </div>
    	</div>
    </form>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        List of customers (<?=count($bookings)?>) 
        <span class="pull-right" style="font-size: 12px; color: #666; font-style: italic;">Page rendered in <strong>{elapsed_time}</strong> seconds</span>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th nowrap="nowrap">Booking No.</th>
                <th nowrap="nowrap">Name</th>
                <th nowrap="nowrap">Service Name</th>
                <th nowrap="nowrap">Description</th>
                <th nowrap="nowrap">PNR</th>
                <th nowrap="nowrap">NET</th>
                <th nowrap="nowrap">SELL</th>
                <th nowrap="nowrap">Profit</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($bookings)):?>
        
            <?php foreach ($bookings as $k => $booking):?>
            <?php
                $service_name = '';
                $flight_pnr = '';
                
                $is_flight = false;
                
                foreach ($booking['service_reservations'] as $service) {
                    $service_name .= $service['service_name'].'<br>';
                    
                    if(!empty($service['flight_pnr'])) {
                        $flight_pnr = $service['flight_pnr'];
                    }
                    
                    if($service['reservation_type'] == RESERVATION_TYPE_FLIGHT) {
                        $is_flight = true;
                    }
                } 
                
                if(!$is_flight) continue;
            ?>
            <tr>
                <td><?=$booking['id']?></td>
                <td><?=$booking['full_name']?></td>
                <td><?=$service_name?></td>
                <td><?=$booking['description']?></td>
                <td><?=$flight_pnr?></td>
                <td><?=number_format($booking['net_price'])?></td>
                <td><?=number_format($booking['selling_price'])?></td>
                <td><?=number_format($booking['profit'])?></td>
            </tr>
            <?php endforeach;?>
        <?php else:?>
            <tr>
                <td colspan="12" class="text-center">No Customer Found!</td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>

<script>
$('#group_start_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});
$('#group_end_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});
</script>