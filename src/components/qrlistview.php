<?php
/*
    QRListView class

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause

    require global definitions: 
        QR_AUTO_REFRESH - from config    
*/
namespace optinsoft\QRShow;

class QRListView {
    public static function render($qrshow_url) {
?>
        <h4 id="cur_time"></h4>
        <div id="dialogs"></div>
        <div id='qr_list'>
<?php
            $error = '';
            QRList::render($qrshow_url, function($message) use (&$error) {
                $error = '500 Error! ' . $message;
            });
?>
        </div>
        <div id="error" style="color:red"><?= htmlspecialchars($error) ?></div>
        <div id="qr_dialog"></div>
        <script>          
            function qr_popup(url) {
                if ($('#qr_dialog').length == 0) {
                    return true;
                }
                $("#qr_dialog").dialog({
                    autoOpen: false,
                    modal: true,
                    width: 500,
                    height: 700,
                    title: <?= json_encode(QR_TITLE) ?>,
                    close: function () {
                        if (typeof qr_interval !== 'undefined') {
                            clearInterval(qr_interval);
                            delete qr_interval;
                        }
                    }
                });
                $('#qr_dialog').load(url).dialog('open');
                return false;
            }      
            $('#cur_time').html("Time: "  + (new Date()).toUTCString());
<?php 
    if (QR_AUTO_REFRESH > 0) { 
?>
            qr_list_interval = setInterval(function(){
                let dt = new Date();
                cur_time.innerHTML = "Time: "  + dt.toUTCString();
                $('#qr_list').load('<?= $qrshow_url ?>list/', function(response, status, xhr) {
                    if (status == 'error') {
                        $('#error').html(xhr.status + ' ' + xhr.statusText );
                    }
                    else {
                        $('#error').html('');
                    }
                });
            }, <?= QR_AUTO_REFRESH * 1000 ?>);
<?php 
    }
?>
        </script>
<?php        
    }
}
?>