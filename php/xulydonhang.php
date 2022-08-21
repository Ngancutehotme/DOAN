<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);
$hoadonBUS = new hoadonBUS();
$db = new DB_driver();

if ($_POST['request'] == 'getall') {
    $sql ="SELECT hd.*, nd.Ten, nd.GioiTinh, nd.SDT AS sdtND, nd.Email FROM `hoadon` AS hd
    LEFT JOIN nguoidung AS nd ON hd.MaND = nd.MaND";
    $donHangs = $db->get_list($sql);
    $result = [];
    $total = [];
    $total['soDonHuy'] = $total['soDonChoXacNhan'] = $total['soDonChoChuanBiHang'] = $total['soDonDangGiao'] = $total['soDonDaGiao'] = 0;
    foreach ($donHangs as $donHang) {
        $id = $donHang['MaHD'];
        switch ($donHang['TrangThai']) {
            case '0':
                $total['soDonChoXacNhan'] += 1;
                break;
            case '1':
                $total['soDonDangGiao'] += 1;
                break;
            case '2':
                $total['soDonDaGiao'] += 1;
                break;
            case '3':
                $total['soDonHuy'] += 1;
                break;
        }
        $sql ="SELECT cthd.SoLuong,cthd.DonGia, sp.TenSP, sp.HinhAnh FROM chitiethoadon AS cthd
        INNER JOIN sanpham AS sp  ON cthd.MaSP = sp.MaSP WHERE cthd.MaHD=$id";
        $chiTiet = $db->get_list($sql);
        $donHang['thongTinChiTiet'] = $chiTiet;
        array_push($result, $donHang);
    }

    die (json_encode([$result, $total]));
} elseif ($_POST['request'] == 'updateStatus') {
    $d = [];
    $id = $_POST['id'];
    $d['TrangThai'] = $_POST['status'];
    $w = "MaHD='" . $id . "'";
    $db->update('hoadon', $d, $w);
    die (json_encode(true));
} else {
    die([]);
}

?>