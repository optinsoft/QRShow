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

	$qrshow_url  = '//'; //isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$qrshow_url .= $_SERVER['SERVER_NAME'];
	if (isset($_SERVER['QRSHOW_ROOT'])) {
		$qrshow_url .= $_SERVER['QRSHOW_ROOT'];
	}
	else {
		$qrshow_url .= str_replace("\\","/", dirname($_SERVER['PHP_SELF']));
	}
	$qrshow_url = rtrim($qrshow_url, '/') . '/';
	$qrshow_spaces_dir = isset($_SERVER['QRSHOW_SPACES_DIR']) && ('1' === $_SERVER['QRSHOW_SPACES_DIR']);

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
	<link rel="stylesheet" href="<?= $qrshow_url ?>css/mdb.min.css"/>
	<script src="<?= $qrshow_url ?>js/mdb.min.js"></script>	
	<link rel="stylesheet" href="<?= $qrshow_url ?>css/qrshow.css?r=<?=  htmlspecialchars(microtime(true)) ?>"/>
</head>
<body>
	<div class="col-md-6 offset-md-3">
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
		if (!$popup) {
?>
			<div>
				<a class="btn btn-primary" role="button" href="<?= $qrshow_url ?>">Back</a>
			</div>
<?php
		}
	}
	else 
	{
		if (isset($_GET['space'])) {
			if (preg_match(QRPatterns::SPACE, $_GET['space'])) {
				QRListView::render($_GET['space'], $qrshow_url, $qrshow_spaces_dir);
			}
			else {
				QRSpaceForm::render($qrshow_url, $qrshow_spaces_dir);
			}
		}
		else {
			QRPostForm::render($qrshow_url, $qrshow_spaces_dir);
		}
	}
?>
<noscript>
	For full functionality of this site it is necessary to enable JavaScript.
</noscript>
<?php if (!$popup) { ?>
	</div>
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