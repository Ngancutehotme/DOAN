<?php

require_once("../BackEnd/ConnectionDB/DB_classes.php");
require_once("../vendor/swiftmailer/swiftmailer/lib/swift_required.php");
// require_once("swift/lib/swift_required.php");

if (!isset($_POST['request']) && !isset($_GET['request'])) die();

switch ($_POST['request']) {
	case 'themdonhang':
		$dulieu = $_POST["dulieu"];

		$hoadonBUS = new HoaDonBUS();
		$chitiethdBUS = new ChiTietHoaDonBUS();

		$hoadonBUS->add_new(array(
			"MaHD" => "",
			"MaND" => $dulieu["maNguoiDung"],
			"NgayLap" => $dulieu["ngayLap"],
			"NguoiNhan" => $dulieu["tenNguoiNhan"],
			"SDT" => $dulieu["sdtNguoiNhan"],
			"DiaChi" => $dulieu["diaChiNguoiNhan"],
			"PhuongThucTT" => $dulieu["phuongThucTT"],
			"TongTien" => $dulieu["tongTien"],
			"TrangThai" => $dulieu["trangThai"]
		));

		$hoadonMaxID = $hoadonBUS->get_list("SELECT * FROM hoadon ORDER BY MaHD DESC LIMIT 0, 1");
		$mahd = $hoadonMaxID[0]["MaHD"];

		foreach ($dulieu["dssp"] as $sp) {
			$dataSp = (new SanPhamBUS())->select_by_id("*", $sp["masp"]);
			$donGia = $dataSp["DonGia"];

			$chitiethdBUS->add_new(array($mahd, $sp["masp"], $sp["soLuong"], $donGia));
		}

		$tos = ['nganvu18102k@gmail.com', 'ngantv2k@gmail.com'];
		$subject = "Có đơn hàng mới: $mahd";
		$body = "Đơn hàng mới vào check ngay!!
		Mã đơn: $mahd
		Người đặt: {$dulieu["maNguoiDung"]}
		Người nhận: {$dulieu["tenNguoiNhan"]}
		SDT: {$dulieu["sdtNguoiNhan"]}
		Địa chỉ: {$dulieu["diaChiNguoiNhan"]}
		Tổng tiền: {$dulieu["tongTien"]}
		Xem chi tiết: http://localhost:8000/DoAn/admin.php";
		$from_name = "Ngân Vũ";
		$from_addr = "nganvu18102k@gmail.com";
		$sendmail = sendmail($tos, $subject, $body, $from_name, $from_addr);
		die(json_encode(true));
		break;
}

function sendmail($tos, $subject, $body, $from_name, $from_addr)
{
	$transport = (new Swift_SmtpTransport('smtp.sendgrid.net', 587, 'tls'))
		->setUsername('apikey')
		->setPassword('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');

	$to_mails = is_array($tos) ? $tos : explode(',', $tos);
	$res = _validate_mails($to_mails, $body);
	$valid_mails = $res['valid_mails'];
	$failures = $res['invalid_mails'];

	if (count($valid_mails) == 0) {
		return [
			'result_num' => 0,
			'failures' => $failures
		];
	}

	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message())
		->setSubject($subject)
		->setFrom([$from_addr => $from_name])
		->setTo($valid_mails)
		->setBody($body);

	if (empty($body)) {
		throw new Exception("エラーを発生しました。");
	}
	$result_num = _send($mailer, $message, $valid_mails);

	if ($result_num == 0) {
		foreach ($valid_mails as $mail) {
			$failures[] = [
				'to' => $mail,
				'reason' => 'Lỗi chuyển máy chủ thư'
			];
		}
	}

	return [
		'result_num' => $result_num,
		'failures' => $failures
	];
}

function _validate_mails($to_mails, $body)
{
	$valid_mails = [];
	$invalid_mails = [];
	foreach ($to_mails as $key => $to) {
		try {
			$is_valid_mail = (new Swift_Message())
				->setTo($to)
				->setBody($body);

			if (!$is_valid_mail->getBody()) throw new Exception('Body is invalid');
			$valid_mails[] = $to;
		} catch (\Swift_RfcComplianceException $e) {
			$invalid_mails[] = [
				'to' => $to,
				'reason' => 'Địa chỉ email không hợp lệ'
			];
		} catch (Exception $e) {
			$invalid_mails[] = [
				'to' => $to,
				'reason' => 'Không có nội dung mail'
			];
		}
	}

	return [
		'valid_mails' => $valid_mails,
		'invalid_mails' => $invalid_mails
	];
}

function _send($mailer, $message, $tos)
{
	$result_num = 0;

	try {
		$sn = $mailer->send($message);
		$result_num = count($tos);
	} catch (\Swift_TransportException $e) {
		return $e;
	} finally {
		return $result_num;
	}
}
