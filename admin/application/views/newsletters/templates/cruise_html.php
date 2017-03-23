<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width"/>
</head>
<body>
<?php 
	if(isset($promotion_full)):
?>

	<table width="610" align="center" cellpadding="0">
		<style type="text/css">
			.img-center{
				text-align:center;
			}
			span.bpv-title{
				font-size:16px;
				font-weight:bold;
				margin:5px 0;
				display:block;
				color:#FC5E0C;
			}
			.btn-book {
				font-size: 14px;
				padding: 5px 10px;
				background-color: #fca903;
				border-bottom: 3px solid #ff9100;
				color: #fff;
				font-weight: normal;
				border-radius: 3px;
			}
			.btn-book:hover {color: #fff; text-decoration: none;}
		</style>
		
		<tbody>
			<tr>
				<td bgcolor="#FFFFFF">
					<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin: 0; padding: 0;">
						<tr>
							<td>
								<img src="http://www.snotevn.com:8888/media/bestpricevn-logo.31052014.png" height="54"/>
							</td>
							<td>
								<b>DATE: <?=date(DB_DATE_FORMAT)?></b>
							</td>
						</tr>
					</table>
					<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin:5px 0; padding: 0;">
						<tr>
							<td>
								<h3 style="color:#FF0000; font-size:16px"><?=$name?></h3>
							</td>
						</tr>
						<tr>
							<td>
								<img id="nguyenson-1" width="100%" height="250" start-photo-1 src="http://www.snotevn.com:8888/images/hotels/uploads/terrace-resort-5337e0fed453d.JPG" end-photo-1/>
							</td>
						</tr>
					</table>
					<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin: 5px 0; padding: 0;">
						<?php foreach($promotion_full as $key =>$promotion):?>
						
						<tr>
							<td width="180">
								<img style="display: block; margin-left: auto; margin-right: auto;" src="<?=get_image_path(CRUISE, $promotion['picture'], '160x120')?>">
							</td>
							<td>
								<p style="margin:0 5px;">
									<span style="font-size:14px; font-weight:bold; margin:5px 0; display:block; color:#2795B6"><b><?=$promotion['tour_name']?></b> <img src="<?=$resource_path?>media/icon/star<?=$promotion['star']?>.png" /></span>
									<span style="font-size:14px; display: block; font-weight:bold;"><?=$promotion['name']?></span></br>
									<?php if(isset($promotion['price_origin'])):?><span style="font-size:15px;">Giá gốc: </span><span style="font-size:15px; text-decoration: line-through"><?=bpv_format_currency($promotion['price_origin'])?></span></br><?php endif;?>
									<?php if(isset($promotion['price_from'])):?><span style="font-size:15px; font-weight:bold; margin:5px 0; display:block; color:#FC5E0C;">Chỉ còn :<?=bpv_format_currency($promotion['price_from'])?><small style="font-size: 13px; margin-bottom: 10px; color:#FC5E0C;"><?=lang('/pax')?></small></span><?php endif;?>
									<a href="<?=cruise_build_url($promotion)?>" class="btn-book" style="text-decoration: none; font-size: 14px; padding: 5px 10px; background-color: #fca903; border-bottom: 3px solid #ff9100; color: #fff; font-weight: normal; border-radius: 3px;"><?=lang('btn_book_now')?></a>
								</p>
							</td>
						</tr>
						<?php endforeach;?>
					</table>
					<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin: 5px 0; padding: 0;">
						<tr>
							<td><img id="nguyenson-2" width="100%" height="250" start-photo-2 src="http://www.snotevn.com:8888/images/hotels/uploads/terrace-resort-5337e0fed453d.JPG" end-photo-2/></td>
						</tr>
					</table>
					<table style="cell-padding: 0; border-collapse: collapse; border-spacing: 0; vertical-align: top; text-align: left; height: 100%; width: 100%; font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 12px; margin: 5px 0; padding: 0;">
						<tr>
							<td>
							<p>Nếu bạn không đọc được email này, vui lòng click <a href="#">ở đây</a><br/>
							Nếu quý khách không muốn nhận email Newsletter, xin vui lòng <a href="#">click vào đây</a>
							<a href="https://www.facebook.com/BestPricevn"><img width="24" height="24" alt="facebook bestprice" title="facebook bestprice" src="<?=$resource_path?>media/icon/icon_fb.png" /></a>
							<a href="https://twitter.com/bestprice_vn"><img width="24" height="24" alt="twiter bestprice" title="facebook bestprice" src="<?=$resource_path?>media/icon/icon_plus.png"/></a>
							<a href="https://plus.google.com/u/1/b/104363857518456717813/104363857518456717813/posts"><img width="24" height="24" alt="google plus bestprice" title="google plus bestprice" src="<?=$resource_path?>media/icon/icon_tw.png"/></a>
							
							</p>
							</td>
						</tr>
						<tr>
							<td>
							<p style="text-align:center">
								<b>Công ty Cổ phần Công nghệ Du lịch Bestprice</b><br>
								<b>Văn phòng Hà Nội:</b> <span>12A, Ngõ Bà Triệu, Phố Bà Triệu, Quận Hai Bà Trưng, Hà Nội.</span><br/>
								<b>Văn phòng Hồ Chí Minh:</b> <span>Phòng 102, Tòa nhà DMC, 223 Điện Biên Phủ, Quận Bình Thạnh, Hồ Chí Minh.</span><br/>
								<span style="color: rgb(51, 51, 51); font-family: Arial; font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 15.7142858505249px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none; background-color: rgb(255, 255, 255)">
									Điện thoại:  (04) 3978 1425 <br/>
									Website: <a href="http://www.snotevn.com:8888"> http://www.snotevn.com:8888</a> <br>
									Email: <a href="mailto:sale@snotevn.com:8888">sale@snotevn.com:8888</a>
								</span>
							</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif;?>
</body>
</html>