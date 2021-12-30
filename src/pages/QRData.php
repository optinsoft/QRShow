<?php
/*
    Set QR data API

    HTTP POST parameters:
        id - qr code identifier, required
        space - redis keys prefix
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
    else {
        $id = $_POST['id'];
    }
    $public = !empty(QR_PUB_SPACE_PREFIX) && isset($_POST['space']) && 'public' === $_POST['space'];
    if ($public){
        $space = QR_PUB_SPACE_PREFIX . bin2hex(random_bytes(8));
        $ch =  curl_init();
        $url = 'https://hcaptcha.com/siteverify';
        $post = [
            'response' => (isset($_POST['h-captcha-response']) ? $_POST['h-captcha-response'] : ''),
            'secret' => QR_HCAPTCHA_SECRET
        ];
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post)
        )); 
        $response  = curl_exec($ch);
        if ($response === false) {
            header("HTTP/1.1 500 hCaptcha failed");
            $message = 'hCaptcha failed! ' . curl_error($ch);
            curl_close($ch);
            die(json_encode(['status' => 8, 'error' => $message]));
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        if (200 !== $statusCode) {
            header("HTTP/1.1 500 hCaptcha failed");
            die(json_encode(['status' => 8, 'error' => 'HTTP Error ' . $statusCode]));
        }
        $body = substr($response, $headerSize);
        $json = @json_decode($body);
        if ($json === null || JSON_ERROR_NONE !== json_last_error()) {
            header("HTTP/1.1 500 hCaptcha failed");
            die(json_encode(['status' => 8, 'error' => 'Bad response JSON']));
        }
        if (true !== $json->success) {
            header("HTTP/1.1 500 hCaptcha failed");
            die(json_encode(['status' => 8, 'error' => 'hCaptcha verification has failed', 
                'details' => $json]));    
        }
        $ttl = QR_PUB_TTL;
    }
    else {
        if (!isset($_POST['space']) || !preg_match(QRPatterns::SPACE, $_POST['space'])) {
            header("HTTP/1.1 400 wrong space");
            die('{"status":7,"error":"wrong space"}');
        }
        $space = $_POST['space'];
    }
    if (!isset($_POST['data']) || !preg_match(QRPatterns::DATA, $_POST['data'])) {
        header("HTTP/1.1 400 wrong data");
        die('{"status":2,"error":"wrong data"}');
    }
    else {
        $data = $_POST['data'];
    }
    if (isset($_POST['title'])) {
        if (!preg_match(QRPatterns::TITLE, $_POST['title'])) {
            header("HTTP/1.1 400 wrong title");
            die('{"status":6,"error":"wrong title"}');
        }
        $title = $_POST['title'];
    }
    if (!$public) {
        if (!isset($_POST['ttl']) || !preg_match(QRPatterns::TTL, $_POST['ttl'])) {
            header("HTTP/1.1 400 wrong ttl");
            die('{"status":3,"error":"wrong ttl"}');
        }
        else {
            $ttl = (int)$_POST['ttl'];
        }
        if ($ttl < QR_MIN_TTL || $ttl > QR_MAX_TTL) {
            header("HTTP/1.1 400 wrong ttl");
            die('{"status":3,"error":"wrong ttl"}');
        }
        if (QR_API_KEY_OR_TOKEN) {
            $authorized = false;
            if (!empty(QR_API_KEY) && isset($_POST['s']) && !empty($_POST['s'])) {
                $hash = base64_encode(hash_hmac('sha512', $id . $space . $data . $ttl, QR_API_KEY, true));
                if ($hash === $_POST['s']) {
                    header("HTTP/1.1 400 wrong hash");
                    die('{"status":4,"error":"wrong hash"}');
                }
                $authorized = true;
            }
            if (!$authorized && !empty(QR_TOKEN) && isset($_POST['t']) && !empty($_POST['t'])) {
                if (QR_TOKEN !== $_POST['t']) {
                    header("HTTP/1.1 400 wrong token");
                    die('{"status":5,"error":"wrong token"}');    
                }
                $authorized = true;
            }
            if (!$authorized) {
                if (!empty(QR_API_KEY)) {
                    header("HTTP/1.1 400 wrong hash");
                    die('{"status":4,"error":"wrong hash"}');
                }
                if (!empty(QR_TOKEN)) {
                    header("HTTP/1.1 400 wrong token");
                    die('{"status":5,"error":"wrong token"}');
                }
            }
        }
        else {
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
        }
    }
    try {
        $redis = new Client();
        $info = [
            'data' => $data
        ];
        if (isset($title)) {
            $info['title'] = $title;
        }
        $key = QR_REDIS_PREFIX . $space . '.' . $id;
        $redis->set($key, json_encode($info));
        $redis->expire($key, $ttl);
	} catch (\Exception $e) {
        header("HTTP/1.1 500 Fatal Error at #2");
		die();
	}
    if ($public && isset($qrshow_url)) {
        if (isset($qrshow_spaces_dir) && $qrshow_spaces_dir) {
            $url = $qrshow_url . 'spaces/' . htmlspecialchars($space) .'/' . htmlspecialchars($id) . '/';
        }
        else {
            $url = $qrshow_url . '?space=' . htmlspecialchars($space) . '&id=' . htmlspecialchars($id);
        }
        if (isset($title)) {
            $url .= '&title=' . htmlspecialchars($title);
        }
        header('Location: ' . $url, 302);
        exit();
    }
    $result = ['status' => 0, 'id' => $id, 'space' => $space, 'ttl' => $ttl ];
    if (isset($title)) {
        $result['title'] = $title;
    }
    echo json_encode($result);
?>