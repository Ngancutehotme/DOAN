<?php
	require_once ("../BackEnd/ConnectionDB/DB_classes.php");
	session_start();

	$data = $_GET['dsdh'];
	if (isset($_SESSION['currentUser'])) {

		if(sizeof($data) > 0) {
			echo'<div class="my-bill">';
			forEach($data as $index => $row) {
				$class = $index === 0 ? '-first' : '';
				$trangThaiDonHang = formatStatuses($row["TrangThai"]);
				echo '<div class="item '.$class.'">
					<div class="head">
						<span class="id">Mã đơn hàng: '.$row["MaHD"].'</span>
						<span class="time">Thời gian: '.$row["NgayLap"].'</span>
						<span class="status">'.$trangThaiDonHang.'</span>
					</div>
					<div class="content">
						<img src="'.$row["HinhAnh"].'" class="image">
						<div class="info">
							<p>Tên sản phẩm: '.$row["TenSP"].'</p>
							<p>Số lượng: '.$row["SoLuong"].'</p>
							<p class="price">'.$row["DonGia"].'vnd </p>
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