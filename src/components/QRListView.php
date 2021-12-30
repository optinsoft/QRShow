<?php
/*
    QRListView class

    @created 13.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

class QRListView {
    public static function render($space, $qrshow_url, $qrshow_spaces_dir) {
?>
        <div class="qr_space" id="space">Space: <?= htmlspecialchars($space) ?></div>
        <div class="qr_time" id="cur_time"></div>
        <div class="qr-list-container bg-white border rounded-5 p-3 mt-3">
            <div class="my-2">QR codes:</div>
            <div id='qr_list'>
<?php
                $error = '';
                QRList::render($space, $qrshow_url, $qrshow_spaces_dir, function($message) use (&$error) {
                    $error = '500 Error! ' . $message;
                });
?>
            </div>
        </div>
        <div id="error"><?= htmlspecialchars($error) ?></div>
        <div id="qr_dialog"></div>
        <script>   
            qr_intervals = [];
            qr_list_items = [];
            qr_open_dialogs = [];
            function refresh_qr_list_items() {
                let new_qr_list_items = [];
                let qr_list = $('#qr_list li');
                for (let li of qr_list) {
                    let qr_item_id = $(li).attr('qr_item_id');
                    if (<?= QRPatterns::ID ?>.test(qr_item_id)) {
                        new_qr_list_items[qr_item_id] = li;
                    }
                } 
                for (let id in qr_list_items) {
                    if (typeof new_qr_list_items[id] == 'undefined') {
                        let open_dialog = qr_open_dialogs[id];
                        if (typeof open_dialog !== 'undefined') {
                            open_dialog.dialog('close');
                        }
                    }
                }                
                qr_list_items = new_qr_list_items;
            }
            window.onload = refresh_qr_list_items;
            function qr_popup(clicked, id, url) {
                let qr_dialog = $("#qr_dialog");
                qr_dialog.dialog({
                    autoOpen: false,
                    modal: true,
                    dialogClass: 'qr-dialog',
                    position: {
                        my: "left top",
                        at: "left bottom",
                        of: clicked
                    },
                    width: 'auto',
                    height: 'auto',
                    title: <?= json_encode(QR_TITLE) ?>,
                    open: function () {
                        qr_open_dialogs[id] = $(this);
                    },
                    close: function () {
                        if (typeof qr_intervals[id] !== 'undefined') {
                            clearInterval(qr_intervals[id]);
                            delete qr_intervals[id];
                        }
                        let qr_popup_content = document.getElementById('qr_popup_content_' + id);
                        if (typeof qr_popup_content !== 'undefined') {
                            qr_popup_content.remove();
                        }
                        delete qr_open_dialogs[id];
                    }
                });
                $('#qr_dialog').load(url).dialog('open');
                return false;
            }      
            $('#cur_time').html("Time: "  + (new Date()).toUTCString());
<?php 
    if (QR_AUTO_REFRESH > 0) { 
?>
            setInterval(function(){
                let dt = new Date();
                $('#cur_time').html("Time: "  + dt.toUTCString());
                $('#qr_list').load('<?= htmlspecialchars($qrshow_url) ?>list/?space=<?= htmlspecialchars($space) ?><?= $qrshow_spaces_dir ? '&sd=1' : '' ?>', function(response, status, xhr) {
                    if (status == 'error') {
                        $('#error').html(xhr.status + ' ' + xhr.statusText );
                    }
                    else {
                        $('#error').html('');  
                        refresh_qr_list_items();
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