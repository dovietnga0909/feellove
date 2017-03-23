<?php if(empty($itinerary)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('tours/')?>" role="button">
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

<form class="form-horizontal" role="form" name="frm" method="post">
<input type="hidden" name="tour_id" value="<?=$itinerary['tour_id']?>">
<input type="hidden" name="itinerary_id" value="<?=$itinerary['id']?>">
<div class="form-group">
	<label for="title" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_title')?>: <?=mark_required()?></label>
	<div class="col-xs-6">
		<input type="text" class="form-control" name="title" value="<?=set_value('title', $itinerary['title'])?>">
		<?=form_error('title')?>
	</div>
</div>


<div class="form-group">
	<label for="tour_departures" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_tour_departures')?>: <?=mark_required()?></label>
	
	
	
	<?php if ($tour['departure_type'] == MULTIPLE_DEPARTING_FROM):?>
	<div class="col-xs-10" style="padding-left: 0">
    	<?php foreach ($tour_departures as $value):?>
    	<div class="col-xs-3 checkbox">
    	    <label>
    		<input type="checkbox" value="<?=$value['id']?>" name="tour_departures[]"
    			<?=set_checkbox('tour_departures[]', $value['id'], in_array($value['id'], $itinerary['tour_departures']))?>>
    		<?=$value['name']?>
    		</label>
        </div>
    	<?php endforeach;?>
    	
    	<div class="col-xs-12">
    	   <?=form_error('tour_departures[]')?>
    	</div>
    </div>
	<?php else:?>
	<div class="col-xs-10">
        <?php foreach ($tour_departures as $value):?>
        <div class="checkbox">
        <input type="hidden" value="<?=$value['id']?>" name="tour_departures[]" checked="checked"><?=$value['name']?>
        </div>
        <?php endforeach;?>
        
        <div class="col-xs-12">
    	   <?=form_error('tour_departures[]')?>
    	</div>
    </div>
    <?php endif;?>
	
</div>

<div class="form-group">
	<label for="meals" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_meals')?>: <?=mark_required()?></label>
	
	<div class="col-xs-10" style="padding-left: 0">
    	<?php foreach ($tour_meals as $key=>$meal):?>
    	<div class="col-xs-3 checkbox">
            <label>
    		<input type="checkbox" value="<?=$key?>" name="meals[]" onclick="checkMeals(this)" 
    			<?=set_checkbox('meals[]', $key, in_array($key, explode('-', $itinerary['meals']))?TRUE:FALSE)?>>
    		<?=$meal?>
    		</label>
    	</div>
    	<?php endforeach;?>
    	
    	<div class="col-xs-12">
    	<?=form_error('meals[]')?>
    	</div>
	</div>
</div>
<div class="form-group">
	<label for="transportations" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_transportations')?>: </label>
	
	<div class="col-xs-10" style="padding-left: 0">
	    <?php foreach ($tour_transportations as $key=>$value):?>
    	<div class="col-xs-3 checkbox">
    	    <label>
    		<input type="checkbox" value="<?=$key?>" name="transportations[]" 
    			<?=set_checkbox('transportations[]', $key, in_array($key, explode('-', $itinerary['transportations']))?TRUE:FALSE)?>>
    		<?=$value?>
    		</label>
        </div>
    	<?php endforeach;?>
    	
    	<div class="col-xs-12">
    	<?=form_error('transportations[]')?>
    	</div>
	</div>
</div>
<div class="form-group">
	<label for="content" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_content')?>: <?=mark_required()?></label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="content"><?=set_value('content', $itinerary['content'])?></textarea>
		<?=form_error('content')?>
	</div>
</div>
<div class="form-group">
	<label for="accommodation" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_accommodation')?>:</label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="accommodation"><?=set_value('accommodation', $itinerary['accommodation'])?></textarea>
		<?=form_error('accommodation')?>
	</div>
</div>
<div class="form-group">
	<label for="activities" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_activities')?>:</label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="activities"><?=set_value('activities', $itinerary['activities'])?></textarea>
		<?=form_error('activities')?>
	</div>
</div>
<div class="form-group">
	<label for="notes" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_notes')?>:</label>
	<div class="col-xs-9">
		<textarea class="form-control rich-text" rows="8" name="notes"><?=set_value('notes', $itinerary['notes'])?></textarea>
		<?=form_error('notes')?>
	</div>
</div>
<div class="form-group">
	<label for="notes" class="col-xs-2 control-label"><?=lang('tour_itineraries_field_photos')?>:</label>
	<div class="col-xs-9">
		<input type="hidden" name="photos" id="itinerary_photos" value="<?=set_value('photos', $itinerary['photos'])?>">
		<a data-toggle="modal" href="<?=site_url('/tours/itinerary/photos/'.$itinerary['tour_id'])?>" data-target="#myModal"><?=lang('tour_itineraries_select_photos')?></a>
	</div>
	<?=form_error('photos')?>
</div>
<div class="row">
	<div class="col-xs-offset-2 col-xs-6">
		<button type="submit" class="btn btn-primary">
			<span class="fa fa-download"></span>
			<?=lang('btn_save')?>
		</button>
		<a class="btn btn-default mg-left-10" href="<?=site_url('tours/itinerary/'.$itinerary['tour_id'])?>" role="button"><?=lang('btn_cancel')?></a>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title"><?=lang('tour_itineraries_select_photos')?></h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

</form>
<script type="text/javascript">
	init_text_editor();

	$('#myModal').on('shown.bs.modal', function (e) {
		init_photos();
	})
			
	
	function checkMeals(ele) {
		if($(ele).is(':checked')) {
			console.log($(ele).val());
			if($(ele).val() == 5) {
				$('input[name="meals[]"]').each(function(index) {	
					if($(this).val() != 5) {
						$(this).attr('checked', false);
					}
				});
			} else {
				$('input[name="meals[]"][value="5"]').attr('checked', false);
			}
		}
	}
</script>
<?php endif;?>