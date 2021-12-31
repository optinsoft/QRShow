<?php
/*
    QRPostForm class

    @created 30.12.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

function millitime() {
    $mt = microtime(true);
    $mti = (int)$mt;
    $mtf = (int)(($mt - (float)$mti) * 1000);
    return $mti . str_pad($mtf, 3, '0', STR_PAD_RIGHT);
}

class QRPostForm {
    public static function render($qrshow_url, $qrshow_spaces_dir) {
?>
        <form id="postDataForm" name="postDataForm" method="POST">
            <div class="qr-post-container">
                <div class="d-flex flex-column">
                    <label for="qrid">QR-code id:</label>
                    <input class="flex-grow-1" id="qrid" name="id" type="text"  value="<?= millitime() ?>"/>
                </div>
                <input type="hidden" name="space" value="public" />
                <div class="d-flex flex-column">
                    <label for="qrdata">QR-code data:</label>
                    <input class="flex-grow-1" id="qrdata" name="data" type="text" />
                </div>
                <div class="d-flex flex-column">
                    <label for="qrtitle">Title:</label>
                    <input id="qrtitle" name="title" type="text" />
                </div>
                <div class="h-captcha" data-sitekey="<?= QR_HCAPTCHA_SITEKEY ?>"></div>
                <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
                <div><input class="btn btn-primary" type="submit" value="Submit" /></div>
            </div>
        </form>
<?php
    }
}
?>