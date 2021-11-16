<?php
/*
    Set QR data API

    HTTP POST parameters:
        id - qr code identifier, required
        data - qr code data
        ttl - qr code expire time in seconds
        s - signature, required when QR_API_KEY is not empty 
        t - token, required when QR_TOKEN is not empty

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
    namespace optinsoft\QRShow;

    use Predis\{Client};

    header('Content-Type: application/json');

    if (!isset($_POST['id']) || !preg_match(QRPatterns::ID, $_POST['id'])) {
        header("HTTP/1.1 400 wrong id");
        die('{"status":1,"error":"wrong id"}');
    }
    if (!isset($_POST['space']) || !preg_match(QRPatterns::SPACE, $_POST['space'])) {
        header("HTTP/1.1 400 wrong space");
        die('{"status":7,"error":"wrong space"}');
    }
    if (!isset($_POST['data']) || !preg_match(QRPatterns::DATA, $_POST['data'])) {
        header("HTTP/1.1 400 wrong data");
        die('{"status":2,"error":"wrong data"}');
    }
    if (isset($_POST['title']) && !preg_match(QRPatterns::TITLE, $_POST['title'])) {
        header("HTTP/1.1 400 wrong title");
        die('{"status":6,"error":"wrong title"}');
    }
    if (!isset($_POST['ttl']) || !preg_match(QRPatterns::TTL, $_POST['ttl'])) {
        header("HTTP/1.1 400 wrong ttl");
        die('{"status":3,"error":"wrong ttl"}');
    }
    $id = $_POST['id'];
    $space = $_POST['space'];
    $data = $_POST['data'];
    $ttl = (int)$_POST['ttl'];
    if ($ttl < QR_MIN_TTL || $ttl > QR_MAX_TTL) {
        header("HTTP/1.1 400 wrong ttl");
        die('{"status":3,"error":"wrong ttl"}');
    }
    if (!empty(QR_API_KEY)) {
        $hash = base64_encode(hash_hmac('sha512', $id . $space . $data . $ttl, QR_API_KEY, true));
        if (!isset($_POST['s']) || $hash !== $_POST['s']) {
            header("HTTP/1.1 400 wrong hash");
            die('{"status":4,"error":"wrong hash"}');
        }
    }
    if (!empty(QR_TOKEN)) {
        if (!isset($_POST['t']) || QR_TOKEN !== $_POST['t']) {
            header("HTTP/1.1 400 wrong token");
            die('{"status":5,"error":"wrong token"}');    
        }
    }
    try {
        $redis = new Client();
        $info = [
            'data' => $data
        ];
        if (isset($_POST['title'])) {
            $info['title'] = $_POST['title'];
        }
        $key = QR_REDIS_PREFIX . $space . '.' . $id;
        $redis->set($key, json_encode($info));
        $redis->expire($key, $ttl);
	} catch (\Exception $e) {
        header("HTTP/1.1 500 Fatal Error at #2");
		die();
	}
    echo '{"status":0}';
?>