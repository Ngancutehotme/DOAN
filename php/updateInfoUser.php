<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');
$db = new DB_driver();
session_start();

if (!isset($_POST['request']) && !isset($_SESSION['currentUser'])) die(null);

switch ($_POST['request']) {
    case 'updateUser':
        $data = $_POST['dataUpdate'];
        $id = $_SESSION['currentUser']['MaND'];
        $matkhau = $_SESSION['currentUser']['MatKhau'];
        $d = array(
            'GioiTinh' => $data['GioiTinh'],
            'Email' => $data['Email'],
            'SDT' => $data['SDT'],
            'Ho' => $data['Ho'],
            'Ten' => $data['Ten'],
            'NgaySinh' => date_format(date_create($data['NgaySinh']), "Y/m/d"),
        );
        $w = "MaND='" . $id . "'";
        $db->update('nguoidung', $d, $w);
        $sql = "SELECT * FROM nguoidung WHERE MatKhau='$matkhau' AND TrangThai=1";
        $result = (new DB_driver())->get_row($sql);

        if ($result != false) {
            $_SESSION['currentUser'] = $result;
            die(json_encode(true));
        }
        die(json_encode(false));
    case 'updatePassword':
        $id = $_SESSION['currentUser']['MaND'];
        $taikhoan = $_SESSION['currentUser']['TaiKhoan'];
        $password = $_SESSION['currentUser']['MatKhau'];
        $oldPassword = $_POST['dataUpdate']['matKhauCu'];
        if ($password == md5($oldPassword)) {
            $newPassword = md5($_POST['dataUpdate']['matKhauMoi']);
            $d = array(
                'MatKhau' => $newPassword,
            );
            $w = "MaND='" . $id . "'";
            $db->update('nguoidung', $d, $w);
            $sql = "SELECT * FROM nguoidung WHERE TaiKhoan='$taikhoan' AND MatKhau='$newPassword' AND TrangThai=1";
            $result = (new DB_driver())->get_row($sql);

            if ($result != false) {
                $_SESSION['currentUser'] = $result;
                die(json_encode(true));
            }
        }
        die(json_encode(false));
}
