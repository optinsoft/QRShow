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
    public static function render($qrshow_url, $onerror) {
        try {
            $redis = new Client();
            $list = $redis->keys(QR_REDIS_PREFIX . '*');
        } catch (\Exception $e) {
            $onerror('Fatal Error at #3');
            return;
        }
?>
        <table>
            <tbody>
<?php        
        foreach ($list as $key) {
            $id = substr($key, strlen(QR_REDIS_PREFIX));
            if (preg_match('/^[0-9a-zA-Z]{1,32}$/', $id)) {
                $json = json_decode($redis->get($key), true);
                $title = isset($json['title']) && !empty($json['title']) ? $json['title'] : 'id=' . $id;
?>
                <tr>
                    <td><img src="<?= $qrshow_url ?>img/qr_code.png" /></td><td>
                        <a onclick="return qr_popup(this.href+'&popup=true');" target='_blank' href="<?= $qrshow_url ?>?id=<?= htmlspecialchars($id) ?>&title=<?= htmlspecialchars($title) ?>"><?= htmlspecialchars($title) ?></a>
                    </td>
                </tr>
<?php
            }
        }
?>        
            </tbody>
        </table>
<?php        
    }
}
?>