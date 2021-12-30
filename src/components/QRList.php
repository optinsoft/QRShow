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
    public static function render($space, $qrshow_url, $qrshow_spaces_dir, $onerror) {
        $key_prefix = QR_REDIS_PREFIX . $space . '.';
        try {
            $redis = new Client();
            $list = $redis->keys($key_prefix . '*');
        } catch (\Exception $e) {
            $onerror('Fatal Error at #3');
            return;
        }
?>
        <ul id ='qr_list' class="list-group">
<?php        
        $id_list = [];
        foreach ($list as $key) {
            $id = substr($key, strlen($key_prefix));
            if (preg_match(QRPatterns::ID, $id)) {
                $id_list[$id] = $key;
            }
        }
        ksort($id_list);
        foreach($id_list as $id => $key) {
            try {
                $json = json_decode($redis->get($key), true);
            } catch (\Exception $e) {
                $onerror('Fatal Error at #4');
                return;
            }
            $title = isset($json['title']) && !empty($json['title']) ? $json['title'] : 'id=' . $id;
?>
            <li id ="qr_list_item_<?= htmlspecialchars($id) ?>" class="list-group-item" qr_item_id="<?= htmlspecialchars($id) ?>">
                <img src="<?= $qrshow_url ?>img/qr_code.png" />
                    <a onclick="return qr_popup(this, '<?= htmlspecialchars($id) ?>', this.href+'&popup=true');" 
                        target='_blank' href="<?= 
                            htmlspecialchars($qrshow_url) 
                        ?><?= 
                            ($qrshow_spaces_dir ? 'spaces/' : '?space=') . htmlspecialchars($space) 
                        ?><?= 
                            ($qrshow_spaces_dir ? '/' : '&id=') . htmlspecialchars($id) 
                        ?><?= 
                            ($qrshow_spaces_dir ? '/?' : '&') . 'title=' . htmlspecialchars($title) 
                        ?>"><?= htmlspecialchars($title) ?></a>
            </li>
<?php
        }
?>        
        </ul>
<?php        
    }
}
?>