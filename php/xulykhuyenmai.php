<?php
require_once('../BackEnd/ConnectionDB/DB_classes.php');

if (!isset($_POST['request']) && !isset($_GET['request'])) die(null);
$db = new DB_driver();

switch ($_POST['request']) {
		// lấy tất cả khuyến mãi
	case 'getall':
		$sql = "SELECT * FROM `khuyenmai`";
		$dskm = $db->get_list($sql);
		die(json_encode($dskm));
		break;

		// lấy khuyến mãi theo id
	case 'getById':
		$km = (new KhuyenMaiBUS())->select_by_id('*', $_POST['id']);
		die(json_encode($km));
		break;

	case 'add':
        $data = $_POST['dataAdd'];
        $kmAddArr = array(
            'MaKM' => $data['maKM'],
            'TenKM' => $data['tenKM'],
            'GiaTriKM' => $data['giaTriKM'],
            'LoaiKM' => $data['loaiKM'],
            'NgayBD' => $data['NgayBD'],
            'NgayKT' => $data['NgayKT'],
        );

        $kmBUS = new KhuyenMaiBUS();
        die (json_encode($kmBUS->add_new($kmAddArr)));
        break;
	default:
		# code...
		break;
}

?>