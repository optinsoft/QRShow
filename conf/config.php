<?php
/*
    QRShow config file 
    
    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause

    !!! ATTENTION !!! Don't edit this file
    Instead copy _user_config.php to user_config.php and use it.
*/
    if (file_exists(__DIR__ . '/user_config.php')) {
        include __DIR__ . '/user_config.php';
    }

    if (!defined('QR_AUTO_REFRESH')) define('QR_AUTO_REFRESH', 1);
    if (!defined('QR_TITLE')) define('QR_TITLE', 'QR Show');
    if (!defined('QR_API_KEY')) define('QR_API_KEY', '');
    if (!defined('QR_TOKEN')) define('QR_TOKEN', '8f83ffeab1a30e2171520589a1d6a01f');
    if (!defined('QR_REDIS_HOST')) define('QR_REDIS_HOST', '127.0.0.1');
    if (!defined('QR_REDIS_PORT')) define('QR_REDIS_PORT', 6379);
    if (!defined('QR_REDIS_PREFIX')) define('QR_REDIS_PREFIX', 'qr.');
    if (!defined('QR_MIN_TTL')) define('QR_MIN_TTL', 1);
    if (!defined('QR_MAX_TTL')) define('QR_MAX_TTL', 60);
?>