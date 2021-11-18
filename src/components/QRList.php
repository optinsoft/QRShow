<?php
/*
    QRList class

    @created 14.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

use Predis\{Client};

class QRList {
    public static function render($space, $qrshow_url, $onerror) {
        $key_prefix = QR_REDIS_PREFIX . $space . '.';
        try {
            $redis = new Client();
            $list = $redis->keys($key_prefix . '*');
        } catch (\Exception $e) {
            $onerror('Fatal Error at #3');
            return;
        }
?>
        <ul class="list-group">
<?php        
        foreach ($list as $key) {
            $id = substr($key, strlen($key_prefix));
            if (preg_match(QRPatterns::ID, $id)) {
                $json = json_decode($redis->get($key), true);
                $title = isset($json['title']) && !empty($json['title']) ? $json['title'] : 'id=' . $id;
?>
                <li class="list-group-item">
                    <img src="<?= $qrshow_url ?>img/qr_code.png" />
                        <a onclick="return qr_popup(this.href+'&popup=true');" target='_blank' href="<?= $qrshow_url ?>?id=<?= htmlspecialchars($id) ?>&space=<?= htmlspecialchars($space) ?>&title=<?= htmlspecialchars($title) ?>"><?= htmlspecialchars($title) ?></a>
                </li>
<?php
            }
        }
?>        
        </ul>
<?php        
    }
}
?>