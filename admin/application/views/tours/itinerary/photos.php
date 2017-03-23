<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?=lang('tour_itineraries_select_photos')?></h4>
</div>
<div class="modal-body">
	<ul class="itinerary-photo">
	<?php if(!empty($tour_photos)):?>
	<li class="title"><?=lang('tour_itineraries_tour_photos')?></li>
	<?php foreach ($tour_photos as $photo):?>
	<li class="i-thumb">
		<img class="h-photo" id="it_<?=$photo['id']?>"
			src="<?=get_static_resources('/images/tours/uploads/'.$photo['name'])?>">
		<span data-id="<?=$photo['id']?>" id="cb_<?=$photo['id']?>" class="fa fa-check check-box hide"></span>
	</li>
	<?php endforeach;?>
	<?php endif;?>
	
	<?php if(!empty($cruise_photos)):?>
	<li class="title"><?=lang('tour_itineraries_cruise_photos')?></li>
	<?php foreach ($cruise_photos as $photo):?>
	<li class="i-thumb">
		<img class="h-photo" id="it_<?=$photo['id']?>"
			src="<?=get_static_resources('/images/cruises/uploads/'.$photo['name'])?>">
		<span data-id="<?=$photo['id']?>" id="cb_<?=$photo['id']?>" class="fa fa-check check-box hide"></span>
	</li>
	<?php endforeach;?>
	<?php endif;?>
	</ul>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-primary" onclick="save_itinerary_photos()">Save changes</button>
</div>
<script>
	init_photos();
	
	function init_photos() {
		var it_photos = $('#itinerary_photos').val();
		
		if(it_photos != '') {
			var photo_ids = it_photos.split('-');

			for (var i = 0; i < it_photos.length; i++) {
				$('#cb_'+photo_ids[i]).removeClass('hide');
			}
		} else {
			/*
			$('.check-box').each(function() {	
				if ( $(this).is(":visible")  ) {
					$(this).addClass('hide');
				} else {
					$(this).removeClass('hide');
				}
			});
			*/
		}
	}

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
		var photos = '';
		$('.check-box').each(function() {	
			if ( $(this).is(":visible")  ) {
				photos += $(this).attr('data-id') + '-';
			}
		});
		
		if (photos.length > 1) {
			photos = photos.substring(0, photos.length - 1);
		}
		
		$('#itinerary_photos').val(photos);
		$('#myModal').modal('hide');
	}
</script>