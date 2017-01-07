<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<title><?=$site_title?> - Best Price Vietnam</title>
		
		<?php $css = isset($lib_css) ? $lib_css : null;?><?php $js = isset($lib_js) ? $lib_js : null;?>
		<?php $p_css = isset($page_css) ? $page_css : null;?><?php $p_js = isset($page_js) ? $page_js : null;?>
		<?=get_core_theme($css, $js, $p_css, $p_js)?>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	    <![endif]-->
	</head>
	
	<?php $this->load->view('_layouts/header')?>

    <div class="container">	
    	<!-- Layout with the Menu on the Left -->
    	<?php if(isset($nav_panel)):?>
    	
    		<div class="row">
    			
    			<div class="col-md-3">
					<div class="bpv-sidebar hidden-print affix" role="complementary" style="width:100%">
						<ul class="nav bpv-sidenav">
							<?php foreach ($nav_panel as $k => $mnu):?>
							<li <?php echo (isset($side_mnu_index) && $side_mnu_index == $k) ? 'class="active"' : ''?>>
								<a href="<?=site_url($mnu['link']).'/'?>"><?=lang($mnu['title'])?>
								<?php if(isset($mnu['icon'])):?>
								<span class="fa <?=$mnu['icon']?>"></span>
								<?php endif;?>
								</a>
							</li>
							<?php endforeach;?>
						</ul>
					</div>
					<div id="navToggle"></div>
				</div>
				
				<div class="col-md-9">
					
					<?php if(isset($site_title)):?>
					<div class="page-header">
			       		<h3><?=$site_title?></h3>
			        </div>
			        <?php endif;?>
					<div class="page-content">
						<?=get_message()?>			
						<?php echo isset($search_frm) ? $search_frm : '';?>										
						<?=$content?>
					</div>
						
				</div>
			</div>
    		
    	
    	<?php else:?>
    		
    		<!-- Layout with no Menu -->
    		<?php if(isset($site_title)):?>
    		<div class="page-header">
	       		<h3><?=$site_title?></h3>
	        </div>
	        <?php endif;?>
			<div class="page-content">			
				<?=get_message()?>
				
    			<?php echo isset($search_frm) ? $search_frm : '';?>								
				<?=$content?>
			</div>
    		
    	<?php endif;?>		
		
    </div> <!-- /container -->
	
	<?php $this->load->view('_layouts/footer')?>    
   
</html>
