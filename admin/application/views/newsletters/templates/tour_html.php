<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width"/>
	<style>
	/* Client-specific Styles & Reset */
	
	#outlook a { 
	  padding:0; 
	} 

	body{ 
	  width:100% !important; 
	  min-width: 100%;
	  -webkit-text-size-adjust:100%; 
	  -ms-text-size-adjust:100%; 
	  margin:0; 
	  padding:0;
	}
	
	img { 
	  outline:none; 
	  text-decoration:none; 
	  -ms-interpolation-mode: bicubic;
	  width: auto;
	  max-width: 100%;
	}
	
	a img { 
	  border: none;
	}
	
	p {
	  margin: 0 0 0 10px;
	}
	
	table {
	  border-spacing: 0;
	  border-collapse: collapse;
	  width: 100%;
	  height: 100%;
	  empty-cells: show;
	}
	
	td { 
	  word-break: break-word;
	  -webkit-hyphens: auto;
	  -moz-hyphens: auto;
	  hyphens: auto;
	  border-collapse: collapse !important; 
	}
	
	table, tr, td {
	  padding: 0;
	  vertical-align: middle;
	  text-align: left;
	}
	
	a { color: #3385d6; text-decoration: none; }
	a:link, a:visited, a:active { text-decoration: none; }
	a:hover { text-decoration: underline; color: #FE8802 }

	/* Typography */
	
	body, table.body, h1, h2, h3, h4, h5, h6, p, td { 
	  color: #333;
	  font-family: "Arial"; 
	  font-weight: normal; 
	  padding:0; 
	  margin: 0;
	  text-align: left; 
	  line-height: 1.3;
	}
	
	h1, h2 {
	  word-break: normal;
	  font-weight: bold;
	  padding: 0;
	  color: #3385d6;
	  margin: 0
	}

	h1 {font-size: 18px; padding-top: 10px;}
	h2 {font-size: 14px; padding-top: 5px}
	body, table.body, p, td {font-size: 12px;line-height:18px;}
	
	.center { text-align: center; }
	.right { text-align: right; }
	.left { text-align: left; }
	.middle { vertical-align: middle; }

	/* Outlook First */
	
	body.outlook p {
	  display: inline !important;
	}
	
	.container { margin: 0 auto; width: 720px; }
	.divider { border-top: 3px solid #FE8802; }
	.btn-book {
		font-size: 14px;
		padding: 5px 10px;
		background-color: #fca903;
		border-bottom: 3px solid #ff9100;
		color: #fff;
		font-weight: normal;
		border-radius: 3px;
	}
	.btn-book:hover { color: #fff; text-decoration: none; }
	.name { color: #333; font-weight: bold; }
	
  </style>
</head>
<body>
	<table class="container" style="margin: 0 auto; width: 700px;">
		<tr>
			<td>
				<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin: 0; padding: 0;">
					<tr>
						<td>
							<img src="http://www.Bestviettravel.xyz/media/bestpricevn-logo.31052014.png" height="54"/>
						</td>
						<td style="vertical-align: middle;">
							<ul style="list-style: none; float: right; padding: 0; margin: 0">
								<li>
									<a href="https://www.facebook.com/BestPricevn">
										<img src="<?=$resource_path?>images/newsletters/facebook.jpg"></img>
									</a>
									<a href="https://plus.google.com/u/1/b/104363857518456717813/104363857518456717813/posts">
										<img src="<?=$resource_path?>images/newsletters/google_plus.jpg"></img>
									</a>
									<a href="https://twitter.com/bestprice_vn">
										<img src="<?=$resource_path?>images/newsletters/twitter.jpg"></img>
									</a>
								</li>
								<li>Ngày: <?=date(DB_DATE_FORMAT)?></li>
								<li>Xem phiên bản trên web <a href="#">tại đây</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><h1 style="word-break: normal; font-weight: bold; font-size: 30px; padding: 0 0 5px; color: #3385d6; margin: 0; border-bottom:3px solid #fe8802"><?=$name?></h1></td>
		</tr>
		
		<tr>
			<td style="padding: 0 10px">
				<table>
					<?php 
						$promotion_full_id = array();
						foreach($promotion_full as $full){
							$promotion_full_id[] = $full['is_outbound'];
						}
						
						$is_outbound = in_array(1, $promotion_full_id);
						$is_domestic = in_array(0, $promotion_full_id);
					?>
					<?php if($is_domestic):?>
						<tr>
							<td colspan="3"><h2 style="word-break: normal; font-weight: bold; padding-top: 5px; color: #3385d6; margin: 0;  font-size: 20px;">Tour trong nước nổi bật</h2></td>
						</tr>
					<?php endif;?>
					
					<?php foreach($promotion_full as $key =>$promotion):?>
						<?php if($promotion['is_outbound'] ==0):?>
						<tr>
							<td colspan="3" style="padding-top: 5px">
								<img width="650" height="200" src="<?=get_image_path(TOUR, $promotion['picture'], '160x120')?>"/>
							</td>
						</tr>
						<tr style="border-bottom: 1px dotted #FE8802;">
							<td width="50%" style="padding-top: 10px;">
								<a class="name" href="<?=tour_build_url($promotion)?>"><?=$promotion['tour_name']?></a><br>
								<div style="color: #FE8802"><?=$promotion['name']?></div>
							</td>
							<td>
								<b>Giá gốc <?php if(isset($promotion['price_origin'])):?><?=bpv_format_currency($promotion['price_origin'])?><?php endif;?></b><br>
								<div style="color: #FE8802; font-weight: bold; font-size: 14px">Chỉ còn <?php if(isset($promotion['price_from'])):?><?=bpv_format_currency($promotion['price_from'])?><?php endif;?></div>
							</td>
							<td>
								<a href="<?=tour_build_url($promotion)?>" class="btn-book" style="text-decoration: none; font-size: 14px; padding: 5px 10px; background-color: #fca903; border-bottom: 3px solid #ff9100; color: #fff; font-weight: normal; border-radius: 3px;"><?=lang('btn_book_now')?></a>
							</td>
						</tr>
						<?php endif;?>
					<?php endforeach;?>
					<?php if($is_domestic):?>
						<tr style="border-bottom: 2px solid #fe8802;">
							<td></td>
						</tr>
					<?php endif;?>
					<?php if($is_outbound):?>
					<tr>
						<td colspan="3"><h2 style="word-break: normal; font-weight: bold; padding-top: 5px; color: #3385d6; margin: 0; font-size:20px;">Tour nước ngoài nổi bật</h2></td>
					</tr>
					<?php endif;?>
					
					<?php foreach($promotion_full as $key =>$promotion):?>
						<?php if($promotion['is_outbound'] ==1):?>
							<tr>
								<td colspan="3" style="padding-top: 5px">
									<img width="650" height="200" src="<?=get_image_path(TOUR, $promotion['picture'], '160x120')?>"/>
								</td>
							</tr>
							<tr style="border-bottom: 1px dotted #FE8802;">
								<td width="50%" style="padding-top: 10px;">
									<a class="name" href="<?=tour_build_url($promotion)?>"><?=$promotion['tour_name']?></a><br>
									<div style="color: #FE8802"><?=$promotion['name']?></div>
								</td>
								<td>
									<b>Giá gốc <?php if(isset($promotion['price_origin'])):?><?=bpv_format_currency($promotion['price_origin'])?><?php endif;?></b><br>
									<div style="color: #FE8802; font-weight: bold; font-size: 14px">Chỉ còn <?php if(isset($promotion['price_from'])):?><?=bpv_format_currency($promotion['price_from'])?><?php endif;?></div>
								</td>
								<td>
									<a href="<?=tour_build_url($promotion)?>" class="btn-book" style="text-decoration: none; font-size: 14px; padding: 5px 10px; background-color: #fca903; border-bottom: 3px solid #ff9100; color: #fff; font-weight: normal; border-radius: 3px;"><?=lang('btn_book_now')?></a>
								</td>
							</tr>
							
						<?php endif;?>
					<?php endforeach;?>
				</table>
			</td>
		</tr>

		<tr>
			<td style="padding-bottom: 20px; padding-top: 20px; text-align:center;">Nếu quý khách không muốn nhận email Newsletter, xin vui lòng <a href="#">click vào đây</a></td>
		</tr>
		<tr>
			<td style="text-align:center;">
				<b>Công ty Cổ phần Công nghệ Du lịch Bestprice</b><br>
				<b>Văn phòng Hà Nội:</b> <span>12A, Ngõ Bà Triệu, Phố Bà Triệu, Quận Hai Bà Trưng, Hà Nội.</span><br/>
				<b>Văn phòng Hồ Chí Minh:</b> <span>Phòng 102, Tòa nhà DMC, 223 Điện Biên Phủ, Quận Bình Thạnh, Hồ Chí Minh.</span><br/>
				Điện thoại:  (04) 3978 1425 / 0936.129.428 <br/>
				Website: <a href="http://www.Bestviettravel.xyz">http://www.Bestviettravel.xyz</a><br>
				Email: <a href="mailto:sale@Bestviettravel.xyz">sale@Bestviettravel.xyz</a><br>
			</td>
		</tr>
	</table>
</body>
</html>