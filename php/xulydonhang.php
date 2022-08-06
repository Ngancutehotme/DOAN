<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);

if ($_POST['request'] == 'getall') {
    $sql ="SELECT hd.*, nd.Ten, nd.GioiTinh, nd.SDT AS sdtND, nd.Email FROM `hoadon` AS hd
    LEFT JOIN nguoidung AS nd ON hd.MaND = nd.MaND";
    $donhang = (new DB_driver())->get_list($sql);

    die (json_encode($donhang));
} else {
    die([]);
}

?>