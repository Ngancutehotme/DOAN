<?php
require_once("../BackEnd/ConnectionDB/DB_classes.php");
$db = new DB_driver();
session_start();

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);

if (isset($_SESSION['currentUser']) && $_POST['request'] == "getall") {

	$manguoidung = $_SESSION['currentUser']['MaND'];
	$sql = "SELECT MaND, MaHD, NgayLap, PhuongThucTT, TongTien, TrangThai FROM hoadon WHERE MaND=$manguoidung";
	$dsdh = $db->get_list($sql);
	
	foreach ($dsdh as $key => $hd) {
		$MaHD = $hd['MaHD'];
		$sql = "SELECT cthd.SoLuong, cthd.DonGia, sp.MaSP, sp.TenSP, sp.HinhAnh, sp.MaKM, km.LoaiKM, km.GiaTriKM
		FROM chitiethoadon AS cthd
		JOIN sanpham AS sp ON cthd.MaSP = sp.MaSP
		LEFT JOIN khuyenmai AS km ON sp.MaKM = km.MaKM
		WHERE MaHD=$MaHD";
		$HDCTs = $db->get_list($sql);
		$dsdh[$key]['CTHD'] = $HDCTs;
	}

	die(json_encode($dsdh));
}
