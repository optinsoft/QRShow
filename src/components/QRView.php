<?php
/*
    QRView class

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause   
*/
namespace optinsoft\QRShow;

class QRView {
    
    public static function render($id, $title, $request_uri) {
        $cur_time_id = 'cur_time_' . $id;
        $qr_image_id = 'qr_image_' . $id;
?>
        <div class='qr_title'><?= htmlspecialchars($title) ?></div>
        <div class='qr_time' id="<?= htmlspecialchars($cur_time_id) ?>"></div>
        <?php
            $img_url = $request_uri . (strpos($request_uri, '?') !== false ? '&' : '?') . 't=' . round(microtime(true) * 1000);
        ?>
        <div class='qr-image-container'>
            <img id="<?= htmlspecialchars($qr_image_id) ?>" class="qr-image" src="<?= htmlspecialchars($img_url) ?>" />
        </div>
        <script>
            $('#<?= $cur_time_id ?>').html("Time: "  + (new Date()).toUTCString());
<?php 
    if (QR_AUTO_REFRESH > 0) { 
?>
            qr_interval = setInterval(function(){
                let dt = new Date();
                $("#<?= $cur_time_id ?>").html("Time: "  + dt.toUTCString());
                $("#<?= $qr_image_id ?>").attr("src", "<?= $img_url ?>&r=" + dt.getTime());
            }, <?= QR_AUTO_REFRESH * 1000 ?>);    
            if (typeof qr_intervals !== 'undefined') {
                qr_intervals[<?= $id ?>] = qr_interval;
            }    
<?php 
    } 
?>
        </script>
<?php
    }
}
?>