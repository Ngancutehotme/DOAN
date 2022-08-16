<?php
require_once("../BackEnd/ConnectionDB/DB_classes.php");
$db = new DB_driver();
session_start();

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);

if (isset($_SESSION['currentUser']) && $_POST['request'] == "getall") {

	$manguoidung = $_SESSION['currentUser']['MaND'];

	$sql = "SELECT hd.NgayLap, hd.PhuongThucTT, hd.TongTien, hd.TrangThai, 
	cthd.SoLuong, cthd.DonGia, 
	sp.TenSP, sp.HinhAnh, sp.MaKM,
	km.LoaiKM, km.GiaTriKM
	FROM hoadon AS hd
	JOIN chitiethoadon AS cthd ON hd.MaHD = cthd.MaHD
	JOIN sanpham AS sp ON cthd.MaSP = sp.MaSP
	LEFT JOIN khuyenmai AS km ON sp.MaKM = km.MaKM
	WHERE MaND=$manguoidung";
	$dsdh = $db->get_list($sql);

	die(json_encode($dsdh));
}
