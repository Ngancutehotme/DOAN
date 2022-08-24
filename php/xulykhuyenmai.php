<?php
	require_once('../BackEnd/ConnectionDB/DB_classes.php');

	if(!isset($_POST['request']) && !isset($_GET['request'])) die(null);
	$db = new DB_driver();

	switch ($_POST['request']) {
    	// lấy tất cả khuyến mãi
    	case 'getall':
				$sql = "SELECT * FROM `khuyenmai` WHERE NOW() BETWEEN NgayBD AND NgayKT";
				$dskm = $db->get_list($sql);
		    	die (json_encode($dskm));
    		break;

        // lấy khuyến mãi theo id
        case 'getById':
                $km = (new KhuyenMaiBUS())->select_by_id('*', $_POST['id']);
                die (json_encode($km));
            break;
    	default:
    		# code...
    		break;
    }

?>