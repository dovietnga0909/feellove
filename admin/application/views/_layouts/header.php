<header role="banner">
	<div class="navbar bpv-header">
		<div class="container">
			<div class="navbar-nav">
				<a class="brand" href="<?=site_url()?>"><img style="height:40px" src="<?=site_url()?>media/bestpricevn-logo.png"></a>
			</div>
			<?php if(isset($hotel) && is_hotel_detail()):?>
			<div class="hotel-info">
				<h3><?=$hotel['name']?> <a href="<?=site_url().'hotels/'?>"><?=lang('back_to_admin_page')?></a></h3>
			</div>
			<?php elseif(isset($cruise)):?>
			<div class="hotel-info">
				<h3><?=$cruise['name']?> <a href="<?=site_url('cruises')?>"><?=lang('back_to_admin_page')?></a></h3>
			</div>
			<?php elseif(isset($tour)):?>
			<div class="hotel-info">
				<h3><?=$tour['name']?> <a href="<?=site_url('tours')?>"><?=lang('back_to_admin_page')?></a></h3>
			</div>
			<?php endif;?>
			<div class="navbar-right">
				<div class="user-info">
					<?=lang('welcome')?><label><?=get_username()?></label>
					(<a href="<?=site_url('change_password')?>"><?=lang('change_password')?></a>
					<a href="<?=site_url('logout')?>" class="sign-out"><?=lang('sign_out')?></a>)
				</div>
			</div>				
		</div>
	</div>

    <div class="navbar navbar-default navbar-static-top bpv-nav" role="navigation">
      <div class="container">      		        
        <div class="navbars">
          <ul class="nav navbar-nav">
          	<?php if( isset($hotel) && is_hotel_detail() ):?>
          		<li <?=get_selected_menu(MNU_HOTEL_PROFILE)?>><a href="<?=site_url().'hotels/profiles/'.$hotel['id']?>"><?=lang('mnu_profile')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL_RATE_AVAILABILITY)?>><a href="<?=site_url().'hotels/rates/'.$hotel['id']?>"><?=lang('mnu_rate_availability')?></a></li>
				
          		<li <?=get_selected_menu(MNU_HOTEL_SURCHARGE)?>><a href="<?=site_url().'hotels/surcharges/'.$hotel['id']?>"><?=lang('mnu_surcharges')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL_PROMOTION)?>><a href="<?=site_url().'hotels/promotions/'.$hotel['id']?>"><?=lang('mnu_promotions')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL_REVIEWS)?>><a href="<?=site_url().'hotels/reviews/'.$hotel['id']?>"><?=lang('mnu_reviews')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL_PARTNER)?>><a href="<?=site_url().'hotels/partner/edit/'.$hotel['id']?>"><?=lang('mnu_partners')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL_CONTRACT)?>><a href="<?=site_url().'hotels/contracts/'.$hotel['id']?>"><?=lang('mnu_contract')?></a></li>
			<?php elseif( isset($cruise) && is_cruise_detail() ):?>
				<li <?=get_selected_menu(MNU_CRUISE_PROFILE)?>><a href="<?=site_url('cruises/profiles/'.$cruise['id'])?>"><?=lang('mnu_profile')?></a></li>
				
				<li <?=get_selected_menu(MNU_CRUISE_SURCHARGE)?>><a href="<?=site_url().'cruises/surcharges/'.$cruise['id']?>"><?=lang('mnu_surcharges')?></a></li>
				
				<li <?=get_selected_menu(MNU_CRUISE_PROMOTION)?>><a href="<?=site_url().'cruises/promotions/'.$cruise['id']?>"><?=lang('mnu_promotions')?></a></li>
				
				<li <?=get_selected_menu(MNU_CRUISE_REVIEWS)?>><a href="<?=site_url().'cruises/reviews/'.$cruise['id']?>"><?=lang('mnu_reviews')?></a></li>
			
				<li <?=get_selected_menu(MNU_CRUISE_CONTRACT)?>><a href="<?=site_url().'cruises/contracts/'.$cruise['id']?>"><?=lang('mnu_contract')?></a></li>
			<?php elseif( isset($tour) && is_tour_detail() ):?>
				<li <?=get_selected_menu(MNU_TOUR_PROFILE)?>><a href="<?=site_url('tours/profiles/'.$tour['id'])?>"><?=lang('mnu_profile')?></a></li>
				
				<li <?=get_selected_menu(MNU_TOUR_RATE_AVAILABILITY)?>><a href="<?=site_url().'tours/rates/'.$tour['id']?>"><?=lang('mnu_rate_availability')?></a></li>
				
				<li <?=get_selected_menu(MNU_TOUR_PROMOTION)?>><a href="<?=site_url().'tours/promotions/'.$tour['id']?>"><?=lang('mnu_promotions')?></a></li>
	      
				<li <?=get_selected_menu(MNU_TOUR_REVIEWS)?>><a href="<?=site_url().'tours/reviews/'.$tour['id']?>"><?=lang('mnu_reviews')?></a></li>
				
				<li <?=get_selected_menu(MNU_TOUR_CONTRACT)?>><a href="<?=site_url().'tours/contracts/'.$tour['id']?>"><?=lang('mnu_contract')?></a></li>
			<?php else:?>
	        	
				<li <?=get_selected_menu(MNU_BOOKING)?>><a href="<?=site_url().'bookings/'?>"><?=lang('mnu_bookings')?></a></li>
				
				<li <?=get_selected_menu(MNU_HOTEL)?>><a href="<?=site_url().'hotels/'?>"><?=lang('mnu_hotels')?></a></li>
				
				<li <?=get_selected_menu(MNU_PARTNER)?>><a href="<?=site_url().'partners/'?>"><?=lang('mnu_partners')?></a></li>
				
				<li <?=get_selected_menu(MNU_DESTINATION)?>><a href="<?=site_url().'destinations/'?>"><?=lang('mnu_destinations')?></a></li>
				
				<li <?=get_selected_menu(MNU_FLIGHTS)?>><a href="<?=site_url().'flights/'?>"><?=lang('mnu_flights')?></a></li>
				
				<li <?=get_selected_menu(MNU_CRUISES)?>><a href="<?=site_url().'cruises/'?>"><?=lang('mnu_cruises')?></a></li>
				
				<li <?=get_selected_menu(MNU_TOURS)?>><a href="<?=site_url().'tours/'?>"><?=lang('mnu_tours')?></a></li>
				
				<li <?=get_selected_menu(MNU_CUSTOMER)?>><a href="<?=site_url().'customers/'?>"><?=lang('mnu_customers')?></a></li>
				
				<li <?=get_selected_menu(MNU_CANCELLATION)?>><a href="<?=site_url().'cancellations/'?>"><?=lang('mnu_cancellations')?></a></li>
				
				<li <?=get_selected_menu(MNU_ADVERTISE)?>><a href="<?=site_url().'advertises/'?>"><?=lang('mnu_advertises')?></a></li>
				
				<li <?=get_selected_menu(MNU_FACILITY)?>><a href="<?=site_url().'facilities/'?>"><?=lang('mnu_facilities')?></a></li>
				
				<li <?=get_selected_menu(MNU_NEWS)?>><a href="<?=site_url().'news/'?>"><?=lang('mnu_news')?></a></li>
				
				<li <?=get_selected_menu(MNU_MARKETING)?>><a href="<?=site_url().'marketings/'?>"><?=lang('mnu_marketings')?></a></li>
				
				<?php if(is_sale_manager() || is_admin()):?>
				
					<li <?=get_selected_menu(MNU_USER)?>>
						<a href="<?=site_url().'users/'?>"><?=lang('mnu_users')?></a>
					</li>
				
				<?php endif;?>
				
				<?php if(is_admin()):?>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=lang('mnu_system')?></a>
					
					<ul class="dropdown-menu">
			            <li <?=get_selected_menu(MNU_SYSTEM)?>><a href="<?=site_url('/system/')?>"><?=lang('mnu_cache')?></a></li>
			            <li class="divider"></li>
			            <li <?=get_selected_menu(MNU_DASHBOARD)?>><a href="<?=site_url('/dashboard/')?>"><?=lang('mnu_dashboard')?></a></li>
			            <li class="divider"></li>
			            <li <?=get_selected_menu(MNU_ROLE)?>><a href="<?=site_url('/roles/')?>"><?=lang('mnu_roles')?></a></li>
			            <li class="divider"></li>
			            <li <?=get_selected_menu(MNU_LOGS)?>><a href="<?=site_url('/logs/')?>"><?=lang('mnu_logs')?></a></li>
			          </ul>
				</li>
				<?php endif;?>
				
	        <?php endif;?>
            	
          </ul>
         
        </div><!--/.nav-collapse -->
      </div>
    </div>
</header>