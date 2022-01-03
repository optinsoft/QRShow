<?php
/*
    QRSpaceForm class

    @created 15.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

class QRSpaceForm {
    public static function render($qrshow_url, $qrshow_spaces_dir) {
?>
        <form id="spaceForm" name="spaceForm" method="GET" action="<?= htmlspecialchars($qrshow_url) ?>" onsubmit="return validateForm()">
            <div class="qr-post-container">
                <div class="d-flex flex-column">
                    <label for="space">Please, enter space (16-40 hex digits)</label>
                    <input id="space" name="space" type="text" />
                </div>
                <div><input class="btn btn-primary" type="submit" value="Submit" /></div>
            </div>
        </form>
        <div id="error"></div>
        <script>
            function validateForm() {
                let space = document.forms["spaceForm"]["space"].value;
                if (!space.match(/^[0-9a-fA-F]{16,40}$/)) {
                    $('#error').html('Wrong space!');
                    return false;
                }
<?php if ($qrshow_spaces_dir) { ?>
                window.location = "<?= htmlspecialchars($qrshow_url) ?>spaces/" + space + "/";
                return false;
<?php } ?>                
            }
        </script>
<?php
    }
}

?>

