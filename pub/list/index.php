<?php
/*
	QRShow List Index

    @created 14.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
    namespace optinsoft\QRShow;	

    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../../conf/config.php';	
?>
<?php
    $qrshow_url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $qrshow_url .= $_SERVER['SERVER_NAME'];
    $qrshow_url .= str_replace("\\","/",dirname($_SERVER['REQUEST_URI']));
    QRList::render($qrshow_url, function($message) {
        header('HTTP/1.1 500 Error! ' . $message);
        die();
    });
?>