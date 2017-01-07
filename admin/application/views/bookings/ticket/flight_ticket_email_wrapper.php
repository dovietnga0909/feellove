<html>
<head>
	<meta charset="utf-8">
</head>
<body style="font-family: Arial;font-size:12px;padding:10px;">

	<p>Kính gửi quý khách <?=ucwords(strtolower($contact['full_name']))?>,</p>
    <p>Chúng tôi đã tiến hành xuất vé theo <b>mã đơn hàng <?=$cb['id']?></b> của quý khách. Vé điện tử được gửi theo file đính kèm. Xin quý vui lòng mang theo vé điện tử này để làm thủ tục tại sân bay.</p>
    <p>Nếu cần bất kỳ sự hỗ trợ nào, xin quý khách vui lòng liên hệ với chúng tôi theo <b style="color: red">Hotline: <?=$cb['hotline_number']?> - <?=$cb['hotline_name']?></b></p>
    <div style="border: 1px solid #DDD;padding:20px">
    	<?=$tform_content?>
    </div>
    
<p style="font-weight:bold">Nếu quý khách có nhu cầu <a href="http://www.Bestviettravel.xyz/khach-san/">phòng khách sạn</a>, <a href="http://www.Bestviettravel.xyz/tour/">tour du lịch</a>, <a href="http://www.Bestviettravel.xyz/du-thuyen-ha-long/">du thuyền Hạ Long</a>, xin quý khách vui lòng liên hệ lại với chúng tôi để nhận được những ưu đãi tốt nhất!</p>

<p>Trân trọng cảm ơn quý khách đã sử dụng dịch vụ của Best Price.</p>

<p>Chúc quý khách thượng lộ bình an!</p>

<?php 
$font_family = 'font-family: Arial;';
$line_text = 'clear:both;margin-bottom:3px;'.$font_family;
?>

<div style="<?=$line_text?>;font-size:14px;font-weight:bold;"><?=lang('tf_company_name')?></div>
<div style="<?=$line_text?>"><b>VP Hà Nội:</b> 12A, Ngõ Bà Triệu, Phố Bà Triệu, Quận Hai Bà Trưng, Hà Nội</div>
<div style="<?=$line_text?>"><b>VP Sài Gòn:</b> P.102, Nhà DMC, 223 Điện Biên Phủ, Quận Bình Thạnh, Tp.Hồ Chí Minh</div>
<div style="<?=$line_text?>">Website: http://www.Bestviettravel.xyz</div>
<div style="<?=$line_text?>">Tel: (04) 3978 1425 - Fax: +84 4 3624-9007</div>
<div style="<?=$line_text?>"><b>Hotline:</b> <?=$cb['hotline_number']?> (<?=$cb['hotline_name']?>)</div>


</body>
</html>