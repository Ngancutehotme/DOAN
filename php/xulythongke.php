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
        GROUP BY lsp.MaLSP
        ORDER BY tong DESC
        LIMIT 5";
        $result = $db->get_list($sql);
        die(json_encode($result));
        break;
    case 'top5SanPhamBanChayNhat':
        $sql = "SELECT COUNT(sp.MaSP) AS luongBan, sp.TenSP FROM `hoadon` AS hd
        JOIN chitiethoadon AS cthd ON hd.MaHD = cthd.MaHD
        JOIN sanpham AS sp ON cthd.MaSP = sp.MaSP
        GROUP BY sp.MaSP 
        ORDER BY luongBan DESC
        LIMIT 5";
        $result = $db->get_list($sql);
        die(json_encode($result));
        break;
    case 'top5SanPhamCoTongDoanhThuCaoNhat':
        $sql = "SELECT SUM(hd.TongTien) AS tongDoanhThu, sp.TenSP FROM `hoadon` AS hd
        JOIN chitiethoadon AS cthd ON hd.MaHD = cthd.MaHD
        JOIN sanpham AS sp ON cthd.MaSP = sp.MaSP
        GROUP BY sp.MaSP 
        ORDER BY tongDoanhThu DESC
        LIMIT 5";
        $result = $db->get_list($sql);
        die(json_encode($result));
        break;
    case 'soDonTheoThang':
        $sql = "SELECT COUNT(*) AS sl, TrangThai FROM hoadon 
        WHERE NgayLap BETWEEN LAST_DAY(curdate() - interval 1 month) + interval 1 day AND last_day(curdate())
        GROUP BY TrangThai";
        $result = $db->get_list($sql);
        die(json_encode($result));
        break;
    default:
        # code...
        break;
}
