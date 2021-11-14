<?php
/*
	QRShow Index

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
	namespace optinsoft\QRShow;	

	require_once '../vendor/autoload.php';
	require_once '../conf/config.php';

	require_once '../src/components/qrview.php';

	//routing
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		require '../src/pages/qrdata.php';
		exit;
	}

	if (preg_match('/^image\/.*$/', $_SERVER['HTTP_ACCEPT'])) {
		require '../src/pages/qrimage.php';
		exit;
	}

	header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?= QR_TITLE ?></title>
	<style>
		body{
			margin: 5em;
			padding: 0;
		}
	</style>
</head>
<body>
	<h1><?= QR_TITLE ?></h1>
<?php 
	if (isset($_GET['id']) && !empty($_GET['id'])) { 
		QRView::render($_GET['id'], $_SERVER['REQUEST_URI']);
	}
?>
<noscript>
	For full functionality of this site it is necessary to enable JavaScript.
</noscript>
</body>
</html>