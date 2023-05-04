<?php

$message	= '';
switch ($this->arrParam['type']) {
	case 'register-success':
		$message	= 'Tài khoản của bạn đã được tạo thành công. Xin vui lòng chờ kích hoạt từ người quản trị!';
		break;
	case 'not-permission':
		$message	= 'Bạn không có quyền truy cập vào chức năng đó!';
		break;
	case 'not-url':
		$message	= 'Đường dẫn không hợp lệ!';
		break;
}

$linkTrangChu = URL::createLink('frontend', 'index', 'index');
?>


<?php	
$xhtml='';
if ($this->arrParam['type'] != 'register-success') {
	$xhtml = '<section class="p-0">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="error-section">
					<h1>404</h1>
					<h2>'.$message.'</h2>
					<a href=" '.$linkTrangChu.'" class="btn btn-solid">Quay lại trang chủ</a>
				</div>
			</div>
		</div>
	</div>
</section>
';
}
?>

<?= $xhtml?>
<div class="phonering-alo-phone phonering-alo-green phonering-alo-show" id="phonering-alo-phoneIcon">
	<div class="phonering-alo-ph-circle"></div>
	<div class="phonering-alo-ph-circle-fill"></div>
	<a href="tel:0905744470" class="pps-btn-img" title="Liên hệ">
		<div class="phonering-alo-ph-img-circle"></div>
	</a>
</div>