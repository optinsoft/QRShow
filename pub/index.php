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
		require __DIR__ . '/../src/pages/QRData.php';
		exit;
	}

	if (preg_match('/^image\/.*$/', $_SERVER['HTTP_ACCEPT'])) {
		require __DIR__ . '/../src/pages/QRImage.php';
		exit;
	}

	header('Content-Type: text/html; charset=utf-8');
?>
<?php
	if (isset($_GET['id']) && preg_match(QRPatterns::ID, $_GET['id'])) { 
		$id = $_GET['id'];
	}
	else {
		$id = null;
	}
	$popup = isset($_GET['popup']) && (bool)$_GET['popup'];
	if (!$popup) {
?>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?= QR_TITLE ?></title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="css/mdb.min.css"/>
	<script src="js/mdb.min.js"></script>	
	<link rel="stylesheet" href="css/qrshow.css?r=<?=  htmlspecialchars(microtime(true)) ?>"/>
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
	} // end if (!$popup)
	else {
		// if (!popup)
?>
	<div id="qr_popup_content<?= !is_null($id) ? '_' . htmlspecialchars($id) : '' ?>">
<?php
	} // end if (popup)
	if (!is_null($id)) { 
		$title = isset($_GET['title']) ? $_GET['title'] : 'id=' . $id;
		QRView::render($id, $title, $_SERVER['REQUEST_URI']);
	}
	else 
	{
		$qrshow_url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$qrshow_url .= $_SERVER['SERVER_NAME'];
		$qrshow_url .= str_replace("\\","/", dirname($_SERVER['PHP_SELF']));
		$qrshow_url = rtrim($qrshow_url, '/') . '/';
		if (isset($_GET['space']) && preg_match(QRPatterns::SPACE, $_GET['space'])) {
			QRListView::render($_GET['space'], $qrshow_url);
		}
		else {
			QRSpaceForm::render($qrshow_url);
		}
	}
?>
<noscript>
	For full functionality of this site it is necessary to enable JavaScript.
</noscript>
<?php if (!$popup) { ?>
</body>
</html>
<?php 
	} // endif (!$popup)
	else {
		// if (!popup)
?>
	</div>
<?php
	} // end if (popup)
?>