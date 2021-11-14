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

	if (isset($_GET['id']) && preg_match('/^[0-9a-zA-Z]{1,32}$/', $_GET['id'])) {
		$id = $_GET['id'];

		try {
			$redis = new Client();
			$data = $redis->get(QR_REDIS_PREFIX . $id);
		} catch (\Exception $e) {
			header("HTTP/1.1 500 Fatal Error at #1");
			die();
		}
		if (isset($data) && !empty($data)) {
			$options = new QROptions([
				'version'      => 10,
				'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
				'eccLevel'     => QRCode::ECC_L,
				'scale'        => 5,
				'imageBase64'  => false,
			]);

			header('Content-type: image/png');
			echo (new QRCode($options))->render($data);

			exit();
		}
	}

	header('Content-type: image/png');
	echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');

?>