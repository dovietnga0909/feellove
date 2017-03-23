<div class="well well-sm">
	<ul class="nav nav-pills">
	  <li class="<?=get_promotion_step_class(1, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_promotion_step_link(1, $current_step, $tour_id, isset($pro)? $pro : '', TOUR)?>"><?=lang('pro_step_1')?></a></li>
	  <li class="<?=get_promotion_step_class(2, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_promotion_step_link(2, $current_step, $tour_id, isset($pro)? $pro : '', TOUR)?>"><?=lang('pro_step_2')?></a></li>
	  <li class="<?=get_promotion_step_class(3, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_promotion_step_link(3, $current_step, $tour_id, isset($pro)? $pro : '', TOUR)?>"><?=lang('pro_step_3')?></a></li>
	  <li class="<?=get_promotion_step_class(4, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_promotion_step_link(4, $current_step, $tour_id, isset($pro)? $pro : '', TOUR)?>"><?=lang('pro_step_4')?></a></li>
	  <li class="<?=get_promotion_step_class(5, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_promotion_step_link(5, $current_step, $tour_id, isset($pro)? $pro : '', TOUR)?>"><?=lang('pro_step_5')?></a></li>
	</ul>
</div>