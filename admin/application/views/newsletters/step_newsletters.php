<div class="well well-sm">
	<ul class="nav nav-pills">
	  <li class="<?=get_promotion_step_class(1, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_newsletter_step_link(1, $current_step, isset($pro)? $pro : '')?>"><?=lang('newsletter_step_1')?></a></li>
	  <li class="<?=get_promotion_step_class(2, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_newsletter_step_link(2, $current_step, isset($pro)? $pro : '')?>"><?=lang('newsletter_step_2')?></a></li>
	  <li class="<?=get_promotion_step_class(3, $current_step, isset($pro)? $pro : '')?>"><a href="<?=get_newsletter_step_link(3, $current_step, isset($pro)? $pro : '')?>"><?=lang('newsletter_step_3')?></a></li>
	</ul>
</div>