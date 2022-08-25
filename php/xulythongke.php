<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);
$db = new DB_driver();

switch ($_POST['request']) {
    case 'getSoLuongBanRa':
        $sql = "SELECT COUNT(cthd.SoLuong) AS tong,lsp.MaLSP, lsp.TenLSP FROM `hoadon` AS hd
        JOIN chitiethoadon AS cthd ON hd.MaHD = cthd.MaHD
        JOIN sanpham AS sp ON cthd.MaSP = sp.MaSP
        JOIN loaisanpham AS lsp ON sp.MaLSP = lsp.MaLSP
        WHERE hd.NgayLap BETWEEN LAST_DAY(curdate() - interval 1 month) + interval 1 day AND last_day(curdate())
        GROUP BY lsp.MaLSP";
        $result = $db->get_list($sql);
        die(json_encode($result));
        break;
    default:
        # code...
        break;
}
