<?php
	require_once '../vendor/autoload.php';
	require_once '../src/config.php';

	//routing
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require '../src/qrdata.php';
		exit;
    }

	if (preg_match('/^image\/.*$/', $_SERVER['HTTP_ACCEPT'])) {
		require '../src/qrimage.php';
		exit;
	}

	header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?= QR_TITLE . $_SERVER['HTTP_ACCEPT'] ?></title>
	<style>
		body{
			margin: 5em;
			padding: 0;
		}

		div.qrcode{
			margin: 0;
			padding: 0;
		}

		/* rows */
		div.qrcode > div {
			margin: 0;
			padding: 0;
			height: 10px;
		}

		/* modules */
		div.qrcode > div > span {
			display: inline-block;
			width: 10px;
			height: 10px;
		}

		div.qrcode > div > span {
			background-color: #ccc;
		}
	</style>
</head>
<body>
	<h1><?= QR_TITLE ?></h1>
	<table>
<?php if (isset($_GET['id'])) { ?>
		<tr>
			<td><b>QR ID:</b></td>
			<td><b><?= $_GET['id'] ?></b></td>
		</tr>
<?php } ?>
	</table>
	<h4 id="generated"></h4>
	<?php
		$request_uri = $_SERVER['REQUEST_URI'];
		$img_url = $request_uri . (strpos($request_uri, '?') !== false ? '&' : '?') . 't=' . round(microtime(true) * 1000);
	?>
	<img id="qr_image" src="<?= $img_url ?>" />
<script>
	let qr_image = document.getElementById('qr_image');
	let generated = document.getElementById('generated');
	generated.innerHTML = "Generated at "  + (new Date()).toUTCString();
<?php 
	if (QR_AUTO_REFRESH > 0) { 
?>
		setInterval(function(){
			let dt = new Date();
			generated.innerHTML = "Generated at "  + dt.toUTCString();
			qr_image.src = "<?= $img_url ?>&r=" + dt.getTime();
		}, <?= QR_AUTO_REFRESH * 1000 ?>);
<?php 
	} 
?>
</script>
<noscript>
	For full functionality of this site it is necessary to enable JavaScript.
</noscript>
</body>
</html>