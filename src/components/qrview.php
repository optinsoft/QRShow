<?php
/*
    QRView class

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause

    require global definitions: 
        QR_AUTO_REFRESH - from config    
*/
namespace optinsoft\QRShow;

class QRView {
    public static function render($title, $request_uri) {
        /*
            $title = $_GET['title'];
            $request_uri = $_SERVER['REQUEST_URI'];
        */
?>
        <h3><?= htmlspecialchars($title) ?></h3>
        <h4 id="cur_time"></h4>
        <?php
            $img_url = $request_uri . (strpos($request_uri, '?') !== false ? '&' : '?') . 't=' . round(microtime(true) * 1000);
        ?>
        <div id='qr_image_container'>
            <img id="qr_image" src="<?= $img_url ?>" />
        </div>
        <script>
            (function() {
                let qr_image = document.getElementById('qr_image');
                let cur_time = document.getElementById('cur_time');
                cur_time.innerHTML = "Time: "  + (new Date()).toUTCString();
            })();
<?php 
    if (QR_AUTO_REFRESH > 0) { 
?>
            qr_interval = setInterval(function(){
                let dt = new Date();
                cur_time.innerHTML = "Time: "  + dt.toUTCString();
                qr_image.src = "<?= $img_url ?>&r=" + dt.getTime();
            }, <?= QR_AUTO_REFRESH * 1000 ?>);        
<?php 
    } 
?>
        </script>
<?php
    }
}
?>