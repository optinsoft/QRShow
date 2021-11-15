<?php
/*
	Generate QR-code PNG for the data from the Redis cache

	HTTP GET parameters:
		id - qr code identifer, required

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause

	require global definitions:
		QR_REDIS_PREFIX - from config
*/
	use chillerlan\QRCode\{QRCode, QROptions};
	use Predis\{Client};

	if (isset($_GET['id']) && preg_match('/^[0-9a-zA-Z]{1,32}$/', $_GET['id']) &&
		isset($_GET['space']) && preg_match('/^[0-9a-fA-F]{16,40}$/', $_GET['space'])) {
		$id = $_GET['id'];
		$space = $_GET['space'];
		try {
			$redis = new Client();
			$key = QR_REDIS_PREFIX . $space . '.' . $id;
			$json = json_decode($redis->get($key), true);
			$data = $json['data'];
		} catch (\Exception $e) {
			header("HTTP/1.1 500 Fatal Error at #1");
			die();
		}
		if (isset($data) && !empty($data)) {
			$options = new QROptions([
				'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
				'eccLevel'     => QRCode::ECC_L,
				'imageBase64'  => false,
			]);

			header('Content-type: image/png');
			//header('qr-data: ' . $data);
			echo (new QRCode($options))->render($data);

			exit();
		}
	}

	header('Content-type: image/png');
	echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');

?>