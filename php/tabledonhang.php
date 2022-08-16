<?php
	require_once ("../BackEnd/ConnectionDB/DB_classes.php");
	session_start();

	$status = $_GET['status'];
	if (isset($_SESSION['currentUser'])) {

		$manguoidung = $_SESSION['currentUser']['MaND'];
	
		$sql="SELECT * FROM hoadon WHERE MaND=$manguoidung";
		$dsdh=(new DB_driver())->get_list($sql);

		if(sizeof($dsdh) > 0) {
			echo'<div class="my-bill">';
			forEach($dsdh as $index => $row) {
				$class = $index === 0 ? '-first' : '';
				$trangThaiDonHang = formatStatuses($row["TrangThai"]);
				echo '<div class="item '.$class.'">
					<div class="head">
						<span class="id">Mã đơn hàng: '.$row["MaHD"].'</span>
						<span class="time">Thời gian: '.$row["NgayLap"].'</span>
						<span class="status">'.$trangThaiDonHang.'</span>
					</div>
					<div class="content">
						<img src="img/logo.jpg" class="image">
						<div class="info">
							<p>Tên sản phẩm: </p>
							<p>Số lượng: </p>
							<p class="price">99999vnd </p>
						</div>
					</div>
				</div>
				<div class="contact">
					<div class="total">Tổng tiền: '.$row["TongTien"].'</div>
					<div class="action">
						<button class="buy">Mua lại</button>
						<button>Liên hệ người bán</button>
					</div>
				</div>
				';
			}
			echo '</div>';
		} else {
			echo '<h2 style="color:green; text-align:center;">
						Hiện chưa có đơn hàng nào, 
						<a href="index.php" style="color:blue">Mua ngay</a>
					</h2>';
		}
	}

	function formatStatuses($status) {
		switch ($status) {
			case '0':
				return 'CHỜ XÁC NHẬN';
			case '1':
				return 'ĐANG GIAO'; 
			case '2':
				return 'ĐÃ GIAO';
			case '3':
				return 'ĐÃ HUỶ';
		}
		return 'Status error';
	}
?>