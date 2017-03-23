<div class="modal-dialog tour-modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=lang('tour_itineraries_select_photos')?></h4>
		</div>
		<div class="modal-body">
			<form action="<?=site_url().'/tours/tour_photos/save_tour_photos/'?>" id="photoForm">
			<input type="hidden" value="<?=$tour['id']?>" id="tour_id">
			<ul class="itinerary-photo">

			<?php if(!empty($cruise_photos)):?>
			<?php foreach ($cruise_photos as $photo):?>
			<li class="i-thumb">
				<img class="h-photo" id="it_<?=$photo['id']?>"
					src="<?=get_static_resources('/images/cruises/uploads/'.$photo['name'])?>">
				<span data-id="<?=$photo['id']?>" id="cb_<?=$photo['id']?>" 
					class="fa fa-check check-box <?= $photo['tour_id'] == $tour['id'] ? '' : 'hide'?>"></span>
			</li>
			<?php endforeach;?>
			<?php endif;?>
			
			</ul>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" onclick="save_itinerary_photos()">Save changes</button>
		</div>
	</div>
</div>

<script>
	$('.h-photo').click(function() {
		var id = '#cb_' + $(this).attr('id');
		id = id.replace('it_', '');

		if ( $(id).is(":visible")  ) {
			$(id).addClass('hide');
		} else {
			$(id).removeClass('hide');
		}
	});

	function save_itinerary_photos() {

		var url = $('#photoForm').attr( "action" );

		var tour_id = $('#tour_id').val();
		
		var photos = '';
		$('.check-box').each(function() {	
			if ( $(this).is(":visible")  ) {
				photos += $(this).attr('data-id') + ',';
			}
		});
		
		if (photos.length > 1) {
			photos = photos.substring(0, photos.length - 1);
		}
		
		$.ajax({
			url: url,
			type: "POST",
			data: { 'photo_ids': photos, 'tour_id': tour_id },
			success:function(value){
				$('#cruisePhotosModal').modal('hide');
				window.location.reload(true);
    		},
		});
	}
</script>