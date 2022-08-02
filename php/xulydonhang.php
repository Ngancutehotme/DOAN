<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);

if ($_POST['request'] == 'getall') {
    $donhang = (new HoaDonBUS())->select_all();
    $ctdonhang = (new ChiTietHoaDonBUS())->select_all();

    for ($i = 0; $i < count($donhang); $i++) {
        $chitiet = find($ctdonhang, 'MaHD', $donhang[$i]['MaHD']);
        if (is_null($chitiet)) {
            $donhang[$i]['Sanpham'] = '';
        } else {
            $sanpham = (new SanPhamBUS())->select_by_id("*", $chitiet['MaSP']);
            $donhang[$i]['Sanpham'] = $sanpham['TenSP'];
        }
    }
    die (json_encode($donhang));
} else {
    die([]);
}

function find($array, $key, $value)
{
    foreach ($array as $element) {
        if ($value == $element[$key]) {
            return $element;
        }
    }

    return null;
}

?>