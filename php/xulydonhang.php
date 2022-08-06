<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);

if ($_POST['request'] == 'getall') {
    $sql ="SELECT hd.*, nd.Ten, nd.GioiTinh, nd.SDT AS sdtND, nd.Email FROM `hoadon` AS hd
    LEFT JOIN nguoidung AS nd ON hd.MaND = nd.MaND";
    $donHangs = (new DB_driver())->get_list($sql);
    $result = [];
    $totalPendingInvoices = 0;
    foreach ($donHangs as $donHang) {
        $id = $donHang['MaHD'];
        if (!$donHang['TrangThai']) {
            $totalPendingInvoices += 1; //
        }
        $sql ="SELECT cthd.SoLuong,cthd.DonGia, sp.TenSP, sp.HinhAnh FROM chitiethoadon AS cthd
        INNER JOIN sanpham AS sp  ON cthd.MaSP = sp.MaSP WHERE cthd.MaHD=$id";
        $chiTiet = (new DB_driver())->get_list($sql);
        $donHang['thongTinChiTiet'] = $chiTiet;
        array_push($result, $donHang);
    }

    die (json_encode([$result, $totalPendingInvoices]));
} else {
    die([]);
}

?>