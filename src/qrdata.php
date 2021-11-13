<?php
    use Predis\{Client};

	require_once '../vendor/autoload.php';
	require_once '../src/config.php';

    header('Content-Type: application/json');

    if (!isset($_POST['id']) || !preg_match('/^[0-9a-zA-Z]{1,32}$/', $_POST['id'])) {
        header("HTTP/1.1 400 wrong id");
        die('{"status":1,"error":"wrong id"}');
    }
    if (!isset($_POST['data']) || empty($_POST['data'])) {
        header("HTTP/1.1 400 wrong data");
        die('{"status":2,"error":"wrong data"}');
    }
    if (!isset($_POST['ttl']) || !preg_match('/^[0-9]{1,10}$/', $_POST['ttl'])) {
        header("HTTP/1.1 400 wrong ttl");
        die('{"status":3,"error":"wrong ttl"}');
    }
    $id = $_POST['id'];
    $data = $_POST['data'];
    $ttl = (int)$_POST['ttl'];
    if (!empty(QR_API_KEY)) {
        $hash = base64_encode(hash_hmac('sha512', $data . $ttl, QR_API_KEY, true));
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
        $redis->set(QR_REDIS_PREFIX . $id, $data);
        $redis->expire(QR_REDIS_PREFIX . $id, $ttl);
	} catch (Exception $e) {
        header("HTTP/1.1 500 Fatal Error at #2");
		die();
	}
    echo '{"status":0}';
?>