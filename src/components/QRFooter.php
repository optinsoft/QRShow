<?php
/*
    QRFooter class

    @created 03.01.2022
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;
class QRFooter {
    public static function render() {
?>
        <div class="footer-container">
            <div>© Opt-In Software <?= date("Y"); ?></div>
            <div>Open Source: <a target="_blank" href="https://github.com/optinsoft/QRShow">GitHub</a></div>
        </div>
<?php
    }
}

?>