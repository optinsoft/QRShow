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

	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/../conf/config.php';	

	//routing
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		require __DIR__ . '/../src/pages/qrdata.php';
		exit;
	}

	if (preg_match('/^image\/.*$/', $_SERVER['HTTP_ACCEPT'])) {
		require __DIR__ . '/../src/pages/qrimage.php';
		exit;
	}

	header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?= htmlspecialchars(QR_TITLE) ?></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<style>
		body{
			margin: 5em;
			padding: 0;
		}
	</style>
</head>
<body>
	<h1><?= htmlspecialchars(QR_TITLE) ?></h1>
<?php 
	if (isset($_GET['id']) && !empty($_GET['id'])) { 
		$title = isset($_GET['title']) ? $_GET['title'] : 'id=' . $_GET['id'];
		QRView::render($title, $_SERVER['REQUEST_URI']);
	}
	else {
		$qrshow_url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$qrshow_url .= $_SERVER['SERVER_NAME'];
		$qrshow_url .= str_replace("\\","/",dirname($_SERVER['REQUEST_URI']));
		QRListView::render($qrshow_url);
	}
?>
<noscript>
	For full functionality of this site it is necessary to enable JavaScript.
</noscript>
</body>
</html>