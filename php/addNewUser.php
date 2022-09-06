<?php
require_once("../BackEnd/ConnectionDB/DB_classes.php");
session_start();

if (!isset($_POST['request']) && !isset($_SESSION['currentUser'])) die(null);

$data = $_POST['data'];
$ho = $data['ho'];
$ten = $data['ten'];
$gioiTinh = $data['gioiTinh'];
$sdt = $data['sdt'];
$email = $data['email'];
$diaChi = $data['diaChi'];
$ngaySinh = $data['ngaySinh'];
$xuli_newUser = $data['tenDangNhap'];
$xuli_newPass = md5('12345678');

//Kiểm tra TK tồn tại hay chưa
$exist_sql = "SELECT TaiKhoan FROM nguoidung WHERE TaiKhoan = '$xuli_newUser'";
$exist_result = (new DB_driver())->get_row($exist_sql);
if ($exist_result == true) {
  die("existed");
}

$status = (new NguoiDungBUS())->add_new(array(
  "MaND" => "",
  "Ho" => $ho,
  "Ten" => $ten,
  "GioiTinh" => $gioiTinh,
  "SDT" => $sdt,
  "Email" => $email,
  "DiaChi" => $diaChi,
  "TaiKhoan" => $xuli_newUser,
  "MatKhau" => $xuli_newPass,
  "NgaySinh" => date_format(date_create($ngaySinh), "Y/m/d"),
  "MaQuyen" => 1,
  "TrangThai" => 1
));
die(json_encode($status));
